<?php
	require_once "../classes/user.php";

	class Authentication
	{
		protected $user;
		//authentication is done here
		public function __construct()
		{
			//check uid in cookie

			//if no user with such uid
			//if
		}

		public function isAuthorized()
		{
			//return $user->getRole() != USERROLE_GUEST;
			return false;
		}
	}

	$auth = new Authentication();