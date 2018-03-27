<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	require_once "../controller/article_controller.php";
?>
<!doctype html>
<html lang="ru">
<head>
	<title><?= $article->name; ?>/ Blog</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.php"; ?>
<div class="container">
	<!--
		output article's name and id
	-->
	<h4><?=$article->name?></h4>
	<em class="text-secondary"><?=$article->id?></em>
	<!-- 
		delete button
	-->
	<?php if($controller->userAllowedToDelete()) : ?>
	<button type="button" class="close float-left" data-toggle="modal" data-target="#deleteArticle<?=$article->id?>" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
	</button>
	<!-- 
		Modal for delete button 
	-->
	<div class="modal fade" id="deleteArticle<?=$article->id?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  		<div class="modal-dialog" role="document">
    		<div class="modal-content">
      			<div class="modal-header">
        			<h5 class="modal-title" id="exampleModalLabel">Подтверждение удаления статьи</h5>
        			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
          				<span aria-hidden="true">&times;</span>
        			</button>
      			</div>
      			<div class="modal-body">
        			Вы действительно хотите удалить статью?
      			</div>
      			<div class="modal-footer">
        			<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
        			<form method="POST">
        				<button type="submit" class="btn btn-primary" name="deleteSubmit" value="<?=$article->id;?>">Подтвердить</button>
        			</form>
      			</div>
    		</div>
  		</div>
	</div>
	<?php endif; ?>
	<!--
		the rest of article
	-->
	<h6 class="text-secondary">Автор статьи <?=$article->author?>. Создал <?=$article->creationDate?></h6>
	<p class="text-success"><?=$article->content?></p>
	<hr> <br />
</div>	<!-- .container ends here -->
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>