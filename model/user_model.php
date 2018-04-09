<?php
	abstract class UserModel
	{
		protected $user; //current user

		abstract public function createNewUser(string $name, string $email, string $password);
		abstract public function getUserByID(int $id);
		abstract public function login(string $email, string $password);
		abstract public function logout();
		abstract public function isAuthorized();
		abstract public function isAdmin();
		abstract public function getUserID();
		abstract public function getUserName();
		abstract protected function updateUser($user);
		abstract protected function emailExists(string $email);
		abstract protected function getUserByEmail(string $email);
	}

	trait Authentication
	{
		//returns true if email and password are correct
		public function login(string $email, string $password)
		{
			$userInputErrors = $this->validateLoginInfo($email, $password);
			if($userInputErrors != false)
				return $userInputErrors;


			$user = $this->getUserByEmail($email);
			if($user && password_verify($password, $user->getHashedPassword())) {
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

				return $userInputErrors; // has to be false
			}

			$userInputErrors["wrongUserOrPassword"] = true; 
			return $userInputErrors;
		}
		public function logout()
		{
			setcookie("uid", "", time() - 3600);
			setcookie("sid", "", time() - 3600);	
		}
	}

	trait UserInfoValidation
	{
		protected function validateNewUserInfo(string $name, string $email, string $password)
		{
			$userInputErrors = false;

			if(!filter_var($name, FILTER_VALIDATE_REGEXP, [
				'options' => [
					'regexp' => "/^[a-zA-Z\d ]{3,15}$/"
				]]))
				$userInputErrors["name"] = true;
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				$userInputErrors["email"] = true;
			if($this->emailExists($email))
				$userInputErrors['emailExists'] = true;
			if(!filter_var($password, FILTER_VALIDATE_REGEXP, [
				'options' => [
					'regexp' => "/^[\d\w]{5,20}$/"
				]]))
				$userInputErrors['password'] = true;

			return $userInputErrors;
		}
		protected function validateLoginInfo(string $email, string $password)
		{
			$userInputErrors = false;

			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
				$userInputErrors["wrongUserOrPassword"] = true;
			if(!filter_var($password, FILTER_VALIDATE_REGEXP, [
				'options' => [
					'regexp' => "/^[\d\w]{3,20}$/"
				]]))
				$userInputErrors["wrongUserOrPassword"] = true;

			return $userInputErrors;
		}
	}