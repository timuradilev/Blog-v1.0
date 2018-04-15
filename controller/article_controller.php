<?php
	require_once "../model/model.php";
	require_once "utility.php";
	require_once "../vendor/autoload.php";

	class ArticleController
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
			} catch (Throwable $ex) {
				$this->logger->alert($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function getData()
		{
			try {
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
					$article = $this->model->getArticle($articleId);
					if (false == $article) {
						include "../public_html/404.php";
						exit();
					}
					return $article;
				} else {
					include "../public_html/404.php";
					exit();
				}
			} catch (Throwable $ex) {
				$this->logger->alert($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function userAllowedToDelete($authorUID)
		{
			try {
				return $this->userModel->isAuthorized() ? $this->userModel->isAdmin() || $this->userModel->getUserID() == $authorUID : false;
			} catch (Throwable $ex) {
				$this->logger->error($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
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
		public function isAuthorized()
		{
			try {
				return $this->userModel->isAuthorized();
			} catch (Throwable $ex) {
				$this->logger->alert($ex->getMessage(), [$ex->getFile() => $ex->getLine()]);
				return false;
			} 
		}
	}

	$controller = new ArticleController();
	$article = $controller->getData();
