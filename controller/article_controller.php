<?php
	require_once "../model/model.php";

	class ArticleController
	{
		private $model;
		private $userModel;
		private $article;
		public function __construct()
		{
			$this->model = getArticleModelInstance();
			$this->userModel = getUserModelInstance();
			$this->article = $this->model->getArticle($_REQUEST['id']);
			if(false == $this->article) {
				include "../public_html/404.php";
				exit();
			}
			if($this->userModel->isAuthorized() && isset($_REQUEST['action']) && $_REQUEST['action'] === "delete") {
				$this->model->deleteArticle($_REQUEST['id']);

				header("Location: /");
				exit();
			}
		}
		public function getArticle()
		{
			return $this->article;
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
	$article = $controller->getArticle();