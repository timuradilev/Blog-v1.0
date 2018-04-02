<?php
	require_once "../model/model.php";
	require_once "../model/user_model.php";

	class ArticleController
	{
		private $model;
		private $userModel;
		private $article;
		public function __construct()
		{
			$this->model = new Model("TextFiles", "../data/articlestextfiles");
			$this->userModel = new UserModel();
			$this->article = $this->model->getArticle($_REQUEST['id']);
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
			return $this->userModel->isAdmin() || $this->userModel->getUserID() == $this->article->getAuthorUID();
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