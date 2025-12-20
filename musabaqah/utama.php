<?php
function getmenu()
{
	global $role;
	$ideventaktif = getonedata("select id from event where aktif=1");
	$page2 = isset($_GET['page2']) ? $_GET['page2'] : '';
?>
	<!-- Master Data Group -->
	<a href='?page=utama&page2=home' class='<?php echo $page2 == "home" ? "aktif" : ""; ?>'>🏠 Home</a>
	<a href='?page=utama&page2=event' class='<?php echo $page2 == "event" ? "aktif" : ""; ?>'>📅 Event</a>
	<a href='?page=utama&page2=kafilah' class='<?php echo $page2 == "kafilah" ? "aktif" : ""; ?>'>👥 Kafilah</a>
	<a href='?page=utama&page2=cabang&idevent=<?php echo $ideventaktif ?>' class='<?php echo $page2 == "cabang" ? "aktif" : ""; ?>'>📂 Cabang</a>

	<!-- Penilaian Group -->
	<a href='?page=utama&page2=nilai0' class='<?php echo $page2 == "nilai0" ? "aktif" : ""; ?>'>📝 Penilaian</a>
	<a href='?page=utama&page2=lapnilai' class='<?php echo $page2 == "lapnilai" ? "aktif" : ""; ?>'>📊 Nilai</a>
	<a target=_blank href='?page=cetak'>🖨️ Cetak</a>

	<!-- Peserta Group -->
	<a href='?page=utama&page2=peserta_all' class='<?php echo $page2 == "peserta_all" ? "aktif" : ""; ?>'>👤 Peserta</a>
	<a href='?page=utama&page2=orang' class='<?php echo $page2 == "orang" ? "aktif" : ""; ?>'>👤 Orang</a>
	<a href='?page=utama&page2=panitera' class='<?php echo $page2 == "panitera" ? "aktif" : ""; ?>'>⚖️ Panitera</a>

	<!-- Settings Group -->
	<a href='?page=utama&page2=babak' class='<?php echo $page2 == "babak" ? "aktif" : ""; ?>'>🔢 Babak</a>
	<a href='?page=utama&page2=keahlian' class='<?php echo $page2 == "keahlian" ? "aktif" : ""; ?>'>💼 Keahlian</a>
	<a href='?page=utama&page2=_viewall' class='<?php echo $page2 == "_viewall" ? "aktif" : ""; ?>'>🌳 Hierarki</a>

	<!-- Users Group -->
	<a href='?page=utama&page2=user' class='<?php echo $page2 == "user" ? "aktif" : ""; ?>'>👤 User</a>
	<a href='?page=utama&page2=user_level' class='<?php echo $page2 == "user_level" ? "aktif" : ""; ?>'>🔑 Level</a>

	<!-- Admin Kab/Ko Management -->
	<?php if ($role == 'adminprov') { ?>
		<a href='?page=utama&page2=admin_kabko' class='<?php echo $page2 == "admin_kabko" ? "aktif" : ""; ?>'>🏛️ Admin Kab/Ko</a>
		<a href='?page=utama&page2=pengaturan' class='<?php echo $page2 == "pengaturan" ? "aktif" : ""; ?>'>⚙️ Pengaturan</a>
	<?php } ?>

	<!-- Hafidz Section -->
	<a href='?page=utama&page2=hafidz' class='<?php echo $page2 == "hafidz" ? "aktif" : ""; ?>'>📖 Hafidz</a>
	<?php if ($role == 'adminprov' || $role == 'adminkabko') { ?>
		<a href='?page=utama&page2=hafidz_dashboard' class='<?php echo $page2 == "hafidz_dashboard" ? "aktif" : ""; ?>'>📈 Dashboard</a>
		<a href='?page=utama&page2=hafidz_nilai' class='<?php echo $page2 == "hafidz_nilai" ? "aktif" : ""; ?>'>✅ Penilaian</a>
		<a href='?page=utama&page2=hafidz_reports' class='<?php echo $page2 == "hafidz_reports" ? "aktif" : ""; ?>'>📋 Laporan</a>
		<a href='?page=utama&page2=hafidz_saran' class='<?php echo $page2 == "hafidz_saran" ? "aktif" : ""; ?>'>💬 Saran</a>
		<a href='?page=utama&page2=hafidz_rekening' class='<?php echo $page2 == "hafidz_rekening" ? "aktif" : ""; ?>'>💳 Rekening</a>
	<?php } ?>
	<?php if ($role == 'adminprov') { ?>
		<a href='?page=utama&page2=hafidz_transfer' class='<?php echo $page2 == "hafidz_transfer" ? "aktif" : ""; ?>'>🔄 Transfer</a>
	<?php } ?>

	<!-- Insentif Gubernur -->
	<?php if ($role == 'adminprov' || $role == 'adminkabko') { ?>
		<a href='?page=utama&page2=insentif_gubernur' class='<?php echo $page2 == "insentif_gubernur" ? "aktif" : ""; ?>'>🏆 Insentif</a>
	<?php } ?>

	<!-- Tools -->
	<a href='?page=utama&page2=acaknomor' class='<?php echo $page2 == "acaknomor" ? "aktif" : ""; ?>'>🎲 Acak Nomor</a>

	<!-- Logout -->
	<a href='?logout' class='logout-btn'>🚪 Logout</a>
<?php
}
?>

<?php
if (isset($_GET["page2"])) {
	$page2 = $_GET["page2"];
} else {
	header("Location:?page=utama&page2=home");
}

$iduser = getiduser();
if ($iduser) {
	$role = getonedata("select role from view_user where iduser=" . $iduser);
} else {
	$role = '';
}
?>

<link rel="stylesheet" href="../global/css/modern.css">
<link rel="stylesheet" href="../global/css/menu.css">

<style>
	/* Override menu for modern dark theme */
	.menu a {
		background: var(--bg-card);
		color: var(--text-secondary);
		border-right: 1px solid var(--border-color);
		padding: 12px 16px;
		font-size: 0.85rem;
		transition: all 0.2s ease;
		text-decoration: none;
		display: flex;
		align-items: center;
		gap: 6px;
	}

	.menu a:hover {
		background: var(--bg-card-hover);
		color: var(--text-primary);
	}

	.menu a.aktif {
		background: rgba(16, 185, 129, 0.15);
		color: var(--primary-light);
		border-bottom: 2px solid var(--primary);
	}

	.menu a.logout-btn {
		background: rgba(239, 68, 68, 0.1);
		color: #f87171;
	}

	.menu a.logout-btn:hover {
		background: rgba(239, 68, 68, 0.2);
	}

	.menu .judul .kiri {
		background: var(--gradient-primary);
		flex: 1;
	}

	.menu .judul .kanan {
		background: var(--bg-card);
	}

	.menu .box .item {
		background: var(--primary);
	}

	.menu .isi {
		background: var(--bg-surface);
		scrollbar-width: thin;
	}

	/* Modern App Container */
	.modern-app-container {
		background: var(--glass-bg);
		backdrop-filter: blur(12px);
		border: 1px solid var(--glass-border);
		border-radius: 16px;
		box-shadow: 0 10px 40px rgba(0, 0, 0, 0.4), 0 0 40px rgba(16, 185, 129, 0.1);
		overflow: hidden;
		margin: 20px;
		min-height: calc(100vh - 40px);
	}

	/* Modern Header */
	.modern-header-main {
		background: var(--gradient-primary);
		padding: 30px 20px;
		text-align: center;
		position: relative;
		overflow: hidden;
	}

	.modern-header-main::before {
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
		opacity: 0.8;
	}

	.modern-header-main h2 {
		position: relative;
		z-index: 1;
		color: white;
		font-size: 2.5rem;
		font-weight: 700;
		margin: 0;
		text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
		letter-spacing: -0.02em;
	}

	.modern-header-main .subtitle {
		position: relative;
		z-index: 1;
		color: rgba(255, 255, 255, 0.8);
		font-size: 0.9rem;
		margin-top: 8px;
	}

	/* Welcome Bar */
	.modern-welcome-bar {
		background: var(--bg-surface);
		border-bottom: 1px solid var(--border-color);
		padding: 12px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
	}

	.modern-welcome-bar .user-info {
		color: var(--text-secondary);
		font-size: 0.875rem;
	}

	.modern-welcome-bar .user-info strong {
		color: var(--primary-light);
		font-weight: 600;
	}

	.modern-welcome-bar .role-badge {
		background: var(--gradient-primary);
		color: white;
		padding: 4px 12px;
		border-radius: 20px;
		font-size: 0.75rem;
		font-weight: 600;
		text-transform: uppercase;
	}

	/* Content Area */
	.modern-content-area {
		padding: 24px;
		min-height: 400px;
		background: var(--bg-dark);
	}

	/* Modern Footer */
	.modern-footer-main {
		background: var(--bg-card);
		border-top: 1px solid var(--border-color);
		padding: 16px 20px;
		text-align: center;
		color: var(--text-muted);
		font-size: 0.8rem;
	}

	.modern-footer-main a {
		color: var(--primary-light);
		text-decoration: none;
	}

	/* Responsive */
	@media (max-width: 768px) {
		.modern-app-container {
			margin: 10px;
			border-radius: 12px;
		}

		.modern-header-main h2 {
			font-size: 1.8rem;
		}

		.modern-content-area {
			padding: 16px;
		}
	}
</style>

<script src="../global/js/menu.js"></script>

<div class="modern-app-container">

	<div class="modern-header-main">
		<h2>✨ Musabaqah</h2>
		<div class="subtitle">Sistem Manajemen Musabaqah & Hafidz</div>
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
			<?php getmenu(); ?>
		</div>
	</div>

	<div class="modern-welcome-bar">
		<div class="user-info">
			<?php
			$iduser = getiduser();
			if ($iduser) {
				$rowvu = getonebaris("select * from view_user where iduser=$iduser");
				echo "👋 Selamat datang, <strong>$rowvu[nama]</strong>";
			} else {
				echo "👋 Selamat datang";
			}
			?>
		</div>
		<?php if ($iduser && isset($rowvu)) { ?>
			<span class="role-badge"><?php echo $rowvu['role']; ?></span>
		<?php } ?>
	</div>

	<div class="modern-content-area">
		<?php
		if ($page2 == "") {
			die("");
		} else {
			include_once("utama/$page2.php");
		}
		?>
	</div>

	<div class="modern-footer-main">
		<p>© 2025 Musabaqah System · Dibuat dengan ❤️ untuk Jawa Timur</p>
	</div>
</div>