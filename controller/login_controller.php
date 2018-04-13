<?php
	require_once "../model/model.php";
	require_once "../classes/user.php";
	require_once "utility.php";

	class LoginController
	{
		private $userModel;

		// the main work is done in this constructor
		public function __construct()
		{
			try {
				$this->userModel = getUserModelInstance();
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function executeUserActions()
		{
			try {
				if($this->userModel->isAuthorized()) { 
					if(isset($_REQUEST['action']) && $_REQUEST['action'] === "logout")
						$this->userModel->logout();
					header("Location: /");
					exit();
				} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === "login" && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
					if(empty($userInputErrors = $this->userModel->login($_REQUEST['email'], $_REQUEST['password']))) {
						header("Location: /");
						exit();
					}
					else
						return $userInputErrors;
				}
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
	}

	$controller = new LoginController();
	$userInputErrors = $controller->executeUserActions();
	
