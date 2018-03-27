<?php
	require_once "../controller/authentication.php";
	require_once "../classes/article.php";
	require_once "../model/model.php";

	class NewArticleController
	{
		private $model;

		//preps and actions
		public function __construct()
		{
			global $auth;
			if(!$auth->isAuthorized()) {
				header("Location: /");
				exit();
			}

			$this->model = new Model("TextFiles", "../data/articlestextfiles");

			//actions
			if(isset($_POST['submit'])) {
				$this->addNewArticle($_POST['ArticleName'], $_POST['ArticleText']);

				header("Location: /");
				exit();
			}
			elseif(@$_GET['action'] === 'random') {
				$this->addRandomArticle();

				header("Location: /");
				exit();
			}
		}

		private function addNewArticle(string $name, string $text)
		{
			//validate data
			if(!$name || !$author || !$text)
				return "Текст слишком короткий";
			$article = new Article(null, $name, $text, $author);
			//save the article
			$this->model->saveNewArticle($article);
		}

		private function addRandomArticle()
		{
			//make random title and text
			$loremIpsum = array_map("strip_tags", file("http://loripsum.net/api/1/short/headers", FILE_IGNORE_NEW_LINES));

			$name = $loremIpsum[0];
			$text = $loremIpsum[2];
			$article = new Article(null, $name, $text, null, "now", $GLOBALS['auth']->getUserID());

			$this->model->saveNewArticle($article);
		}
	}

	$controller = new NewArticleController();