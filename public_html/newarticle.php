<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	include_once "../controller/new_article_controller.php";
?>

<!DOCTYPE html>
<html lang='ru'>
<head>
	<meta charset='utf-8'>
	<title>Новая статья</title>
	<!--<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>-->
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.php"; ?>
<div class="container">
	<!--
		Форма ввода названия и текста статьи
	-->
	<h4>Разместить статью</h4>
	<form action="newarticle.php" method="POST">
  		<div class="form-group">
    		<label for="ArticleNameInput">Заголовок</label>
    		<input type="text" class="form-control" id="ArticleNameInput" placeholder="Название статьи" name="title" required>
    		<?php if(!empty($controller->userInputErrors) && !empty($controller->userInputErrors['title'])): ?>
    			<small class="text-danger"><em><?=$controller->userInputErrors['title'];?></em></small>
    		<?php endif; ?>
  		</div>
  		<div class="form-group">
    		<label for="TextInput">Текст</label>
    		<textarea class="form-control" id="TextInput" rows="15" name="content" required></textarea>
    		<?php if(!empty($controller->userInputErrors) && !empty($controller->userInputErrors['content'])): ?>
    			<small class="text-danger"><em><?=$controller->userInputErrors['content'];?></em></small>
    		<?php endif; ?>
  		</div>
  		<button type="submit" class="btn btn-success" name="action" value="newarticle">Опубликовать</button>
	</form>
</div>
	<?php include "../views/body_footer_template.html"; ?>
</body>