<?php

/*
Hey i wrote this from work to the dev-branch!
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
				"cid"=>md5(time().rand(1,999).microtime(true)."WHAT?"),
				"first_set"=>time(),
				"last_modified"=>time(),
				"last_seen"=>time(),
				"type"=>"post",
				"status"=>"draft",
				"name"=>"",
				"title"=>"Untitled ".date("d.m.Y H:i:s"),
				"content"=>"",
				"excerpt"=>"",
				"parent"=>0,
				"order"=>0,
				"author"=>0,
				"level"=>0,
				"pass"=>"",
				"mime"=>"text/html"
			);
			foreach ($s as $k => $v)
			{
				if (isset($arr[$k])) { $arr[$k]=$v; }
				else
				{
					// ITS MAYBE A META OR SO...
				}
			}
			$sql="INSERT INTO [table] ( `cid` , `first_set` , `last_modified` , `last_seen` , `type` , `status` , `name` , `title` , `content` , `excerpt` , `parent` , `order` , `author` , `level` , `pass` , `mime` ) ".
					" VALUES ( :cid , :first_set , :last_modified , :last_seen , :type , :status , :name , :title , :content , :excerpt , :parent , :order , :author , :level , :pass , :mime )";
			
			#echo $sql;
			$sql=$_EF->DB->prepare($sql,"content");
			$sql->execute($arr);
			

		}
	}
}

?>
