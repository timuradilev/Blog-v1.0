<?php
	require_once "../model/model.php";

	class ArticleController
	{
		public $article;
		private $model;
		private $userModel;
		public function __construct()
		{
			try {
				$this->model = getArticleModelInstance();
				$this->userModel = getUserModelInstance();

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

					$this->article = $this->model->getArticle($articleId);
					if (false == $this->article) {
						include "../public_html/404.php";
						exit();
					}
				} else {
					include "../public_html/404.php";
					exit();
				}
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function userAllowedToDelete()
		{
			try {
				return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $this->article->authorUID : false;
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
	$article = $controller->article;
