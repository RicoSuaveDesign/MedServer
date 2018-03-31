<?php

// Create a new medicine.

// JSON array
$response = array();

// dump post results to see if werking
$file = 'postdump.txt';
$current = file_get_contents($file);

$data = file_get_contents("php://input");
$obj = json_decode($data);

/*$current .= $obj->{'uid'};
$current .= "\r\n";
$current .= $obj->{'name'};
$current .= "\r\n";
$current .= $obj->{'medFreqPerTime'};
$current .= "\r\n";
$current .= $obj->{'medFreqInterval'};
$current .= "\r\n";
$current .= $obj->{'dosage'};
$current .= "\r\n";
$current .= $obj->{'unit'};
$current .= "\r\n";
$current .= $obj->{'expiration'};
$current .= "\r\n";
$current .= $obj->{'dosesLeft'};
$current .= "\r\n";
$current .= $obj->{'checkTime'};
$current .= "\r\n";

foreach ($obj->{'checkTime'} as $value) {

	$current .= $value;
	$current .= "\r\n";
}
*/
//check fields
if (isset($obj->{'uid'}) && isset($obj->{'name'}) && isset($obj->{'medFreqPerTime'}) && isset($obj->{'medFreqInterval'}) && isset($obj->{'dosage'}) && isset($obj->{'unit'}) && isset($obj->{'expiration'}) && isset($obj->{'dosesLeft'})){

		$user_id = $obj->{'uid'};
		$current .= $user_id;
		$current .= "\r\n";
		
		$name = $obj->{'name'};
		$current .= $name;
		$current .= "\r\n";
		
		$medFreqPerTime = $obj->{'medFreqPerTime'};
		$current .= $medFreqPerTime;
		$current .= "\r\n";

		$medFreqInterval = $obj->{'medFreqInterval'};
		$current .= $medFreqInterval;
		$current .= "\r\n";

		$dosage = $obj->{'dosage'} ;
		$current .= $dosage;
		$current .= "\r\n";

		$unit= $obj->{'unit'};
		$current .= $unit;
		$current .= "\r\n";

		//$expiration = $obj->{'expiration'};
		$expiration = "2018-03-31";
		$current .= $expiration;
		$current .= "\r\n";

		$dosesLeft = $obj->{'dosesLeft'};
		$current .= $dosesLeft;
		$current .= "\r\n";

		$taken = 0;

		// this should be an array, which will later be iterated over
		//$times = (array) $_POST['$checkTime[]'];
		//$current .= $times[0] + "\n";


		require_once __DIR__ . '/db_connect.php';

		//connect to database
		$db = new DB_CONNECT();

		//Prepare so as to prevent sql injection
		//$stmt = $db->prepare('INSERT INTO Users(name, allergies) VALUES(?, $allergies);
		//$stmt->execute(array('name' => $name));
		// But first lets just make it work, then use prepared stmts

		$con = $db->showconn();
		
		//insert row, not safe
		$result = mysqli_query($con, "INSERT INTO MEDICINES(user_id, med_name, medFreqPerTime, medFreqInterval, dosage, unit, expiration, dosesLeft, taken) VALUES('$user_id', '$name', '$medFreqPerTime', '$medFreqInterval', '$dosage', '$unit', '$expiration', '$dosesLeft', '$taken')");

		//TODO: Drill into timecheck array and add to timecheck table.
		foreach ($obj->{'checkTime'} as $value) {

			// VERY TEMPORARY to put checktimes all on a single medicine. 
			// Medicines will actually be an update to a medicine with how the sys will work.
			$value += "0";
			$timeRes = mysqli_query($con, "INSERT INTO CHECKTIMES(checkTime, tag_id) VALUES($value, 1)");
			if ($timeRes){
			$current .= "time added";
			$current .= "\r\n";}
			else {
				$current .= "time not added";
				$current .= "\r\n";
			}


		}

		//check if success
		if ($result) {
		$response["success"] = 1;
		$response["message"] = "Medicine created.";

		$current .= "Medicine created";


		file_put_contents($file, $current);

		//echo response
		echo json_encode($response);
	} else {

		$response["success"] = 0;
		$response["message"] = "Error: Medicine not created";

		$current .= "Medicine not created";
		file_put_contents($file, $current);

		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Error: A field is missing";

	$current .= "Error: A field is missing";

	file_put_contents($file, $current);

	echo json_encode($response);
}
?>
