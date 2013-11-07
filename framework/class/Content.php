<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Content
 *
 * @author Eric
 */
class Content {
	public function Content ($s=array("cid"=>"0"))
	{
		global $_EF;
		foreach ($s as $k => $v)
		{
			$filter.=" AND $k = :".$k." ";
		}
		$sql="SELECT * FROM [table] WHERE id != 0 ".$filter;
		$sql=$_EF->DB->prepare($sql,"content");
		$sql->execute($s);
		$catch=$sql->fetch(PDO::FETCH_ASSOC);
		if (sizeof($catch)>1)
		{
			$this->exists=true;
			foreach ($catch as $k => $v)
			{
				$this->$k=$v;
			}
		}
		else {
			$this->exists=false;
		}
	}
	public function create($s)
	{
		$this->exists=true;
		global $_EF;
		$filter="";

		foreach ($s as $k => $v)
		{
			$filter.=" AND $k = :".$k." ";
		}
		$sql="SELECT * FROM [table] WHERE id != 0 ".$filter;
		$sql=$_EF->DB->prepare($sql,"content");
		$sql->execute($s);
		$catch=$sql->fetch(PDO::FETCH_ASSOC);
		if (sizeof($catch)>1)
		{
			foreach ($catch as $k => $v)
			{
				$this->$k=$v;
			}
			
		}
		else
		{
			// cid,first_set,last_modified,last_seen,type,status,name,title,content,excerpt,parent,order,author,level,pass,mime
			$arr=array(
				"first_set"=>time(),
				"last_modified"=>time(),
				"last_seen"=>time(),
				"url"=>"/",
				"type"=>"content",
				"type_id"=>md5("content1"),
				"mode"=>"default",
				"parameters"=>  json_encode(new stdClass()),
				"status"=>200
			);
			foreach ($s as $k => $v)
			{
				$arr[$k]=$v;
			}
			$sql="INSERT INTO [table] ( `first_set` , `last_seen` , `last_modified` , `url` , `type` , `type_id` , `mode` , `parameters` , `status` ) ".
					" VALUES ( :first_set , :last_seen , :last_modified , :url , :type , :type_id , :mode , :parameters , :status )";
			
			
			$sql=$_EF->DB->prepare($sql,"content");
			$sql->execute($arr);
			

		}
	}
}

?>
