<?php

// Update user. Initially used to keep track of med reminder's token, assuming one user.

// JSON array
$response = array();

		$token = $_POST['token'];
		//$response["sent_id"] = $mid;
		
		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();

		$con = $db->showconn();
		
		//update row, not safe
		$user_update = mysqli_query($con, "UPDATE USERS SET token = '$token'");

?>