<?php

function telegram_send_message($token, $chatid, $text){
$url = 'https://api.telegram.org/bot'.$token.'/sendMessage?chat_id=';
$url .= $chatid.'&text='.urlencode($text);
file_get_contents($url);
}

function telegram_send_photo($token, $chatid, $filename){

$url = 'https://api.telegram.org/bot'.$token.'/sendPhoto';

$post_fields = array('chat_id' => $chatid,
	'photo' => new CURLFile($filename)
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	'Content-Type:multipart/form-data'
));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
return curl_exec($ch);

}



?>
