<?php
// Load configuration
include_once __DIR__ . '/../global/class/Config.php';

function getmenu()
{
	global $role;
	$ideventaktif = getonedata("select id from event where aktif=1");
	$page2 = isset($_GET['page2']) ? $_GET['page2'] : '';

	// Helper function to check if menu is active
	$isActive = function ($pages) use ($page2) {
		if (is_array($pages)) {
			return in_array($page2, $pages) ? "aktif" : "";
		}
		return $page2 == $pages ? "aktif" : "";
	};

	// Check if any submenu in group is active
	$groupActive = function ($pages) use ($page2) {
		return in_array($page2, $pages) ? "aktif" : "";
	};
?>
	<!-- 1. Home -->
	<a href='?page=utama&page2=home' class='<?php echo $isActive("home"); ?>'>🏠 Home</a>

	<!-- 1.5. Portal Hafiz (Special for Hafiz Role) -->
	<?php if ($role == 'hafiz') { ?>
		<div class="menu-group">
			<a class="menu-parent <?php echo $groupActive(["hafiz_dashboard", "hafiz_laporan", "hafiz_dokumen"]); ?>">📖 Portal Hafiz</a>
			<div class="menu-dropdown">
				<a href='?page=utama&page2=hafiz_dashboard' class='<?php echo $isActive("hafiz_dashboard"); ?>'>📊 Dashboard Saya</a>
				<a href='?page=utama&page2=hafiz_laporan' class='<?php echo $isActive("hafiz_laporan"); ?>'>📝 Laporan Harian</a>
				<a href='?page=utama&page2=hafiz_dokumen' class='<?php echo $isActive("hafiz_dokumen"); ?>'>📁 Dokumen Saya</a>
			</div>
		</div>
	<?php } ?>

	<!-- 2. Pengaturan -->
	<div class="menu-group">
		<a class="menu-parent <?php echo $groupActive(["event", "cabang", "babak", "keahlian", "user", "user_level"]); ?>">⚙️ Pengaturan</a>
		<div class="menu-dropdown">
			<a href='?page=utama&page2=event' class='<?php echo $isActive("event"); ?>'>📅 Event</a>
			<a href='?page=utama&page2=cabang&idevent=<?php echo $ideventaktif ?>' class='<?php echo $isActive("cabang"); ?>'>📂 Cabang</a>
			<a href='?page=utama&page2=babak' class='<?php echo $isActive("babak"); ?>'>🔢 Babak</a>
			<a href='?page=utama&page2=keahlian' class='<?php echo $isActive("keahlian"); ?>'>💼 Keahlian</a>
			<a href='?page=utama&page2=user' class='<?php echo $isActive("user"); ?>'>👤 User</a>
			<a href='?page=utama&page2=user_level' class='<?php echo $isActive("user_level"); ?>'>🔑 Level</a>
			<?php if ($role == 'adminprov' || $role == 'role1') { ?>
				<a href='?page=utama&page2=admin_kabko' class='<?php echo $isActive("admin_kabko"); ?>'>🏛️ Admin Kab/Ko</a>
				<a href='?page=utama&page2=pengaturan' class='<?php echo $isActive("pengaturan"); ?>'>⚙️ Pengaturan User</a>
			<?php } ?>
		</div>
	</div>

	<!-- 3. Pendaftaran -->
	<div class="menu-group">
		<a class="menu-parent <?php echo $groupActive(["kafilah", "panitera", "peserta_all", "hakim", "orang"]); ?>">📋 Pendaftaran</a>
		<div class="menu-dropdown">
			<a href='?page=utama&page2=kafilah' class='<?php echo $isActive("kafilah"); ?>'>👥 Kafilah</a>
			<a href='?page=utama&page2=panitera' class='<?php echo $isActive("panitera"); ?>'>⚖️ Panitera</a>
			<a href='?page=utama&page2=peserta_all' class='<?php echo $isActive("peserta_all"); ?>'>👤 Peserta</a>
			<a href='?page=utama&page2=hakim' class='<?php echo $isActive("hakim"); ?>'>⚖️ Hakim</a>
			<a href='?page=utama&page2=orang' class='<?php echo $isActive("orang"); ?>'>👤 Official</a>
		</div>
	</div>

	<!-- 4. Penilaian -->
	<div class="menu-group">
		<a class="menu-parent <?php echo $groupActive(["nilai0", "lapnilai"]); ?>">📝 Penilaian</a>
		<div class="menu-dropdown">
			<a href='?page=utama&page2=nilai0' class='<?php echo $isActive("nilai0"); ?>'>✏️ Mulai Nilai</a>
			<a href='?page=utama&page2=lapnilai' class='<?php echo $isActive("lapnilai"); ?>'>📊 Rekap Nilai</a>
			<a target='_blank' href='?page=cetak'>🖨️ Cetak Nilai</a>
		</div>
	</div>

	<!-- 5. Emaqra -->
	<div class="menu-group">
		<a class="menu-parent <?php echo $groupActive(["emaqra_tilawah", "emaqra_qiraat", "emaqra_tafsir", "emaqra_mfq", "emaqra_mushaf", "emaqra_kategori"]); ?>">📖 Emaqra</a>
		<div class="menu-dropdown">
			<a href='?page=utama&page2=emaqra_tilawah' class='<?php echo $isActive("emaqra_tilawah"); ?>'>🎤 Tilawah & MHQ</a>
			<a href='?page=utama&page2=emaqra_qiraat' class='<?php echo $isActive("emaqra_qiraat"); ?>'>📜 Qiraat</a>
			<a href='?page=utama&page2=emaqra_tafsir' class='<?php echo $isActive("emaqra_tafsir"); ?>'>📚 Tafsir</a>
			<a href='?page=utama&page2=emaqra_mfq' class='<?php echo $isActive("emaqra_mfq"); ?>'>📝 MFQ/Hadits</a>
			<a href='?page=utama&page2=emaqra_mushaf&jenis=indonesia' class='<?php echo ($page2 == "emaqra_mushaf" && isset($_GET['jenis']) && $_GET['jenis'] == 'indonesia') ? "aktif" : ""; ?>'>📕 Mushaf Indonesia</a>
			<a href='?page=utama&page2=emaqra_mushaf&jenis=madinah' class='<?php echo ($page2 == "emaqra_mushaf" && isset($_GET['jenis']) && $_GET['jenis'] == 'madinah') ? "aktif" : ""; ?>'>📗 Mushaf Madinah</a>
			<a href='?page=utama&page2=emaqra_kategori' class='<?php echo $isActive("emaqra_kategori"); ?>'>📂 Kategori</a>
		</div>
	</div>

	<!-- 6. Tunjangan Huffadz -->
	<?php if ($role == 'adminprov' || $role == 'adminkabko' || $role == 'role1' || $role == 'role2') { ?>
		<div class="menu-group">
			<a class="menu-parent <?php echo $groupActive(["hafidz", "hafidz_dashboard", "hafidz_nilai", "hafidz_reports", "hafidz_saran", "hafidz_rekening", "hafidz_transfer", "insentif_gubernur", "verifikasi_laporan"]); ?>">💰 Tunjangan Huffadz</a>
			<div class="menu-dropdown">
				<a href='?page=utama&page2=hafidz' class='<?php echo $isActive("hafidz"); ?>'>📖 Data Hafidz</a>
				<a href='?page=utama&page2=verifikasi_laporan' class='<?php echo $isActive("verifikasi_laporan"); ?>'>✅ Verif Laporan</a>
				<a href='?page=utama&page2=hafidz_dashboard' class='<?php echo $isActive("hafidz_dashboard"); ?>'>📈 Dashboard</a>
				<a href='?page=utama&page2=hafidz_nilai' class='<?php echo $isActive("hafidz_nilai"); ?>'>✅ Penilaian</a>
				<a href='?page=utama&page2=hafidz_reports' class='<?php echo $isActive("hafidz_reports"); ?>'>📋 Laporan Rekap</a>
				<a href='?page=utama&page2=hafidz_rekening' class='<?php echo $isActive("hafidz_rekening"); ?>'>💳 Rekening</a>
				<a href='?page=utama&page2=insentif_gubernur' class='<?php echo $isActive("insentif_gubernur"); ?>'>🏆 Insentif Gubernur</a>
			</div>
		</div>
	<?php } ?>

	<!-- Tools (hidden in dropdown for cleaner look) -->
	<a href='?page=utama&page2=_viewall' class='<?php echo $isActive("_viewall"); ?>'>🌳 Hierarki</a>
	<a href='?page=utama&page2=acaknomor' class='<?php echo $isActive("acaknomor"); ?>'>🎲 Acak</a>

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

	/* Dropdown Menu Overrides - Desktop */
	@media (min-width: 786px) {
		.menu .isi {
			display: flex;
			flex-direction: row;
			flex-wrap: wrap;
			align-items: center;
			overflow: visible !important;
		}

		.menu-group {
			position: relative;
			display: inline-block;
		}

		.menu-group>a.menu-parent {
			cursor: pointer;
		}

		.menu-dropdown {
			position: absolute;
			top: 100%;
			left: 0;
			min-width: 200px;
			background: var(--bg-card, #1e293b);
			border: 1px solid var(--border-color);
			border-radius: 8px;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
			opacity: 0;
			visibility: hidden;
			transform: translateY(-10px);
			transition: all 0.2s ease;
			z-index: 1000;
			display: block !important;
		}

		.menu-group:hover .menu-dropdown {
			opacity: 1;
			visibility: visible;
			transform: translateY(0);
		}

		.menu-dropdown a {
			display: block !important;
			padding: 10px 16px !important;
			border-right: none !important;
			border-bottom: 1px solid var(--border-color);
			white-space: nowrap;
		}

		.menu-dropdown a:last-child {
			border-bottom: none;
		}

		.menu-dropdown a:hover {
			background: rgba(16, 185, 129, 0.1) !important;
			color: var(--primary-light) !important;
		}
	}

	/* Mobile Dropdown */
	@media (max-width: 785px) {
		.menu-dropdown {
			display: none;
			position: static;
			background: rgba(0, 0, 0, 0.2);
			border: none;
			border-radius: 0;
		}

		.menu-group.open .menu-dropdown {
			display: block;
		}

		.menu-dropdown a {
			padding-left: 32px !important;
		}
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
		<h2><?php echo Config::get('app.logo_emoji', '🕌'); ?> <?php echo Config::get('app.name', 'E-LPTQ'); ?></h2>
		<div class="subtitle"><?php echo Config::get('app.description', 'Sistem Manajemen Musabaqah & Hafidz'); ?></div>
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
				$rowvu = getonebaris("select * from view_user where iduser=" . $iduser);
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
		<p>© <?php echo Config::get('copyright.year', '2025'); ?> <?php echo Config::get('copyright.footer_text', 'Musabaqah System · Dibuat dengan ❤️ untuk Jawa Timur'); ?></p>
	</div>
</div>