<?php
require("../../../config/db.php");

session_start();
error_reporting(0);

$changeStatus = array();

if(checkUserSession($db) !== False){
	if(!empty($_POST["cr_password"]) && !empty($_POST["nw_password"])){
		$current_password 	= (string) $_POST["cr_password"];
		$new_password 		= (string) $_POST["nw_password"];
		
		$user = searchUser_bSession($db, $_COOKIE["user_session"]);
		
		if($current_password === $user["password"]){
			if(strlen($new_password) >= 6){
				$userSession = md5($user["username"].$new_password.(time() / 2));
			
				mysqli_query($db, "UPDATE user SET session = '$userSession', password = '$new_password' WHERE username = '{$user["username"]}'") or die(json_encode(array("success" => false, "message" => "Error update sql query")));
				
				$changeStatus = array("success" => true, "message" => "Change password success, you will login again after this!");
			} else {
				$changeStatus = array("success" => false, "message" => "New password must be 6 characters or more!");
			}
		} else {
			$changeStatus = array("success" => false, "message" => "Current password doesn't match!");
		}
	} else {
		$changeStatus = array("success" => false, "message" => "Empty data");
	}
} else {
	$changeStatus = array("success" => false, "message" => "Require login");
}

echo json_encode($changeStatus);