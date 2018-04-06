<?php
// Delete a new medicine.
// JSON array
$response = array();

$data = file_get_contents("php://input");
$obj = json_decode($data);
// dump post results to see if werking

//check fields
if (isset($obj->{'mid'})){
		
		$name = $obj->{'mid'};
		
		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();

		$con = $db->showconn();
		
		//delete row, not safe
		$result = mysqli_query($con, "DELETE FROM MEDICINES WHERE med_id = '$mid'");
		
		//check if success
		if ($result) {
		$response["success"] = 1;
		$response["message"] = "Medicine deleted.";

		echo json_encode($response);
	} else {
		$response["success"] = 0;
		$response["message"] = "Error: Medicine not deleted";

		echo json_encode($response);
	}
} else {
	$response["success"] = 0;
	$response["message"] = "Error: A field is missing";

	echo json_encode($response);
}
?>
