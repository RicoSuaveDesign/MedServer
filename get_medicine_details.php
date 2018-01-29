<?php

// Gets the details on one medicine.

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["mid"])) {
	$mid = $_GET['mid'];

	$result = mysql_query("SELECT * FROM Medicine WHERE id = $mid");

	if(!empty($result)) {

	if(mysql_num_rows($result) > 0) {

		$result = mysql_fetch_array($result);

		$med = array();
		$med["mid"] = $result["mid"];
		$med["name"] = $result["name"];
		$med["medFreqPerTime"] = $result["medFreqPerTime"];
		$med["medFreqInterval"] = $result["$medFreqInterval"];
		$med["dosage"] = $result["dosage"];
		$med["unit"] = $result["unit"];
		$med["taken"] = $result["taken"];

		// ADD CHECK TIME CODE HERE WHEN IS INSERTABLE

		$response["success"] = 1;

		$response["medicine"] = array();
		array_push($response["medicine"], $medicine);

		echo json_encode($response);
} else {

		$response["success"] = 0;
		$response["message"] = "Medicine not found.";

		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Medicine not found, and no rows returned.";

	echo json_encode($response);
} else {

	$response["success"] = 0;
	$response["message"] = "A medicine id wasn't sent by clicking on that medicine.";

	echo json_encode($response);
}
?>
