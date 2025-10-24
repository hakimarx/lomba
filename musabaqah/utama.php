<?php
	function getmenu(){
		$ideventaktif=getonedata("select id from event where aktif=1");
		?>
		<a href='?page=utama&page2=acaknomor'>acak nomor peserta</a>
			<!-- <a href='?page=utama&page2=nilai0'>nilai0</a> -->
			<a target=_blank href='?page=cetak'>cetak nilai</a>

			<a href='?page=utama&page2=home'>home</a>
			<a href='?page=utama&page2=event'>event</a>
			<a href='?page=utama&page2=kafilah'>kafilah</a>

			<a href='?page=utama&page2=lapnilai'>nilai</a>
			<a href='?page=utama&page2=peserta_all'>all peserta</a>


			<a href='?page=utama&page2=cabang&idevent=<?php echo $ideventaktif?>'>cabang</a>
			<a href='?page=utama&page2=nilai0'>penilaian</a>

			<a href='?page=utama&page2=orang'>orang</a>
			<a href='?page=utama&page2=panitera'>panitera</a>
			<a href='?page=utama&page2=babak'>babak</a>
			<a href='?page=utama&page2=keahlian'>keahlian</a>

			<a href='?page=utama&page2=_viewall'>hierarki cabang</a>

			<a href='?page=utama&page2=user'>user</a>
			<a href='?page=utama&page2=user_level'>user level</a>

			<a href='?logout'>logout</a>

			<a href='?page=utama&page2=allthetime'>all the time</a>
<?php
	}
?>

<?php


	if(isset($_GET["page2"])){
		$page2=$_GET["page2"];
	}else{
		header("Location:?page=utama&page2=home");
	}

	$role=getonedata("select role from view_user where iduser=".getiduser());
?>



<style>
	.nav0 {
		display: none;
	}

	.nav {
		display: flex;
		flex-wrap: unset;
		white-space: nowrap;
	}

	.drop a,
	.down a {
		display: block;
	}

	.down {
		display: none;
	}

	.drop:hover .down {
		display: block;
		position: absolute;
	}

	.nav a {
		text-decoration: none;
		color: white;
		background: #000;
		padding: 8px;
		border-right: 2px solid yellow;
	}

	.down a {
		background-color: blue;
	}

	.nav a:hover {
		background-color: red;
		//border-bottom: 5px solid green;
	}

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
	}

	.utama .header {
		text-align: center;
		padding: 11px;
	}

	.utama .footer {
		background-color: crimson;
		padding: 22px;
		text-align: center;
		color: white;
	}

	.row {
		padding: 22px;
	}

	.aktif {
		background-color: blue !important;
	}

	.drop {
		position: relative;
	}

	.down {
		position: absolute;
		z-index: 1;
	}

	.nav0 {
		color: white;
		background-color: black;
		cursor: pointer;
	}

	@media (max-width: 768px) {
		.nav0 {
			display: unset;
		}

		.nav {
			flex-direction: column;
			display: none;
		}
	}
</style>


<link rel="stylesheet" href="../global/css/menu.css">
<script src="../global/js/menu.js"></script>



<div class="utama">

    <div class="header">
        <h2 style="font-size: 43px;">musabaqah</h2>

    </div>

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
            <?php getmenu();?>
        </div>
    </div>

	<div class="row" style="    display: flex;    justify-content: space-between;">
		<div class="welcomeuser">
			<?php
						$iduser=getiduser();
						$rowvu=getonebaris("select * from view_user where iduser=$iduser");
						echo "selamat datang user : $rowvu[nama] ($rowvu[level]/$rowvu[role])";
				?>
		</div>
	</div>

	<div class="row" style="min-height: 200px;">
		<?php
					if($page2==""){
						die("");
					}else{
						include_once("utama/$page2.php");
					}
				?>

	</div>

	<div class="footer">
		<!-- first time 8 juli 2025 -->
		copyright &copy;2025 - now
	</div>
</div>