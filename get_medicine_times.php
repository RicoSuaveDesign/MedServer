<?php

// Gets all check times for one medicine, based on the id

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET['mid'])) {
	$mid = $_GET['mid'];

	$con = $db->showconn();

	$result = mysqli_query($con, "SELECT * FROM CHECKTIMES WHERE tag_id = $mid");

	if(!empty($result)) {

			if(mysqli_num_rows($result) > 0) {

				//$result = mysqli_fetch_array($result);
				$response["times"] = array();

				
				while($row = mysqli_fetch_array($result)) {
				$time = array();
				$time["timeid"] = $row["time_id"];
				$time["thetime"] = $row["checkTime"];
				$time["thedate"] = $row["checkDate"];
				$time["tagid"] = $row["tag_id"];

				
				array_push($response["times"], $time);
				}


				$response["success"] = 1;



				echo json_encode($response);
		} else {

				$response["success"] = 0;
				$response["message"] = "Medicine not found.";

				echo json_encode($response);
			}
	} else {
		$response["success"] = 0;
		$response["message"] = "An id was sent, and nothing was found.";

		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "An id wasn't sent.";

	echo json_encode($response);	
}
?>
