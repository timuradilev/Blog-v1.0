<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";
	require_once "utility.php";

	class MainPageController
	{
		private $currentPage;
		private $numberOfPages;
		private $model;
		private $userModel;
		private $protocol = "http://";

		// the main work is done in this constructor
		public function __construct()
		{
			try {
				$this->model = getArticleModelInstance();
				$this->userModel = getUserModelInstance();
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function getData($numberOfArticles)
		{
			try {
				$this->currentPage = isset($_REQUEST['page']) ? (int)$_REQUEST['page'] : 1;
				$this->numberOfPages =ceil((double)$this->model->getNumberOfArticles() / $numberOfArticles);

				if($this->currentPage >= 1 && $this->numberOfPages >= $this->currentPage) {
					$offset = $numberOfArticles * ($this->currentPage - 1);
					$articles = $this->model->getNArticles($offset, $numberOfArticles);

					return [$articles, $this->currentPage, $this->numberOfPages];
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
	list($articles, $currentPage, $numberOfPages) = $controller->getData(4);
