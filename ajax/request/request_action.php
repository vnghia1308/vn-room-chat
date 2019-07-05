<?php
require("../../config/db.php");

if(!session_id()){
	session_start();
}

$actionStatus = array();
if(checkUserSession($db) == TRUE &&
	!empty($_POST["user_id"]) && !empty($_POST["room_id"])){
	$user_id = $_POST["user_id"];
	$room_id = $_POST["room_id"];
	
	if($_GET["do"] == "approve"){
		$query = mysqli_query($db, "select * from request_join where user_id=$user_id and room_id=$room_id") or die(json_encode(array("success" => false, "message" => "Error sql query")));
		if(mysqli_num_rows($query) > 0){
			try {
				$dateTime = date("Y-m-d H:i:s");
				mysqli_query($db, "INSERT INTO room_member(id,user_id,room_id,join_date) VALUES (NULL,$user_id,$room_id,'$dateTime')") or die(json_encode(array("success" => false, "message" => "Can't add this user to that room member")));
			} finally {
				mysqli_query($db, "DELETE FROM request_join WHERE user_id=$user_id and room_id=$room_id");
				
				$actionStatus = array("success" => true, "message" => "Approve successfully");
			}
		}
	} elseif($_GET["do"] == "reject") {
		$query = mysqli_query($db, "select * from request_join where user_id=$user_id and room_id=$room_id") or die(json_encode(array("success" => false, "message" => "Error sql query")));
		if(mysqli_num_rows($query) > 0){
			mysqli_query($db, "DELETE FROM request_join WHERE user_id=$user_id and room_id=$room_id");
				
			$actionStatus = array("success" => true, "message" => "Reject successfully");
		} else {
			$actionStatus = array("success" => true, "message" => "Request doesn't exist");
		}
	} elseif($_GET["do"] == "delete_member") {
		$query = mysqli_query($db, "select * from room_member where user_id=$user_id and room_id=$room_id") or die(json_encode(array("success" => false, "message" => "Error sql query")));
		if(mysqli_num_rows($query) > 0){
			mysqli_query($db, "DELETE FROM room_member WHERE user_id=$user_id and room_id=$room_id");
			$actionStatus = array("success" => true, "message" => "Delete member success");
		} else {
			$actionStatus = array("success" => false, "message" => "This member's no longer a member of this group");
		}
	} else {
		$actionStatus = array("success" => false, "message" => "empty data");
	}
	
	
} else {
	$actionStatus = array("success" => false, "message" => "Require login");
}

echo json_encode($actionStatus);