<?php
	require_once('TwitterAPIExchange.php');

	const POST = "POST";
	const GET = "GET";

	$ini = parse_ini_file('default.ini', false);
	$settings = array(
    	'oauth_access_token' => $ini['oauth_access_token'],
    	'oauth_access_token_secret' => $ini['oauth_access_token_secret'],
    	'consumer_key' => $ini['consumer_key'],
    	'consumer_secret' => $ini['consumer_secret']
	);

	$fishy_str = $_POST['fishy_str'];
	// make url encoded query
	$fishy_str = urlencode($fishy_str);
	$api_data = api($fishy_str);
	echo json_encode(array($api_data));

function api($fishy_str) {

	// $twitter = new TwitterAPIExchange($settings);

	// $twitter->setGetfield($getfield)
 //    ->buildOauth($url, $requestMethod)
 //    ->performRequest();
	$percentage = "0.5";
	return $percentage;
}
?>