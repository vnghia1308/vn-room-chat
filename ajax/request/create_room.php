<?php
require("../../config/db.php");

if(!session_id()){
	session_start();
}

if(checkUserSession($db) !== FALSE){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$room_name = $_POST["room_name"];
	$room_desc = $_POST["room_desc"];
	$createStatus = array();
	
	$user_id = (int)$user["id"];
	if($limit_create_mode && $user["admin"] != 1){ /* value.php */
		$room_query = mysqli_query($db, "select * from chat_room where owner=$user_id");
		
		if(mysqli_num_rows($room_query) >= $limit_create_count){
			die(json_encode(array(
				"success" => false,
				"message" => "Can't create room. Only $limit_create_count rooms can be created"
			)));
		}
	}
	if(strlen($room_name) <= 30){
		if(strlen($room_desc) <= 500){
				$dateTime = date("Y-m-d H:i:s");
				$insert_query = mysqli_query($db, "INSERT INTO chat_room(room_id,room_name,room_description,owner,created_time) VALUES (NULL, '$room_name', '$room_desc', $user_id, '$dateTime')");
				
				if($insert_query){
					$createStatus = array(
						"success" => true,
						"message" => "Room created. Back to My room to see it"
					);
				} else {
					$createStatus = array(
						"success" => false,
						"message" => "Can't create your room"
					);
				}
		} else {
			$createStatus = array("success" => false, "message" => "Room description must be 500 characters or less.");
		}
	} else {
		$createStatus = array("success" => false, "message" => "Room name must be 30 characters or less.");
	}
	
	
	echo json_encode($createStatus);
}