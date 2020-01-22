<?php 
function milliseconds() {
    $mt = explode(' ', microtime());
    return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
}
function returntf($str)
{
	if($str=="1" || $str=="")
	{
		return "true";
	}
	else
	{
		return "false";
	}
}

?>