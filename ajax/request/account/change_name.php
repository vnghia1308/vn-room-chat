<?php
require("../../../config/db.php");

session_start();
error_reporting(0);

$changeStatus = array();

if(checkUserSession($db) !== False){
	if(!empty($_POST["firstName"]) && !empty($_POST["lastName"])){
		$firstName 	= (string) $_POST["firstName"];
		$lastName 	= (string) $_POST["lastName"];
		
		$user = searchUser_bSession($db, $_COOKIE["user_session"]);
		
		$blackList = ["admin", "administrator", "gm", "fuck", "nigga", "faggot"];
		
		if(strlen($firstName) <= 15 && strlen($lastName) <= 15){	
			if(0 < count(array_intersect(array_map('strtolower', explode(' ', $firstName)), $blackList)) || 0 < count(array_intersect(array_map('strtolower', explode(' ', $lastName)), $blackList))){
				$changeStatus = array("success" => false, "message" => "Your name contains the banned word(s)");
			} else {
				mysqli_query($db, "UPDATE user SET firstName = '$firstName', lastName = '$lastName' WHERE username = '{$user["username"]}'") or die(json_encode(array("success" => false, "message" => "Error update sql query")));
				
				$changeStatus = array("success" => true, "message" => "Name has changed, please reload page to see new change.");
			}
		} else {
			$changeStatus = array("success" => false, "message" => "First name and last name must be 15 characters or less.");
		}
	} else {
		$changeStatus = array("success" => false, "message" => "Empty data");
	}
} else {
	$changeStatus = array("success" => false, "message" => "Require login");
}

echo json_encode($changeStatus);