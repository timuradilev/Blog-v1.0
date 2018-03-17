<?php
	setlocale(LC_CTYPE, 'ru.RU.UTF-8');
	date_default_timezone_set("Europe/Moscow");	
	error_reporting(E_ALL);
	ini_set("display_errors", "on");

	include_once "../classes/controller.php";

	$numberOfPages = $controller->getNumberOfPages();

	if(isset($_REQUEST['page']) )
		$currentPage = $_REQUEST['page'];
	else
		$currentPage = 1;


	//$model = new Model("TextFiles", "/var/www/timur.com/data/articlestextfiles");
	//$model->deleteArticle(13);

	//удаляем статью, если был запрос
	$controller->deleteArticle();
	//генерация случайной статьи
	$controller->addRandomArticle();
	//получаем статьи для текущей страницы
	$articles = $controller->getPage();
	//$article = new Article(0, "name7", "content7", "author7");
	//$controller = new Controller();
	//$controller->addNewArticle("name8", "author8", "content8");
	
	//$controller = new Controller();
?>
<!doctype html>
<html lang="ru">
<head>
	<title>База знаний по веб-программированию</title>
	<?php include "../views/head_template.html"; ?>
</head>
<body>
<?php include "../views/header.html"; ?>
<div class="container">
	<?php include "../views/pagination.html"; ?>
	<!--
		Вывод статей
	-->
	<?php foreach($articles as $art) { ?>
	<h4><?=$art->name?></h4>
	<em class="text-secondary"><?=$art->id?></em>
	<!-- 
		Удаление статьи
	-->
	<button type="button" class="close float-left" data-toggle="modal" data-target="#deleteArticle<?=$art->id?>" aria-label="Close">
  		<span aria-hidden="true">&times;</span>
	</button>

	<!-- 
		Modal 
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
        			<form method="POST">
        				<button type="submit" class="btn btn-primary" name="deleteSubmit" value="<?=$art->id;?>">Подтвердить</button>
        			</form>
      			</div>
    		</div>
  		</div>
	</div>
	<!--
		Вывод остальной части статьи
	-->
	<h6 class="text-secondary">Автор статьи <?=$art->author?>. Создал <?=$art->creationDate?></h6>
	<p class="text-success"><?=$art->content?></p>
	<hr> <br />
	<?php } ?>
	<?php include "../views/pagination.html"; ?>
</div>	<!-- .container ends here -->
	<?php include "../views/body_footer_template.html"; ?>
</body>
</html>