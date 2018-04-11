<?php
	require_once "article_model.php";
	require_once "../classes/article.php";
	require_once "model.php";
	
	class ArticleModelTextFiles extends ArticleModel
	{
		use ArticleInfoValidation;
		
		public $path;

		public function __construct(string $path)
		{
			$this->path = $path;
		}
		public function getNArticles(int $offset, int $number)
		{
			$articles = [];
			//find article id that will be first article in the final array
			for($curId = $this->getLastId(), $curPos = 0; $curId && $curPos != $offset; --$curId) {
				if(file_exists("{$this->path}/$curId"))
					++$curPos;
			}
			for(; $number && $curId; --$curId) {
				if(file_exists("{$this->path}/$curId")) {
					--$number;

					$file = fopen("{$this->path}/$curId", "rt");
					flock($file, LOCK_SH);
					$articleName = fgets($file);
					$articleAuthorUID = fgets($file);
					$articleCreationDate = fgets($file);
					$articleContent = file_get_contents("{$this->path}/$curId", false, null, ftell($file));
					fclose($file);

					$user = (getUserModelInstance())->getUserByID($articleAuthorUID);

					$articles[] = new Article($curId, $articleName, $articleContent, $articleAuthorUID, $user->getUserName(),$articleCreationDate);
				}
			}
			return $articles;
		}
		public function getArticle(int $id)
		{
			if(file_exists("{$this->path}/$id")) {
				$file = fopen("{$this->path}/$id", "rt");
				flock($file, LOCK_SH);
				$articleName = fgets($file);
				$articleAuthorUID = fgets($file);
				$articleCreationDate = fgets($file);
				$articleContent = file_get_contents("{$this->path}/$id", false, null, ftell($file));
				fclose($file);

				$user = (getUserModelInstance())->getUserByID($articleAuthorUID);

				return new Article($id, $articleName, $articleContent, $articleAuthorUID, $user->getUserName(), $articleCreationDate);
			}
			else
				return false;
		}
		public function saveNewArticle(string $title, string $content)
		{
			//validate and encode
			$userInputErrors = $this->validateNewArticleInfo($title, $content);
			if($userInputErrors)
				return $userInputErrors;
			$title = htmlspecialchars($title, ENT_QUOTES);
			$content = htmlspecialchars($content, ENT_QUOTES);

			$userModel = getUserModelInstance();
			//get new article id
			$id = $this->getLastId() + 1;
			$uid = $userModel->getUserID();
			$author = $userModel->getUserName();
			$article = new Article($id, $title, $content, $uid, $author);
			try {
				$file = fopen("{$this->path}/$id", "xt");
				$lastIdFile = fopen("{$this->path}/lastid.data", "r+t");
				flock($file, LOCK_EX);
				flock($lastIdFile, LOCK_EX);
				
				fwrite($file, $article->title."\n");
				fwrite($file, $article->authorUID."\n");
				fwrite($file, $article->creationDate."\n");
				fwrite($file, $article->content."\n");
				
				fwrite($lastIdFile, $article->id);
				
				fclose($file);
				fclose($lastIdFile);
			}
			catch (Throwable $er) {
				if($file) {
					unlink("{$this->path}/$id");
				}
				throw $er;
			}

			return $userInputErrors;
		}
		public function deleteArticle(int $id) : bool
		{
			$article = $this->getArticle($id);

			if(false != $article) {
				$userModel = getUserModelInstance();
				$uid = $userModel->getUserID();

				if($article->authorUID == $uid) {
					$file = fopen("{$this->path}/$id", "r");
					flock($file, LOCK_EX);
					unlink("{$this->path}/$id");
					fclose($file);
					
					return true;
				}
			}
			return false;
		}
		public function getNumberOfArticles() : int
		{
			$dir = dir($this->path);

			$count = 0;
			while(false !== ($file = $dir->read())) {
				++$count;
			}
			$dir->close();

			return $count - 3; // - ".", ".." and "lastid.data"
		}
		private function getLastId() : int
		{
			$fileName = "{$this->path}/lastid.data";

			$file = fopen($fileName, "rt");
			flock($file, LOCK_SH);
			$id = fgets($file);
			fclose($file);

			return (int)$id;
		}
	}
