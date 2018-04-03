<?php
	require_once "modelTextFiles.php";
	require_once "../classes/article.php";
	class Model
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
		public function getLastArticles(int $number)
		{
			return $this->modelProxy->getLastArticles($number);
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
		/*
		public function getNFromUpperIdToLower($highestId, $number)
		{
			return $this->modelProxy->getNFromUpperIdToLower($highestId, $number);
		}
		public function getNFromLowerIdToUpper($lowerId, $number)
		{
			return $this->modelProxy->getNFromLowerIdToUpper($lowerId, $number);
		}
		*/
	}