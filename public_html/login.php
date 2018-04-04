<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	require_once "../controller/login_controller.php";
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
		<div class="col-sm-4 offset-sm-4">
			<form method="post" action="login.php">
				<div class="form-group">
				    <label for="inputEmail1">E-mail</label>
				    <div class="input-group">
				    	<div class="input-group-prepend">
	      					<div class="input-group-text"><i class="far fa-envelope"></i></div>
	    				</div>
				    	<input type="email" class="form-control" id="inputEmail1" placeholder="" name="email" required value="<?=!empty($controller->userInputErrors)? htmlspecialchars($_REQUEST['email'], ENT_QUOTES):"";?>">
					</div>
				</div>
			  	<div class="form-group">
				    <label for="inputPassword1">Пароль</label>
				    <div class="input-group">
				    	<div class="input-group-prepend">
	      					<div class="input-group-text"><i class="fas fa-unlock"></i></div>
	    				</div>
				    	<input type="password" class="form-control" id="
				    inputPassword1" placeholder="" name="password" required>
				    </div>
			  	</div>
			  <button type="submit" class="btn btn-primary" name="action" value="login">Submit</button>
			</form>
			<br>
			<em class="text-danger"><?=$controller->userInputErrors['wrongUserOrPassword'] ?? ""?></em>
		</div>
	</div>
</div> <!-- .container -->

	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>
