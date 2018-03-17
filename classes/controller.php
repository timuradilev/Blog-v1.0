<?php
	require_once "model.php";
	require_once "article.php";

	class Controller
	{
		private $numberOfArticlesOnPage = 3;
		private $protocol = "http://";

		public function getPage()
		{
			$model = new Model("TextFiles", "../data/articlestextfiles");
			//если нет параметра page, то выводится первая страница
			if(!isset($_REQUEST['page']) || $_REQUEST['page'] >= 1) {
				//для нужной страницы получаем позицию статьи относительно самой новой
				$offset = $this->numberOfArticlesOnPage * (($_REQUEST['page'] ?? 1) - 1);
				//получаем статьи для нужной страницы 
				$articles = $model->getNArticles($offset,$this->numberOfArticlesOnPage);
				return $articles;
			} else {
				//неправильно указан номер страницы
			}
		}

		public function addNewArticle(string $name, string $author, string $text) : string
		{
			//проверить валидность текста
			if(!$name || !$author || !$text)
				return "Текст слишком короткий";
			$article = new Article(-1, $name, $text, $author);
			//сохранить текст
			$model = new Model("TextFiles", "../data/articlestextfiles");
			$model->saveNewArticle($article);

			return "Статья добавлена";
		}

		public function addRandomArticle()
		{
			if(isset($_GET['random'])) {
				$loremIpsum = array_map("strip_tags", file("http://loripsum.net/api/1/short/headers", FILE_IGNORE_NEW_LINES));

				$name = $loremIpsum[0];
				$text = $loremIpsum[2];
				$article = new Article(-1, $name, $text, "Generator");

				$model = new Model("TextFiles", "../data/articlestextfiles");
				$model->saveNewArticle($article);

				header("Location: {$this->makeMainPageUrl()}");
				exit();
			}
		}

		public function deleteArticle()
		{
			if(isset($_POST['deleteSubmit'])) {
				$model = new Model("TextFiles", "../data/articlestextfiles");
				$model->deleteArticle($_POST['deleteSubmit']);

				header("Location: ".$_SERVER['REQUEST_URI']);
				exit();
			}
		}

		public function getNumberOfPages() : int
		{
			$model = new Model("TextFiles", "../data/articlestextfiles");
			$count = $model->getNumberOfArticles();
			//на каждой странице будет по три статьи
			$numberOfArticlesOnPage = 3;
			return ceil((double)$count / $numberOfArticlesOnPage);
		}

		public function makePageUrl($pageNumber) : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=$pageNumber";
		}

		public function makeNextPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".(isset($_REQUEST['page']) ? $_REQUEST['page'] + 1 : 2);
		}

		public function makePrevPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME']."/?page=".(isset($_REQUEST['page']) ? $_REQUEST['page'] - 1 : 2);
		}

		public function makeMainPageUrl() : string
		{
			return $this->protocol.$_SERVER['SERVER_NAME'];
		}
	}

	$controller = new Controller();