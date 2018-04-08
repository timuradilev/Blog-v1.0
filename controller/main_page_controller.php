<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";

	class MainPageController
	{
		public $articles;
		public $currentPage;
		public $numberOfPages;
		public $numOfEntries = 4;
		private $model;
		private $userModel;
		private $protocol = "http://";

		public function __construct()
		{
			try {
				$this->model = getArticleModelInstance();
				$this->userModel = getUserModelInstance();

				//when no the 'page' parameter, get the first page
				if(!isset($_REQUEST['page']) || $_REQUEST['page'] >= 1) {
					$this->currentPage = $_REQUEST['page'] ?? 1;
					//calculate the entry number that will be the first entry on the page
					$offset = $this->numOfEntries * (($_REQUEST['page'] ?? 1) - 1);
					$this->articles = $this->model->getNArticles($offset, $this->numOfEntries);

					
					$this->numberOfPages =ceil((double)$this->model->getNumberOfArticles() / $this->numOfEntries);
					 
					 if($this->currentPage > $this->numberOfPages) {
					 	include "../public_html/404.php";
					 	exit();
					 }
				} else {
					include "../public_html/404.php";
					exit();
				}
			}
			catch(Exception $ex){
				echo $ex->getMessage();
				exit();
			}
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
		public function userAllowedToDelete($authorUID)
		{
			return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $authorUID : false;
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