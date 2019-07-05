<?php
require("../../../config/db.php");

if(!session_id()){
	session_start();
}

$deleteStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["user_id"])){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$user_id = $_POST["user_id"];
	
	if($user["admin"] == 1){
		$delete_query = mysqli_query($db, "DELETE FROM user WHERE id = $user_id");
	
		if($delete_query){
			$deleteStatus = array(
				"success" => true,
				"message" => "User deleted"
			);
		} else {
			$deleteStatus = array(
				"success" => false,
				"message" => "Can't delete this user"
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