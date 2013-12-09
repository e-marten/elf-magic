<?php

	function the_title($cid="")
	{
		$c= get_the_title($cid);
		echo $c;
		return $c;
}