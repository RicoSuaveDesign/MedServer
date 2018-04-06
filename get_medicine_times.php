<?php

// Gets all check times for one medicine, based on the id

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["mid"])) {
	$mid = $_GET['mid'];

	$con = $db->showconn();

	$result = mysqli_query($con, "SELECT * FROM CHECKTIMES WHERE tag_id = $mid");

	if(!empty($result)) {

			if(mysqli_num_rows($result) > 0) {

				$result = mysqli_fetch_array($result);

				$med = array();
				$med["timeid"] = $result["time_id"];
				$med["thetime"] = $result["checkTime"];
				$med["thedate"] = $result["checkDate"];


				$response["success"] = 1;

				$response["medicine"] = array();
				array_push($response["times"], $med);

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
