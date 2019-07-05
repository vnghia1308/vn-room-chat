<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Admin Area";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== True){
	header("location: $_LOGIN_FILE");exit; //$_LOGIN_FILE --> /config/value.php
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);

if($user["admin"] != 1){
	error("You are not admin.", $_HOME_FILE);exit;
}
?>

<body class=" pace-done">
<div id="wrapper">

<?php
$adminArea = "active";
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/admin_menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Admin Area</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="">Admin</a>
                        </li>
                    </ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeIn">
            <div class="row">
				<!-- ROOM -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Room control</h5>
                        </div>
                        <div class="ibox-content">
						<?php
						if($_GET['room_page'] == null)
								$_GET['room_page'] = 1;
						if($_GET['room_page'] >= 2)
							$room_pages = ($_GET['room_page'] - 1) * 5;
						else
							$room_pages = 0;
												
						$query = mysqli_query($db, "select * from chat_room LIMIT 5 OFFSET {$room_pages}") or error("Can't get room data", $_HOME_FILE);
						
						while($room = mysqli_fetch_array($query)):
						?>
								<div class="social-feed-box" id="room-<?= $room["room_id"] ?>">
								<div class="social-avatar">
									<small class="text-muted"><?= $room["room_name"] ?></small>
								</div>
								<div class="social-body">
									<strong>Description: </strong> <?= !empty($room["room_description"]) ? $room["room_description"] : "null"; ?>
								<p></p><hr><p></p>
								<div class="file-option">
									<button class="btn btn-danger btn-rounded btn-sm" onclick="delete_room(<?= $room["room_id"] ?>)"><i class="fa fa-times"></i> Delete Room</button>
								</div>
										</div>
								</div>
							<?php endwhile;
							if(mysqli_num_rows($query) < 1){
								echo "Chưa có room nào!";
							}
							
							$query1 = mysqli_query($db, "select * from chat_room") or error("Can't get room data", $_HOME_FILE);
							
							$n = mysqli_num_rows($query1) / 5;							
							if(mysqli_num_rows($query1) % 5 > 0)
								$n+=1;
							$n = (int) $n;
														
							if(mysqli_num_rows($query) > 0):
							?>
							
							<ul class="pagination pagination-sm">
							<li class="<?php echo ($_GET['page']-1 != 0) ? 'first' : 'first disabled';?>"><a href="secret_admin.php?room_page=1">First</a></li>
							<li class="<?php echo ($_GET['room_page']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="secret_admin.php?room_page=<?php echo ($_GET['room_page']-1 != 0) ? $_GET['room_page']-1 : 1;?>">Previous</a></li>
							<?php for($i = 1; $i <= $n; $i++): ?>
							<li class="<?php echo ($_GET['room_page'] == $i) ? 'page active' : 'page'; ?>"><a href="secret_admin.php?room_page=<?php echo $i ?>"><?php echo $i ?></a></li>
							<?php endfor; ?>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="secret_admin.php?room_page=<?php echo $_GET['room_page']+1 ?>">Next</a></li>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="secret_admin.php?room_page=<?php echo $n ?>">Last</a></li>
						</ul>
						
							<?php endif; ?>
                        </div>
                    </div>
                </div>
				
				<!-- USER -->
				<div id="user_control" class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>User control</h5>
                        </div>
                        <div class="ibox-content">
						<?php
						if($_GET['user_page'] == null)
								$_GET['user_page'] = 1;
						if($_GET['user_page'] >= 2)
							$user_pages = ($_GET['user_page'] - 1) * 5;
						else
							$user_pages = 0;
						
						$query = mysqli_query($db, "select * from user LIMIT 5 OFFSET {$user_pages}") or error("Can't get room data");
						
						while($_user = mysqli_fetch_array($query)):
						$ban_check = mysqli_query($db, "select * from ban_list where user_id = {$_user["id"]}");
						?>
								<div class="social-feed-box" id="user-id-<?= $_user["id"] ?>">
								<div class="social-avatar">
									<small class="text-muted"><?= $_user["username"] ?></small>
								</div>
								<div class="social-body">
									<strong><?= $_user["firstName"] . " " . $_user["lastName"]; ?></strong>
								<p></p><hr><p></p>
								<div class="file-option">
									<button class="btn btn-danger btn-rounded btn-sm" onclick="delete_user(<?= $_user["id"] ?>)"><i class="fa fa-times"></i> Delete user</button> 
									<button id="ban-user-id-<?= $_user["id"] ?>" <?php if(mysqli_num_rows($ban_check) > 0) echo 'style="display: none;"'; ?> class="btn btn-warning btn-rounded btn-sm" onclick="ban_user(<?= $_user["id"] ?>)"><i class="fa fa-ban"></i> Ban user</button>
									
									<button id="unban-user-id-<?= $_user["id"] ?>" <?php if(mysqli_num_rows($ban_check) < 1) echo 'style="display: none;"'; ?> class="btn btn-warning btn-rounded btn-sm" onclick="unban_user(<?= $_user["id"] ?>)"><i class="fa fa-ban"></i> Un-ban user</button> 
								
									<button id="promote-admin-id-<?= $_user["id"] ?>" class="btn btn-info btn-rounded btn-sm" <?php if($_user["admin"] == 1) echo 'style="display: none;"'; ?>onclick="promote_user(<?= $_user["id"] ?>)"><i class="fa fa-level-up"></i> Promote to Admin</button>
									
									<button id="unpromote-admin-id-<?= $_user["id"] ?>" class="btn btn-info btn-rounded btn-sm" <?php if($_user["admin"] == 0) echo 'style="display: none;"'; ?> onclick="unpromote_user(<?= $_user["id"] ?>)"><i class="fa fa-level-up"></i> Remove Admin</button>
								</div>
										</div>
								</div>
							<?php endwhile;
							if(mysqli_num_rows($query) < 1){
								echo "Chưa có room nào!";
							}
							
							$query1 = mysqli_query($db, "select * from user") or error("Can't get user data", $_HOME_FILE);
							
							$n = mysqli_num_rows($query1) / 5;							
							if(mysqli_num_rows($query1) % 5 > 0)
								$n+=1;
							$n = (int) $n;
																					
							if(mysqli_num_rows($query) > 0):
							?>
							
							<ul class="pagination pagination-sm">
							<li class="<?php echo ($_GET['page']-1 != 0) ? 'first' : 'first disabled';?>"><a href="secret_admin.php?user_page=1#user_control">First</a></li>
							<li class="<?php echo ($_GET['user_page']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="secret_admin.php?user_page=<?php echo ($_GET['user_page']-1 != 0) ? $_GET['user_page']-1 : 1;?>#user_control">Previous</a></li>
							<?php for($i = 1; $i <= $n; $i++): ?>
							<li class="<?php echo ($_GET['user_page'] == $i) ? 'page active' : 'page'; ?>"><a href="secret_admin.php?user_page=<?php echo $i ?>#user_control"><?php echo $i ?></a></li>
							<?php endfor; ?>
							<li class="<?php echo ($_GET['user_page']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="secret_admin.php?user_page=<?php echo $_GET['user_page']+1 ?>#user_control">Next</a></li>
							<li class="<?php echo ($_GET['user_page']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="secret_admin.php?user_page=<?php echo $n ?>#user_control">Last</a></li>
						</ul>
						
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		<!-- footer -->
		<?php require("layout/footer.php") ?>
		<!-- ./fotter -->
	</div>
</div>

<!-- Mainly scripts -->
<script src="assets/js/jquery-3.1.1.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>

<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script>

function delete_room(room_id){
	var c = confirm("Are you sure? Press OK if you wanna delete this room!");
	if (c == true) {
		$.ajax({
			url: "ajax/request/admin/delete_room.php",
			type: "POST",
			data: {
				room_id: room_id
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#room-" + room_id).remove()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	}
}

//delete user
function delete_user(user_id){
	var c = confirm("Are you sure? Press OK if you wanna delete this user!");
	if (c == true) {
		$.ajax({
			url: "ajax/request/admin/delete_user.php",
			type: "POST",
			data: {
				user_id: user_id
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#user-id-" + user_id).remove()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	}
}

//ban user
function ban_user(user_id){
	var reason;
	var input = prompt("Reason you ban this user:", "Violation of rules");
	reason = input;
	
	if (reason != null && reason != "") {
	  $.ajax({
			url: "ajax/request/admin/ban_user.php",
			type: "POST",
			data: {
				user_id: user_id,
				reason: reason
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#ban-user-id-" + user_id).hide()
					$("#unban-user-id-" + user_id).show()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	} 
}

//un ban user
function unban_user(user_id){
	var c = confirm("Are you sure? Press OK if you wanna do this");
	
	if (c == true) {
	  $.ajax({
			url: "ajax/request/admin/unban_user.php",
			type: "POST",
			data: {
				user_id: user_id
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#ban-user-id-" + user_id).show()
					$("#unban-user-id-" + user_id).hide()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	} 
}

function promote_user(user_id){
	var c = confirm("Are you sure? Press OK if you wanna do this");
	
	if (c == true) {
	  $.ajax({
			url: "ajax/request/admin/promote_user.php",
			type: "POST",
			data: {
				user_id: user_id,
				role: 1
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#promote-admin-id-" + user_id).hide()
					$("#unpromote-admin-id-" + user_id).show()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	} 
}

function unpromote_user(user_id){
	var c = confirm("Are you sure? Press OK if you wanna do this");
	
	if (c == true) {
	  $.ajax({
			url: "ajax/request/admin/promote_user.php",
			type: "POST",
			data: {
				user_id: user_id,
				role: 0
			},
			dataType:  'json',
			beforeSend: function () {
				
			},
			success: function(r) {
				if(r.success){
					$("#promote-admin-id-" + user_id).show()
					$("#unpromote-admin-id-" + user_id).hide()
					toastr.success(r.message)
				} else {
					toastr.error(r.message)
				}
			},
			error: function(){
				toastr.error("Unkown error?!")
			},
			complete: function(){
				
			}
	   });
	} 
}
</script>
</body>
</html>