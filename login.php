<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Login";
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
                    <h2>Login</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="<?= $_HOME_FILE ?>">Home</a>
                        </li>
                        <li>
                            <a>Forms</a>
                        </li>
                        <li class="active">
                            <strong>Login</strong>
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
                            <h5>Login form</h5>
                        </div>
                        <div class="ibox-content">
                            <form id="Login" method="POST" action="" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Username</label>
									<div class="col-sm-10"><input type="text" name="username" value="" class="form-control" autocomplete="off"></div>
								</div>
								<div class="form-group"><label class="col-sm-1 control-label">Password</label>
									<div class="col-sm-10"><input type="password" name="password" value="" class="form-control"></div>
								</div>
								<div class="form-group">
									<div class="col-sm-4 col-sm-offset-1">
										<button id="lgbtn" class="btn btn-primary" name="submit" type="submit">Login</button>
										<br />
										<p>Not registered account? <a href="register.php">Register now</a></p>
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
$("#Login").on('submit',(function(e) {
	e.preventDefault();
	$.ajax({
		url: "ajax/auth/login.php",
		type: "POST",
		data:  new FormData(this),
		dataType:  'json',
		contentType: false,
		cache: false,
		processData:false,
		beforeSend: function () {
			$('#lgbtn').text('Processing...').prop('disabled', true)
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
			$('#lgbtn').text('Login').prop('disabled', false)
		}
   });
}));
</script>
</body>
</html>