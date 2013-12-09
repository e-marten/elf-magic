<?php

if (is_content())
{
	echo "<h1>".get_the_title()."</h1>";
	the_content();
}

if (is_404())
{
	echo "404";
}
