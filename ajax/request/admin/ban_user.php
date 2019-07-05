<?php
require("../../../config/db.php");

if(!session_id()){
	session_start();
}

$banStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["user_id"]) && !empty($_POST["reason"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$user_id 	= $_POST["user_id"];
	$ban_reason = $_POST["reason"];
	
	$ban_check = mysqli_query($db, "select * from ban_list where user_id = $user_id");
	
	if($user["admin"] == 1){
		if(mysqli_num_rows($ban_check) < 1){
			$dateTime = date("Y-m-d H:i:s");
			$ban_query = mysqli_query($db, "INSERT INTO ban_list(id, user_id, ban_reason, banned_time) VALUES (NULL, $user_id, '$ban_reason', '$dateTime')");
		
			if($ban_query){
				mysqli_query($db, "UPDATE user SET session = '' WHERE id = $user_id");
				$banStatus = array(
					"success" => true,
					"message" => "User's banned"
				);
			} else {
				$banStatus = array(
					"success" => false,
					"message" => "Can't ban this user"
				);
			}
		} else {
			$banStatus = array(
			"success" => false,
			"message" => "User's was banned"
		);
		}
	} else {
		$banStatus = array(
			"success" => false,
			"message" => "Require admin"
		);
	}
} else {
	$banStatus = array(
		"success" => false,
		"message" => "Require login"
	);
}

echo json_encode($banStatus);