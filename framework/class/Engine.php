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
			// Welcome, installation is done and everything is fine for a Software, isn't it? :-)
			// no, at first we will receive url object to requester
			$this->Request->URL=new URL(array(
				"url"=>$this->Request->virtual_path,
				"mode"=>$this->Config->active_mode
			));
			if ($this->Request->URL->type=="content")
			{
				$this->Content=new Content(array(
					"cid"=>$this->Request->URL->type_id
				));
			}
			// and software developers are lazy.. print-saving functions:
			$this->load_functions();
			// now SOFTWARE:
			$software_file=$this->get_option("software");
			include($software_file);
			//
			//404? etc... handle-clean-things
			// i could die, but i dont want to.. api etc
		}
		
		private function include_directory($dir)
		{
			if (substr($dir,strlen($dir)-1)!="/") { $dir.="/"; }
			if (substr_count($dir,$_SERVER["DOCUMENT_ROOT"])<=0)
			{
				$dir=dirname(dirname(__FILE__))."/".$dir."";
			}
			
			$od=opendir($dir);
			while ($rd=readdir($od))
			{
				if (substr($rd,0,1)!=".")
				{
					if (is_dir($dir.$rd))
					{
						$this->include_directory($dir.$rd."/");
					}
					else {
						include($dir.$rd);
					}
				}
			}
			closedir($od);
		}
		private function load_functions()
		{
			if (!isset($this->functions_loaded))
			{
				$this->include_directory("functions");
				$this->functions_loaded=true;
			}
		}
		
		public function get_option($name,$default=false)
		{
			$sql=$this->DB->prepare("SELECT * FROM [table] WHERE option_name = :name","option");
			$sql->execute(array("name"=>$name));
			$fetch=$sql->fetchAll(PDO::FETCH_ASSOC);
			if (sizeof($fetch)>=1)
			{
				return $fetch[0]["option_value"];
			}			
			else
			{
				return $default;
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
