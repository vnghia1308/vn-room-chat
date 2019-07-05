<?php
require("../../config/db.php");

if(!session_id()){
	session_start();
}

if(checkUserSession($db) !== FALSE && !empty($_POST["room_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$room_id = $_POST["room_id"]; $user_id = $user["id"];
	
	$query = mysqli_query($db, "select * from request_join where user_id=$user_id and room_id=$room_id");
	
	$joinStatus = array();
	if(mysqli_num_rows($query) == 0){
		$dateTime = date("Y-m-d H:i:s");
		mysqli_query($db, "INSERT INTO request_join(id, user_id, room_id, time) VALUES (NULL, $user_id, $room_id, '$dateTime')") or die(json_encode(array("success" => false, "message" => "Error sql query")));
		
		$joinStatus = array("success" => true, "message" => "Join success");
	} else {
		$joinStatus = array("success" => false, "message" => "Request exists");
	}
	
	echo json_encode($joinStatus);
} else
	echo "err";