<?php
// JSON array
$response = array();
if (isset($_POST['mid'])){
		$tag_id = $_POST['mid'];
		$time = $_POST['checktime'];
		$date = $_POST['checkdate'];
		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();
		$con = $db->showconn();
		$result = mysqli_query($con, "INSERT INTO CHECKTIMES(checkTime, checkDate, tag_id) VALUES('$time', '$date', '$tag_id')");
		
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
	$response["message"] = "Error: No medicine ID selected";
	
	echo json_encode($response);
}
?>