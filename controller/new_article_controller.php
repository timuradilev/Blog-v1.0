<?php
	require_once "../model/model.php";
	require_once "../classes/article.php";

	class newArticleController
	{
		private $model;

		//preps and actions
		public function __construct()
		{
			$this->model = new Model("TextFiles", "../data/articlestextfiles");

			//actions
			if(isset($_POST['submit'])) {
				$this->addNewArticle($_POST['ArticleName'], "test", $_POST['ArticleText']);

				header("Location: /");
				exit();
			}
			elseif(@$_GET['action'] === 'random') {
				$this->addRandomArticle();

				header("Location: /");
				exit();
			}
		}

		private function addNewArticle(string $name, string $author, string $text)
		{
			//validate data
			if(!$name || !$author || !$text)
				return "Текст слишком короткий";
			$article = new Article(-1, $name, $text, $author);
			//save the article
			$this->model->saveNewArticle($article);
		}

		private function addRandomArticle()
		{
			//make random title and text
			$loremIpsum = array_map("strip_tags", file("http://loripsum.net/api/1/short/headers", FILE_IGNORE_NEW_LINES));

			$name = $loremIpsum[0];
			$text = $loremIpsum[2];
			$article = new Article(-1, $name, $text, "Generator");

			$this->model->saveNewArticle($article);
		}
	}

	$controller = new newArticleController();