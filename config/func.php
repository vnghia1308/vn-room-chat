<?php
/**
 * Return user info by username.
 *
 * @param mysqli	 	$db
 * @param string 		$usrename
 *
 * @return array
 **/
function searchUser_bUsername($db, $username){
	if(!empty($username) && !mysqli_error($db)){
		$sql = mysqli_query($db, "select id,firstName,lastName,profilePicture from user where username = '$username'");
		if(mysqli_num_rows($sql) > 0){
			$result = mysqli_fetch_array($sql);
			
			return $result;
		} else {
			return;
		}
	} else {
		return;
	}
}

/**
 * Return user info by user id.
 *
 * @param mysqli	 	$db
 * @param int 			$usrename
 *
 * @return array
 **/
function searchUser_bId($db, $userid){
	if(!empty($userid) && !mysqli_error($db)){
		$sql = mysqli_query($db, "select username,firstName,lastName,profilePicture from user where id=$userid");
		if(mysqli_num_rows($sql) > 0){
			$result = mysqli_fetch_array($sql);
			
			return $result;
		} else {
			return false;
		}
	} else {
		return;
	}
}

/**
 * Return user info by user session.
 *
 * @param mysqli	 	$db
 * @param string 		$usrename
 *
 * @return array
 **/
function searchUser_bSession($db, $session){
	if(!empty($session) && !mysqli_error($db)){
		$sql = mysqli_query($db, "select id,username,password,firstName,lastName,profilePicture,admin from user where session = '$session'");
		if(mysqli_num_rows($sql) > 0){
			$result = mysqli_fetch_array($sql);
			
			return $result;
		} else {
			return;
		}
	} else {
		return;
	}
}

/**
 * Check user session alive or not.
 *
 * @param 	mysqli	 	$db
 *
 * @return 	bool
 * @global 	$inLogin 			login status (true or false)
 * @global 	$inAdmin 			admin account (true or false)
 * @global 	$profilePicture 	profile picture (picture/image url)
 **/
function checkUserSession($db){
	global $inLogin, $isAdmin, $profilePicture;
	
	if(!empty($_COOKIE["user_session"])){
		$user = searchUser_bSession($db, $_COOKIE["user_session"]);
		if(empty($user)){
			destroyAllCookies();
			return false;
		}
		
		$ban_check = mysqli_query($db, "select * from ban_list where user_id = {$user["id"]}");
		if(mysqli_num_rows($ban_check) > 0){
			$ban = mysqli_fetch_array($ban_check);
			mysqli_query($db, "UPDATE user SET session = '' WHERE id = {$ban["user_id"]}");
			die(array("success" => false, "message" => "You got banned! Reason: {$ban["ban_reason"]}"));
		}
		
		if($user["admin"] == 1){
			$isAdmin = true;
		}
		
		$inLogin = true;
		$profilePicture = $user["profilePicture"];
		
		return true;
	} else {
		return false;
	}
}

/**
 * Store error information and redirect to the home page to display the error log(s).
 *
 * @param string $e		error messages
 * @param string $r		redirect url
 **/
function error($e, $r){
	$_SESSION["error_log"] = $e;
	header("location: $r");exit;
}

/**
 * Delete all cookies
 *
 **/
function destroyAllCookies(){
	if (isset($_SERVER['HTTP_COOKIE'])) {
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		foreach($cookies as $cookie) {
			$parts = explode('=', $cookie);
			$name = trim($parts[0]);
			setcookie($name, null, -1, '/');
		}
	}
}

/**
 * Convert DateTime to time ago.
 *
 * @param strtotime $time_ago
 *
 * @example format_time_ago(strtotime($time_ago))
 **/
function format_time_ago($time_ago){
  $cur_time 	= time();
  $time_elapsed = $cur_time - $time_ago;
  $seconds 		= $time_elapsed ;
  $minutes 		= round($time_elapsed / 60 );
  $hours 		= round($time_elapsed / 3600);
  $days 		= round($time_elapsed / 86400 );
  $weeks 		= round($time_elapsed / 604800);
  $months 		= round($time_elapsed / 2600640 );
  $years 		= round($time_elapsed / 31207680 );
  // Seconds
  if($seconds <= 60){
	return "$seconds giây trước";
  }
  //Minutes
  else if($minutes <=60){
	if($minutes==1){
	  return "1 phút trước";
	}
	else{
	  return "$minutes phút trước";
	}
  }
  //Hours
  else if($hours <=24){
	if($hours==1){
	  return "1 giờ trước";
	}else{
	  return "$hours giờ trước";
	}
  }
  //Days
  else if($days <= 7){
	if($days==1){
	  return "hôm qua";
	}else{
	  return "$days ngày tước";
	}
  }
  //Weeks
  else if($weeks <= 4.3){
	if($weeks==1){
	  return "1 tuần trước";
	}else{
	  return "$weeks tuần trước";
	}
  }
  //Months
  else if($months <=12){
	if($months==1){
	  return "1 tháng trước";
	}else{
	  return "$months tháng trước";
	}
  }
  //Years
  else{
	if($years==1){
	  return "1 năm trước";
	}else{
	  return "$years năm trước";
	}
  }
}