<?php

// Create a new medicine.

// JSON array
$response = array();

//check fields
if (isset($_POST['user_id']) && isset($_POST['name']) && isset($_POST['medFreqPerTime']) && isset($_POST['medFreqInterval']) && isset($_POST['dosage']) && isset($_POST['unit']) && isset($_POST['expiration']) && isset($_POST['dosesLeft'])){

		$user_id = $_POST['user_id'];
		$name = $_POST['name'];
		$medFreqPerTime = $_POST['medFreqPerTime'];
		$dosage = $_POST['dosage'];
		$unit= $_POST['unit'];
		$expiration = $_POST['expiration'];
		$dosesLeft = $_POST['$dosesLeft'];
		$taken = 0;

		// this should be an array, which will later be iterated over
		$times = $_POST['$checkTimes'];

		require_once __DIR__ . '/db_connect.php';

		//connect to database
		$db = new DB_CONNECT();

		//Prepare so as to prevent sql injection
		//$stmt = $db->prepare('INSERT INTO Users(name, allergies) VALUES(?, $allergies);
		//$stmt->execute(array('name' => $name));
		// But first lets just make it work, then use prepared stmts

		$con = $db->showconn();
		
		//insert row, not safe
		$result = mysqli_query($con, "INSERT INTO Medicines(user_id, med_name, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft, taken) VALUES('$user_id', '$name', '$medFreqPerTime', '$medFreqInterval', '$dosage', '$unit', '$expiration', '$dosesLeft', '$taken')");

		//TODO: Drill into timecheck array and add to timecheck table.

		//check if success
		if ($result) {
		$response["success"] = 1;
		$response["message"] = "Medicine created.";

		//echo response
		echo json_encode($response);
	} else {

		$response["success"] = 0;
		$response["message"] = "Error: Medicine not created";

		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Error: A field is missing";

	echo json_encode($response);
}
?>
