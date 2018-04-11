<?php
	class Limiter
	{
		protected $count = 100;
		protected $db;
		protected $tableName;

		public function __construct($db, $tableName)
		{
			$this->db = $db;
			$this->tableName = $tableName;
		}

		public function check()
		{
			$query = "SELECT COUNT(*) as count
					  FROM {$this->tableName}";
			$stmt = $this->db->prepare($query);
			$stmt->execute();

			if($stmt->rowCount()) {
				$rowsCount = $stmt->fetch()['count'];
				if($rowsCount >= $this->count)
					throw new Exception("Limit is exceeded for table: ".$this->tableName);
			}
			else
				throw new Exception("Failed to get count in Limiter->check()");
		}
	}
