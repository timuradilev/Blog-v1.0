<?php
	require_once "../classes/user.php";

	class UserModel
	{
		private $path = "../data/users/users.data";
		private $user; // current user

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
		public function createNewUser($name, $email, $password)
		{
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
			setcookie("uid", $newUID, time() + 2678400);

			$newUser = new User($name, $email, USERROLE_USER, password_hash($password, PASSWORD_DEFAULT), $newUID, $sid);

			$users[] = $newUser;
			$this->saveUsers($users);

		}
		//returns user with given uid of false
		public function getUserByID($uid)
		{
			$users = $this->getUsers();

			foreach($users as $user) {
				if($uid == $user->getUID())
					return $user;
			}
			//no such user
			return false;
		}
		public function updateUser($user)
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
		//returns true if email and password are correct
		public function login($email, $password)
		{
			$users = $this->getUsers();

			foreach($users as &$user) {
				if($email === $user->getEmail())
					if(password_verify($password, $user->getHashedPassword())) {
						//reuse sid
						session_id($user->getSID());
						session_start([
							"name"            => "sid",
							"cookie_lifetime" => 2678400,
							"read_and_close"  => true
						]);
						$user->setNewAuthDate();
						//uid
						setcookie("uid", $user->getUID(), time() + 2678400);

						//save user's data
						$this->updateUser($user);

						return true;
					}
			}
			return false;
		}
		public function logout()
		{
			setcookie("uid", "", time() - 3600);
			setcookie("sid", "", time() - 3600);	
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
		private function getUsers()
		{
			$file = fopen($this->path, "r");
			flock($file, LOCK_SH);
			$usersRaw = file_get_contents($this->path);
			fclose($file);
			return unserialize($usersRaw);
		}
		private function saveUsers($users)
		{
			$file = fopen($this->path, "r+t");
			flock($file, LOCK_EX);
			ftruncate($file, 0);
			fwrite($file, serialize($users));
			fclose($file);
		}
	}