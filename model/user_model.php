<?php
	abstract class UserModel
	{
		protected $user; //current user

		abstract public function createNewUser($name, $email, $password);
		abstract public function getUserByID($id);
		abstract public function updateUser($user);
		abstract public function login($email, $password);
		abstract public function logout();
		abstract public function isAuthorized();
		abstract public function isAdmin();
		abstract public function getUserID();
		abstract public function getUserName();
		abstract protected function validateNewUserInfo($name, $email, $password);
		abstract protected function validateLoginInfo($email, $password);
		abstract protected function emailExists($email);
	}