<?php

require_once __DIR__ . '/db_config.php';
#API access key from Google API's Console

# TODO: query db for the user reg id

    $registrationIds = 'eZU7koTSVCE:APA91bHu2TdU0UzdmsyP65rY1RC-bx_EPJs8syAg-qNwb8KkSOUvLEXavd-oP-Bz3LfKky2tY2IgfIv0clURJp9QJejM0Wv_owIHPZu6kE76yjSmQEEf9_ZtBOVshwITTRF4iF_3rzNS';

$body = '';
$body .= 'hey look ';
$body .= 'ur too st00pid';

#prep the bundle
     $msg = array
          (
		'body' 	=> $body,
		'title'	=> 'loooool',
             	'icon'	=> 'myicon',/*Default Icon*/
              	'sound' => 'mySound'/*Default sound*/
          );
	$fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg
			);
	
	
	$headers = array
			(
				'Authorization: key=' . FIREBASE_API_KEY,
				'Content-Type: application/json'
			);


#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch );
		curl_close( $ch );
#Echo Result Of FireBase Server
echo $result;