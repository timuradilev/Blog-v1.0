<?php require_once "../controller/main_page_controller.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>Блог</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.php"; ?>
<div class="container">

	<?php foreach($articles as $art) { ?>
	<h4><a class="article_title_link" href="article.php?id=<?=$art->id; ?>"><?=$art->title?></a></h4>
	
	<h6 class="text-secondary">Автор статьи <?=$art->author?>. Создал <?=$art->creationDate?></h6>
	<?php if($controller->userAllowedToDelete($art->authorUID)) : ?>
	<button type="button" class="close float-left" data-toggle="modal" data-target="#deleteArticle<?=$art->id?>" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
	</button>

	<div class="modal fade" id="deleteArticle<?=$art->id?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
        			<form action="article.php?id=<?=$art->id;?>" method="POST">
        				<button type="submit" class="btn btn-primary" name="action" value="delete">Подтвердить</button>
        			</form>
      			</div>
    		</div>
  		</div>
	</div>
	<?php endif; ?>
	
	<br>
	<p><?=$art->content?></p>
	<hr> <br />
	<?php } ?>
	<?php include "../views/pagination.php"; ?>
</div>
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>