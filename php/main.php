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

	if (isset($_POST['fishy_str'])) $fishy_str = $_POST['fishy_str']; else $fishy_str = "";
	$qstring = encode($fishy_str);
	$since = timespan($fishy_str);
	$result = api_req($qstring, $since, $settings);
	list($average_favs, $average_rts) = analyse_grades($result['statuses']);
	var_dump($average_favs);
	var_dump($average_rts);
	var_dump($result);

	// $api_data = api($fishy_str);
	// echo json_encode(array($api_data));

function api_req($qstring, $since, $settings) {
	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$getfield = '?q='.$qstring.' since:'.$since.'&lang=en&result_type=recent&count=1';
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	return json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(), true);
	// return $twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest();
}

function analyse_grades($statuses){

	$favorite_count = 0;
	$retweet_count = 0;

	foreach($statuses as $astatus){
		$favorite_count += $astatus['favorite_count'];
		$retweet_count += $astatus['retweet_count'];

		return array(($favorite_count / count($statuses)), ($retweet_count / count($statuses)));
	}

}

function encode($fishy_str) {
	$fishy_str = preg_replace("/[^a-zA-Z0-9# ]/u","", $fishy_str); 
	$fs_array = explode(" ", $fishy_str);
	$j = 0;
	$fsa_new = array();
	foreach ($fs_array as $i) {
		if (strlen($i)>=4) {
			$fsa_new[$j] = $i;
			$j++;
		}
	}
	$qstring = "";
	$once = false;
	foreach ($fsa_new as $i) {
		if($once) {
			$qstring .= "+";
			$qstring .= $i;
		}
		else {
			$qstring = $i;
			$once = true;
		}
	}
	return $qstring;
}

function timespan($fishy_str) {
	// calculate time span based on tweets length
	$time_span = intval(40*log(strlen($fishy_str), 10)+1);
	// calculate since for twitter api
	$since = date('Y-m-d', strtotime('-'.$time_span.'day'));
	return $since;
}
?>