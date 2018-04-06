<?php

// list all meds for a user

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["uid"])) {
	$uid = $_GET['uid'];

	$con = $db->showconn();

	$result = mysqli_query($con, "SELECT * FROM MEDICINES WHERE user_id = $uid");

	if (mysqli_num_rows($result) > 0) {

	$response["medicines"] = array();

	while($row = mysqli_fetch_array($result)) {

		$medicine = array();
		$medicine["uid"] = $row["user_id"];
		$medicine["tag_id"] = $row["tag_id"];
		$medicine["name"] = $row["med_name"];
		$medicine["med_desc"] = $row["med_desc"];
		$medicine["medFreqPerTime"] = $row["medFreqPerTime"];
		$medicine["medFreqInterval"] = $row["medFreqInterval"];
		$medicine["dosage"] = $row["dosage"];
		$medicine["unit"] = $row["unit"];
		$medicine["expiration"] = $row["expiration"];
		$medicine["dosesLeft"] = $row["dosesLeft"];
		//$medicine["taken"] = $row["taken"];
		$medicine["reminded"] = $row["reminded"];
		//$medicine["newmed"] = $row["newmed"];
		//$medicine["stolen"] = $row["stolen"];
		//$medicine["inout"] = $row["inOrOut"];
		$medicine["lastout"] = $row["lastout"];

		// For whatever reason, retrofit isn't serializing tinyints as booleans so here we are
		// Using wall of if/else to force it to get trues/falses.

		if($row["newmed"] == 1) {
			$medicine["newmed"] = "true";
		}
		else {
			$medicine["newmed"] = "false";
		}

		if($row["stolen"] == 1) {
			$medicine["stolen"] = "true";
		}
		else {
			$medicine["stolen"] = "false";
		}

		if($row["inOrOut"] == 1) {
			$medicine["inOrOut"] = "true";
		}
		else {
			$medicine["inOrOut"] = "false";
		}

		if($row["taken"] == 1) {
			$medicine["taken"] = "true";
		}
		else {
			$medicine["taken"] = "false";
		}

		

		array_push($response["medicines"], $medicine);
	}

$response["success"] = 1;

echo json_encode($response);

	} else {

		$response["success"] = 0;
		$response["message"] = "No medicine found for this user";

		echo json_encode($response);
	}
}
?>
