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
	}