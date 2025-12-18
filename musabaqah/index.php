<?php
// Include core functions and session early (before any output) to avoid headers already sent
include_once __DIR__ . "/../global/class/fungsi.php";
include_once __DIR__ . "/../global/class/userrole.php";
include_once __DIR__ . "/dbku.php";
include_once __DIR__ . "/login/sesi.php";
if (!isset($_GET['pure'])):

?>

	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="stylesheet" href="../global/css/ku.css">
		<link rel="stylesheet" href="../global/css/web.css">
		<script src="../global/js/ku.js"></script>
	</head>
	<?php
	function gotologin()
	{
		header("location:./login/login.php");
	}

	//--- cek logout
	if (isset($_GET['logout'])) {
		setlogout();
		gotologin();
		return;
	}

	// cek login
	if (!islogined()) {
		gotologin();
		return;
	}
	?>

<?php endif; ?>
<?php
if (isset($_GET["page"])) {
	$hal = $_GET["page"];
} else {
	$hal = "utama";
}
include_once("$hal.php");




?>