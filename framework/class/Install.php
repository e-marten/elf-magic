<?php

/**
 * Description of Install
 *
 * @author Eric
 */
class Install {
	public function Install()
	{
		global $_EF;
		$this->set_tables();
		$this->create_table("option");
		$this->create_table("url");
		$this->create_table("content");
		$_EF->update_option("installed",time());
	}
	public function create_table($table)
	{
		global $_EF;
		if (!$_EF->DB->table_exists($table))
		{
			if (isset($this->tables[$table]))
			{
				$sql=$_EF->DB->prepare($this->tables[$table],$table);
				$sql->execute();
			}
		}
	}
	
	public function set_tables()
	{
		$this->tables=array(
			"option"=>"CREATE TABLE [table] (
							`id` INT( 100 ) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'AI',
							`first_set` INT( 25 ) NOT NULL ,
							`last_seen` INT( 25 ) NOT NULL ,
							`last_modified` INT( 25 ) NOT NULL ,
							`option_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'unique',
							`option_value` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
							`autoload` BOOLEAN NOT NULL ,
							UNIQUE (
								`option_name`
							)
						) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'option-table';",
			
			"url"=>"CREATE TABLE [table] (
					`id` INT( 100 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
					`first_set` INT( 25 ) NOT NULL ,
					`last_seen` INT( 25 ) NOT NULL ,
					`last_modified` INT( 25 ) NOT NULL ,
					`url` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`type` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`mode` VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`parameters` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
					`status` INT( 20 ) NOT NULL DEFAULT '200'
					) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'url-routing';",
			"content"=>"CREATE TABLE [table] (
				`id` INT(22) NOT NULL AUTO_INCREMENT PRIMARY KEY, 
				`cid` VARCHAR(44) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				`first_set` INT(22) NOT NULL, 
				`last_modified` INT(22) NOT NULL, 
				`last_seen` INT(22) NOT NULL, 
				`type` VARCHAR(44) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'post', 
				`status` VARCHAR(44) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'draft', 
				`name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				`title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				`content` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				`excerpt` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, 
				`parent` VARCHAR(44) NOT NULL, 
				`order` INT(6) NOT NULL DEFAULT '0', 
				`author` INT(6) NOT NULL DEFAULT '1', 
				`level` INT(6) NOT NULL DEFAULT '1', 
				`pass` VARCHAR(44) NOT NULL DEFAULT '', 
				`mime` VARCHAR(22) NOT NULL DEFAULT 'text/html', 
				UNIQUE (`cid`)) ENGINE = MyISAM CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT = 'content-db';"
		);
	}
}

?>
