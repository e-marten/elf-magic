<?php


	include("./framework/fw.inc.php");
	
	echo "<pre>".print_r($_EF,true)."</pre>";
	exit;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*  EXAMPLE DATA */
	$requested_url=$_SERVER["SERVER_NAME"]."".$_SERVER["REQUEST_URI"]; #test.fw.www-nerd.de/";	
	$i="1";
	/* /EXAMPLE DATA */

#########################################################################################
	
	/*	Find $active_page or error, load $config for $active_page	*/
	$active_page=false;
	$active_match=false;
	$config_directory=dirname(__FILE__)."/data/config/";
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

##################################################################################################
	
	/*	DB Connection	*/
	if (isset($config["db"]))
	{
		$db=$config["db"]; unset($config["db"]);
		try {
			$config["pdo"]=new PDO("mysql:host=".$db["host"].";dbname=".$db["db"],$db["user"],$db["pass"],array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		}
		catch (Exception $e)
		{
			die(json_encode("Database connection failed."));
		}
		unset($db);
	} else { die(json_encode("No database configuration.")); }
	
	
#####################################################################################################
	
	
	
	// $active_mode from $config | default: "default"
	$active_mode="default";
	if (isset($config["mode"]))
	{
		if (is_array($config["mode"]))
		{		
			foreach ($config["mode"] as $modus => $func)
			{
				if ($func())
				{
					$active_mode=$modus;
				}
			}
		}
	}

	
	header("Content-Type: text/plain; charset=utf-8");
	echo "Active Page:	".$active_page."\n";
	echo "Active Match:	".$active_match."\n";
	echo "Active Mode:	".$active_mode."\n";
	
	echo "\n";
	print_r($config);
