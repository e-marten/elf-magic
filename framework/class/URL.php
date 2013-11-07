<?php

class URL {
	public function URL ($s=array("id"=>0))
	{
		global $_EF;
		$filter="";
		if (!isset($s["mode"]))
		{
			$s["mode"]="default";
		}
		foreach ($s as $k => $v)
		{
			$filter.=" AND $k = :".$k." ";
		}
		$sql="SELECT * FROM [table] WHERE id != 0 ".$filter;
		$sql=$_EF->DB->prepare($sql,"url");
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
		global $_EF;
		$filter="";
		$this->exists=true;

		foreach ($s as $k => $v)
		{
			$filter.=" AND $k = :".$k." ";
		}
		$sql="SELECT * FROM [table] WHERE id != 0 ".$filter;
		$sql=$_EF->DB->prepare($sql,"url");
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
			$sql="INSERT INTO [table] ( `first_set` , `last_modified` , `last_seen` , `url` , `type` , `type_id` , `mode` , `parameters` , `status` ) ".
					" VALUES ( :first_set , :last_seen , :last_modified , :url , :type , :type_id , :mode , :parameters , :status )";
			
			
			$sql=$_EF->DB->prepare($sql,"url");
			$sql->execute($arr);
			

		}
	}
}

?>
