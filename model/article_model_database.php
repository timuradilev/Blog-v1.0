<?php
	require_once "article_model.php";
	require_once "../classes/article.php";
	require_once "user_model.php";

	class ArticleModelDatabase extends ArticleModel
	{
		private $database;

		public function __construct()
		{
			$connectionInfo = parse_ini_file("../db/db.ini");
			$this->database = new PDO('mysql:host='.$connectionInfo['host'].';dbname='.$connectionInfo['dbname'],
				$connectionInfo['user'],
				$connectionInfo['password'],
				[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
			);
		}
		//вернуть $number статей начиная со статьи, имеющей позицию $offset по отношению к самой новой странице(у нее позиция - 0).
		public function getNArticles($offset, $number)
		{
			$articles = [];
			
			//query
			$query = 'SELECT id, title, creationdate, authoruid, content
					  FROM articles 
					  ORDER BY id DESC 
					  LIMIT :offset,:number';
			$statement = $this->database->prepare($query);
			$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
			$statement->bindValue(':number', $number, PDO::PARAM_INT);
			$statement->execute();
			
			//fetch data
			while($art = $statement->fetch(PDO::FETCH_ASSOC)){
				$authorName = (new UserModel())->getUserByID($art['authoruid'])->getUserName();
				$articles[] = new Article($art['id'], $art['title'], $art['content'], $art['authoruid'], $authorName, $art['creationdate']);
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

			//make Article object
			$userModel = new UserModel();
			$uid = $userModel->getUserID();
			$article = new Article(null, $title, $content, $uid, null);

			//save to database
			$query = 'INSERT INTO articles
					  (title, creationdate, authoruid, content)
					  VALUES (:title, :creationdate, :authoruid, :content)';
			$statement = $this->database->prepare($query);
			$statement->execute(['title' => $article->title, 'creationdate' => date("Y-m-d H-i-s", $article->creationDate), 'authoruid' => $article->authorUID, 'content' => $article->content ]);

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