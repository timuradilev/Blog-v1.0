<?php
	//first, check whether user is authorized
	require_once "../model/model.php";
	require_once "../classes/user.php";

	class LoginController
	{
		public $userInputErrors;
		private $userModel;
		public function __construct()
		{
			$this->userModel = getUserModelInstance();

			if($this->userModel->isAuthorized()) { 
				if(isset($_REQUEST['action']) && $_REQUEST['action'] === "logout")
					$this->userModel->logout();

				header("Location: /");
				exit();
			} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === "login" && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
				if(empty($this->userInputErrors = $this->userModel->login($_REQUEST['email'], $_REQUEST['password']))) {
					//redirect to the main page
					header("Location: /");
					exit();
				}
			}
		}
	}

	$controller = new LoginController();