<?php
// Literal copy paste of delete medicine. Maybe condense deletions into one file later?
// JSON array
$response = array();
//check fields
if (isset($_POST['timeid'])){
		
		$timeid = $_POST['timeid'];
		
		
		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();
		$con = $db->showconn();
		
		//delete row, not safe
		
		$result = mysqli_query($con, "DELETE FROM CHECKTIMES WHERE time_id = '$timeid'");
		
		//check if success
		if ($result) {
		$response["success"] = 1;
		$response["message"] = "Time deleted.";
		echo json_encode($response);
	} else {
		$response["success"] = 0;
		$response["message"] = "Error: Time not deleted";
		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Error: A field is missing";
	echo json_encode($response);
}
?>