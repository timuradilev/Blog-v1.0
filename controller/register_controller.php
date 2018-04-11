<?php
	require_once "../model/model.php";
	require_once "../classes/user.php";

	class RegistrationController
	{
		public $userInputErrors;
		private $userModel;

		// the main work is done in this constructor
		public function __construct()
		{
			try {
				$this->userModel = getUserModelInstance();
				if($this->userModel->isAuthorized()) {
					header("Location: /");
					exit();
				} elseif(isset($_REQUEST['action']) && $_REQUEST['action'] === "register" && !empty($_REQUEST['name']) && !empty($_REQUEST['email']) && !empty($_REQUEST['password'])) {
					$this->userInputErrors = $this->userModel->createNewUser($_REQUEST['name'], $_REQUEST['email'], $_REQUEST['password']);
					if(empty($this->userInputErrors)) {
						header("Location: /");
						exit();
					}
				}
			} catch (Throwable $ex) {
				include "../public_html/servererror.php";
				exit();
			}
		}
	}

	$controller = new RegistrationController();
