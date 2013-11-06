<?php

	class Engine {
		public function Engine($_init)
		{
			#echo "<pre>".print_r($_init,true)."</pre>";
			foreach ($_init as $key => $val)
			{
				$this->$key=$val;
			}
			
		}
		public function init()
		{
			$this->Request=new Request();
			$this->Config=new Config();
			$this->DB=new DB();
			if (!$this->DB->table_exists("option"))
			{
				$this->Install=new Install();
			}
		}
		
		public function update_option($name,$value)
		{
			$sql=$this->DB->prepare("SELECT * FROM [table] WHERE option_name = :name","option");
			$sql->execute(array("name"=>$name));
			$fetch=$sql->fetchAll(PDO::FETCH_ASSOC);
			if (sizeof($fetch)>=1)
			{
				// UPDATE
				#print_r($fetch);
				#echo "er will updaten weil ".sizeof($fetch);
				#echo "<br />";
				$sql=$this->DB->prepare("UPDATE [table] SET option_value = :value , last_modified = :time WHERE option_name = :name","option");
				$sql->execute(array(
					"name"=>$name,
					"value"=>$value,
					"time"=>time()
				));
			}
			else
			{
				// INSERT
				$sql=$this->DB->prepare("INSERT INTO [table]	( first_set , last_seen , last_modified , option_name , option_value , autoload )
													VALUES		( :time , :time , :time , :name , :value , :true )","option");
				$sql->execute(array(
					"name"=>$name,
					"value"=>$value,
					"time"=>time(),
					"true"=>true
				));
			}
		}
		
	}

?>
