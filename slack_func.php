<?php

function slack_api_chat_post_message($token, $channel, $text, $username){
$query = array(
	'token' => $token,
	'channel' => $channel,
	'text' => $text,
	'parse' => Null,
	'link_names' => Null,
	'attachments' => Null,
	'unfurl_links' => Null,
	'unfurl_media' => Null,
	'username' => $username,
	'as_user' => 'false',
	'icon_url' => Null,
	'icon_emoji' => Null
);

$url = 'https://slack.com/api/chat.postMessage?';
$url .= http_build_query($query);
file_get_contents($url);
}

function slack_api_channels_history(
	$token, $channel, $latest, $oldest, $inclusive, $count, $unreads){

$query = array(
	'token' => $token,
	'channel' => $channel,
	'latest' => $latest,
	'oldest' => $oldest,
	'inclusive' => $inclusive,
	'count' => $count,
	'unreads' => $unreads
);

$url = 'https://slack.com/api/channels.history?';
$url .= http_build_query($query);
$json = file_get_contents($url);
return json_decode($json, true);

}

function slack_file_download($token, $url, $filename){
$headers = array('Authorization: Bearer '.$token);
$fp = fopen ($filename, 'w+');
$ch = curl_init(str_replace(" ","%20",$url));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 50);
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$out = curl_exec($ch);
curl_close($ch);
fclose($fp);
return $out;
}

?>
