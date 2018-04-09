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

				$this->currentPage = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
				$this->numberOfPages =ceil((double)$this->model->getNumberOfArticles() / $this->numOfEntries);
				//when no the 'page' parameter, get the first page
				if($this->currentPage >= 1 && $this->numberOfPages >= $this->currentPage) {
					//calculate the entry number that will be the first entry on the page
					$offset = $this->numOfEntries * ($this->currentPage - 1);
					$this->articles = $this->model->getNArticles($offset, $this->numOfEntries);
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
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".($this->currentPage + 1);
		}

		public function makePrevPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".($this->currentPage - 1);
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