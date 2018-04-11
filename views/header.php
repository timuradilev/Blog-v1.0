<div class="container">
	<div class="row align-items-center mt-3 mb-3">
		<div class="col-sm-4">
			<a class="h4" href="/">Блог</a>
		</div>
		<?php if($controller->isAuthorized()): ?>
		<div class="col-sm-5">
			<a class="btn btn-info" href="newarticle.php?action=random">Random</a>
			<a class="btn btn-dark" href="newarticle.php">Написать</a>
		</div>
		<div class="col-sm-3">
			<span class="h4 btn"><?=$controller->getUserName(); ?></span>
			<a class="btn btn-warning float-right" href="login.php?action=logout">Выйти</a>
		</div>
		<?php else: ?>
		<div class="col-sm-8">
			<a class="btn btn-dark float-right" href="register.php">Регистрация</a>
			<a class="btn btn-info float-right mr-2" href="login.php">Войти</a>
		</div>
		<?php endif; ?>
	</div>
</div>
<hr>