<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";

	class mainPageController
	{
		private $model;
		private $protocol = "http://";

		//preps and makes some actions like: delete articles and etc
		public function __construct()
		{
			$this->model = new Model("TextFiles", "../data/articlestextfiles");


			if(isset($_POST['deleteSubmit'])) {
				$this->deleteArticle();
				//Post/Redirection/GET method
				header("Location: ".$_SERVER['REQUEST_URI']);
				exit();
			}
		}
		//get data for the page
		public function getPage($numOfEntries)
		{
			//if no the 'page' parameter, get the first page
			if(!isset($_REQUEST['page']) || $_REQUEST['page'] >= 1) {
				//calculate the entry number that will be the first entry on the page
				$offset = $numOfEntries * (($_REQUEST['page'] ?? 1) - 1);
				$articles = $this->model->getNArticles($offset, $numOfEntries);
				return $articles;
			} else {
				//Page number error
				header("Location: 404.php");
			}
		}
		//
		private function deleteArticle()
		{
			$this->model->deleteArticle($_POST['deleteSubmit']);
		}

		public function getCurrentPage()
		{
			if(isset($_REQUEST['page']) )
				return $_REQUEST['page'];
			else
				return 1;
		}

		public function getNumberOfPages($numOfEntries) : int
		{
			$totalNumberOfEntries = $this->model->getNumberOfArticles();
			//на каждой странице будет по три статьи
			return ceil((double)$totalNumberOfEntries / $numOfEntries);
		}

		public function makePageUrl($pageNumber) : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=$pageNumber";
		}

		public function makeNextPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".(isset($_REQUEST['page']) ? $_REQUEST['page'] + 1 : 2);
		}

		public function makePrevPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".(isset($_REQUEST['page']) ? $_REQUEST['page'] - 1 : 2);
		}
	}

	$controller = new mainPageController();