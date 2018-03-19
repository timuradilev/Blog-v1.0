<?php
	require_once "../classes/user.php";
	require_once "../model/user_model.php";

	class Authentication
	{
		protected $user;
		//authentication is done here
		public function __construct()
		{
			//check uid in cookie
			if(isset($_COOKIE['uid'])) {
				$uid = $_COOKIE['uid'];

				if(false != ($this->user = (new UserModel)->getUserByID($uid)))
					return;
			}

			//if no user with such uid or no uid
			$this->user = User::getGuestUser();
		}

		public function isAuthorized()
		{
			return $this->user->getRole() != USERROLE_GUEST;
		}
		public function isAdmin()
		{
			return $this->user->getRole() == USERROLE_ADMIN;
		}
		public function getUserID()
		{
			return $this->user->getUID();
		}
		public function getUserName()
		{
			return $this->user->getUserName();
		}
	}

	$auth = new Authentication();