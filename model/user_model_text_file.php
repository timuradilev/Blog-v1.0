<?php
	require_once "user_model.php";
	require_once "../classes/user.php";

	class UserModelTextFile extends UserModel
	{
		use UserInfoValidation;
		use Authentication;

		private $path = "../data/users/users.data";
		protected $user; // current user

		public function __construct()
		{
			//check uid in cookie
			if(isset($_COOKIE['uid']) && isset($_COOKIE['sid'])) {
				$uid = $_COOKIE['uid'];
				$sid = $_COOKIE['sid'];
				//check sid in cookie
				if(false != ($this->user = $this->getUserByID($uid))) {
					$userSID = $this->user->getSID();
					if($userSID == $sid)
						return;
				}
			}
			$this->user = User::getGuestUser();
		}
		public function createNewUser(string $name, string $email, string $password)
		{
			$userInputErrors = $this->validateNewUserInfo($name, $email, $password);
			if($userInputErrors != false)
				return $userInputErrors;

			$users = $this->getUsers();

			usort($users, function($user1, $user2) {
				return $user1->getUID() <=> $user2->getUID();
			});
			$newUID = end($users)->getUID() + 1;

			//make sid and login user 
			session_start([
							"name"            => "sid",
							"cookie_lifetime" => 2678400,
							"read_and_close"  => true
						]);
			$sid = session_id();

			try {
				$newUser = new User($name, $email, USERROLE_USER, password_hash($password, PASSWORD_DEFAULT), $newUID, $sid);

				$users[] = $newUser;
				$this->saveUsers($users);

				setcookie("uid", $newUID, time() + 2678400);
			} catch (Throwable $er) {
				session_destroy();
				setcookie("sid", "", time() - 3600);
				throw $er;
			}

			return $userInputErrors;

		}
		//returns user with given uid of false
		public function getUserByID(int $uid)
		{
			$users = $this->getUsers();

			foreach($users as $user) {
				if($uid == $user->getUID())
					return $user;
			}
			//no such user
			return false;
		}
		protected function getUserByEmail(string $email)
		{
			$users = $this->getUsers();

			foreach($users as $user) {
				if($email == $user->getEmail())
					return $user;
			}
			return false;
		}
		protected function updateUser($user)
		{
			$users = $this->getUsers();

			foreach($users as &$userOld) {
				if($user->getUID() == $userOld->getUID()) {
					$userOld = clone $user;

					$this->saveUsers($users);
					return;
				}
			}
			throw new Exception("No such user!");
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
		protected function getUsers()
		{
			$file = fopen($this->path, "r");
			flock($file, LOCK_SH);
			$usersRaw = file_get_contents($this->path);
			fclose($file);
			return unserialize($usersRaw);
		}
		protected function saveUsers($users)
		{
			$file = fopen($this->path, "r+t");
			flock($file, LOCK_EX);
			fwrite($file, serialize($users));
			fclose($file);
		}
		protected function emailExists(string $email)
		{
			$users = $this->getUsers();
			foreach($users as $user)
				if($user->getEmail() == $email)
					return true;

			return false;
		}
	}