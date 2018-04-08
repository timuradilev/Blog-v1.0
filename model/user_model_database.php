<?php
	require_once "user_model.php";
	require_once "../classes/user.php";

	class UserModelDatabase extends UserModel
	{
		use UserInfoValidation;
		use Authentication;

		protected $user; //current user
		private $database;

		public function __construct()
		{
			$connectionInfo = parse_ini_file("../db/db.ini");
			$this->database = new PDO('mysql:host='.$connectionInfo['host'].';dbname='.$connectionInfo['dbname'],
				$connectionInfo['user'],
				$connectionInfo['password'],
				[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
			);

			//check uid and sid in cookie
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
		// creates new user, saves to database and login user
		public function createNewUser($name, $email, $password)
		{
			$userInputErrors = $this->validateNewUserInfo($name, $email, $password);
			if($userInputErrors != false)
				return $userInputErrors;

			//make sid and set to cookie
			session_start([
							"name"            => "sid",
							"cookie_lifetime" => 2678400,
							"read_and_close"  => true
						]);
			$sid = session_id();

			$user = new User($name, $email, USERROLE_USER, password_hash($password, PASSWORD_DEFAULT), null, $sid);
			
			//sql
			$query = 'INSERT INTO users (name, email, role, sid, lastauthdate, password)
			  		  VALUES(:name, :email, :role, :sid, :lastauthdate, :password)';
			$stmt = $this->database->prepare($query);
			$roleToStr[USERROLE_ADMIN] = 'admin';
			$roleToStr[USERROLE_USER] = 'user';
			$stmt->execute(['name' => $user->getUserName(), 'email' => $user->getEmail(), 'role' => $roleToStr[$user->getRole()], 'sid' => $user->getSID(), 'lastauthdate' => $user->getLastAuthDate(), 'password' => $user->getHashedPassword()]);

			if($stmt->rowCount()) {
				$newUID = $this->database->lastInsertId();

				setcookie("uid", $newUID, time() + 2678400);
			}
			else
				throw new Exception("Failed to insert new user into table");

			return $userInputErrors;
		}
		public function getUserByID($uid)
		{
			//sql
			$query = 'SELECT id, name, email, role, sid, lastauthdate, password
					  FROM users
					  WHERE id = :id';
			$stmt = $this->database->prepare($query);
			$stmt->bindValue(':id', $uid, PDO::PARAM_INT);
			$stmt->execute();

			//fetch data
			if($stmt->rowCount()) {
				$result = $stmt->fetch();
				$role = $result['role'] == 'admin' ? USERROLE_ADMIN : ($result['role'] == 'user' ? USERROLE_USER : USERROLE_GUEST);
				return new User($result['name'], $result['email'], $role, $result['password'], $result['id'], $result['sid']);
			}
			else
				return false;
		}
		protected function getUserByEmail($email)
		{
			//sql
			$query = 'SELECT id, name, email, role, sid, lastauthdate, password 
					  FROM users
					  WHERE email=:email';
			$stmt = $this->database->prepare($query);
			$stmt->execute(['email' => $email]);

			if($stmt->rowCount()) {
				$result = $stmt->fetch();
				$role = $result['role'] == 'admin' ? USERROLE_ADMIN : ($result['role'] == 'user' ? USERROLE_USER : USERROLE_GUEST);
				$user = new User($result['name'], $result['email'], $role, $result['password'], $result['id'], $result['sid']);
				return $user;
			}
			else
				return false;
		}
		public function updateUser($user)
		{
			//sql
			$query = 'UPDATE users SET name=:name, email=:email, role=:role,
					  sid=:sid, lastauthdate=:lastauthdate, password=:password
					  WHERE id=:id';
			$stmt = $this->database->prepare($query);
			$stmt->bindValue(':name', $user->getUserName(), PDO::PARAM_STR);
			$stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
			$roleToStr[USERROLE_ADMIN] = 'admin';
			$roleToStr[USERROLE_USER] = 'user';
			$stmt->bindValue(':role', $roleToStr[$user->getRole()], PDO::PARAM_STR);
			$stmt->bindValue(':sid', $user->getSID(), PDO::PARAM_STR);
			$stmt->bindValue(':lastauthdate', $user->getLastAuthDate(), PDO::PARAM_STR);
			$stmt->bindValue(':password', $user->getHashedPassword(), PDO::PARAM_STR);
			$stmt->bindValue(':id', $user->getUID(), PDO::PARAM_INT);
			$stmt->execute();

			if(!$stmt->rowCount())
				throw new Exception("Couldn't update user info!");
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
		protected function emailExists($email)
		{
			$query = 'SELECT id FROM users WHERE email=:email';
			$stmt = $this->database->prepare($query);
			$stmt->execute(['email' => $email]);

			return (bool)$stmt->rowCount();
		}
	}