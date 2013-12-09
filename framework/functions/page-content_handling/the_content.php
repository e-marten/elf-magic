<?php

	function the_content($cid="")
	{
		$c= get_the_content($cid);
		echo $c;
		return $c;
}