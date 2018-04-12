<?php
	require_once "../model/model.php";
	require_once "../classes/user.php";

	class RegistrationController
	{
		private $userModel;

		// the main work is done in this constructor
		public function __construct()
		{
			try {
				$this->userModel = getUserModelInstance();

				if($this->userModel->isAuthorized()) {
					header("Location: /");
					exit();
				}
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
		public function executeUserActions()
		{
			try {
				if(isset($_REQUEST['action']) && $_REQUEST['action'] === "register" && !empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
					$userInputErrors = $this->userModel->createNewUser($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);
					if(empty($userInputErrors)) {
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

	$controller = new RegistrationController();
	$userInputErrors = $controller->executeUserActions();
