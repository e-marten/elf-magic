<?php

	array(
		"url"=>array(
			"*.fw.www-n*rd.de",
			"fw.www-nerd.de",
			"sandbox.www-nerd.de/_fw/"
		),
		"mode"=>array(
			/* "default"=>function(){	return true;	}, /* comes with new Config(); */
			"WILDCARD_mode"=>function(){ global $active_match; if (trim($active_match," /")=="*.fw.www-n*rd.de") { return true; } },
			"mytyp_3"=>function(){ return false; },
			// else: default
		),
		"db"=>array(
			"host"=>"localhost",
			"db"=>"fw.www-nerd.de_1",
			"user"=>"fw.www-nerd.de_1",
			"pass"=>"pass",
			"prefix"=>"pre_"
		)
	);


