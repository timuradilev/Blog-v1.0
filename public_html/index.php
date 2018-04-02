<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	//get the main page controller
	require_once "../controller/main_page_controller.php";
	//get data (3 is number of articles)
	$articles = $controller->getPage(3);
	//get the total number of pages
	$numberOfPages = $controller->getNumberOfPages(3);
	//get the current page
	$currentPage = $controller->getCurrentPageNumber();
	// TESTING below
	//$model = new Model("TextFiles", "/var/www/timur.com/data/articlestextfiles");
	//$model->deleteArticle(13);

	//$article = new Article(0, "name7", "content7", "author7");
	//$controller = new Controller();
	//$controller->addNewArticle("name8", "author8", "content8");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<title>База знаний по веб-программированию</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.php"; ?>
<div class="container">
	<?php include "../views/pagination.php"; ?>
	<!--
		output articles
	-->
	<?php foreach($articles as $art) { ?>
	<h4><a class="article_title_link" href="article.php?id=<?=$art->id; ?>"><?=$art->title?></a></h4>
	<em class="text-secondary"><?=$art->id?></em>
	<!-- 
		delete button
	-->
	<?php if($controller->userAllowedToDelete($art)) : ?>
	<button type="button" class="close float-left" data-toggle="modal" data-target="#deleteArticle<?=$art->id?>" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
	</button>
	<!-- 
		Modal for delete button 
	-->
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
	<!--
		the rest of article
	-->
	<h6 class="text-secondary">Автор статьи <?=$art->author?>. Создал <?=$art->creationDate?></h6>
	<p class="text-success"><?=$art->content?></p>
	<hr> <br />
	<?php } ?>
	<?php include "../views/pagination.php"; ?>
</div>	<!-- .container ends here -->
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>