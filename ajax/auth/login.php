<?php
require("../../config/db.php");

session_start();
error_reporting(0);

if(checkUserSession($db) !== True){
	$loginStatus = array();
	
	if(!empty($_POST["username"]) && !empty($_POST["password"])){
		$username = (string)$_POST["username"];
		$password = (string)$_POST["password"];
		
		$sql = mysqli_query($db, "select * from user where username = '$username' and password = '$password'") or die(json_encode(array("success" => false, "message" => "Error sql query")));
		
		if(mysqli_num_rows($sql) > 0){
			$user = searchUser_bUsername($db, $username);
			$ban_check = mysqli_query($db, "select * from ban_list where user_id = {$user["id"]}");
			if(mysqli_num_rows($ban_check) == 0){
				$userSession = md5($username.$password.(time() / 2));
				
				mysqli_query($db, "UPDATE user SET session = '$userSession' WHERE username = '$username'") or die(json_encode(array("success" => false, "message" => "Error update sql query")));
				
				$cookie_name = "user_name";
				$cookie_value = $username;
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); //86400s = 1 day. 1 * 30 = 30 days
				
				$cookie_name = "user_session";
				$cookie_value = $userSession;
				setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); //86400s = 1 day. 1 * 30 = 30 days
						
				$loginStatus = array("success" => true, "message" => "Login success");
			} else {
				$ban = mysqli_fetch_array($ban_check);
				$loginStatus = array("success" => false, "message" => "You got banned! Reason: {$ban["ban_reason"]}");
			}
		} else {
			$loginStatus = array("success" => false, "message" => "Login falied. Please try again!");
		}
	} else {
		$loginStatus = array("success" => false, "message" => "Empty method");
	}
	
	echo json_encode($loginStatus);
}