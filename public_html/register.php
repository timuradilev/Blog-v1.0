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
		<div class="col-sm-4 offset-sm-4">
			<form method="post" action="register.php">
				<div class="form-group">
			    	<label for="formGroupInputName">Имя</label>
			    	<div class="input-group">
				    	<div class="input-group-prepend">
	      					<div class="input-group-text"><i class="far fa-user"></i></div>
	    				</div>
			    		<input type="text" class="form-control" id="formGroupInputName" placeholder="" name="name" required autofocus autocomplete="off" value="<?=!empty($controller->userInputErrors)? htmlspecialchars($_REQUEST['name'], ENT_QUOTES):"";?>">
			    	</div>
			    	<?php if(!empty($controller->userInputErrors['name'])): ?>
			    	<small class="text-danger"><em>Некорректное имя</em></small>
			    	<?php endif; ?>
			  	</div>
			 	<div class="form-group">
				    <label for="inputEmail1">E-mail</label>
				    <div class="input-group">
				    	<div class="input-group-prepend">
	      					<div class="input-group-text"><i class="far fa-envelope"></i></div>
	    				</div>
				    	<input type="email" class="form-control" id="inputEmail1" placeholder="" name="email" autocomplete="off" required value="<?=!empty($controller->userInputErrors)? htmlspecialchars($_REQUEST['email'], ENT_QUOTES):"";?>">
				    	<small><em>Упрощенная регистрация. Почта не проверяется на принадлежность вам.</em></small>
				    </div>
				    <?php if(!empty($controller->userInputErrors['email'])): ?>
			    	<small class="text-danger"><em><Некорректный адрес почты</em></small>
			    	<?php elseif(!empty($controller->userInputErrors) && !empty($controller->userInputErrors['emailExists'])): ?>
			    	<small class="text-danger"><em>Пользователь с такой почтой уже существует</em></small>
			    	<?php endif; ?>
			  	</div>
				<div class="form-group">
					<label for="inputPassword1">Пароль</label>
					<div class="input-group">
				    	<div class="input-group-prepend">
	      					<div class="input-group-text"><i class="fas fa-unlock"></i></div>
	    				</div>
				    	<input type="password" class="form-control" id="inputPassword1" placeholder="" name="password" autocomplete="off" required>
				    </div>
				    <?php if(!empty($controller->userInputErrors['password'])): ?>
			    	<small class="text-danger"><em>Некорретный пароль</em></small>
			    	<?php endif; ?>
				</div>
			 	<button type="submit" class="btn btn-primary" name="action" value="register">Submit</button>
			</form>
		</div>
	</div>
</div>	
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>
