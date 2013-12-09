<?php
	function is_404()
	{
		global $_EF;
		if ($_EF->Request->URL->type=="404")
		{
			return true;
		}
		else
		{
			return false;
		}
	}