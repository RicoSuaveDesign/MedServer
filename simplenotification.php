<?php

require_once __DIR__ . '/db_connect.php';
#API access key from Google API's Console



    $db = new DB_CONNECT();
    $con = $db->showconn();
    $result = mysqli_query($con, "SELECT token FROM USERS WHERE user_id = 1");

if (mysqli_num_rows($result) > 0) {

	while($row = mysqli_fetch_array($result)) {
	

  
    
    $registrationIds = $row["token"];
    //echo $registrationIds;


$body = 'Take';
if($argc > 1)
{
	
     for($i = 1; $i<$argc; $i++){
        $body .= " ";
        $body .= $argv[$i];

     }
}
else
{
     $body .= " a medicine";
}




#prep the bundle
     $msg = array
          (
		'body' 	=> $body,
		'title'	=> 'Take your medicine',
             	'icon'	=> 'myicon',/*Default Icon*/
              	'sound' => 'mySound'/*Default sound*/
          );
	$fields = array
			(
				'to'		=> $registrationIds,
				'notification'	=> $msg,
				'priority' => 'high'
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
}}