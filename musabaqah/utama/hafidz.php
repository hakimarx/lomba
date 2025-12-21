<?php
// Hafidz CRUD admin page for adminprov / adminkabko
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov", "adminkabko"]);

$iduser = getiduser();
$user = getonebaris("select * from user where id=$iduser");
$user_kab_kota = $user['kab_kota'] ?? '';
$role_name = getonedata("select nama from user_level where id=" . $user['iduser_level']);

// Handle Deceased Report
if (isset($_POST['action']) && $_POST['action'] == 'lapor_meninggal') {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal_meninggal'];
    $bukti = null;
    if (isset($_FILES['bukti_meninggal']) && $_FILES['bukti_meninggal']['error'] == 0) {
        $dir = '../../assets/documents/bukti_meninggal';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $fn = time() . "_" . basename($_FILES['bukti_meninggal']['name']);
        $dest = "$dir/" . $fn;
        move_uploaded_file($_FILES['bukti_meninggal']['tmp_name'], $dest);
        $bukti = "assets/documents/bukti_meninggal/" . $fn;
    }
    execute("update hafidz set status='meninggal', tanggal_meninggal='$tanggal', bukti_meninggal='$bukti' where id=$id");
    alert("Laporan meninggal berhasil disimpan.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

// Handle Transfer Request
if (isset($_POST['action']) && $_POST['action'] == 'pindah_kabko') {
    $id = $_POST['id'];
    $tujuan = $_POST['tujuan_pindah'];
    execute("update hafidz set status='pindah_request', tujuan_pindah='$tujuan' where id=$id");
    alert("Permintaan pindah berhasil dikirim.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

// Handle KabKo Incentive Verification
if (isset($_POST['action']) && $_POST['action'] == 'verifikasi_kabko') {
    $id = $_POST['id'];
    $status = $_POST['status_insentif_kabko'];
    $catatan = mysqli_real_escape_string($GLOBALS['koneksi'], $_POST['catatan_insentif_kabko']);
    execute("update hafidz set status_insentif_kabko='$status', catatan_insentif_kabko='$catatan', tgl_insentif_kabko=NOW(), verifikator_insentif_kabko=$iduser where id=$id");
    alert("Verifikasi berhasil disimpan.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

// Handle Approve Mutation (Admin Prov only)
if (isset($_POST['action']) && $_POST['action'] == 'approve_pindah' && ($role_name == 'admin prov' || $role_name == 'role1')) {
    $id = $_POST['id'];
    $h = getonebaris("select * from hafidz where id=$id");
    $asal = $h['kab_kota'];
    $tujuan = $h['tujuan_pindah'];
    execute("INSERT INTO mutasi (idhafidz, asal_kabko, tujuan_kabko, tanggal, keterangan) VALUES ($id, '$asal', '$tujuan', CURDATE(), 'Disetujui Admin Prov')");
    execute("update hafidz set kab_kota='$tujuan', status='aktif', tujuan_pindah='' where id=$id");
    $nik = $h['nik'];
    if ($nik) {
        execute("update user set kab_kota='$tujuan' where nik='$nik'");
    }
    alert("Mutasi berhasil disetujui.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

// Handle KTP Upload with compression
if (isset($_POST['action']) && $_POST['action'] == 'upload_ktp') {
    $id = $_POST['id'];
    $ktpData = $_POST['ktp_data'] ?? '';
    if ($ktpData) {
        $dir = '../../assets/documents/ktp';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $ktpData = str_replace('data:image/jpeg;base64,', '', $ktpData);
        $ktpData = str_replace('data:image/png;base64,', '', $ktpData);
        $ktpData = str_replace('data:image/webp;base64,', '', $ktpData);
        $ktpData = base64_decode($ktpData);
        $fn = time() . "_ktp_$id.jpg";
        file_put_contents("$dir/$fn", $ktpData);
        execute("update hafidz set foto_ktp='assets/documents/ktp/$fn' where id=$id");
        echo json_encode(['success' => true, 'path' => "assets/documents/ktp/$fn"]);
    } else {
        echo json_encode(['success' => false, 'error' => 'No data']);
    }
    exit;
}

// CRUD Setup
$vtabel = "hafidz";
$filter_kabko = isset($_GET['filter_kabko']) ? $_GET['filter_kabko'] : '';
$where = "";
if ($role_name == 'admin prov' || $role_name == 'role1') {
    $where = "1=1";
} else {
    $where = "kab_kota='$user_kab_kota'";
}
if ($filter_kabko !== '') {
    $where .= " AND is_insentif_kabko='$filter_kabko'";
}
$querytabel = "select * from $vtabel where $where ORDER BY nama ASC";

$kolomtrue = ["tahun_anggaran", "periode", "asal", "nik", "nama", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "umur", "jk", "alamat", "rt", "rw", "desa", "desa_kelurahan", "kecamatan", "kab_kota", "jumlah_hafalan_juz", "lembaga_tahfidz", "nama_pembimbing", "tahun_khatam", "no_sertifikat_tahfidz", "status_mengajar", "nama_lembaga_mengajar", "tmt_mengajar", "telepon", "keterangan", "lulus", "is_insentif_kabko", "status_insentif_kabko", "username", "password", "idkafilah", "nama_bank", "no_rekening", "atas_nama_rekening"];

if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") dbdelete($vtabel, $crudid);
    if ($mode == "tambah" || $mode == "edit") {
        $vpost = array();
        foreach ($kolomtrue as $item) {
            array_push($vpost, $_POST[$item] ?? '');
        }
        if ($mode == "tambah") {
            $insertCols = $kolomtrue;
            $insertVals = $vpost;
            $insertCols[] = 'created_by';
            $insertVals[] = getiduser();
            dbinsert($vtabel, $insertCols, $insertVals);
        } else {
            dbupdate($vtabel, $kolomtrue, $vpost, "where id=$crudid");
        }
    }
}
?>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
    <style>
        .hafidz-page {
            padding: 20px;
        }

        .hafidz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .hafidz-filters {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .hafidz-filters select {
            padding: 8px 12px;
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
        }

        .data-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.2s;
        }

        .data-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .card-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .card-nik {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-family: monospace;
        }

        .card-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            font-size: 0.85rem;
        }

        .info-item {
            display: flex;
            gap: 8px;
        }

        .info-label {
            color: var(--text-muted);
            min-width: 80px;
        }

        .info-value {
            color: var(--text-primary);
        }

        .card-actions {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border-color);
            flex-wrap: wrap;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-edit {
            background: var(--accent);
            color: white;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-info {
            background: #3b82f6;
            color: white;
        }

        .btn-success {
            background: var(--primary);
            color: white;
        }

        .btn-ktp {
            background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
            color: white;
        }

        /* KTP Modal Styles */
        .ktp-modal .box {
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }

        .ktp-upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .ktp-upload-area:hover {
            border-color: var(--primary);
            background: rgba(16, 185, 129, 0.05);
        }

        .ktp-preview {
            display: none;
            margin-bottom: 20px;
        }

        .ktp-preview canvas {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: var(--shadow-md);
        }

        .image-controls {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 15px 0;
            padding: 15px;
            background: var(--bg-surface);
            border-radius: 8px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .control-group label {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .control-group input[type="range"] {
            width: 120px;
        }

        .ocr-result {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }

        .ocr-result h4 {
            color: var(--primary-light);
            margin-bottom: 10px;
        }

        .ocr-field {
            display: flex;
            gap: 10px;
            margin-bottom: 8px;
            align-items: center;
        }

        .ocr-field label {
            min-width: 100px;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .ocr-field input {
            flex: 1;
            padding: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 4px;
            color: var(--text-primary);
        }

        .pending-card {
            border: 1px solid #fbbf24;
            background: rgba(245, 158, 11, 0.05);
        }

        .badge-status {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .badge-aktif {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }

        .badge-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }

        .badge-meninggal {
            background: rgba(107, 114, 128, 0.2);
            color: #6b7280;
        }

        /* Form Modal Improvements */
        .form-sections {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-section {
            background: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
        }

        .form-section h4 {
            color: var(--primary-light);
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 12px;
        }

        .form-field {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-field label {
            font-size: 0.75rem;
            color: var(--text-muted);
            text-transform: uppercase;
        }

        .form-field input,
        .form-field select,
        .form-field textarea {
            padding: 8px 10px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-field input:focus,
        .form-field select:focus {
            border-color: var(--primary);
            outline: none;
        }
    </style>
</head>

<div class="hafidz-page vmaxwidth centermargin">
    <div class="modern-card">
        <div class="hafidz-header">
            <h1 style="margin:0;">📖 DATA HAFIDZ</h1>
            <div class="hafidz-filters">
                <select onchange="window.location.href='?page=utama&page2=hafidz&filter_kabko='+this.value">
                    <option value="">-- Semua (Insentif) --</option>
                    <option value="1" <?php echo $filter_kabko == '1' ? 'selected' : ''; ?>>Ya (Insentif Kab Ko)</option>
                    <option value="0" <?php echo $filter_kabko == '0' ? 'selected' : ''; ?>>Tidak</option>
                </select>
                <button class="modern-btn modern-btn-primary" onclick="tambah()">➕ TAMBAH HAFIDZ</button>
            </div>
        </div>

        <?php
        // Show Pending Mutations for Admin Prov
        if ($role_name == 'admin prov' || $role_name == 'role1') {
            $pendingMutasi = getdata("SELECT * FROM hafidz WHERE status='pindah_request'");
            if (mysqli_num_rows($pendingMutasi) > 0) {
                echo "<div class='pending-card data-card'>";
                echo "<h3 style='color: #fbbf24; margin-bottom: 12px;'>⚠️ Permintaan Mutasi Wilayah</h3>";
                while ($pm = mysqli_fetch_assoc($pendingMutasi)) {
                    echo "<div style='display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid var(--border-color);'>";
                    echo "<div><strong>$pm[nama]</strong> — $pm[kab_kota] → <span style='color: var(--primary-light);'>$pm[tujuan_pindah]</span></div>";
                    echo "<form method='post' style='display:inline;'>
                            <input type='hidden' name='action' value='approve_pindah'>
                            <input type='hidden' name='id' value='$pm[id]'>
                            <button type='submit' class='btn-sm btn-success'>✓ Setujui</button>
                          </form>";
                    echo "</div>";
                }
                echo "</div>";
            }
        }
        ?>

        <!-- Data Cards -->
        <div id="dataContainer">
            <?php
            $data = getdata($querytabel);
            if (mysqli_num_rows($data) == 0) {
                echo "<div style='text-align: center; padding: 40px; color: var(--text-muted);'>
                        <div style='font-size: 3rem; margin-bottom: 10px;'>📋</div>
                        <p>Belum ada data hafidz</p>
                      </div>";
            }
            while ($row = mysqli_fetch_array($data)) {
                $id = $row['id'];
                $status = $row['status'] ?? 'aktif';
                $statusClass = $status == 'aktif' ? 'badge-aktif' : ($status == 'meninggal' ? 'badge-meninggal' : 'badge-pending');
                $ktpPath = $row['foto_ktp'] ?? '';
            ?>
                <div class="data-card" id="card<?php echo $id; ?>">
                    <div class="card-header">
                        <div>
                            <div class="card-name"><?php echo $row['nama_lengkap'] ?: $row['nama']; ?></div>
                            <div class="card-nik">NIK: <?php echo $row['nik'] ?: '-'; ?></div>
                        </div>
                        <span class="badge-status <?php echo $statusClass; ?>"><?php echo strtoupper($status); ?></span>
                    </div>
                    <div class="card-info">
                        <div class="info-item">
                            <span class="info-label">Kab/Kota:</span>
                            <span class="info-value"><?php echo $row['kab_kota']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Juz:</span>
                            <span class="info-value"><?php echo $row['jumlah_hafalan_juz']; ?> Juz</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Lembaga:</span>
                            <span class="info-value"><?php echo $row['lembaga_tahfidz'] ?: '-'; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Telepon:</span>
                            <span class="info-value"><?php echo $row['telepon'] ?: '-'; ?></span>
                        </div>
                    </div>
                    <div class="card-actions">
                        <button class="btn-sm btn-ktp" onclick="uploadKTP(<?php echo $id; ?>, '<?php echo addslashes($row['nama']); ?>')">📷 KTP<?php echo $ktpPath ? ' ✓' : ''; ?></button>
                        <button class="btn-sm btn-edit" onclick='editHafidz(<?php echo json_encode($row); ?>)'>✏️ Edit</button>
                        <button class="btn-sm btn-warning" onclick="laporMeninggal(<?php echo $id; ?>, '<?php echo addslashes($row['nama']); ?>')">⚰️ Meninggal</button>
                        <button class="btn-sm btn-info" onclick="pindahKabko(<?php echo $id; ?>, '<?php echo addslashes($row['nama']); ?>')">🔄 Pindah</button>
                        <?php if ($row['is_insentif_kabko']): ?>
                            <button class="btn-sm btn-success" onclick="verifKabko(<?php echo $id; ?>, '<?php echo addslashes($row['nama']); ?>')">✅ Verif</button>
                        <?php endif; ?>
                        <button class="btn-sm btn-delete" onclick="hapusHafidz(<?php echo $id; ?>)">🗑️ Hapus</button>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal" id="popup">
    <div class="box" style="max-width: 900px; max-height: 90vh; overflow-y: auto;">
        <form action="" method="post" name="formcrud">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    <h3 id="formTitle">📝 TAMBAH DATA HAFIDZ</h3>

                    <div class="form-sections">
                        <!-- Data Pribadi -->
                        <div class="form-section">
                            <h4>👤 DATA PRIBADI</h4>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>NIK</label>
                                    <input type="text" name="nik" id="f_nik" maxlength="16">
                                </div>
                                <div class="form-field">
                                    <label>Nama Panggilan</label>
                                    <input type="text" name="nama" id="f_nama">
                                </div>
                                <div class="form-field">
                                    <label>Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="f_nama_lengkap" required>
                                </div>
                                <div class="form-field">
                                    <label>Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" id="f_tempat_lahir">
                                </div>
                                <div class="form-field">
                                    <label>Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" id="f_tanggal_lahir">
                                </div>
                                <div class="form-field">
                                    <label>Jenis Kelamin</label>
                                    <select name="jk" id="f_jk">
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label>Telepon</label>
                                    <input type="text" name="telepon" id="f_telepon">
                                </div>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="form-section">
                            <h4>📍 ALAMAT</h4>
                            <div class="form-grid">
                                <div class="form-field" style="grid-column: span 2;">
                                    <label>Alamat Lengkap</label>
                                    <input type="text" name="alamat" id="f_alamat">
                                </div>
                                <div class="form-field">
                                    <label>RT</label>
                                    <input type="text" name="rt" id="f_rt">
                                </div>
                                <div class="form-field">
                                    <label>RW</label>
                                    <input type="text" name="rw" id="f_rw">
                                </div>
                                <div class="form-field">
                                    <label>Desa/Kelurahan</label>
                                    <input type="text" name="desa_kelurahan" id="f_desa_kelurahan">
                                </div>
                                <div class="form-field">
                                    <label>Kecamatan</label>
                                    <input type="text" name="kecamatan" id="f_kecamatan">
                                </div>
                                <div class="form-field">
                                    <label>Kabupaten/Kota</label>
                                    <input type="text" name="kab_kota" id="f_kab_kota">
                                </div>
                            </div>
                        </div>

                        <!-- Data Tahfidz -->
                        <div class="form-section">
                            <h4>📖 DATA TAHFIDZ</h4>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Jumlah Hafalan (Juz)</label>
                                    <input type="number" name="jumlah_hafalan_juz" id="f_jumlah_hafalan_juz" min="1" max="30">
                                </div>
                                <div class="form-field">
                                    <label>Lembaga Tahfidz</label>
                                    <input type="text" name="lembaga_tahfidz" id="f_lembaga_tahfidz">
                                </div>
                                <div class="form-field">
                                    <label>Nama Pembimbing</label>
                                    <input type="text" name="nama_pembimbing" id="f_nama_pembimbing">
                                </div>
                                <div class="form-field">
                                    <label>Tahun Khatam</label>
                                    <input type="number" name="tahun_khatam" id="f_tahun_khatam">
                                </div>
                                <div class="form-field">
                                    <label>No. Sertifikat</label>
                                    <input type="text" name="no_sertifikat_tahfidz" id="f_no_sertifikat_tahfidz">
                                </div>
                            </div>
                        </div>

                        <!-- Data Mengajar -->
                        <div class="form-section">
                            <h4>🎓 DATA MENGAJAR</h4>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Status Mengajar</label>
                                    <select name="status_mengajar" id="f_status_mengajar">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="form-field">
                                    <label>Nama Lembaga Mengajar</label>
                                    <input type="text" name="nama_lembaga_mengajar" id="f_nama_lembaga_mengajar">
                                </div>
                                <div class="form-field">
                                    <label>TMT Mengajar</label>
                                    <input type="date" name="tmt_mengajar" id="f_tmt_mengajar">
                                </div>
                            </div>
                        </div>

                        <!-- Data Bank & Insentif -->
                        <div class="form-section">
                            <h4>💰 DATA BANK & INSENTIF</h4>
                            <div class="form-grid">
                                <div class="form-field">
                                    <label>Nama Bank</label>
                                    <input type="text" name="nama_bank" id="f_nama_bank">
                                </div>
                                <div class="form-field">
                                    <label>No. Rekening</label>
                                    <input type="text" name="no_rekening" id="f_no_rekening">
                                </div>
                                <div class="form-field">
                                    <label>Atas Nama</label>
                                    <input type="text" name="atas_nama_rekening" id="f_atas_nama_rekening">
                                </div>
                                <div class="form-field">
                                    <label>Insentif Kab/Ko</label>
                                    <select name="is_insentif_kabko" id="f_is_insentif_kabko">
                                        <option value="0">Tidak</option>
                                        <option value="1">Ya</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden fields -->
                        <input type="hidden" name="tahun_anggaran" id="f_tahun_anggaran">
                        <input type="hidden" name="periode" id="f_periode">
                        <input type="hidden" name="asal" id="f_asal">
                        <input type="hidden" name="umur" id="f_umur">
                        <input type="hidden" name="desa" id="f_desa">
                        <input type="hidden" name="keterangan" id="f_keterangan">
                        <input type="hidden" name="lulus" id="f_lulus" value="0">
                        <input type="hidden" name="status_insentif_kabko" id="f_status_insentif_kabko">
                        <input type="hidden" name="username" id="f_username">
                        <input type="hidden" name="password" id="f_password">
                        <input type="hidden" name="idkafilah" id="f_idkafilah">
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="popup(false)">BATAL</button>
                    <button type="submit" class="modern-btn modern-btn-primary">💾 SIMPAN DATA</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- KTP Upload Modal -->
<div class="modal ktp-modal" id="popupKTP" style="display:none;">
    <div class="box">
        <div class="isian">
            <div class="isi">
                <h3>📷 UPLOAD & PROSES KTP</h3>
                <p id="ktpNama" style="color: var(--text-muted); margin-bottom: 15px;"></p>

                <div class="ktp-upload-area" id="ktpDropArea" onclick="document.getElementById('ktpInput').click()">
                    <div style="font-size: 2rem; margin-bottom: 10px;">📁</div>
                    <p>Klik atau seret file KTP di sini</p>
                    <small style="color: var(--text-muted);">Maksimal 500KB - Format: JPG, PNG</small>
                    <input type="file" id="ktpInput" accept="image/*" style="display:none;">
                </div>

                <div class="ktp-preview" id="ktpPreview">
                    <canvas id="ktpCanvas"></canvas>

                    <div class="image-controls">
                        <div class="control-group">
                            <label>🔄 Rotasi</label>
                            <input type="range" id="rotateSlider" min="0" max="360" value="0">
                        </div>
                        <div class="control-group">
                            <label>☀️ Kecerahan</label>
                            <input type="range" id="brightnessSlider" min="-100" max="100" value="0">
                        </div>
                        <div class="control-group">
                            <label>🎨 Kontras</label>
                            <input type="range" id="contrastSlider" min="-100" max="100" value="0">
                        </div>
                        <button type="button" class="btn-sm btn-info" onclick="resetImageControls()">↺ Reset</button>
                    </div>

                    <div class="ocr-result" id="ocrResult" style="display:none;">
                        <h4>🔍 HASIL DETEKSI OCR</h4>
                        <div class="ocr-field">
                            <label>NIK:</label>
                            <input type="text" id="ocr_nik" placeholder="Terdeteksi otomatis...">
                        </div>
                        <div class="ocr-field">
                            <label>Nama:</label>
                            <input type="text" id="ocr_nama" placeholder="Terdeteksi otomatis...">
                        </div>
                        <div class="ocr-field">
                            <label>Tempat/Tgl Lahir:</label>
                            <input type="text" id="ocr_ttl" placeholder="Terdeteksi otomatis...">
                        </div>
                        <div class="ocr-field">
                            <label>Alamat:</label>
                            <input type="text" id="ocr_alamat" placeholder="Terdeteksi otomatis...">
                        </div>
                        <button type="button" class="btn-sm btn-success" onclick="applyOCRToForm()" style="margin-top: 10px;">✓ Terapkan ke Form</button>
                    </div>
                </div>
            </div>
            <div class="footer">
                <button type="button" class="modern-btn modern-btn-secondary" onclick="closeKTPModal()">BATAL</button>
                <button type="button" class="modern-btn modern-btn-primary" onclick="saveKTP()" id="btnSaveKTP" disabled>💾 SIMPAN KTP</button>
            </div>
        </div>
    </div>
</div>

<!-- Other Modals (Meninggal, Pindah, Verif) -->
<div class="modal" id="popupMeninggal" style="display:none;">
    <div class="box">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="isian">
                <div class="isi">
                    <h3>⚰️ Lapor Hafidz Meninggal</h3>
                    <p id="namaMeninggal" style="color: var(--primary-light);"></p>
                    <input type="hidden" name="action" value="lapor_meninggal">
                    <input type="hidden" name="id" id="idMeninggal">
                    <div class="form-field" style="margin: 15px 0;">
                        <label>Tanggal Meninggal</label>
                        <input type="date" name="tanggal_meninggal" required>
                    </div>
                    <div class="form-field">
                        <label>Bukti (JPG/PDF)</label>
                        <input type="file" name="bukti_meninggal" required>
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="gi('popupMeninggal').style.display='none'">BATAL</button>
                    <button type="submit" class="modern-btn modern-btn-primary">SIMPAN</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="popupPindah" style="display:none;">
    <div class="box">
        <form action="" method="post">
            <div class="isian">
                <div class="isi">
                    <h3>🔄 Request Pindah Kab/Ko</h3>
                    <p id="namaPindah" style="color: var(--primary-light);"></p>
                    <input type="hidden" name="action" value="pindah_kabko">
                    <input type="hidden" name="id" id="idPindah">
                    <div class="form-field" style="margin: 15px 0;">
                        <label>Tujuan Kab/Kota</label>
                        <input type="text" name="tujuan_pindah" required placeholder="Nama Kab/Kota Tujuan">
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="gi('popupPindah').style.display='none'">BATAL</button>
                    <button type="submit" class="modern-btn modern-btn-primary">KIRIM REQUEST</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="popupVerifKabko" style="display:none;">
    <div class="box">
        <form action="" method="post">
            <div class="isian">
                <div class="isi">
                    <h3>✅ Verifikasi Insentif Kab/Ko</h3>
                    <p id="namaVerifKabko" style="color: var(--primary-light);"></p>
                    <input type="hidden" name="action" value="verifikasi_kabko">
                    <input type="hidden" name="id" id="idVerifKabko">
                    <div class="form-field" style="margin: 15px 0;">
                        <label>Status</label>
                        <select name="status_insentif_kabko" required>
                            <option value="Pending">Pending</option>
                            <option value="Diterima">Diterima</option>
                            <option value="Ditolak">Ditolak</option>
                        </select>
                    </div>
                    <div class="form-field">
                        <label>Catatan</label>
                        <textarea name="catatan_insentif_kabko" rows="3"></textarea>
                    </div>
                </div>
                <div class="footer">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="gi('popupVerifKabko').style.display='none'">BATAL</button>
                    <button type="submit" class="modern-btn modern-btn-primary">SIMPAN VERIFIKASI</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="../global/js/crud.js"></script>
<script>
    let currentKTPId = null;
    let originalImage = null;
    let canvas, ctx;

    // Form functions
    function popup(isopen) {
        gi("popup").style.display = isopen ? "flex" : "none";
    }

    function tambah() {
        gi("crud").value = "tambah";
        gi("formTitle").innerText = "📝 TAMBAH DATA HAFIDZ";
        // Clear all form fields
        document.querySelectorAll("#popup input, #popup select").forEach(el => {
            if (el.type !== 'hidden' && el.type !== 'submit' && el.type !== 'button') {
                el.value = '';
            }
        });
        popup(true);
    }

    function editHafidz(data) {
        gi("crud").value = "edit";
        gi("crudid").value = data.id;
        gi("formTitle").innerText = "✏️ EDIT DATA HAFIDZ";

        // Fill form fields
        const fields = ["nik", "nama", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "jk", "telepon",
            "alamat", "rt", "rw", "desa_kelurahan", "kecamatan", "kab_kota",
            "jumlah_hafalan_juz", "lembaga_tahfidz", "nama_pembimbing", "tahun_khatam", "no_sertifikat_tahfidz",
            "status_mengajar", "nama_lembaga_mengajar", "tmt_mengajar",
            "nama_bank", "no_rekening", "atas_nama_rekening", "is_insentif_kabko",
            "tahun_anggaran", "periode", "asal", "umur", "desa", "keterangan", "lulus",
            "status_insentif_kabko", "username", "password", "idkafilah"
        ];

        fields.forEach(f => {
            const el = gi("f_" + f);
            if (el) el.value = data[f] || '';
        });

        popup(true);
    }

    function hapusHafidz(id) {
        if (!confirm("Yakin ingin menghapus data hafidz ini?")) return;
        gi("crud").value = "hapus";
        gi("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    // KTP Functions
    function uploadKTP(id, nama) {
        currentKTPId = id;
        gi("ktpNama").innerText = nama;
        gi("popupKTP").style.display = "flex";
        gi("ktpPreview").style.display = "none";
        gi("ocrResult").style.display = "none";
        gi("btnSaveKTP").disabled = true;
        gi("ktpInput").value = '';
    }

    function closeKTPModal() {
        gi("popupKTP").style.display = "none";
        currentKTPId = null;
        originalImage = null;
    }

    // Image processing
    gi("ktpInput").addEventListener("change", function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Check file size (max 500KB before compression)
        if (file.size > 2 * 1024 * 1024) {
            xalert("File terlalu besar! Maksimal 2MB sebelum kompresi.");
            return;
        }

        const reader = new FileReader();
        reader.onload = function(event) {
            const img = new Image();
            img.onload = function() {
                originalImage = img;
                canvas = gi("ktpCanvas");
                ctx = canvas.getContext("2d");

                // Resize if needed (max 1200px width)
                let w = img.width;
                let h = img.height;
                if (w > 1200) {
                    h = h * (1200 / w);
                    w = 1200;
                }

                canvas.width = w;
                canvas.height = h;
                ctx.drawImage(img, 0, 0, w, h);

                gi("ktpPreview").style.display = "block";
                gi("btnSaveKTP").disabled = false;
                resetImageControls();

                // Show OCR button area
                gi("ocrResult").style.display = "block";
            };
            img.src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    function applyImageEffects() {
        if (!originalImage || !canvas || !ctx) return;

        const rotation = parseInt(gi("rotateSlider").value);
        const brightness = parseInt(gi("brightnessSlider").value);
        const contrast = parseInt(gi("contrastSlider").value);

        let w = originalImage.width;
        let h = originalImage.height;
        if (w > 1200) {
            h = h * (1200 / w);
            w = 1200;
        }

        // Handle rotation
        if (rotation === 90 || rotation === 270) {
            canvas.width = h;
            canvas.height = w;
        } else {
            canvas.width = w;
            canvas.height = h;
        }

        ctx.save();
        ctx.translate(canvas.width / 2, canvas.height / 2);
        ctx.rotate(rotation * Math.PI / 180);

        if (rotation === 90 || rotation === 270) {
            ctx.drawImage(originalImage, -w / 2, -h / 2, w, h);
        } else {
            ctx.drawImage(originalImage, -w / 2, -h / 2, w, h);
        }
        ctx.restore();

        // Apply brightness and contrast
        if (brightness !== 0 || contrast !== 0) {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            const factor = (259 * (contrast + 255)) / (255 * (259 - contrast));

            for (let i = 0; i < data.length; i += 4) {
                data[i] = Math.min(255, Math.max(0, factor * (data[i] - 128) + 128 + brightness));
                data[i + 1] = Math.min(255, Math.max(0, factor * (data[i + 1] - 128) + 128 + brightness));
                data[i + 2] = Math.min(255, Math.max(0, factor * (data[i + 2] - 128) + 128 + brightness));
            }

            ctx.putImageData(imageData, 0, 0);
        }
    }

    function resetImageControls() {
        gi("rotateSlider").value = 0;
        gi("brightnessSlider").value = 0;
        gi("contrastSlider").value = 0;
        if (originalImage) applyImageEffects();
    }

    // Event listeners for sliders
    ["rotateSlider", "brightnessSlider", "contrastSlider"].forEach(id => {
        gi(id).addEventListener("input", applyImageEffects);
    });

    function saveKTP() {
        if (!canvas || !currentKTPId) return;

        // Compress to max 500KB
        let quality = 0.8;
        let dataUrl = canvas.toDataURL("image/jpeg", quality);

        while (dataUrl.length > 500 * 1024 * 1.37 && quality > 0.1) {
            quality -= 0.1;
            dataUrl = canvas.toDataURL("image/jpeg", quality);
        }

        // Send to server
        fetch("?page=utama&page2=hafidz", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "action=upload_ktp&id=" + currentKTPId + "&ktp_data=" + encodeURIComponent(dataUrl)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    xalert("KTP berhasil disimpan!");
                    closeKTPModal();
                    location.reload();
                } else {
                    xalert("Gagal menyimpan: " + (data.error || 'Unknown error'));
                }
            })
            .catch(err => xalert("Error: " + err));
    }

    function applyOCRToForm() {
        // Get OCR values and apply to main form
        const nik = gi("ocr_nik").value;
        const nama = gi("ocr_nama").value;

        if (nik) gi("f_nik").value = nik;
        if (nama) gi("f_nama_lengkap").value = nama;

        closeKTPModal();
        popup(true);
        xalert("Data OCR diterapkan ke form. Silakan lengkapi data lainnya.");
    }

    // Other modal functions
    function laporMeninggal(id, nama) {
        gi('idMeninggal').value = id;
        gi('namaMeninggal').innerText = nama;
        gi('popupMeninggal').style.display = 'flex';
    }

    function pindahKabko(id, nama) {
        gi('idPindah').value = id;
        gi('namaPindah').innerText = nama;
        gi('popupPindah').style.display = 'flex';
    }

    function verifKabko(id, nama) {
        gi('idVerifKabko').value = id;
        gi('namaVerifKabko').innerText = nama;
        gi('popupVerifKabko').style.display = 'flex';
    }

    // Drag and drop support
    const dropArea = gi("ktpDropArea");
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, e => {
            e.preventDefault();
            e.stopPropagation();
        });
    });

    dropArea.addEventListener('dragover', () => dropArea.style.borderColor = "var(--primary)");
    dropArea.addEventListener('dragleave', () => dropArea.style.borderColor = "var(--border-color)");
    dropArea.addEventListener('drop', e => {
        dropArea.style.borderColor = "var(--border-color)";
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            gi("ktpInput").files = e.dataTransfer.files;
            gi("ktpInput").dispatchEvent(new Event('change'));
        }
    });
</script>