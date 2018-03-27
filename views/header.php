<div class="container">
	<!--
		Название сайта и ссылка на создание статей
	-->
	<div class="row align-items-center mt-3 mb-3">
	<div class="col-md-6">
		<a class="h4" href="/">Блог</a>
	</div>
	<div class="col-md-6">
		<?php if($auth->isAuthorized()): //not guest?>
		<a class="btn btn-info" href="newarticle.php?action=random">Random</a>
		<a class="btn btn-dark" href="newarticle.php">Написать</a>
		<a class="btn btn-warning float-right" href="login.php?action=logout">Выйти</a>
		<?php else: ?>
		<a class="btn btn-dark float-right" href="register.php">Регистрация</a>
		<a class="btn btn-info float-right mr-2" href="login.php">Войти</a>
		<?php endif; ?>
	</div>
	</div>
</div>
<hr>