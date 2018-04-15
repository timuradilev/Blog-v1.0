<?php
	require_once "../classes/article.php";
	require_once "../model/model.php";
	require_once "utility.php";
	require_once "../vendor/autoload.php";

	class NewArticleController
	{		
		private $model;
		private $userModel;
		private $logger;

		// the main work is done in this constructor
		public function __construct()
		{
			try {
				$this->logger = new Monolog\Logger('my_logger');
				$this->logger->pushHandler(new Monolog\Handler\StreamHandler("../log/error.log", Monolog\Logger::DEBUG));

				$this->model = getArticleModelInstance();
				$this->userModel = getUserModelInstance();

				if(!$this->userModel->isAuthorized()) {
					header("Location: /");
					exit();
				}
			} catch (Throwable $ex) {
				$this->logger->alert($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function executeUserActions()
		{
			try {
				if(isset($_REQUEST['action']) && $_REQUEST['action'] === "newarticle" && !empty($_REQUEST['title']) && !empty($_REQUEST['content'])) {
					$userInputErrors = $this->model->saveNewArticle($_REQUEST['title'], $_REQUEST['content']);

					if(empty($userInputErrors)) {
						header("Location: /");
						exit();
					}
					else
						return $userInputErrors;
				}
				elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === 'random') {
					//make random title and content
					$loremIpsum = array_map("strip_tags", file("http://loripsum.net/api/1/short/headers", FILE_IGNORE_NEW_LINES));

					$title = $loremIpsum[0];
					$content = $loremIpsum[2];

					if(!empty($this->userInputErrors = $this->model->saveNewArticle($title, $content))) {
						include "../public_html/servererror.php";
						exit();
					}

					header("Location: /");
					exit();
				}
			} catch (Throwable $ex) {
				$this->logger->alert($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function isAuthorized()
		{
			try {
				return $this->userModel->isAuthorized();
			} catch (Throwable $ex) {
				$this->logger->critical($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				return false;
			}
		}
		public function getUserName()
		{
			try {
				return $this->userModel->getUserName();
			} catch (Throwable $ex) {
				$this->logger->error($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				return "ошибка";
			}	
		}
	}

	$controller = new NewArticleController();
	$userInputErrors = $controller->executeUserActions();
