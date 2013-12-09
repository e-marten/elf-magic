<?php

class DB {
	public function DB()
	{
		$this->connect();


		
		/*
		if ($this->table_exists("test1"))
		{
			echo "Table test1 exists<br />";
		}/**/
		
	}
	
	public function table_exists($table)
	{
		$table=$this->pre_table.$table;
		$tables=$this->get_all_tables();
		return in_array($table,$tables);
	}
	
	public function get_all_tables()
	{
		if (!isset($this->all_tables))
		{
			$tables=$this->prepare("SHOW TABLES");
			$tables->execute();
			$temp=$tables->fetchAll(PDO::FETCH_NUM);
			$tables=array();
			foreach ($temp as $t)
			{
				array_push($tables,$t[0]);
			}
			$this->all_tables=$tables;
		}
		return $this->all_tables;
	}
	
	public function connect()
	{
		global $_EF;
		/*	DB Connection	*/
		if (isset($_EF->Config->db))
		{
			$db=$_EF->Config->db;
			if (!isset($db["prefix"])) { $db["prefix"]=""; }
			$this->pre_table=$db["prefix"];
			unset($_EF->Config->db);

			try {
				$this->pdo=new PDO("mysql:host=".$db["host"].";dbname=".$db["db"],$db["user"],$db["pass"],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			}
			catch (Exception $e)
			{
				die(json_encode("Database connection failed."));
			}
			unset($db);
		} else { die(json_encode("No database configuration.")); }
	}
	
	public function prepare($sql,$table="")
	{
		if (!empty($table))
		{
			$sql=str_replace("[table]",$this->pre_table.$table,$sql);
		}
		$this->last_sql=$sql;
		return $this->pdo->prepare($sql);
	}
}

?>
