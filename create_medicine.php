<?php

// Create a new medicine.

// JSON array
$response = array();

// dump post results to see if werking
//$file = 'postdump.txt';
//$current = file_get_contents($file);

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

		$tag_id = $obj->{'tag_id'};
		$user_id = $obj->{'uid'};
		
		$name = $obj->{'name'};
	
		$med_desc = $obj->{'med_desc'};
		
		$medFreqPerTime = $obj->{'medFreqPerTime'};

		$medFreqInterval = $obj->{'medFreqInterval'};

		$dosage = $obj->{'dosage'} ;

		$unit= $obj->{'unit'};

		//$expiration = $obj->{'expiration'};
		$expiration = "2018-03-31";

		$dosesLeft = $obj->{'dosesLeft'};



		require_once __DIR__ . '/db_connect.php';

		//connect to database
		$db = new DB_CONNECT();

		//Prepare so as to prevent sql injection
		//$stmt = $db->prepare('INSERT INTO Users(name, allergies) VALUES(?, $allergies);
		//$stmt->execute(array('name' => $name));
		// But first lets just make it work, then use prepared stmts

		$con = $db->showconn();
		
		//insert row, not safe
		$result = mysqli_query($con, "UPDATE MEDICINES SET med_name = '$name', med_desc = '$med_desc', medFreqPerTime = '$medFreqPerTime', medFreqInterval = '$medFreqInterval', dosage = '$dosage', unit = '$unit', expiration = '$expiration', dosesLeft = '$dosesLeft', taken = 0, reminded = 0, newmed = 0, stolen = 0, inOrOut = 1, user_id = '$user_id'  WHERE tag_id = '$tag_id'");

		//TODO: Drill into timecheck array and add to timecheck table.
		
		foreach (array_combine($obj->{'checkTime'}, $obj->{'checkDates'}) as $time => $date) {

			
			$timeRes = mysqli_query($con, "INSERT INTO CHECKTIMES(checkTime, checkDate, tag_id) VALUES('$time', '$date', '$tag_id')");


		}

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
