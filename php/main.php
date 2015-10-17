<?php
/*
$fishi_str = $_POST["grade"];
$whoami = $_POST["p_m"]; // true == premium || false == 08/15
*/
// debug routine
if(isset($_POST['submit'])) {
    $fishi_str = $_POST['fishi_str'];
    $whoami = $_POST['p_m'];
} 

$fishi_str = data_proc($fishi_str);

$key = " DdMn8EydPcd2I4A2qjCAX9TQH";
$secret = "Buh2YPnFFrAgUxMmWxPiILo4XXDkn9AaSohFpzyctug0F0wKwa";

$req_start = "https://api.twitter.com/1.1/search/tweets.json?q=";
$tokenUrl = "https://api.twitter.com/oauth2/token";

$fishi_str = data_proc($fishi_str);

$auth = base64_encode(urlencode($key) . ':' . urlencode($secret));
$getToken = curl_init();
    curl_setopt($getToken, CURLOPT_URL, $tokenUrl);
    curl_setopt($getToken, CURLOPT_POST, 1);
    curl_setopt($getToken, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $auth));
    curl_setopt($getToken, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
    curl_setopt($getToken, CURLOPT_RETURNTRANSFER, 1);
	$token = json_decode(curl_exec($getToken));
    $token = $token->access_token;
curl_close($getToken);
if ($whoami)
{
	//premium
	
}
else
{
	// 08/15
	$quest = $req_start . $fishi_str;
	$curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $quest);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
    $result = curl_exec($curl);
    curl_close($curl);
    
    $tweets = json_decode($result);
    
    var_dump($tweets);
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

<!-- html code for debugging routine -->
<form method="post" action="?submit=true">
    <p>Premium</p>
    <select name="p_m">
        <option value="true" selected="selected">true</option>
        <option value="false">false</option>
    </select>
    <p>Input fishi_str</p>
    <input name="fishi_str" type="text">
    <input name="submit" type="submit" value="Submit">
</form>
