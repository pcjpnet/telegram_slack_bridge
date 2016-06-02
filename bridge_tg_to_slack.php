<?php
require('slack_func.php');

// Slack API Token
$slack_api_token = 'xoxp-123456789-123456789-123456789-abcdefghijklm';

// Telegram Chat ID to Slack Channel
$chatid_list = array(
	'-123456789' => array('#general', $slack_api_token),
	'-123456789' => array('#lounge', $slack_api_token),
);

// LOCALHOST ONLY
//if ($_SERVER["REMOTE_ADDR"] != ""){
//	exit();
//}

$post = file_get_contents("php://input");
$data = json_decode($post, true);
$text = $data['message']['text'];
$chatid = $data['message']['chat']['id'];
$username = $data['message']['from']['username'];
$first_name = $data['message']['from']['first_name'];
$last_name = $data['message']['from']['last_name'];

if (!isset($chatid_list[$chatid])) {
	exit();
} else {
	slack_api_chat_post_message(
		$chatid_list[$chatid][1],
		$chatid_list[$chatid][0],
		$text, $username.' (from Telegram)'
	);
}

?>
