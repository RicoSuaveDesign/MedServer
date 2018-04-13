<?php

// Gets the details on one medicine.

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["mid"])) {
	$mid = $_GET['mid'];

	$con = $db->showconn();

	$result = mysqli_query($con, "SELECT * FROM MEDICINES WHERE tag_id = $mid");

	if(!empty($result)) {

			if(mysqli_num_rows($result) > 0) {

				$result = mysqli_fetch_array($result);

				$med = array();
				$med["mid"] = $result["tag_id"];
				$med["name"] = $result["med_name"];
				$med["medFreqPerTime"] = $result["medFreqPerTime"];
				$med["medFreqInterval"] = $result["medFreqInterval"];
				$med["dosage"] = $result["dosage"];
				$med["unit"] = $result["unit"];
				$med["taken"] = $result["taken"];

				// ADD CHECK TIME CODE HERE WHEN IS INSERTABLE

				$response["success"] = 1;

				$response["medicine"] = array();
				array_push($response["medicine"], $med);

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
