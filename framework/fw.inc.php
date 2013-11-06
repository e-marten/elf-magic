<?php
	
/*
 * AUTOLOADER
 */
	function autoloader($classname)
	{
		global $_EF_AUTOLOAD_DIRECTORIES;
		$found=false;
		foreach ($_EF_AUTOLOAD_DIRECTORIES as $dir)
		{
			if (file_exists($dir.$classname.".php") AND !$found)
			{
				require_once($dir.$classname.".php");
			}
		}
		return $found;
	}
	$_EF_AUTOLOAD_DIRECTORIES=array(
		dirname(__FILE__)."/class/"
	);
	spl_autoload_register("autoloader");
/*
 * [END]AUTOLOADER
 */
	
/*
 * Prepare Engine Configuration $_init
 */
	function set_init($key,$value,$overwrite=false)
	{
		global $_init;
		if (!is_array($_init)) { $_init=array(); }
		if (!isset($_init[$key]) OR $overwrite)
		{
			$_init[$key]=$value;
		}
	}

	set_init("initial_directory",dirname($_SERVER["SCRIPT_FILENAME"])."/",false);
	set_init("framework_directory",dirname(__FILE__)."/",true);
	set_init("use_template",true);	//3.paramenter-default=false
	set_init("data_directory",dirname(dirname(__FILE__))."/data/");
/*
 * [END]Prepare Engine Configuration $_init
 */
	
	
	
/*
 * START!
 */
	$_EF=new Engine($_init);
	$_EF->init();