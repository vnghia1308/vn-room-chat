<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Members";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== TRUE){
	header("location: $_LOGIN_FILE");exit; //$_LOGIN_FILE --> /config/value.php
}

if(!empty($_GET["room_id"])){
	$room_id = $_GET["room_id"];
	
	$query = mysqli_query($db, "select * from chat_room where room_id=$room_id") or error("Room id doesn't exist", $_HOME_FILE);
	if(mysqli_num_rows($query) > 0){
		$room_data = mysqli_fetch_array($query) or error("Can't get room data");
	} else {
		error("Room id doesn't exist", $_HOME_FILE);
	}
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);
if($room_data["owner"] !== $user["id"]){
	error("You isn't owner this room", $_HOME_FILE);
}
?>

<body class=" pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

<div id="wrapper">

<?php
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php 
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Members management</h2>
                    <ol class="breadcrumb">
						<li class="active">
							<a href="chat.php?room_id=<?= $room_data["room_id"] ?>"><?= $room_data["room_name"] ?></a>
						</li>
						<li>
							<strong>Members management</strong>
						</li>
					</ol>
                </div>
                <div class="col-lg-2">

                </div>
            </div>
        <div class="wrapper wrapper-content animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Members management</h5>
                        </div>
                        <div class="ibox-content">
						<?php
						$query = mysqli_query($db, "select * from room_member where room_id={$room_data["room_id"]}") or error("Can't get request join");
						
						while($req = mysqli_fetch_array($query)):
						$_user = searchUser_bId($db, $req["user_id"]);
						if(!empty($_user)):
						?>
								<div class="social-feed-box" id="request-user-<?= $req["user_id"] ?>">
								<div class="social-avatar">
									<small class="text-muted">Đã tham gia lúc <?= $req["join_date"] ?></small>
								</div>
								<div class="social-body">
									<strong><?= $_user["firstName"] . " " . $_user["lastName"]; ?></strong> hiện đang là thành viên trong nhóm.
								<p></p><hr><p></p>
								<div class="file-option">
									<!--<button class="btn btn-primary btn-rounded btn-sm" onclick="approve_request(<?= $req["user_id"] ?>, <?=  $req["room_id"] ?>)"><i class="fa fa-check"></i> Đưa lên chủ sở hữu</button>-->
									<button class="btn btn-danger btn-rounded btn-sm" onclick="delete_member(<?= $req["user_id"] ?>,<?= $req["room_id"] ?>)"><i class="fa fa-times"></i> Xóa thành viên</button>
								</div>
										</div>
								</div>
							<?php 
							endif;
							endwhile;
							
							if(mysqli_num_rows($query) < 1){
								echo "Room has no members yet!";
							}
							?>
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
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>

<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script>
function delete_member(user_id, room_id){
	$.ajax({
		url: "ajax/request/request_action.php?do=delete_member",
		type: "POST",
		data: {
			user_id: user_id,
			room_id: room_id
		},
		dataType:  'json',
		beforeSend: function () {
			
		},
		success: function(r) {
			if(r.success){
				$("#request-user-" + user_id).remove()
				toastr.success(r.message)
			} else {
				toastr.error(r.message)
			}
		},
		error: function(){
			
		},
		complete: function(){
			
		}
   });
}
function approve_request(user_id, room_id){
	$.ajax({
		url: "ajax/request/request_action.php?do=approve",
		type: "POST",
		data: {
			user_id: user_id,
			room_id: room_id
		},
		dataType:  'json',
		beforeSend: function () {
			
		},
		success: function(r) {
			if(r.success){
				$("#request-user-" + user_id).remove()
				toastr.success("Đã phê duyệt thành công thành viên này")
			} else {
				toastr.error("Không thể phê duyệt thành viên này")
			}
		},
		error: function(){
			
		},
		complete: function(){
			
		}
   });
}
</script>
</body>
</html>
