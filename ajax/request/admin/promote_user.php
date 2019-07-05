<?php
require("../../../config/db.php");

if(!session_id()){
	session_start();
}

$promoteStatus = array();
if(checkUserSession($db) !== False && !empty($_POST["user_id"]) && ($_POST["role"] == 1 or $_POST["role"] == 0)){
	$user = searchUser_bSession($db, $_COOKIE["user_session"]);
	$user_id = $_POST["user_id"];
	
	if($user["admin"] == 1){
		$promote_query = mysqli_query($db, "UPDATE user SET admin = {$_POST["role"]} WHERE id = $user_id");
	
		if($promote_query){
			if($_POST["role"] == 1){
				$promoteStatus = array(
					"success" => true,
					"message" => "User's promoted"
				);
			} else {
				$promoteStatus = array(
					"success" => true,
					"message" => "User's unpromoted"
				);
			}
		} else {
			$promoteStatus = array(
				"success" => false,
				"message" => "Can't promote/unpromote this user"
			);
		}
	} else {
		$promoteStatus = array(
			"success" => false,
			"message" => "Require admin"
		);
	}
} else {
	$promoteStatus = array(
		"success" => false,
		"message" => "Require session & POST/GET data "
	);
}

echo json_encode($promoteStatus);