<?php require_once "../controller/article_controller.php"; ?>
<!doctype html>
<html lang="ru">
<head>
	<title><?= $article->title; ?>/ Блог</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.php"; ?>
<div class="container">

	<h4><?=$article->title?></h4>
  <h6 class="text-secondary">Автор статьи <?=$article->author?>. Создал <?=$article->creationDate?></h6>
	<?php if($controller->userAllowedToDelete($article->authorUID)) : ?>
	<button type="button" class="close float-left" data-toggle="modal" data-target="#deleteArticle<?=$article->id?>" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
	</button>

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
        			<form action="<?=$_SERVER['REQUEST_URI'];?>" method="POST">
        				<button type="submit" class="btn btn-primary" name="action" value="delete">Подтвердить</button>
        			</form>
      			</div>
    		</div>
  		</div>
	</div>
	<?php endif; ?>

  <br>
	<p><?=$article->content?></p>
	<hr> <br />
</div>
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>