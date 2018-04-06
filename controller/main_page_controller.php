<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";
	require_once "../model/user_model.php";

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
				$this->userModel = new UserModel();

				//if no the 'page' parameter, get the first page
				if(!isset($_REQUEST['page']) || $_REQUEST['page'] >= 1) {
					//calculate the entry number that will be the first entry on the page
					$offset = $this->numOfEntries * (($_REQUEST['page'] ?? 1) - 1);
					$this->articles = $this->model->getNArticles($offset, $this->numOfEntries);

					$this->currentPage = $_REQUEST['page'] ?? 1;
					$this->numberOfPages =ceil((double)$this->model->getNumberOfArticles() / $this->numOfEntries);
					 
					
				} else {
					//Page number error
					header("Location: 404.php");
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