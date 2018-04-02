<?php
	class Article
	{
		public $id;
		public $title;
		public $content;
		public $creationDate;
		public $authorUID;
		public $author;

		public function __construct($id, $title, $content, $uid, $author, $creationDate = "now")
		{
			$this->id = $id;
			$this->title = $title;
			$this->content = $content;
			$this->authorUID = $uid;
			$this->author = $author;
			$this->creationDate = $creationDate === "now" ? date("d.m.Y в H:i:s") : $creationDate;
		}
	}