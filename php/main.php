<?php
$fishi_str = $_POST["grade"];
$whoami = $_POST["p_m"]; // true == premium || false == 08/15

$req_start = "https://api.twitter.com/1.1/search/tweets.json?q=";
$fishi_str = data_proc($fishi_str);
echo $fishi_str;
if ($whoami)
{
	//premium

}
else
{
	// 08/15

}

function data_proc($fishi_str) {
	// calculate time span based on tweets length
	$time_span = intval(40*log(strlen($fishi_str), 10)+1);
	// calculate since for twitter api
	$since = date('Y-m-d', strtotime('-'.$time_span.'day'));

	// make url encoded query
	$fishi_str = urlencode($fishi_str);

	return $fishi_str;
}
?>
