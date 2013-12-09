<?php
function get_the_content($cid="")
{
	global $_EF;
	if (empty($cid))
	{
		$cid=$_EF->Request->URL->type_id;
		return $_EF->Content->content;
	}
	else
	{
		$content=new Content(array("cid"=>$cid));
		return $content->content;
	}
	
}