<?php
if(!session_id()){
	session_start();
}

require("../../config/db.php");

if(checkUserSession($db) !== TRUE){
	die(json_encode(array(
		"error" => true,
		"message" => "Session has expired"
	)));
}
if(!isset($_GET["room_id"])){
	exit;
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);

$room_id = $_GET["room_id"]; $member = false;
$roomQuery = mysqli_query($db, "select * from chat_room where room_id=$room_id") or error("Room id doesn't exist", $_HOME_FILE);
if(mysqli_num_rows($roomQuery) > 0){
	$room_data = mysqli_fetch_array($roomQuery) or error("Can't get room data", $_HOME_FILE);
			
	$mem_query = mysqli_query($db, "select * from room_member where user_id={$user["id"]} and room_id=$room_id");
	if(mysqli_num_rows($mem_query) > 0 || $user["id"] == $room_data["owner"]){
		$member = true;
	}		
} else {
	die(json_encode(array(
		"error" => true,
		"message" => "Room doesn't exists"
	)));
}

if($member == false){
	die(json_encode(array(
		"error" => true,
		"message" => "You're not member this group"
	)));
}


$messageQuery = mysqli_query($db, "select * from messages where room_id=$room_id");

$messages = array();
while($r = mysqli_fetch_array($messageQuery)){
	$user_message = searchUser_bId($db, $r["user_id"]);
	$name = $user_message["firstName"] . " " . $user_message["lastName"];
	if(!empty($user_message)){
		$message_time = format_time_ago(strtotime($r["sent_time"]));
		if(strpos($message_time, 'giây') !== false){
			$message_time = "Just now";
		}
		if($user_message["username"] == $user["username"]){	
			$_m = array(
			"id" => $r["id"], 
			"owner" => true, 
			"sender" => $name, 
			"message" => $r["message"], 
			"time_ago" => $message_time,
			"profilePicture" => $user_message["profilePicture"]
			);
			
			array_push($messages, $_m);
		} else {
			$_m = array(
				"id" => $r["id"], 
				"owner" => false, 
				"sender" => $name, 
				"message" => $r["message"], 
				"time_ago" => $message_time,
				"profilePicture" => $user_message["profilePicture"]
			);
			
			array_push($messages, $_m);
		}
	}
}

echo json_encode($messages);exit;
