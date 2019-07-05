<?php
require("../../../config/db.php");

session_start();
error_reporting(0);

$changeStatus = array();

if(checkUserSession($db) !== False){
	if(!empty($_POST["room_name"]) && !empty($_GET["room_id"])){
		$room_id 	= (string) $_GET["room_id"];
		$roomName 	= (string) $_POST["room_name"];
		$roomDesc 	= (string) $_POST["room_desc"];
		
		$user = searchUser_bSession($db, $_COOKIE["user_session"]);
		
		$blackList = ["admin", "administrator", "gm", "fuck", "nigga", "faggot"];
		
		$room_query = mysqli_query($db, "select * from chat_room WHERE room_id = $room_id and owner = {$user["id"]}");
		if(mysqli_num_rows($room_query) > 0){
			if(strlen($roomName) <= 30){
				if(strlen($roomDesc) <= 500){			
					if(0 < count(array_intersect(array_map('strtolower', explode(' ', $roomName)), $blackList))){
						$changeStatus = array("success" => false, "message" => "Your room name contains the banned word(s)");
					} else {
						mysqli_query($db, "UPDATE chat_room SET room_name = '$roomName', room_description = '$roomDesc' WHERE room_id = '{$room_id}'") or die(json_encode(array("success" => false, "message" => "Error update sql query")));
						
						$changeStatus = array("success" => true, "message" => "Room info has changed.");
					}
				} else {
					$changeStatus = array("success" => false, "message" => "Room description must be 500 characters or less.");
				}
			} else {
				$changeStatus = array("success" => false, "message" => "Room name must be 30 characters or less.");
			}
		} else {
			$changeStatus = array("success" => false, "message" => "You're not this room owner.");
		}
	} else {
		$changeStatus = array("success" => false, "message" => "Empty data");
	}
} else {
	$changeStatus = array("success" => false, "message" => "Require login");
}

echo json_encode($changeStatus);