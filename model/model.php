<?php
	require_once "article_model_text_files.php";
	require_once "article_model_database.php";
	require_once "user_model_text_file.php";
	require_once "user_model_database.php";

	//return a object of ArticleModelTextFiles or ... classes
	//the purpose of this function to choose ArticleModel* class, the site is working with, only in one place 
	function getArticleModelInstance()
	{
		//return new ArticleModelTextFiles("../data/articlestextfiles");
		return new ArticleModelDatabase();
	}
	function getUserModelInstance()
	{
		return new UserModelDatabase();
		//return new UserModelTextFile();
	}
	