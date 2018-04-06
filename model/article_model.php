<?php
	abstract class ArticleModel
	{
		abstract public function getNArticles($offset, $number);
		abstract public function getArticle($id);
		abstract public function saveNewArticle($title, $content);
		abstract public function deleteArticle($id);
		abstract public function getNumberOfArticles();
	}