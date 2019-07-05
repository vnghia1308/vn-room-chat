<html>
<?php 
if(!session_id()){
	session_start();
}
require("config/db.php");
$title = "Home";
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
$homeChatMenu = "active";
$userName = $user["firstName"] . " " . $user["lastName"];
require("layout/menu.php");
?>
        <div id="page-wrapper" class="gray-bg" style="min-height: 1263px;">
        <?php
		require("layout/navtop.php");
		?>
            <div class="row wrapper border-bottom white-bg page-heading">
                <div class="col-lg-10">
                    <h2>Home</h2>
                    <ol class="breadcrumb">
                        <li>
                            <a href="home.php">Home</a>
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
                            <h5>Home panel</h5>
                        </div>
                        <div class="ibox-content">
							<form method="GET" action="<?= $_CHAT_FILE ?>" class="form-horizontal">
								<div class="form-group"><label class="col-sm-1 control-label">Join room id</label>
									<div class="col-sm-10"><input type="text" name="room_id" value="" class="form-control" autocomplete="off"></div>
								</div>
							</form>
                            <?php
							if(!empty($_SESSION["error_log"])){
								echo $_SESSION["error_log"];
								unset($_SESSION["error_log"]);
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
<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- Custom and plugin javascript -->
<script src="assets/js/inspinia.js"></script>
<script src="assets/js/plugins/pace/pace.min.js"></script>

<script src="assets/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
</body>
</html>