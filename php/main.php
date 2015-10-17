<?php
	require_once('TwitterAPIExchange.php');

	const POST = "POST";
	const GET = "GET";
	const AVGFOLLOW = 100;

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
	$retweet_count = analyse_grades($result['statuses']);
	$responsarray = array($retweet_count/AVGFOLLOW, $retweet_count);
	$encoded = json_encode($responsarray);
	echo $encoded;
	

function api_req($qstring, $since, $settings) {
	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$getfield = '?q='.$qstring.'+since:'.$since.'&lang=en&result_type=recent&count=100';
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($settings);
	return json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(), true);
}

function analyse_grades($statuses){

	//$favorite_count = 0;
	$retweet_count = 0;
	$j = 0;
	foreach($statuses as $i){
		//$favorite_count += $i['favorites_count'];
		$retweet_count += $i['retweet_count'];
		$j++;
	}
	return $retweet_count / $j;
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
			$qstring .= "+OR+";
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