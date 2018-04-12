<?php
	require_once "../model/model.php";

	class ArticleController
	{
		private $model;
		private $userModel;

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
		public function getData()
		{
			try {
				if (isset($_REQUEST['id'])) {
					$articleId = (int)$_REQUEST['id'];

					if ($this->userModel->isAuthorized() && isset($_REQUEST['action']) && $_REQUEST['action'] === "delete") {
						if ($this->model->deleteArticle($articleId)) {
							header("Location: /");
							exit();
						} else {
							include "../public_html/servererror.php";
							exit();
						}
					}
					$article = $this->model->getArticle($articleId);
					if (false == $article) {
						include "../public_html/404.php";
						exit();
					}
					return $article;
				} else {
					include "../public_html/404.php";
					exit();
				}
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function userAllowedToDelete($authorUID)
		{
			try {
				return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $authorUID : false;
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
		public function isAuthorized()
		{
			try {
				return $this->userModel->isAuthorized();
			} catch (Throwable $ex) {
				return false;
			} 
		}
	}

	$controller = new ArticleController();
	$article = $controller->getData();
