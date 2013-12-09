<?php
	function is_content()
	{
		global $_EF;
		if ($_EF->Request->URL->type=="content")
		{
			return true;
		}
		else
		{
			return false;
		}
	}