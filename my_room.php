<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "My room";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== True){
	header("location: $_LOGIN_FILE");exit; //$_LOGIN_FILE --> /config/value.php
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);
?>

<body class=" pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

<div id="wrapper">

<?php
$myRoomMenu = "active";
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>My room</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= $_HOME_FILE ?>">Home</a>
                        </li>
						<li>
                            <a>My room</a>
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
                            <h5>Your room has owner</h5>
                        </div>
                        <div class="ibox-content">
						<?php
						if($_GET['room_page'] == null)
								$_GET['room_page'] = 1;
						if($_GET['room_page'] >= 2)
							$room_pages = ($_GET['room_page'] - 1) * 5;
						else
							$room_pages = 0;
												
						$query = mysqli_query($db, "select * from chat_room where owner = {$user["id"]} LIMIT 5 OFFSET {$room_pages}") or error("Can't get room data", $_HOME_FILE);
						
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
									<a href="<?= $_CHAT_FILE ?>?room_id=<?= $room["room_id"] ?>"><button class="btn btn-success btn-rounded btn-sm"><i class="fa fa-sign-in"></i> Redirect to room</button></a>
									<button class="btn btn-danger btn-rounded btn-sm" onclick="delete_room(<?= $room["room_id"] ?>)"><i class="fa fa-times"></i> Delete Room</button>
								</div>
										</div>
								</div>
							<?php endwhile;
							if(mysqli_num_rows($query) < 1){
								echo "You haven't created a room yet!";
							}
							
							$query1 = mysqli_query($db, "select * from chat_room where owner = {$user["id"]}") or error("Can't get room data", $_HOME_FILE);
							
							$n = mysqli_num_rows($query1) / 5;							
							if(mysqli_num_rows($query1) % 5 > 0)
								$n+=1;
							$n = (int) $n;
														
							if(mysqli_num_rows($query) > 0):
							?>
							
							<ul class="pagination pagination-sm">
							<li class="<?php echo ($_GET['page']-1 != 0) ? 'first' : 'first disabled';?>"><a href="my_room.php?room_page=1">First</a></li>
							<li class="<?php echo ($_GET['room_page']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="my_room.php?room_page=<?php echo ($_GET['room_page']-1 != 0) ? $_GET['room_page']-1 : 1;?>">Previous</a></li>
							<?php for($i = 1; $i <= $n; $i++): ?>
							<li class="<?php echo ($_GET['room_page'] == $i) ? 'page active' : 'page'; ?>"><a href="my_room.php?room_page=<?php echo $i ?>"><?php echo $i ?></a></li>
							<?php endfor; ?>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="my_room.php?room_page=<?php echo $_GET['room_page']+1 ?>">Next</a></li>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="my_room.php?room_page=<?php echo $n ?>">Last</a></li>
						</ul>
						
							<?php endif; ?>
                        </div>
                    </div>
                </div>
				
				<!-- ROOM JOINED -->
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Your room has joined</h5>
                        </div>
                        <div class="ibox-content">
						<?php
						if($_GET['room_joined'] == null)
								$_GET['room_joined'] = 1;
						if($_GET['room_joined'] >= 2)
							$room_joined = ($_GET['room_joined'] - 1) * 5;
						else
							$room_joined = 0;
												
						$query = mysqli_query($db, "select * from room_member where user_id = {$user["id"]} ORDER BY join_date DESC LIMIT 5 OFFSET {$room_joined}") or error("Can't get member data", $_HOME_FILE);
						
						while($mem = mysqli_fetch_array($query)):
						$room_query = mysqli_query($db, "select * from chat_room where room_id = {$mem["room_id"]}") or error("Can't get room data", $_HOME_FILE);
						
						$room = mysqli_fetch_array($room_query);
						?>
								<div class="social-feed-box" id="room-<?= $room["room_id"] ?>">
								<div class="social-avatar">
									<small class="text-muted"><?= $room["room_name"] ?> (đã tham gia vào <?= format_time_ago(strtotime($mem["join_date"])) ?>)</small>
								</div>
								<div class="social-body">
									<strong>Description: </strong> <?= !empty($room["room_description"]) ? $room["room_description"] : "null"; ?>
								<p></p><hr><p></p>
								<div class="file-option">
									<a href="<?= $_CHAT_FILE ?>?room_id=<?= $room["room_id"] ?>"><button class="btn btn-success btn-rounded btn-sm"><i class="fa fa-sign-in"></i> Redirect to room</button></a>
								</div>
										</div>
								</div>
							<?php endwhile;
							if(mysqli_num_rows($query) < 1){
								echo "You haven't joined a room yet!";
							}
							
							$query1 = mysqli_query($db, "select * from room_member where user_id = {$user["id"]}") or error("Can't get room data", $_HOME_FILE);
							
							$n = mysqli_num_rows($query1) / 5;							
							if(mysqli_num_rows($query1) % 5 > 0)
								$n+=1;
							$n = (int) $n;
														
							if(mysqli_num_rows($query) > 0):
							?>
							
							<ul class="pagination pagination-sm">
							<li class="<?php echo ($_GET['page']-1 != 0) ? 'first' : 'first disabled';?>"><a href="my_room.php?room_joined=1">First</a></li>
							<li class="<?php echo ($_GET['room_page']-1 != 0) ? 'prev' : 'prev disabled';?>"><a href="my_room.php?room_joined=<?php echo ($_GET['room_page']-1 != 0) ? $_GET['room_page']-1 : 1;?>">Previous</a></li>
							<?php for($i = 1; $i <= $n; $i++): ?>
							<li class="<?php echo ($_GET['room_page'] == $i) ? 'page active' : 'page'; ?>"><a href="my_room.php?room_joined=<?php echo $i ?>"><?php echo $i ?></a></li>
							<?php endfor; ?>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'next disabled' : 'next'; ?>"><a href="my_room.php?room_joined=<?php echo $_GET['room_page']+1 ?>">Next</a></li>
							<li class="<?php echo ($_GET['room_page']+1 > $n) ? 'last disabled' : 'last'; ?>"><a href="my_room.php?room_joined=<?php echo $n ?>">Last</a></li>
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
			url: "ajax/request/delete_room.php",
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
</script>
</body>
</html>