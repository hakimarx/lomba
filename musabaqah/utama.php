<?php
	if(isset($_GET["page2"])){
		$page2=$_GET["page2"];
	}else{
		header("Location:?page=utama&page2=home");
	}

	$iduser = getiduser();
	if($iduser){
		$role=getonedata("select role from view_user where iduser=".$iduser);
	}else{
		$role='';
	}
	
	$ideventaktif=getonedata("select id from event where aktif=1");
?>

<style>
/* ================================
   DROPDOWN NAVIGATION STYLES
   ================================ */
   
.navbar {
	background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
	padding: 0;
	box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.navbar-container {
	display: flex;
	flex-wrap: wrap;
	align-items: stretch;
	max-width: 100%;
}

.nav-item {
	position: relative;
}

.nav-link {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 14px 18px;
	color: white;
	text-decoration: none;
	font-weight: 500;
	font-size: 14px;
	transition: all 0.3s ease;
	border-right: 1px solid rgba(255,255,255,0.1);
	cursor: pointer;
}

.nav-link:hover {
	background: rgba(255,255,255,0.15);
}

.nav-link.active {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.nav-link .icon {
	font-size: 16px;
}

.nav-link .arrow {
	font-size: 10px;
	transition: transform 0.3s;
}

.nav-item:hover .arrow {
	transform: rotate(180deg);
}

/* Dropdown Menu */
.dropdown-menu {
	display: none;
	position: absolute;
	top: 100%;
	left: 0;
	min-width: 220px;
	background: white;
	border-radius: 0 0 8px 8px;
	box-shadow: 0 8px 25px rgba(0,0,0,0.15);
	z-index: 1000;
	overflow: hidden;
}

.nav-item:hover .dropdown-menu {
	display: block;
	animation: fadeIn 0.2s ease;
}

@keyframes fadeIn {
	from { opacity: 0; transform: translateY(-10px); }
	to { opacity: 1; transform: translateY(0); }
}

.dropdown-menu a {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 12px 18px;
	color: #333;
	text-decoration: none;
	font-size: 14px;
	transition: all 0.2s;
	border-bottom: 1px solid #f0f0f0;
}

.dropdown-menu a:last-child {
	border-bottom: none;
}

.dropdown-menu a:hover {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: white;
	padding-left: 24px;
}

.dropdown-menu a .sub-icon {
	width: 20px;
	text-align: center;
}

/* Submenu Divider */
.dropdown-divider {
	height: 1px;
	background: #e0e0e0;
	margin: 5px 0;
}

/* Logout special style */
.nav-link.logout {
	background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
	border-radius: 0;
}

.nav-link.logout:hover {
	background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
}

/* Insentif special style */
.nav-link.insentif {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Mobile responsive */
@media (max-width: 768px) {
	.navbar-container {
		flex-direction: column;
	}
	
	.dropdown-menu {
		position: static;
		box-shadow: none;
		border-radius: 0;
	}
	
	.nav-item:hover .dropdown-menu {
		display: block;
	}
}

/* Main Content Styles */
.utama {
	border: none;
	width: 95%;
	margin: auto;
	background: white;
	margin-top: 20px;
	border-radius: 12px;
	box-shadow: 0 4px 20px rgba(0,0,0,0.1);
	margin-bottom: 22px;
	overflow: hidden;
}

.utama .header {
	text-align: center;
	padding: 20px;
	background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
	color: white;
}

.utama .header h2 {
	margin: 0;
	font-size: 32px;
	font-weight: 700;
}

.utama .footer {
	background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
	padding: 15px;
	text-align: center;
	color: white;
	font-size: 13px;
}

.row {
	padding: 22px;
}

.welcomeuser {
	background: #f8f9fa;
	padding: 10px 15px;
	border-radius: 8px;
	font-size: 14px;
	color: #555;
}
</style>

<link rel="stylesheet" href="../global/css/menu.css">
<script src="../global/js/menu.js"></script>

<div class="utama">

    <div class="header">
        <h2>🏆 Musabaqah System</h2>
    </div>

	<!-- Navigation Bar -->
	<nav class="navbar">
		<div class="navbar-container">
			
			<!-- 1. HOME -->
			<div class="nav-item">
				<a href="?page=utama&page2=home" class="nav-link <?php echo $page2=='home'?'active':''; ?>">
					<span class="icon">🏠</span> Home
				</a>
			</div>

			<!-- 2. PENGATURAN -->
			<div class="nav-item">
				<div class="nav-link">
					<span class="icon">⚙️</span> Pengaturan <span class="arrow">▼</span>
				</div>
				<div class="dropdown-menu">
					<a href="?page=utama&page2=event"><span class="sub-icon">📅</span> Event</a>
					<a href="?page=utama&page2=cabang&idevent=<?php echo $ideventaktif; ?>"><span class="sub-icon">🌳</span> Cabang</a>
					<a href="?page=utama&page2=babak"><span class="sub-icon">🔄</span> Babak</a>
					<a href="?page=utama&page2=keahlian"><span class="sub-icon">🎯</span> Keahlian</a>
					<a href="?page=utama&page2=user"><span class="sub-icon">👤</span> User</a>
					<a href="?page=utama&page2=user_level"><span class="sub-icon">🔐</span> User Level</a>
					<a href="?page=utama&page2=kafilah"><span class="sub-icon">🏕️</span> Kafilah</a>
					<a href="?page=utama&page2=_viewall"><span class="sub-icon">📊</span> Hierarki Cabang</a>
				</div>
			</div>

			<!-- 3. PENDAFTARAN -->
			<div class="nav-item">
				<div class="nav-link">
					<span class="icon">📝</span> Pendaftaran <span class="arrow">▼</span>
				</div>
				<div class="dropdown-menu">
					<a href="?page=utama&page2=peserta3"><span class="sub-icon">👥</span> Peserta</a>
					<a href="?page=utama&page2=peserta_all"><span class="sub-icon">📋</span> All Peserta</a>
					<a href="?page=utama&page2=panitera"><span class="sub-icon">📝</span> Panitera</a>
					<a href="?page=utama&page2=hakim"><span class="sub-icon">⚖️</span> Hakim</a>
					<a href="?page=utama&page2=orang"><span class="sub-icon">👤</span> Orang</a>
					<a href="?page=utama&page2=acaknomor"><span class="sub-icon">🎲</span> Acak Nomor Peserta</a>
				</div>
			</div>

			<!-- 4. EMAQRA -->
			<div class="nav-item">
				<div class="nav-link">
					<span class="icon">📖</span> Emaqra <span class="arrow">▼</span>
				</div>
				<div class="dropdown-menu">
					<a href="?page=utama&page2=emaqra_tilawah"><span class="sub-icon">🎤</span> Tilawah & MHQ</a>
					<a href="?page=utama&page2=emaqra_tafsir"><span class="sub-icon">📚</span> Tafsir</a>
					<a href="?page=utama&page2=emaqra_qiraat"><span class="sub-icon">📜</span> Qiraat</a>
					<a href="?page=utama&page2=emaqra_mfq"><span class="sub-icon">📖</span> MFQ/Hadits</a>
					<a href="?page=utama&page2=emaqra_mushaf"><span class="sub-icon">🕌</span> Mushaf</a>
					<a href="?page=utama&page2=emaqra_kategori"><span class="sub-icon">🏷️</span> Kategori</a>
				</div>
			</div>

			<!-- 5. PENILAIAN -->
			<div class="nav-item">
				<div class="nav-link">
					<span class="icon">📊</span> Penilaian <span class="arrow">▼</span>
				</div>
				<div class="dropdown-menu">
					<a href="?page=utama&page2=nilai0"><span class="sub-icon">✏️</span> Penilaian</a>
					<a href="?page=cetak" target="_blank"><span class="sub-icon">🖨️</span> Cetak Nilai</a>
					<a href="?page=utama&page2=lapnilai"><span class="sub-icon">📈</span> Rekapitulasi Nilai</a>
					<a href="?page=utama&page2=datanilai"><span class="sub-icon">📋</span> Data Nilai</a>
				</div>
			</div>

			<!-- 6. HAFIDZ (for adminprov/adminkabko) -->
			<?php if($role=='adminprov' || $role=='adminkabko'){ ?>
			<div class="nav-item">
				<div class="nav-link">
					<span class="icon">🧕</span> Hafidz <span class="arrow">▼</span>
				</div>
				<div class="dropdown-menu">
					<a href="?page=utama&page2=hafidz"><span class="sub-icon">📋</span> Data Hafidz</a>
					<a href="?page=utama&page2=hafidz_dashboard"><span class="sub-icon">📊</span> Dashboard</a>
					<a href="?page=utama&page2=hafidz_nilai"><span class="sub-icon">✏️</span> Penilaian Hafidz</a>
					<a href="?page=utama&page2=hafidz_reports"><span class="sub-icon">📝</span> Laporan</a>
					<a href="?page=utama&page2=hafidz_saran"><span class="sub-icon">💬</span> Saran</a>
					<a href="?page=utama&page2=hafidz_rekening"><span class="sub-icon">🏦</span> Rekening</a>
					<?php if($role=='adminprov'){ ?>
					<a href="?page=utama&page2=hafidz_transfer"><span class="sub-icon">💸</span> Transfer</a>
					<a href="?page=utama&page2=admin_kabko"><span class="sub-icon">👥</span> Manage Admin Kab/Ko</a>
					<?php } ?>
				</div>
			</div>
			<?php } ?>

			<!-- INSENTIF GUBERNUR -->
			<?php if($role=='adminprov' || $role=='adminkabko'){ ?>
			<div class="nav-item">
				<a href="?page=utama&page2=insentif_gubernur" class="nav-link insentif <?php echo $page2=='insentif_gubernur'?'active':''; ?>">
					<span class="icon">🏆</span> Insentif Gubernur
				</a>
			</div>
			<?php } ?>

			<!-- LOGOUT -->
			<div class="nav-item" style="margin-left: auto;">
				<a href="?logout" class="nav-link logout">
					<span class="icon">🚪</span> Logout
				</a>
			</div>

		</div>
	</nav>

	<div class="row" style="display: flex; justify-content: space-between;">
		<div class="welcomeuser">
			<?php
				$iduser=getiduser();
				if($iduser){
					$rowvu=getonebaris("select * from view_user where iduser=$iduser");
					echo "👋 Selamat datang, <strong>$rowvu[nama]</strong> ($rowvu[level] / $rowvu[role])";
				}else{
					echo "Selamat datang";
				}
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
		copyright &copy;2025 - Musabaqah System
	</div>
</div>