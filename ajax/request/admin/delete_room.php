<?php
require("../../../config/db.php");

if(!session_id()){
	session_start();
}

$deleteStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["room_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$room_id = $_POST["room_id"];
	
	if($user["admin"] == 1){
		$delete_query = mysqli_query($db, "DELETE FROM chat_room WHERE room_id = $room_id");
	
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
			"message" => "Require admin"
		);
	}
} else {
	$deleteStatus = array(
		"success" => false,
		"message" => "Require login"
	);
}

echo json_encode($deleteStatus);