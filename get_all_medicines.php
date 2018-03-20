<?php

// list all meds for a user

$response = array();

require_once __DIR__ . '/db_connect.php';

$db = new DB_CONNECT();

if (isset($_GET["uid"])) {
	$uid = $_GET['uid'];

	$result = mysql_query("SELECT * FROM MEDICINES WHERE user_id = $uid");

	if (mysql_num_rows($result) > 0) {

	$response["medicines"] = array();

	while($row = mysql_fetch_array($result)) {

		$medicine = array();
		$medicine["uid"] = $row["user_id"];
		$medicine["name"] = $row["name"];
		$medicine["medFreqPerTime"] = $row["medFreqPerTime"];
		$medicine["medFreqInterval"] = $row["medFreqInterval"];
		$medicine["dosage"] = $row["dosage"];
		$medicine["unit"] = $row["unit"];
		$medicine["expiration"] = $row["expiration"];
		$medicine["dosesLeft"] = $row["dosesLeft"];
		$medicine["taken"] = $row["taken"];

		array_push($response["medicines"], $medicine);
}

$response["success"] = 1;

echo json_encode($response);

} else {

	$response["success"] = 0;
	$response["message"] = "No medicine found for this user";

	echo json_encode($response);
}
?>
