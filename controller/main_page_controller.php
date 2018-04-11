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
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
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
			try {
				return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $authorUID : false;
			} catch (Throwable $ex) {
				return false;
			}
		}
		public function isAuthorized()
		{
			try {
				return $this->userModel->isAuthorized();
			} catch (Throwable $ex) {
				return false;
			}
		}
		public function getUserName()
		{
			try {
				return $this->userModel->getUserName();
			} catch (Throwable $ex) {
				return "ошибка";
			}
		}
	}

	$controller = new MainPageController();
