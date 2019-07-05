<?php
require("func.php");
require("value.php");
$db = mysqli_connect("localhost","ctjoppmqhosting_vnghia","1151985611") or die("can't connect this database");
mysqli_select_db($db, "ctjoppmqhosting_test");
mysqli_set_charset($db, 'utf8');