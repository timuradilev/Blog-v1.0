<?php
	require_once "article_model.php";
	require_once "../classes/article.php";
	require_once "user_model.php";
	
	class ArticleModelTextFiles extends ArticleModel
	{
		public $path;

		public function __construct(string $path)
		{
			$this->path = $path;
		}
		//вернуть $number статей начиная со статьи, имеющей позицию $offset по отношению к самой новой странице(у нее позиция - 0).
		public function getNArticles($offset, $number)
		{
			$articles = [];
			//определить id нужной статьи
			for($curId = $this->getLastId(), $curPos = 0; $curId && $curPos != $offset; --$curId) {
				if(file_exists("{$this->path}/$curId"))
					++$curPos;
			}
			//начать считывать в массив $articles нужное количество статей
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

					$user = (new UserModel())->getUserByID($articleAuthorUID);

					$articles[] = new Article($curId, $articleName, $articleContent, $articleAuthorUID, $user->getUserName(),$articleCreationDate);
				}
			}
			return $articles;
		}
		//возвращает статью с указанным id
		public function getArticle($id)
		{
			if(file_exists("{$this->path}/$id")) {
				$file = fopen("{$this->path}/$id", "rt");
				flock($file, LOCK_SH);
				$articleName = fgets($file);
				$articleAuthorUID = fgets($file);
				$articleCreationDate = fgets($file);
				$articleContent = file_get_contents("{$this->path}/$id", false, null, ftell($file));
				fclose($file);

				$user = (new UserModel())->getUserByID($articleAuthorUID);

				return new Article($id, $articleName, $articleContent, $articleAuthorUID, $user->getUserName(), $articleCreationDate);
			}
		}
		//сохранить новую статью
		public function saveNewArticle($title, $content)
		{
			//validate and encode
			$userInputErrors = $this->validateNewArticleInfo($title, $content);
			if($userInputErrors)
				return $userInputErrors;
			$content = htmlspecialchars($content, ENT_QUOTES);

			$userModel = new UserModel();
			//новый id
			$id = $this->getLastId() + 1;
			$uid = $userModel->getUserID();
			$author = $userModel->getUserName();
			$article = new Article($id, $title, $content, $uid, $author);
			//Создать файл
			$newFileName = (string)$id;
			if(($file = fopen("{$this->path}/$newFileName", "xt")) === false)
				throw new Exception("Failed to create a new file!");
			flock($file, LOCK_EX);
			$lastIdFileName = "{$this->path}/lastid.data";
			$lastIdFile = fopen($lastIdFileName, "r+t") or die("Не могу открыть файл!");
			flock($lastIdFile, LOCK_EX);
			ftruncate($lastIdFile, 0);
			//Записать в файл все данные
			fwrite($file, $article->title."\n");
			fwrite($file, $article->authorUID."\n");
			fwrite($file, $article->creationDate."\n");
			fwrite($file, $article->content."\n");
			//изменить lastId
			fwrite($lastIdFile, $article->id);
			//Закрыть файлы
			fclose($file);
			fclose($lastIdFile);

			return $userInputErrors;
		}
		//удалить статью
		public function deleteArticle($id) : bool
		{
			echo "test";
			//если статья сущ.
			if(file_exists("{$this->path}/$id")) {
				//если статья является самой новой и ее ИД хранится в файле lastid.data
				if($id == ($lastId = $this->getLastId())) {
					//узнать ид предыдущей по номеру ид статьи
					for(--$lastId; $lastId && !file_exists("{$this->path}/$lastId"); --$lastId);

					//записать новый lastid
					$file = fopen("{$this->path}/lastid.data", "r+t");
					flock($file, LOCK_EX);
					ftruncate($file, 0);
					fwrite($file, $lastId);
					fclose($file);
				}
				$file = fopen("{$this->path}/$id", "r");
				flock($file, LOCK_EX);
				unlink("{$this->path}/$id");
				fclose($file);
				return true;
			} else {
				return false;
			}
		}
		//вернуть общее количество существующих статей
		public function getNumberOfArticles() : int
		{
			//открыть текущий каталог
			//посчитать количество файлов, не считая файл lastid.data
			$dir = dir($this->path);

			$count = 0;
			while(false !== ($file = $dir->read())) {
				++$count;
			}

			$dir->close();

			return $count - 3; // три файла: ".", ".." и 'lastid.data'
		}
		//олучить ид последней статьи
		private function getLastId() : int
		{
			$fileName = "{$this->path}/lastid.data";

			$file = fopen($fileName, "rt") or die("Не могу открыть файл!");
			flock($file, LOCK_SH);
			$id = fgets($file);
			fclose($file);

			return (int)$id;
		}
		private function validateNewArticleInfo($title, $content)
		{
			$errors = false;

			if(!filter_var($title, FILTER_VALIDATE_REGEXP, [
				'options' => [
					'regexp' => "/^[\d\w\p{P} ]{5,100}$/"
				]]))
				$errors['title'] = "incorrect title";
			if(strlen($content) > 1000)
				$errors['content'] = "content is too long";

			return $errors;
		}
	}