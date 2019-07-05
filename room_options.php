<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Room options";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== TRUE){
	header("location: $_LOGIN_FILE");exit; //$_LOGIN_FILE --> /config/value.php
}

if(!empty($_GET["room_id"])){
	$room_id = $_GET["room_id"];
	
	$query = mysqli_query($db, "select * from chat_room where room_id=$room_id") or error("Room id doesn't exist", $_HOME_FILE); //$_HOME_FILE --> /config/value.php
	if(mysqli_num_rows($query) > 0){
		$room_data = mysqli_fetch_array($query) or error("Can't get room data");
	} else {
		error("Room id doesn't exist", $_HOME_FILE); //$_HOME_FILE --> /config/value.php
	}
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);
if($room_data["owner"] !== $user["id"]){
	error("You isn't owner this room", $_HOME_FILE);
}
?>

<body class=" pace-done">

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
                    <h2><?= $title ?></h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="chat.php?room_id=<?= $room_data["room_id"] ?>"><?= $room_data["room_name"] ?></a>
                        </li>
						<li>
                            <a href="#"><?= $title ?></a>
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
                            <h5>Change room name & description</h5>
                        </div>
                        <div class="ibox-content">
							<form id="change_name_description" method="GET" action="chat.php" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Room name: </label>
									<div class="col-sm-10"><input type="text" name="room_name" value="<?= $room_data["room_name"] ?>" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-1 control-label">Room description: </label>
									<div class="col-sm-10"><input type="text" name="room_desc" value="<?= $room_data["room_description"] ?>" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="crndbtn" class="btn btn-primary" name="submit" type="submit">Change</button>
									</div>
								</div>
							</form>
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
$("#change_name_description").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/request/room/change_name_description.php?room_id=<?= $room_id ?>",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#crndbtn').text('Processing...').prop('disabled', true)
		},
		success: function(r) {
			if(r.success){
				toastr.success(r.message)
			} else {
				toastr.error(r.message)
			}
		},
		error: function(){
			
			
		},
		complete: function(){
			$('#crndbtn').text('Change').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>