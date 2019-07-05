<?php
require("../../config/db.php");

if(!session_id()){
	session_start();
}

$deleteStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["room_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$room_id = $_POST["room_id"];
	
	$room_query = mysqli_query($db, "select * from chat_room WHERE room_id = $room_id and owner = {$user["id"]}");
	
	if(mysqli_num_rows($room_query) > 0){
		$delete_query = mysqli_query($db, "DELETE FROM chat_room WHERE room_id = $room_id") and mysqli_query($db, "DELETE FROM room_member WHERE room_id = $room_id");
	
		if($delete_query){
			$deleteStatus = array(
				"success" => true,
				"message" => "Room deleted"
			);
		} else {
			$deleteStatus = array(
				"success" => false,
				"message" => "Can't delete this room"
			);
		}
	} else {
		$deleteStatus = array(
			"success" => false,
			"message" => "You're not this room owner"
		);
	}
} else {
	$deleteStatus = array(
		"success" => false,
		"message" => "Require login"
	);
}

echo json_encode($deleteStatus);