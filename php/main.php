<?php

$fishi_str = string($_POST["grade"]);	// der string 
$whoami = $_POST["p_m"];		// true == premium || false == 08/15

$req_start = "https://api.twitter.com/1.1/";

if ($whoami)
{
	//premium
		
}
else
{
	// 08/15
	
}

function pre_pro()	// input = string || output = time_span; language (default english); 
{
	// calculate time span based on tweets length
	$time_span = intval(40*log(strlen($fishi_str), 10)++);

	// make url encoded query
	$string = array(explode(" ", $fishi_string));
	$once = false;
	$fishi_str = null;
	foreach ($string as $i) {
		if ($once) {
			$fishi_str .= "%20";
		}
		else {
			$once = true;
		}
		$fishi_str .= $i;
	}
}
?>