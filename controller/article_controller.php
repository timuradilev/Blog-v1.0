<?php
	require_once "authentication.php";
	require_once "../model/model.php";

	class ArticleController
	{
		private $model;
		private $article;
		public function __construct()
		{
			$this->model = new Model("TextFiles", "../data/articlestextfiles");
			$this->article = $this->model->getArticle($_REQUEST['id']);

			global $auth;
			if($auth->isAuthorized() && isset($_REQUEST['deleteSubmit'])) {
				$this->model->deleteArticle($_REQUEST['deleteSubmit']);

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
			global $auth;
			return $auth->isAdmin() || $auth->getUser()->getUID() == $this->article->getAuthorUID();
		}
	}

	$controller = new ArticleController();
	$article = $controller->getArticle();