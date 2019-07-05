<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Register";
require("layout/head.php"); // $title = "page title"

if(checkUserSession($db) !== False){
	header("location: $_HOME_FILE");exit;
}
?>

<body class=" pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>

<div id="wrapper">

<?php 
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php 
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Register</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= $_HOME_FILE ?>">Home</a>
                        </li>
                        <li>
                            <a>Forms</a>
                        </li>
                        <li class="active">
                            <strong>Register</strong>
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
                            <h5>Register form</h5>
                        </div>
                        <div class="ibox-content">
                            <form id="register_form" method="POST" action="" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">First Name</label>
									<div class="col-sm-10"><input type="text" name="firstName" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<div class="form-group"><label class="col-sm-1 control-label">Last Name</label>
									<div class="col-sm-10"><input type="text" name="lastName" value="" class="form-control" autocomplete="off"></div>
								</div>
								
								<hr />
								
								<div class="form-group"><label class="col-sm-1 control-label">Username</label>
									<div class="col-sm-10"><input type="text" name="username" value="" class="form-control" autocomplete="off"></div>
								</div>
								<div class="form-group"><label class="col-sm-1 control-label">Password</label>
									<div class="col-sm-10"><input type="password" name="password" value="" class="form-control"></div>
								</div>
								<div class="form-group"><label class="col-sm-1 control-label">Re-Password</label>
									<div class="col-sm-10"><input type="password" name="re_password" value="" class="form-control"></div>
								</div>
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="rgbtn" class="btn btn-primary" value="submit" name="submit" type="submit">Start register</button>
										<br />
										<p>Already have an account? <a href="login.php">Login now</a></p>
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
$("#register_form").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/auth/register.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#rgbtn').text('Processing...').prop('disabled', true)
		},
		success: function(r) {
			if(r.success){
				location.reload()
			} else {
				toastr.error(r.message)
			}
		},
		error: function(){
			
			
		},
		complete: function(){
			$('#rgbtn').text('Start register').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>
