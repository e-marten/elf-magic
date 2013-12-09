<?php
function get_the_title($cid="")
{
	global $_EF;
	if (empty($cid))
	{
		$cid=$_EF->Request->URL->type_id;
		return $_EF->Content->title;
	}
	else
	{
		$content=new Content(array("cid"=>$cid));
		return $content->title;
	}
	
}