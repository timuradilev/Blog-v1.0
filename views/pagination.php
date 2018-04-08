<!-- 
		Вывод нумерации страниц 
	-->
	<nav aria-label="Page navigation">
  		<ul class="pagination">
  			<!-- предыдущая страница -->
  			<li class="page-item <?=$controller->currentPage == 1 ? "disabled" : "" ?>"><a class="page-link" href="<?=$controller->makePrevPageUrl(); ?>"><i class="fas fa-long-arrow-alt-left"></i></a></li>
  			<!-- номера страниц -->
    		<?php for($pageNumber = 1; $pageNumber <= $controller->numberOfPages; ++$pageNumber) {?>
    		<li class="page-item <?= $pageNumber == $controller->currentPage ? "active" : "" ?>"><a class="page-link" href="<?=$controller->makePageUrl($pageNumber);?>"><?=$pageNumber;?></a></li>
    		<?php } ?>
    		<!-- следующая страница -->
    		<li class="page-item <?=$controller->currentPage >= $controller->numberOfPages ? "disabled" : "" ?>"><a class="page-link" href="<?=$controller->makeNextPageUrl();?>"><i class="fas fa-long-arrow-alt-right"></i></a></li>
  		</ul>
	</nav>