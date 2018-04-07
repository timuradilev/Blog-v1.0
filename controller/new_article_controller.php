<?php
	require_once "../classes/article.php";
	require_once "../model/model.php";

	class NewArticleController
	{
		public $userInputErrors;
		private $model;
		private $userModel;

		//preps and actions
		public function __construct()
		{
			$this->model = getArticleModelInstance();
			$this->userModel = getUserModelInstance();

			if(!$this->userModel->isAuthorized()) {
				header("Location: /");
				exit();
			}

			//actions
			if(isset($_REQUEST['action']) && $_REQUEST['action'] === "newarticle") {
				$this->userInputErrors = $this->model->saveNewArticle($_REQUEST['title'], $_REQUEST['content']);

				if(!$this->userInputErrors) {
					header("Location: /");
					exit();
				}
			}
			elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === 'random') {
				//make random title and content
				$loremIpsum = array_map("strip_tags", file("http://loripsum.net/api/1/short/headers", FILE_IGNORE_NEW_LINES));

				$title = $loremIpsum[0];
				$content = $loremIpsum[2];

				$this->model->saveNewArticle($title, $content);

				header("Location: /");
				exit();
			}
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

	$controller = new NewArticleController();