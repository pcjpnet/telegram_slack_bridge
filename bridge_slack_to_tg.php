<?php
require('slack_func.php');
require('telegram_func.php');

// Slack Outgoing WebHooks Token
$slack_webhook_token = 'XXXXXXXXXXXXXXXXXXXXX';

// Slack API Token
$slack_api_token = 'xoxp-123456789-123456789-123456789-abcdefghijklm';

// Telegram Bot Token
$telegram_bot_token = 'XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX';

// Telegram Chat ID
$telegram_chat_id = '-123456789';


// LOCALHOST ONLY
//if ($_SERVER["REMOTE_ADDR"] != ""){
//	exit();
//}

// GET POST DATA FROM SLACK
parse_str(file_get_contents("php://input"), $slack);
// $slack['token'];
// $slack['team_id'];
// $slack['team_domain'];
// $slack['service_id'];
// $slack['channel_id'];
// $slack['channel_name'];
// $slack['timestamp'];
// $slack['user_id'];
// $slack['user_name'];
// $slack['text'];
// $slack['trigger_word'];

if ($slack['token'] != $slack_webhook_token){
	exit();
}
if ($slack['user_name'] == 'slackbot'){
	exit();
}

if (isset($slack['text'])){
	// FOWERED TEXT MESSAGE
	telegram_send_message($telegram_bot_token,
		$telegram_chat_id,
		$slack['user_name'].'> '.$slack['text']
		);
} else {
	//Get Channnel History
	$array = slack_api_channels_history(
		$slack_api_token,
		$slack['channel_id'],
		floor($slack['timestamp'])+1,
		floor($slack['timestamp']),
		Null,
		'1',
		Null
		);

	// Found Channel Message
	if($array['ok'] == true && 
		$array['messages'][0]['subtype'] == 'file_share' && 
		$array['messages'][0]['ts'] == $slack['timestamp']){

		// File Type = JPEG
		if($array['messages'][0]['file']['filetype'] == 'jpg'){
			$dl_url = $array['messages'][0]['file']['url_private_download'];
			$filename = dirname(__FILE__).'/tmp_'.mt_rand(10000, 99999).'.jpg';
			slack_file_download($slack_api_token, $dl_url, $filename);
			$text = telegram_send_photo($telegram_bot_token, $telegram_chat_id, $filename);
			telegram_send_message($telegram_bot_token,
				$telegram_chat_id,
				$slack['user_name'].'> '.$array['messages'][0]['file']['title']
				);
			unlink($filename);
		} else {
			telegram_send_message($telegram_bot_token,
				$telegram_chat_id,
				'other file'
				);
		}
	} else {
		telegram_send_message($telegram_bot_token,
			$telegram_chat_id,
			'file not found'
			);
	}

}

?>
