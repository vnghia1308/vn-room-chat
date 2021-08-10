<?php
require("../../config/db.php");

session_start();
error_reporting(0);

$registerStatus = array();
if(checkUserSession($db) !== True){
	if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["re_password"])){
		$firstName 		= (string)$_POST["firstName"];
		$lastName 		= (string)$_POST["lastName"];
		
		$username 		= (string)$_POST["username"];
		$password 		= (string)$_POST["password"];
		$re_password 	= (string)$_POST["re_password"];
		
		$firstName = preg_replace('/\s+/', ' ', $firstName);
		$lastName = preg_replace('/\s+/', ' ', $lastName);
		
		$profilePicture = "https://i.imgur.com/Srxxu0u.jpg"; //default
		
		$blackList = ["admin", "administrator", "gm", "root", "user", "~"];
		
		if(strlen($password) >= 6){
			if(0 < count(array_intersect(array_map('strtolower', explode(' ', $username)), $blackList)) || 
			   0 < count(array_intersect(array_map('strtolower', explode(' ', $firstName)), $blackList)) || 
			   0 < count(array_intersect(array_map('strtolower', explode(' ', $lastName)), $blackList))){
					$registerStatus = array("success" => false, "message" => "Your name or username contains the banned word(s)");	
				} else {
					if (!preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $username))
					{
						if(strpos($username, " ") === False){
							if($password === $re_password){
								$sql = mysqli_query($db, "select * from user where username = '$username'") or die(json_encode(array("success" => false, "message" => "Error sql query")));
							
								if(mysqli_num_rows($sql) < 1){
									$userSession = md5($username.$password.(time() / 2));
									$dateTime = date("Y-m-d H:i:s");
									
									mysqli_query($db, "INSERT INTO user(id, firstName, lastName, profilePicture, username, password, session, admin,verified,joinned_time) VALUES (NULL, '$firstName', '$lastName ', '$profilePicture', '$username', '$password', '$userSession', 0, 0, '$dateTime')");
									
									$cookie_name = "user_name";
									$cookie_value = $username;
									setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); //86400s = 1 day. 1 * 30 = 30 days
									
									$cookie_name = "user_session";
									$cookie_value = $userSession;
									setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/"); //86400s = 1 day. 1 * 30 = 30 days
									
									$registerStatus = array("success" => true, "message" => "Successfully registered.");
								} else {
									$registerStatus = array("success" => false, "message" => "Account already exists.");
								}
							
							} else {
								$registerStatus = array("success" => false, "message" => "Password Confirmiation doesn't match your first password!");
							}
						} else {
							$registerStatus = array("success" => false, "message" => "Username cannot contain spaces");
						}
					} else {
						$registerStatus = array("success" => false, "message" => "Username cannot have special characters");
					}
				}
			} else {
			$registerStatus = array("success" => false, "message" => "Password must be 6 characters or more");
		}
		
	} else {
		$registerStatus = array("success" => false, "message" => "Error, please provide your desired credentials.");
	}
} else {
	$registerStatus = array("success" => false, "message" => "You need to login to view this page.");
}

echo json_encode($registerStatus);
