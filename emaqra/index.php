
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../global/css/ku.css">
	<link rel="stylesheet" href="../global/css/web.css">
	<script src="../global/js/ku.js"></script>
</head>

<?php
	include "../global/class/fungsi.php";
	require "../musabaqah/dbku.php";
?>

<?php
	if(isset($_GET["page"])){
		$hal=$_GET["page"];
	}else{
		$hal="utama";
	}
	include_once("$hal.php");
?>
