<?php
require("../../../config/db.php");

if(!session_id()){
	session_start();
}

$unbanStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["user_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$user_id 	= $_POST["user_id"];
	
	$ban_check = mysqli_query($db, "select * from ban_list where user_id = $user_id");
	
	if($user["admin"] == 1){
		if(mysqli_num_rows($ban_check) > 0){
			$ban_query = mysqli_query($db, "DELETE FROM ban_list Where user_id = $user_id");
		
			if($ban_query){
				mysqli_query($db, "UPDATE user SET session = '' WHERE id = $user_id");
				$unbanStatus = array(
					"success" => true,
					"message" => "User's un-banned"
				);
			} else {
				$unbanStatus = array(
					"success" => false,
					"message" => "Can't un-banned this user"
				);
			}
		} else {
			$unbanStatus = array(
			"success" => false,
			"message" => "User's not banned"
		);
		}
	} else {
		$unbanStatus = array(
			"success" => false,
			"message" => "Require admin"
		);
	}
} else {
	$unbanStatus = array(
		"success" => false,
		"message" => "Require login"
	);
}

echo json_encode($unbanStatus);