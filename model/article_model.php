<?php
	abstract class ArticleModel
	{
		abstract public function getNArticles(int $offset, int $number);
		abstract public function getArticle(int $id);
		abstract public function saveNewArticle(string $title, string $content);
		abstract public function deleteArticle(int $id);
		abstract public function getNumberOfArticles();
	}

	trait ArticleInfoValidation
	{
		private function validateNewArticleInfo(string $title, string $content)
		{
			$errors = false;

			if(!filter_var($title, FILTER_VALIDATE_REGEXP, [
				'options' => [
					'regexp' => "/^[\d\w\p{P} ]{5,100}$/u"
				]]))
				$errors['title'] = true;
			if(strlen($content) < 5 || strlen($content) > 1000)
				$errors['content'] = true;

			return $errors;
		}
	}