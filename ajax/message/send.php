<?php
require("../../config/db.php");

session_start();
//error_reporting(0);

if(checkUserSession($db) !== FALSE && !empty($_POST["txt-message"]) && !empty($_GET["room_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$user_query = mysqli_query($db, "select * from user where username='{$user["username"]}'");
	
	$sendStatus = array();
	if(mysqli_num_rows($user_query) > 0){
			$room_id = $_GET["room_id"];
			$room_query = mysqli_query($db, "select * from chat_room where room_id=$room_id") or die(json_encode(array("success" => false, "message" => "Error sql query room_id: " . $room_id)));
			
			if(mysqli_num_rows($room_query) > 0){
				$user = searchUser_bUsername($db, $user["username"]);
				$message = str_replace("'", "&apos;" ,htmlspecialchars($_POST["txt-message"])) and str_replace('"', "&quot;" ,htmlspecialchars($_POST["txt-message"]));
				
				$dateTime = date("Y-m-d H:i:s");
				mysqli_query($db, "INSERT INTO messages(id,user_id,room_id,message,sent_time) VALUES (NULL, {$user["id"]}, $room_id, '$message', '$dateTime')") or die(json_encode(array("success" => false, "message" => "Error sql query")));
				
				$sendStatus = array("success" => true, "message" => "Send success");
			} else {
				$sendStatus = array("success" => false, "message" => "Room id doesn't exist");
			}
	} else {
		$sendStatus = array("success" => false, "message" => "Session is expired");
	}
	
	echo json_encode($sendStatus);
} else
	echo "err";
