<?php

class Config {
	public function Config()
	{
		$this->get_active_page();
		$this->get_active_mode();
	}
	public function get_active_mode()
	{
		if (!isset($this->active_mode))
		{
			$active_mode="default";
			if (isset($this->config["mode"]))
			{
				if (is_array($this->config["mode"]))
				{		
					foreach ($this->config["mode"] as $modus => $func)
					{
						if ($func())
						{
							$active_mode=$modus;
						}
					}
				}
			}
			$this->active_mode=$active_mode;
			unset($this->mode);
		}
		return $this->active_mode;
	}
	public function get_active_page()
	{
		if (!isset($this->active_page))
		{
			global $_EF;
			/*	Find $active_page or error, load $config for $active_page	*/
			$active_page=false;
			$active_match=false;
			$requested_url=$_EF->Request->get_identifier_domain();
			$config_directory=$_EF->data_directory."config/";
			$_EF->config_directory=$config_directory;
			if ($od=opendir($config_directory))
			{
				$configs=array();
				while ($rd= readdir($od))
				{
					if (substr($rd,0,1)!="." AND $rd!="index.php" AND $rd!="default.php")
					{
						array_push($configs,$rd);
					}
				}
				closedir($od);

				if (file_exists($config_directory."default.php")) { array_push($configs,"default.php"); }

				foreach ($configs as $rd)
				{
					if (!$active_page)
					{
						$conf=  file_get_contents($config_directory.$rd);
						$this_file=str_replace(".php","",$rd); // Pagename!
						eval("\$config=".str_replace("<?php","",  str_replace("<?php", "", $conf)));
						// Is this page the active requested page?
						if (isset($config["url"]))
						{
							if (!is_array($config["url"])) { $config["url"]=array($config["url"]); }
							foreach ($config["url"] as $url)
							{
								$r=  trim($requested_url," /");
								$u=  trim($url," /");
								if (fnmatch($u, $r))
								{
									$active_page=$this_file;
									$active_match=$u;
								}
							}
						}
					}
				}

				if (!$active_page) { die(json_encode("Page not found.")); }
			}	else	{ die(json_encode("Software error: No configuration.")); }

			$this->active_page=$active_page;
			$this->active_match=$active_match;
			foreach ($config as $key => $val)
			{
				$this->$key=$val;
			}
		}
		return $this->active_page;
		
	}
}

?>
