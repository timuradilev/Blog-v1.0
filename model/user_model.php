<?php
	require_once "../classes/user.php";

	class UserModel
	{
		protected $path = "../data/users/users.data";

		//public function __construct()
		//{

		//}
		public function getUsers()
		{
			$usersRaw = file_get_contents($this->path);
			return unserialize($usersRaw);
		}
		public function createNewUser($name, $email, $password)
		{
			$users = $this->getUsers();

			usort($users, function($user1, $user2) {
				return $user1->getUID() <=> $user2->getUID();
			});
			$newUID = end($users)->getUID() + 1;

			$newUser = new User($name, $email, USERROLE_USER, password_hash($password, PASSWORD_DEFAULT), $newUID);

			$users[] = $newUser;
			$file = fopen($this->path, "wt");
			fwrite($file, serialize($users));
			fclose($file);
			print_r($users);

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

					$file = fopen($this->path, "wt");
					fwrite($file, serialize($users));
					fclose($file);

					return;
				}
			}
			throw new Exception("No such user!");
		}
		public function logout($user)
		{
			session_start(["name" => "sid"]);

			setcookie("uid", "", time() - 3600);
			setcookie(session_name(), "", time() - 3600);

			$_SESSION = [];
			//$sessionName = session_name();
			unset($_COOKIE[session_name()]);
			session_destroy();

			

			$user->setSID(null);
			$this->updateUser($user);
		}
	}