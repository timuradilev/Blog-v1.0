<?php
	require_once "../model/user_model.php";
	require_once "../classes/user.php";

	class RegistrationController
	{
		public $userInputErrors;
		private $userModel;
		public function __construct()
		{
			$this->userModel = new UserModel();
			if($this->userModel->isAuthorized()) {
				//redirect to the main page
				header("Location: /");
				exit();
			} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === "register") {
				if(!empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
					$this->userInputErrors = $this->userModel->createNewUser($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);

					if(empty($this->userInputErrors)) {
						header("Location: /");
						exit();
					}
				}
				else {
					header("Location: ".$_SERVER['REQUEST_URI']);
					exit();
				}
			} else {
				//nothing to do here
			}

		}
	}

	$controller = new RegistrationController();