
<?php if(!isset($_GET['pure'])):?>

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" href="../global/css/ku.css">
	<link rel="stylesheet" href="../global/css/web.css">
	<script src="../global/js/ku.js"></script>
</head>

<?php
	include "../global/class/fungsi.php";
	include "../global/class/userrole.php";
	include "dbku.php";
	include "login/sesi.php";
	function gotologin(){
        header("location:./login/login.php");
    }

	//--- cek logout
	if(isset($_GET['logout'])){
		setlogout();
		gotologin();
		return;
	}

	// cek login
	if(!islogined()){
		gotologin();
		return;
	}
?>

<?php endif;?>
<?php
	if(isset($_GET["page"])){
		$hal=$_GET["page"];
	}else{
		$hal="utama";
	}
	include_once("$hal.php");




?>

