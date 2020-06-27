<?php error_reporting(0);

$parts = explode('/', __FILE__);
$filename = $parts[count($parts) - 1];

if (strpos($_SERVER["SCRIPT_URI"], $filename) !== false) {
	exit("Method for Script URI Not valid");
}
?>
<div class="footer">
    <div class="pull-right">
		<p>&copy; Copyright 2017-2020 CYGO Network</p>
    </div>
    <div>
		<p><a href="https://cygo.network/landing/tos">Terms of Service</a></p>
    </div>
</div>