<?php
?>
<?php
	function getmenu(){
		$ideventaktif=getonedata("select id from event where aktif=1");
		$role=$GLOBALS['role'];

		if($role=="role2"){
			echo "<a href='?page=utama&page2=acaknomor'>acak nomor peserta</a>";
			echo "<a href='?page=utama&page2=nilai0'>nilai0</a>";
			echo "<a target=_blank href='?page=cetak'>cetak nilai</a>";
			echo "<a href='?logout'>logout</a>";


		}

		if($role=="role1"){
			?>
					<a href='?page=utama&page2=home'>home</a>

		<div class="drop">
			<a href="javascript:void()">event ></a>
			<div class="down">
				<a href='?page=utama&page2=event'>event</a>
				<a href='?page=utama&page2=kafilah'>kafilah</a>
			</div>
		</div>	

		<div class="drop">
			<a href="javascript:void()">laporan ></a>
			<div class="down">
				<a href='?page=utama&page2=lapnilai'>nilai</a>
				<a href='?page=utama&page2=peserta_all'>all peserta</a>
			</div>
		</div>	
		<a href='?page=utama&page2=cabang&idevent=<?php echo $ideventaktif?>'>cabang</a>
		<a href='?page=utama&page2=nilai0'>penilaian</a>
		<div class="drop">
			<a href="javascript:void()">master data ></a>
			<div class="down">
				<a href='?page=utama&page2=orang'>orang</a>
				<a href='?page=utama&page2=panitera'>panitera</a>
				<a href='?page=utama&page2=babak'>babak</a>
				<a href='?page=utama&page2=keahlian'>keahlian</a>
				<!-- <a href='?page=utama&page2=peserta'>peserta</a> -->
			</div>
		</div>	

		<div class="drop">
			<a href="javascript:void()">view data per event ></a>
			<div class="down">
				<a href='?page=utama&page2=_viewall'>view hierarki cabang</a>
			</div>
		</div>	

		<div class="drop">
			<a href="javascript:void()">for level admin ></a>
			<div class="down">
				<a href='?page=utama&page2=user'>manage user</a>
				<a href='?page=utama&page2=user_level'>manage user level</a>
			</div>
		</div>	

		<a href='?logout'>logout</a>

		<div class="drop">
			<a href="javascript:void()">for programmer only ></a>
			<div class="down">
				<a href='?page=utama&page2=allthetime'>all the time</a>
				<a target=_blank href='../global/tool/tabel.php'>view all tabel</a>
				<a target=_blank href='../global/tool/insertfake.php'>insert fake</a>
			</div>
		</div>

			<?php
		}
	}
?>

<?php


	if(isset($_GET["page2"])){
		$page2=$_GET["page2"];
	}else{
		//$page2="tilawah";
		header("Location:?page=utama&page2=home");
	}

	$role=getonedata("select role from view_user where iduser=".getiduser());
?>


<style>
	.nav{
		display:flex;
		flex-wrap:unset;
		white-space:nowrap;
	}
	.drop a, .down a {
 	   display: block;
	}
	.nav>a,.drop>div,.down>a{
		//background-color: black;
	}
	.down{
		display:none;
	}
	.drop:hover .down{
		display:block;
		position:absolute;
	}
	.nav a {
		text-decoration: none;
		color: white;
		background: #000;
		padding: 8px;
		//flex: 1;
		border-right: 2px solid yellow;
		//transition: all .5s;
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

	.drop{
		position: relative;
	}
	.down{
		position:absolute;
	}


</style>


<head></head>

<div class="utama">

	<div class="header">
		<h2 style="font-size: 43px;">musabaqah</h2>

	</div>

	<div class="nav">
		<?php getmenu();?>		
		
	</div>

	<div class="row" style="    display: flex;    justify-content: space-between;">
		<div class="breadcumb">
			<?php
				//bc1=home, bc2=cabang,bc3=jeniscabang,bc4=jenisgolongan
				function aa($link,$label){
					return "<div><a href='?page=utama&page2=$link'>$label</a></div>\n";
				}

				$bc2="";
				$bc3="";
				$bc4="";
				$bc5="";

				$page2=$_GET['page2'];

				if($page2!="home"){
					echo aa("home","home");
					$bc2=aa($page2,$page2);
					if($page2=="golongan"){
						$bc2=aa("cabang","cabang");
						$idcabang=$_GET['idcabang'];
						$cabang=getonedata("select nama from cabang where id=$idcabang");
						$bc3=aa("golongan&idcabang=$idcabang",$cabang);
					}
					if($page2=="peserta2" or $page2=="bidang"){
						$bc2=aa("cabang","cabang");
						$idgolongan=$_GET['idgolongan'];
						$idcabang=getonedata("select idcabang from golongan where id=$idgolongan");
						$cabang=getonedata("select nama from cabang where id=$idcabang");
						$golongan=getonedata("select nama from golongan where id=$idgolongan");
						$bc3=aa("golongan&idcabang=$idcabang",$cabang);
						$bc4=aa("$page2&idgolongan=$idgolongan",$golongan);

					}
					if($page2=="hakim2"){
						$bc2=aa("cabang","cabang");
						$idbidang=$_GET['idbidang'];
						$idgolongan=getonedata("select idgolongan from bidang where id=$idbidang");
						$golongan=getonedata("select nama from golongan where id=$idgolongan");
						$idcabang=getonedata("select idcabang from golongan where id=$idgolongan");
						$cabang=getonedata("select nama from cabang where id=$idcabang");
						$bc3=aa("golongan&idcabang=$idcabang",$cabang);
						$bc4=aa("bidang&idgolongan=$idgolongan",$golongan);
						$bidang=getonedata("select nama from bidang where id=$idbidang");
						$bc5=aa("hakim2&idbidang=$idbidang",$bidang);
					}

					echo $bc2;
					echo $bc3;
					echo $bc4;
					echo $bc5;


				}

			?>
		</div>
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


