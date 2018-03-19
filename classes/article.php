<?php
	class Article
	{
		public $id;
		public $name;
		public $content;
		public $author;
		public $creationDate;
		private $authorUID;

		public function __construct($id, $name, $content, $author, $creationDate = "now", $uid = null)
		{
			$this->id = $id;
			$this->name = $name;
			$this->content = $content;
			$this->author = $author;
			$this->creationDate = $creationDate === "now" ? date("d.m.Y в H:i:s") : $creationDate;
			$this->authorUID = $uid;
		}
		public function getAuthorUID()
		{
			return $this->authorUID;
		}
	}