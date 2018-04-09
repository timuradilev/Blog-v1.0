<?php
	require_once "../model/model.php";

	class ArticleController
	{
		public $article;
		private $model;
		private $userModel;
		public function __construct()
		{
			$this->model = getArticleModelInstance();
			$this->userModel = getUserModelInstance();

			if(isset($_REQUEST['id'])) {
				$articleId = (int)$_REQUEST['id'];

				if($this->userModel->isAuthorized() && isset($_REQUEST['action']) && $_REQUEST['action'] === "delete") {
					if($this->model->deleteArticle($articleId)) {
						header("Location: /");
						exit();
					} else {
						include "../public_html/servererror.php";
						exit();
					}
				}

				$this->article = $this->model->getArticle($articleId);
				if(false == $this->article) {
					include "../public_html/404.php";
					exit();
				}
			} else {
				include "../public_html/404.php";
				exit();
			}
		}
		public function userAllowedToDelete()
		{
			return $this->userModel->isAdmin() || $this->userModel->getUserID() == $this->article->authorUID;
		}
		public function getUserName()
		{
			return $this->userModel->getUserName();
		}
		public function isAuthorized()
		{
			return $this->userModel->isAuthorized();
		}
	}

	$controller = new ArticleController();
	$article = $controller->article;