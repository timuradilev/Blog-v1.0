<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	require_once "../controller/login_controller.php";

	//require_once "../classes/user.php";
	//$user = new User("admin", "admin@adilev.ru", USERROLE_ADMIN, password_hash("12345", PASSWORD_DEFAULT), 1, null);
	//$user2 = clone $user;
	//echo password_hash("12345", PASSWORD_DEFAULT);
	//$file = fopen("../data/users/users.data", "wt");
	//$unser = file_get_contents("../data/users/users.data");
	//$arr = unserialize($unser);
	//echo password_verify("12345", $arr[0]->getHashedPassword());
	//$user = $arr[0];
	//print_r($user);
	//print_r($arr[1]);
	//ftruncate($file, 0);
	//$ser = serialize([$user]);
	//echo "<pre>".$ser."</pre>";
	//fwrite($file, $ser);
	//fclose($file);
?>

<!doctype HTML>
<html lang="ru">
<head>
	<title>Авторизация</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-4 offset-md-4">
			<form method="post" action="login.php">
			  <div class="form-group">
			    <label for="inputEmail1">Email address</label>
			    <input type="email" class="form-control" id="inputEmail1" placeholder="Enter email" name="email">
			  </div>
			  <div class="form-group">
			    <label for="inputPassword1">Password</label>
			    <input type="password" class="form-control" id="inputPassword1" placeholder="Password" name="password">
			  </div>
			  <button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
</div> <!-- .container -->

	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>
