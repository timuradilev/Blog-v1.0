<?php
	require_once "article_model_text_files.php";
	require_once "article_model_database.php";
	require_once "user_model_text_file.php";
	require_once "user_model_database.php";

	//return a object of ArticleModelTextFiles or ... classes
	//the purpose of this function to choose ArticleModel* class, the site is working with, only in one place 
	function getArticleModelInstance()
	{
		//return new ArticleModelTextFiles("../data/articlestextfiles");
		return new ArticleModelDatabase();
	}
	function getUserModelInstance()
	{
		return new UserModelDatabase();
	}


	/*class Model
	{
		private $modelProxy;
		public function __construct($modelType = "DB", $path)
		{
			switch($modelType){
				case "DB":
					//$modelProxy = new ModelDB;
					break;
				case "TextFile":
					//$modelProxy = new ModelTextFile;
					break;
				case "TextFiles":
					$this->modelProxy = new ModelTextFiles($path);
					break;
				case "XML":
					//$modelProxy = new ModelXML;
					break;
				default:
					throw new Exception("Model switch error!");
			}
		}
		public function getNArticles($offset, $number)
		{
			return $this->modelProxy->getNArticles($offset, $number);
		}
		public function getArticle($id)
		{
			return $this->modelProxy->getArticle($id);
		}
		public function saveNewArticle($title, $content)
		{
			return $this->modelProxy->saveNewArticle($title, $content);
		}
		public function getLastId()
		{
			return $this->modelProxy->getLastId();
		}
		public function getNumberOfArticles()
		{
			return $this->modelProxy->getNumberOfArticles();
		}
		public function deleteArticle($id)
		{
			return $this->modelProxy->deleteArticle($id);
		}
	}
	*/