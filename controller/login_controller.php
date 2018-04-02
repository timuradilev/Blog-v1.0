<?php
	//first, check whether user is authorized
	require_once "../model/user_model.php";
	require_once "../classes/user.php";

	class LoginController
	{
		private $userModel;
		public function __construct()
		{
			$this->userModel = new UserModel();
			if($this->userModel->isAuthorized()) { // if user or admin
				if(isset($_REQUEST['action']) && $_REQUEST['action'] === "logout") {
					$this->userModel->logout();

					//redirect to the main page
					header("Location: /");
					exit();
				}
				else { //authorized user can only logout
					//redirect to the main page
					header("Location: /");
					exit();
				}
			} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === "login") {
				if($this->userModel->login($_REQUEST['email'], $_REQUEST['password'])) {
					//redirect to the main page
					header("Location: /");
					exit();
				} else {
					//if no such user or in case of incorrect password
					//redirect to this page to switch from POST to GET
					header("Location: ".$_SERVER['REQUESTED_URI']);
					exit();
				}
			} else {
				//nothing to do here. Show the login form further
			}
		}
	}

	$controller = new LoginController();