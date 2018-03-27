<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	require_once "../controller/register_controller.php";
?>

<!doctype html>
<html lang='ru'>
<head>
	<title>Регистрация пользователя</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<div class="container">
	<div class="row">
		<div class="col-md-4 offset-md-4">
			<form method="post" action="register.php">
				<div class="form-group">
			    	<label for="formGroupInputName">Name</label>
			    	<input type="text" class="form-control" id="formGroupInputName" placeholder="Name" name="name">
			  	</div>
			 	<div class="form-group">
				    <label for="inputEmail1">Email address</label>
				    
				    <input type="email" class="form-control" id="inputEmail1" placeholder="Enter email" name="email">
				    <small><em>Упрощенная регистрация. Почта не проверяется.</em></small>
			 		<br>
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
