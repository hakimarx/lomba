<?php


	/* skip ndisik
	//--- cek logout
	session_start();

	if(isset($_GET['logout'])){
		$_SESSION['user']="";
	}

	//cek sudah login
	if($_SESSION['user']==""){
		header("location:?page=login");
	}

	*/
	//---
	if(isset($_GET["page2"])){
		$page2=$_GET["page2"];
	}else{
		//$page2="tilawah";
		header("Location:?page=utama&page2=acak");
	}

	function getmenu(){
		$menu = array (
			array("import","importsoal"),
			// array("tilawah","Tilawah&MHQ"),
			array("tilawah2","Tilawah&MHQ"),
			array("tafsir","tafsir"),
			array("acak","acak"),
			array("mfq","MFQ/Hadits"),
			array("mushaf&jenis=indonesia","mushaf indonesia"),
			array("mushaf&jenis=madinah","mushaf madinah"),
			array("qiraat","qiraat"),
			array("kategori","kategori"),
			);

			$sekarang=$_GET["page2"];
			$aktif="";
			$kata="";
			foreach ($menu as $row) {
				$nama=$row[0];
				$label=$row[1];
				if($nama==$sekarang){
					$aktif="class=aktif";
				}else{
					$aktif="";
				}
				$kata.="<a $aktif href='?page=utama&page2=$nama'>$label</a>\n";
			}
		return $kata;
	}



?>


<style>
.utama {
    border: 2px solid red;
    width: 95%;
    margin: auto;
    background: white;
    margin-top: 10px;
    border-radius: 5px;
    border-style: dashed;
    box-shadow: 11px 11px 0px 0px #00000029;
    margin-bottom: 22px;

	&>.header {
		text-align: center;
	}

	&>.footer {
		background-color: #ed143dc7;
		padding: 22px;
		text-align: center;
		color: white;
	}

	&>.isi {
		padding: 22px;
		min-height: 200px;
		overflow:auto;
	}
}

h2{
	font-size: 43px;
    background-color: blue;
    color: transparent;
    background-clip: text;
	background-image: linear-gradient(to right, pink, green, yellow, orange);
}

@keyframes anim1 {
from{

}	
to{
	
}
}

</style>


<link rel="stylesheet" href="../global/css/menu.css">
<script src="../global/js/menu.js"></script>

<head></head>

<div class="utama">

    <div class="header">
        <h2 style="font-size: 43px;">emaqra</h2>

    </div>

    <div class="menu0">
        <div class="menu">
            <div class="judul">
				<div class="kiri"></div>
				<div class="kanan">
					<div class="box">
						<div class="item"></div>
						<div class="item"></div>
						<div class="item"></div>
					</div>
				</div>
			</div>
            <div class="isi">
                <?php echo getmenu();?>

            </div>
        </div>
    </div>

    <div class="isi">
        <?php
				include_once("utama/$page2.php");
			?>

    </div>

    <div class="footer">
        <!-- first time 8 juli 2025 -->
        copyright &copy;2025 - now
    </div>
</div>