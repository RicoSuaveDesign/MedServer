<?php

// JSON array
$response = array();

if (isset($_POST['timeid'])){

		$timeid = $_POST['timeid'];
		$checktime = $_POST['checktime'];
		$checkdate = $_POST['checkdate'];

		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();
		$con = $db->showconn();

		$result = mysqli_query($con, "UPDATE CHECKTIMES SET checkTime = '$checktime', checkDate = '$checkdate' WHERE time_id = '$timeid'");
		
		if ($result) {
		$response["success"] = 1;
		$response["message"] = "Check time edited.";
		
		
		//echo response
		echo json_encode($response);
		}  else {
		$response["success"] = 0;
		$response["message"] = "Error: Time not edited";
		
		echo json_encode($response);
	}
		

} else {
	$response["success"] = 0;
	$response["message"] = "Error: No time ID selected";
	
	echo json_encode($response);
}


?>