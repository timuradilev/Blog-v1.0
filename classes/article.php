<?php
	class Article
	{
		public $id;
		public $name;
		public $content;
		public $author;
		public $creationDate;

		public function __construct($id, $name, $content, $author, $creationDate = "now")
		{
			$this->id = $id;
			$this->name = $name;
			$this->content = $content;
			$this->author = $author;
			$this->creationDate = $creationDate === "now" ? date("d.m.Y в H:i:s") : $creationDate;
		}
	}