<?php

// Create a new user of the med reminder. 
// For now fingerprint is just a number,
// but position can be grabbed from search.

// JSON array
$response = array();

//check fields
if (isset($_POST['name']) && isset($_POST['allergies'])){

	$name = $POST['name'];
	$allergies = $POST['allergies'];

	require_once __DIR__ . '/db_connect.php';

	//connect to database
	$db = new DB_CONNECT();

	//Prepare so as to prevent sql injection
	//$stmt = $db->prepare('INSERT INTO Users(name, allergies) VALUES(?, $allergies);
	//$stmt->execute(array('name' => $name));
	// But first lets just make it work, then use prepared stmts
	
	//insert row, not safe
	$result = mysql_query("INSERT INTO Users(name, allergies) VALUES('$name', '$allergies')");

	//check if success
	if ($result) {
	$response["success"] = 1;
	$response["message"] = "User created.";

	//echo response
	echo json_encode($response);
} else {

	$response["success"] = 0;
	$response["message"] = "Error: User not created";

	echo json_encode($response);
} else {
	//required field is missing
	$response["success"] = 0;
	$response["message"] = "A required field is missing.";

	echo json_encode($response);
}
?>
