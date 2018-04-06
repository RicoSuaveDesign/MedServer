<?php
// Delete a new medicine.
// JSON array
$response = array();




//check fields
if (isset($_POST['mid'])){
		
		$mid = $_POST['mid'];
		//$response["sent_id"] = $mid;
		
		require_once __DIR__ . '/db_connect.php';
		//connect to database
		$db = new DB_CONNECT();

		$con = $db->showconn();
		
		//delete row, not safe
		$timeres = mysqli_query($con, "DELETE FROM CHECKTIMES WHERE tag_id = '$mid'");
		$result = mysqli_query($con, "DELETE FROM MEDICINES WHERE tag_id = '$mid'");
		
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
