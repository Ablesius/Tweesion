<?php
	require_once('TwitterAPIExchange.php');
	require_once('hashtagprediction.php');

	const AVGFOLLOW = 100;

	$ini = parse_ini_file('default.ini', false);

	$apiSettings = array(
		'oauth_access_token' => $ini['oauth_access_token'],
		'oauth_access_token_secret' => $ini['oauth_access_token_secret'],
		'consumer_key' => $ini['consumer_key'],
		'consumer_secret' => $ini['consumer_secret']
	);
	$inputDbSettings = array(
		'db_host' => $ini['db_host'],
		'db_name' => $ini['db_name'],
		'db_user' => $ini['db_user'],
		'db_passwd' => $ini['db_passwd']
	);

	$fishy_str = $_POST['fishy_str'];
	$qstring = encode($fishy_str, $inputDbSettings);
	$since = timespan($fishy_str);
	$result = api_req($qstring, $since, $apiSettings);
	$retweet_count = analyse_grades($result['statuses']);
	echo json_encode(array($retweet_count/AVGFOLLOW, $retweet_count));

 function objectToArray( $object )
    {
        if( !is_object( $object ) && !is_array( $object ) )
        {
            return $object;
        }
        if( is_object( $object ) )
        {
            $object = get_object_vars( $object );
        }
        return array_map( 'objectToArray', $object );
    }

function api_req($qstring, $since, $apiSettings) {
	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	$getfield = '?q='.$qstring.'+since:'.$since.'&lang=en&result_type=recent&count=50';
	$requestMethod = 'GET';

	$twitter = new TwitterAPIExchange($apiSettings);
	return json_decode($twitter->setGetfield($getfield)->buildOauth($url, $requestMethod)->performRequest(), true);
}

function analyse_grades($statuses){

	// $favorite_count = 0;
	$retweet_count = 0;
	$j = 1;
	foreach($statuses as $i){
		// $favorite_count += $i['favorites_count'];
		$retweet_count += $i['retweet_count'];
		$j++;
	}
	return $retweet_count / $j;
}

function encode($fishy_str, $inputDbSettings) {
	// remove all words without [a-zA-Z0-9# ]
	$fishy_str = preg_replace("/[^a-zA-Z0-9# ]/u","", $fishy_str);
	// convert words to an an array
	$fs_array = explode(" ", $fishy_str);
	// filter out all words of length smaller then 4
	$j = 0;
	$fsa_new = array('');
	$words = array('');
	$hashtags = array('');
	$k = 0;
	$l = 0;
	foreach ($fs_array as $i) {
		if (strlen($i)>=4) {
			$fsa_new[$j] = $i;
			$j++;
			$i = strtolower($i);
			// create arrays for words and hashtags
			if ('#' == $i[0]) {
				$hashtags[$k] = $i;
				$k++;
			}
			else {
				$words[$l] = $i;
				$l++;
			}
		}
	}
	inputDb($inputDbSettings, $words, $hashtags);
	// create query
	$qstring = "";
	$once = false;
	foreach ($fsa_new as $i) {
		if($once) $qstring .= "+OR+".$i;
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

function inputDb($inputDbSettings, $words, $hashtags) {
	$input = new inputDb($inputDbSettings);

	$input->connectDb();
	$db_name = $input->getDatabaseName();

	// insert data into words
	foreach ($words as $i) {
		$input->sendQuery("SELECT * FROM `words` WHERE `words` = '$i';");
		// only send query if it does not already exist
		if ('int(0)' == $input->getResult()[0]->num_rows)
			$input->sendQuery("INSERT INTO `$db_name`.`words` (`id`,`words`) VALUES (NULL, '$i');");
	}

	// insert data into hashtags
	foreach ($hashtags as $i) {
		$input->sendQuery("SELECT * FROM `hashtags` WHERE `hashtags` = '$i';");
		// only send query if it does not already exist
		if ('int(0)' == $input->getResult()[0]->num_rows)
			$input->sendQuery("INSERT INTO `$db_name`.`hashtags` (`id`,`hashtags`) VALUES (NULL, '$i');");
	}

	foreach ($words as $i) {
		foreach ($hashtags as $j) {
			$input->sendQuery("SELECT * FROM `links` WHERE `word` = '$i' AND `hashtag` = '$j';");
			// only make new row if it does not already exist
			// else add one to count
			if ('int(0)' == $input->getResult()[0]->num_rows)
				$input->sendQuery("INSERT INTO `$db_name`.`links`(`word`, `hashtag`, `id`, `weight`) VALUES ('$i', '$j', NULL, '1');");
			else $input->sendQuery("UPDATE `links` SET `weight`=`weight`+1 WHERE `word` = '$i' AND `hashtag` = '$j';");
		}
	}
}
?>