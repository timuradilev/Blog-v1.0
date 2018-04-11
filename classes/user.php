<?php
	define("USERROLE_ADMIN", 0);
	define("USERROLE_GUEST", 1);
	define("USERROLE_USER",  2);

	class User
	{
		protected $uid;
		protected $name;
		protected $email;
		protected $role;
		protected $hashedPassword;
		protected $sid;
		protected $lastAuthDate;

		public function __construct($name, $email, $role, $hashedPassword, $uid, $sid, $lastAuthDate = null)
		{
			$this->name = $name;
			$this->email = $email;
			$this->role = $role;
			$this->hashedPassword = $hashedPassword;
			$this->uid = $uid;
			$this->sid = $sid;

			$this->lastAuthDate = time();
		}
		public static function getGuestUser() : User
		{
			return new User(null, null, USERROLE_GUEST, null, null, null);
		}
		public function getUserName()
		{
			return $this->name;
		}
		public function getEmail()
		{
			return $this->email;
		}
		public function getHashedPassword()
		{
			return $this->hashedPassword;
		}
		public function setSID($newSID)
		{
			$this->sid = $newSID;
		}
		public function getSID()
		{
			return $this->sid;
		}
		public function setUID($newUID)
		{
			$this->uid = $newUID;
		}
		public function getUID()
		{
			return $this->uid;
		}
		public function getRole()
		{
			return $this->role;
		}
		public function setNewAuthDate()
		{
			$this->lastAuthDate = time();
		}
		public function getLastAuthDate()
		{
			return $this->lastAuthDate;
		}
	}