<?php
	require_once "authentication.php";
	require_once "../model/user_model.php";
	require_once "../classes/user.php";

	class RegistrationController
	{
		public function __construct()
		{
			global $auth;
			if($auth->isAuthorized()) {
				//redirect to the main page
				header("Location: /");
				exit();
			} elseif(!empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
				$userModel = new UserModel();
				$userModel->createNewUser($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);

				header("Location: login.php");
				exit();

			} // if no name or no email or no password
			elseif(!empty($_REQUEST['name']) or !empty($_REQUEST['email']) or !empty($_REQUEST['password'])) {
				header("Location: ".$_SERVER['REQUEST_URI']);
				exit();
			} else {
				//nothing to do here
			}

		}
	}

	$registrationController = new RegistrationController();