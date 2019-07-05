<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Create room";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== True){
	header("location: $_HOME_FILE");exit;
}

$user = searchUser_bSession($db, $_COOKIE["user_session"]);
?>

<body class=" pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

<div id="wrapper">

<?php
$createRoomMenu = "active";
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Room</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="create_room.php">Create Room Chat</a>
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
                            <h5>Create Room Form</h5>
                        </div>
                        <div class="ibox-content">
							<form id="create_form" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Room Name</label>
									<div class="col-sm-10"><input type="text" name="room_name" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-1 control-label">Room description</label>
									<div class="col-sm-10"><input type="text" name="room_desc" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="create_button" class="btn btn-primary" value="submit" name="submit" type="submit">Create room</button>
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
$("#create_form").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/request/create_room.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#create_button').text('Processing...').prop('disabled', true)
		},
		success: function(r) {
			if(r.success){
				$("#room_name").val(null)
				$("#room_desc").val(null)
				toastr.success(r.message)
			} else {
				toastr.error(r.message)
			}
		},
		error: function(){
			
			
		},
		complete: function(){
			$('#create_button').text('Create room').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>
