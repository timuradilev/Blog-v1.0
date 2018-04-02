<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";
	require_once "../model/user_model.php";

	class MainPageController
	{
		private $model;
		private $userModel;
		private $protocol = "http://";

		public function __construct()
		{
			$this->model = new Model("TextFiles", "../data/articlestextfiles");
			$this->userModel = new UserModel();
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
		public function getCurrentPageNumber()
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
		public function userAllowedToDelete($article)
		{
			return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $article->authorUID : false;
		}
		public function isAuthorized()
		{
			return $this->userModel->isAuthorized();
		}
		public function getUserName()
		{
			return $this->userModel->getUserName();
		}
	}

	$controller = new MainPageController();