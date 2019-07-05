<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "My account";
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
$accountMenu = "active";
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Account</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="home.php">Account</a>
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
                            <h5>Change profile picture</h5>
                        </div>
                        <div class="ibox-content">
							<form id="change_picture" method="GET" action="chat.php" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Image URL</label>
									<div class="col-sm-10"><input type="text" name="profile_picture" value="<?= $profilePicture ?>" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<small><p>You will be banned if using sensitive image/picture.</p></small>
										<button id="cppbtn" class="btn btn-primary" name="submit" type="submit">Change</button>
									</div>
								</div>
							</form>
                            
                        </div>
                    </div>
                </div>
			
			<div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Change name</h5>
                        </div>
                        <div class="ibox-content">
							<form id="change_name" method="GET" action="chat.php" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">First name</label>
									<div class="col-sm-10"><input type="text" name="firstName" value="<?= $user["firstName"]; ?>" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-1 control-label">Last name</label>
									<div class="col-sm-10"><input type="text" name="lastName" value="<?= $user["lastName"]; ?>" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="cgnbtn" class="btn btn-primary" name="submit" type="submit">Change</button>
									</div>
								</div>
							</form>
                            
                        </div>
                    </div>
                </div>
				
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>Change password</h5>
                        </div>
                        <div class="ibox-content">
							<form id="change_password" method="GET" action="chat.php" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Current Password</label>
									<div class="col-sm-10"><input type="password" name="cr_password" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-1 control-label">New password</label>
									<div class="col-sm-10"><input type="password" name="nw_password" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="cgpbtn" class="btn btn-primary" name="submit" type="submit">Change</button>
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
<script src="assets/js/plugins/toastr/toastr.min.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>

<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script>
$("#change_picture").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/request/account/change_picture.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#cppbtn').text('Processing...').prop('disabled', true)
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
			$('#cppbtn').text('Change').prop('disabled', false)
		}
   });
}));

$("#change_password").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/request/account/change_password.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#cgpbtn').text('Processing...').prop('disabled', true)
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
			$('#cgpbtn').text('Change').prop('disabled', false)
		}
   });
}));

$("#change_name").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/request/account/change_name.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#cgnbtn').text('Processing...').prop('disabled', true)
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
			$('#cgnbtn').text('Change').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>