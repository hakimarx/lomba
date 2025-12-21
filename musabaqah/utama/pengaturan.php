<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
include_once __DIR__ . "/../../global/class/fungsi_config.php";

// Only admin prov can access settings
openfor(["adminprov", "role1"]);

// Get current user info
$iduser = getiduser();
$currentUser = getonebaris("SELECT * FROM view_user WHERE iduser=$iduser");

// Handle CRUD operations
if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'] ?? '';
    $userType = $_POST['user_type'] ?? 'adminprov';

    if ($mode == "hapus" && $crudid) {
        // Prevent self-deletion
        if ($crudid != $iduser) {
            dbdelete("user", $crudid);
        }
    }

    if ($mode == "tambah" || $mode == "edit") {
        $username = $_POST['username'] ?? '';
        $nama = $_POST['nama'] ?? '';
        $password = $_POST['password'] ?? '';
        $kab_kota = $_POST['kab_kota'] ?? '';

        // Determine user level based on type
        $levelMap = [
            'adminprov' => 100,
            'adminkabko' => 101,
            'panitera' => 103,
            'hafiz' => 104
        ];
        $iduser_level = $levelMap[$userType] ?? 100;

        if ($mode == "tambah") {
            $columns = ["username", "nama", "password", "iduser_level", "kab_kota", "nik"];
            $values = [$username, $nama, $password, $iduser_level, $kab_kota, $_POST['nik'] ?? ''];
            dbinsert("user", $columns, $values);
        } else {
            $columns = ["username", "nama", "password", "iduser_level", "kab_kota", "nik"];
            $values = [$username, $nama, $password, $iduser_level, $kab_kota, $_POST['nik'] ?? ''];
            dbupdate("user", $columns, $values, "WHERE id=$crudid");
        }
    }
}

// Handle system config updates
if (isset($_POST['save_config'])) {
    $mushaf_source = $_POST['mushaf_source'] ?? 'offline';
    setConfig('mushaf_source', $mushaf_source);
    $config_saved = true;
}

// XML Import: Manifest Lomba
if (isset($_POST['import_manifest']) && isset($_FILES['xml_manifest'])) {
    $xmlFile = $_FILES['xml_manifest']['tmp_name'];
    if (file_exists($xmlFile)) {
        $xml = @simplexml_load_file($xmlFile);
        if ($xml) {
            $idevent = $_GET['idevent'] ?? 1;
            $import_count = 0;

            foreach ($xml->Datas->Cabang as $cabXml) {
                $cabName = strtoupper((string)$cabXml['name']);
                $cabNameE = dbescape($cabName);
                $cabType = (string)$cabXml['type'];
                $cabTypeE = dbescape($cabType);

                $idpenilaian = getonedata("SELECT id FROM penilaian WHERE nama LIKE '%$cabTypeE%' OR id='$cabTypeE'") ?: 1;

                $existingCab = getonebaris("SELECT id FROM cabang WHERE nama='$cabNameE' AND idevent=$idevent");
                if ($existingCab) {
                    $idcabang = $existingCab['id'];
                    execute("UPDATE cabang SET idpenilaian='$idpenilaian' WHERE id=$idcabang");
                } else {
                    execute("INSERT INTO cabang (nama, idevent, idpenilaian) VALUES ('$cabNameE', $idevent, '$idpenilaian')");
                    $idcabang = mysqli_insert_id($koneksi);
                }

                foreach ($cabXml->Golongan as $golXml) {
                    $golName = strtoupper((string)$golXml['name']);
                    $golNameE = dbescape($golName);
                    $time_min = (int)$golXml['time'];
                    $warn_sec = (int)$golXml['warn'];
                    $topik = (int)$golXml['topik'];
                    $juzawal = (int)$golXml['juzawal'] ?: 0;
                    $juzakhir = (int)$golXml['juzakhir'] ?: 0;
                    $jmlsoal = (int)$golXml['jmlsoal'] ?: 0;

                    $existingGol = getonebaris("SELECT id FROM golongan WHERE nama='$golNameE' AND idcabang=$idcabang");
                    if ($existingGol) {
                        $idgolongan = $existingGol['id'];
                        execute("UPDATE golongan SET waktupenilaian=$time_min, waktumenjelang=$warn_sec, istopik=$topik, juzawal=$juzawal, juzakhir=$juzakhir, jmlsoal=$jmlsoal WHERE id=$idgolongan");
                    } else {
                        execute("INSERT INTO golongan (nama, idcabang, waktupenilaian, waktumenjelang, istopik, jumlah_hakim, juzawal, juzakhir, jmlsoal) VALUES ('$golNameE', $idcabang, $time_min, $warn_sec, $topik, 5, $juzawal, $juzakhir, $jmlsoal)");
                        $idgolongan = mysqli_insert_id($koneksi);
                    }

                    foreach ($golXml->Bidang as $bidXml) {
                        $bidName = strtoupper((string)$bidXml['name']);
                        $bidNameE = dbescape($bidName);
                        $min = (int)$bidXml['min'];
                        $max = (int)$bidXml['max'];
                        $urut = (int)$bidXml['urut'];

                        $existingBid = getonebaris("SELECT id FROM bidang WHERE nama='$bidNameE' AND idgolongan=$idgolongan");
                        if ($existingBid) {
                            execute("UPDATE bidang SET nmin=$min, nmax=$max, urut=$urut WHERE id=" . $existingBid['id']);
                        } else {
                            execute("INSERT INTO bidang (nama, idgolongan, nmin, nmax, urut) VALUES ('$bidNameE', $idgolongan, $min, $max, $urut)");
                        }
                    }
                }
                $import_count++;
            }
            $import_success = "MANIFEST LOMBA BERHASIL DI-IMPORT ($import_count CABANG)!";
        } else {
            $import_error = "FORMAT XML TIDAK VALID.";
        }
    }
}

// XML Import: Event Config
if (isset($_POST['import_event']) && isset($_FILES['xml_event'])) {
    $xmlFile = $_FILES['xml_event']['tmp_name'];
    if (file_exists($xmlFile)) {
        $xml = @simplexml_load_file($xmlFile);
        if ($xml) {
            foreach ($xml->Section as $section) {
                $sectionId = strtoupper((string)$section['id']);

                if ($sectionId == 'EVENT') {
                    // Search children case-insensitively
                    foreach ($section->children() as $key) {
                        if (strtoupper($key->getName()) == 'KEY') {
                            $kId = (string)$key['id'];
                            $kval = (string)$key['value'];
                            setConfig("event_" . strtolower($kId), $kval);
                        }
                    }
                }

                if ($sectionId == 'KAFILAH') {
                    foreach ($section->children() as $key) {
                        if (strtoupper($key->getName()) == 'KEY') {
                            $kafilahName = strtoupper((string)$key['value']);
                            $kafilahNameE = dbescape($kafilahName);
                            $existing = getonedata("SELECT id FROM wilayah WHERE nama='$kafilahNameE'");
                            if (!$existing) {
                                execute("INSERT INTO wilayah (nama) VALUES ('$kafilahNameE')");
                            }
                        }
                    }
                }

                if ($sectionId == 'JOB') {
                    foreach ($section->children() as $key) {
                        if (strtoupper($key->getName()) == 'KEY') {
                            $jobName = strtoupper((string)$key['id']);
                            $jobNameE = dbescape($jobName);
                            $existing = getonedata("SELECT id FROM job WHERE nama='$jobNameE'");
                            if (!$existing) {
                                execute("INSERT INTO job (nama) VALUES ('$jobNameE')");
                            }
                        }
                    }
                }
            }
            $import_success = "KONFIGURASI EVENT BERHASIL DI-IMPORT!";
        } else {
            $import_error = "FORMAT XML TIDAK VALID.";
        }
    }
}

// Get current config values
$currentMushafSource = getConfig('mushaf_source', 'offline');

// Get user lists by level
function getUsersByLevel($levelId)
{
    $query = "SELECT u.*, ul.nama as level_nama, ul.role 
              FROM user u 
              LEFT JOIN user_level ul ON u.iduser_level = ul.id 
              WHERE u.iduser_level = $levelId 
              ORDER BY u.nama";
    return getdata($query);
}

$adminProvUsers = getUsersByLevel(100);
$adminKabkoUsers = getUsersByLevel(101);
$paniteraUsers = getUsersByLevel(103);
$hafizUsers = getUsersByLevel(104);

// Get kabupaten list for dropdown
$kabupatenList = getdata("SELECT DISTINCT kab_kota FROM hafidz WHERE kab_kota IS NOT NULL AND kab_kota != '' ORDER BY kab_kota");
?>

<style>
    .settings-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .tabs {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .tab-btn {
        padding: 12px 24px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-secondary);
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }

    .tab-btn:hover {
        background: var(--bg-card-hover);
        color: var(--text-primary);
    }

    .tab-btn.active {
        background: var(--gradient-primary);
        color: white;
        border-color: var(--primary);
    }

    .tab-content {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .tab-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-add {
        padding: 10px 20px;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--bg-card);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    .user-table th {
        background: var(--bg-surface);
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .user-table td {
        padding: 14px 16px;
        border-top: 1px solid var(--border-color);
        color: var(--text-primary);
    }

    .user-table tr:hover td {
        background: var(--bg-card-hover);
    }

    .action-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.8rem;
        transition: all 0.2s ease;
        margin-right: 4px;
    }

    .btn-edit {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    .btn-edit:hover {
        background: rgba(59, 130, 246, 0.3);
    }

    .btn-delete {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
    }

    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.3);
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(4px);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }

    .modal-box {
        background: var(--bg-card);
        border-radius: 16px;
        padding: 24px;
        width: 90%;
        max-width: 500px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        border: 1px solid var(--border-color);
    }

    .modal-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin-bottom: 6px;
        font-weight: 500;
    }

    .form-input {
        width: 100%;
        padding: 12px 14px;
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }

    .form-select {
        width: 100%;
        padding: 12px 14px;
        background: var(--bg-surface);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-primary);
        font-size: 0.95rem;
        cursor: pointer;
    }

    .modal-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 24px;
    }

    .btn-cancel {
        padding: 10px 20px;
        background: var(--bg-surface);
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .btn-save {
        padding: 10px 24px;
        background: var(--gradient-primary);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.9rem;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-prov {
        background: rgba(139, 92, 246, 0.2);
        color: #a78bfa;
    }

    .badge-kabko {
        background: rgba(59, 130, 246, 0.2);
        color: #60a5fa;
    }

    .badge-panitera {
        background: rgba(245, 158, 11, 0.2);
        color: #fbbf24;
    }

    .badge-hafiz {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--text-muted);
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 10px;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-light);
    }

    .stat-label {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin-top: 4px;
    }
</style>

<div class="settings-container">
    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($adminProvUsers); ?></div>
            <div class="stat-label">Admin Provinsi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($adminKabkoUsers); ?></div>
            <div class="stat-label">Admin Kab/Ko</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($paniteraUsers); ?></div>
            <div class="stat-label">Panitera</div>
        </div>
        <div class="stat-card">
            <div class="stat-number"><?php echo mysqli_num_rows($hafizUsers); ?></div>
            <div class="stat-label">Hafiz</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <button class="tab-btn active" onclick="showTab('adminprov')">👑 ADMIN PROVINSI</button>
        <button class="tab-btn" onclick="showTab('adminkabko')">🏛️ ADMIN KAB/KO</button>
        <button class="tab-btn" onclick="showTab('panitera')">⚖️ PANITERA</button>
        <button class="tab-btn" onclick="showTab('hafiz')">📖 HAFIZ</button>
        <button class="tab-btn" onclick="showTab('import_manifest')">📋 IMPORT MANIFEST</button>
        <button class="tab-btn" onclick="showTab('import_event')">📅 IMPORT EVENT</button>
        <button class="tab-btn" onclick="showTab('sistem')">⚙️ SISTEM</button>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($import_success)): ?>
        <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--primary); border-radius: 8px; padding: 16px; margin-bottom: 20px; color: var(--primary-light); display: flex; align-items: center; gap: 10px;">
            <span>✅</span> <?php echo $import_success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($import_error)): ?>
        <div style="background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; border-radius: 8px; padding: 16px; margin-bottom: 20px; color: #f87171; display: flex; align-items: center; gap: 10px;">
            <span>❌</span> <?php echo $import_error; ?>
        </div>
    <?php endif; ?>

    <!-- Tab Content: Admin Prov -->
    <div id="tab-adminprov" class="tab-content active">
        <div class="section-header">
            <h2 class="section-title">👑 DAFTAR ADMIN PROVINSI</h2>
            <button class="btn-add" onclick="tambahUser('adminprov')">➕ TAMBAH ADMIN PROV</button>
        </div>

        <?php if (mysqli_num_rows($adminProvUsers) > 0): ?>
            <?php mysqli_data_seek($adminProvUsers, 0); ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($adminProvUsers)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><span class="badge badge-prov">Admin Prov</span></td>
                            <td>
                                <button class="action-btn btn-edit"
                                    onclick="editUser(<?php echo $row['id']; ?>, 'adminprov', '<?php echo addslashes($row['username']); ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['password']); ?>', '<?php echo addslashes($row['kab_kota'] ?? ''); ?>')">
                                    ✏️ Edit
                                </button>
                                <?php if ($row['id'] != $iduser): ?>
                                    <button class="action-btn btn-delete" onclick="hapusUser(<?php echo $row['id']; ?>)">
                                        🗑️ Hapus
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">👑</div>
                <p>Belum ada Admin Provinsi</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Admin Kab/Ko -->
    <div id="tab-adminkabko" class="tab-content">
        <div class="section-header">
            <h2 class="section-title">🏛️ DAFTAR ADMIN KAB/KO</h2>
            <button class="btn-add" onclick="tambahUser('adminkabko')">➕ TAMBAH ADMIN KAB/KO</button>
        </div>

        <?php if (mysqli_num_rows($adminKabkoUsers) > 0): ?>
            <?php mysqli_data_seek($adminKabkoUsers, 0); ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Kab/Kota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($adminKabkoUsers)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><span class="badge badge-kabko"><?php echo htmlspecialchars($row['kab_kota'] ?? '-'); ?></span></td>
                            <td>
                                <button class="action-btn btn-edit"
                                    onclick="editUser(<?php echo $row['id']; ?>, 'adminkabko', '<?php echo addslashes($row['username']); ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['password']); ?>', '<?php echo addslashes($row['kab_kota'] ?? ''); ?>')">
                                    ✏️ Edit
                                </button>
                                <button class="action-btn btn-delete" onclick="hapusUser(<?php echo $row['id']; ?>)">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">🏛️</div>
                <p>Belum ada Admin Kab/Ko</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Panitera -->
    <div id="tab-panitera" class="tab-content">
        <div class="section-header">
            <h2 class="section-title">⚖️ DAFTAR PANITERA</h2>
            <button class="btn-add" onclick="tambahUser('panitera')">➕ TAMBAH PANITERA</button>
        </div>

        <?php if (mysqli_num_rows($paniteraUsers) > 0): ?>
            <?php mysqli_data_seek($paniteraUsers, 0); ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($paniteraUsers)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><span class="badge badge-panitera">Panitera</span></td>
                            <td>
                                <button class="action-btn btn-edit"
                                    onclick="editUser(<?php echo $row['id']; ?>, 'panitera', '<?php echo addslashes($row['username']); ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['password']); ?>', '<?php echo addslashes($row['kab_kota'] ?? ''); ?>')">
                                    ✏️ Edit
                                </button>
                                <button class="action-btn btn-delete" onclick="hapusUser(<?php echo $row['id']; ?>)">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">⚖️</div>
                <p>Belum ada Panitera</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Hafiz -->
    <div id="tab-hafiz" class="tab-content">
        <div class="section-header">
            <h2 class="section-title">📖 DAFTAR HAFIZ</h2>
            <button class="btn-add" onclick="tambahUser('hafiz')">➕ TAMBAH HAFIZ</button>
        </div>

        <?php if (mysqli_num_rows($hafizUsers) > 0): ?>
            <?php mysqli_data_seek($hafizUsers, 0); ?>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Kab/Kota</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_array($hafizUsers)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['nik'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($row['kab_kota'] ?? '-'); ?></td>
                            <td>
                                <button class="action-btn btn-edit"
                                    onclick="editUser(<?php echo $row['id']; ?>, 'hafiz', '<?php echo addslashes($row['username']); ?>', '<?php echo addslashes($row['nama']); ?>', '<?php echo addslashes($row['password']); ?>', '<?php echo addslashes($row['kab_kota'] ?? ''); ?>', '<?php echo addslashes($row['nik'] ?? ''); ?>')">
                                    ✏️ Edit
                                </button>
                                <button class="action-btn btn-delete" onclick="hapusUser(<?php echo $row['id']; ?>)">
                                    🗑️ Hapus
                                </button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <div class="empty-state-icon">📖</div>
                <p>Belum ada Hafiz</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Tab Content: Sistem -->
    <div id="tab-sistem" class="tab-content">
        <div class="section-header">
            <h2 class="section-title">⚙️ Pengaturan Sistem</h2>
        </div>

        <?php if (isset($config_saved)): ?>
            <div style="background: rgba(16, 185, 129, 0.2); border: 1px solid var(--primary); border-radius: 8px; padding: 12px; margin-bottom: 20px; color: var(--primary-light);">
                ✅ Pengaturan berhasil disimpan!
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 20px;">
                <h3 style="color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    📖 Sumber Gambar Mushaf
                </h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 20px;">
                    Pilih sumber gambar untuk menampilkan mushaf Al-Quran. Mode <strong>Online</strong> menghemat storage server dengan mengambil gambar dari API eksternal.
                </p>

                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                    <label style="flex: 1; min-width: 200px; cursor: pointer;">
                        <input type="radio" name="mushaf_source" value="offline"
                            <?php echo $currentMushafSource === 'offline' ? 'checked' : ''; ?>
                            style="display: none;">
                        <div class="source-option <?php echo $currentMushafSource === 'offline' ? 'active' : ''; ?>" onclick="selectSource(this, 'offline')">
                            <div style="font-size: 2rem; margin-bottom: 8px;">💾</div>
                            <div style="font-weight: 600; color: var(--text-primary);">Offline (Lokal)</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 4px;">
                                Gambar dari folder assets/mushaf/<br>
                                ~530 MB storage
                            </div>
                        </div>
                    </label>

                    <label style="flex: 1; min-width: 200px; cursor: pointer;">
                        <input type="radio" name="mushaf_source" value="online"
                            <?php echo $currentMushafSource === 'online' ? 'checked' : ''; ?>
                            style="display: none;">
                        <div class="source-option <?php echo $currentMushafSource === 'online' ? 'active' : ''; ?>" onclick="selectSource(this, 'online')">
                            <div style="font-size: 2rem; margin-bottom: 8px;">🌐</div>
                            <div style="font-weight: 600; color: var(--text-primary);">Online (API)</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 4px;">
                                Gambar dari everyayah.com<br>
                                0 MB storage, butuh internet
                            </div>
                        </div>
                    </label>
                </div>

                <div style="margin-top: 20px; text-align: right;">
                    <button type="submit" name="save_config" value="1" class="btn-save">💾 Simpan Pengaturan</button>
                </div>
            </div>
        </form>

        <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
            <h3 style="color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                ℹ️ Informasi
            </h3>
            <ul style="color: var(--text-secondary); font-size: 0.9rem; line-height: 1.8; padding-left: 20px;">
                <li><strong>Mode Offline:</strong> Semua gambar di-load dari server lokal. Lebih cepat, tapi butuh upload ~530MB ke server.</li>
                <li><strong>Mode Online:</strong> Gambar di-load dari API everyayah.com. Hemat storage server, tapi butuh koneksi internet stabil.</li>
                <li>Perubahan berlaku langsung setelah disimpan.</li>
            </ul>
        </div>
    </div>
</div>

<!-- Tab Content: Import Manifest -->
<div id="tab-import_manifest" class="tab-content">
    <div class="section-header">
        <h2 class="section-title">📋 IMPORT MANIFEST LOMBA</h2>
    </div>
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
        <p style="color: var(--text-secondary); margin-bottom: 20px;">
            Unggah file XML manifest untuk mengatur Cabang, Golongan, dan Bidang Penilaian secara otomatis.
        </p>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">File XML Manifest</label>
                <input type="file" name="xml_manifest" class="form-input" accept=".xml" required>
            </div>
            <div style="text-align: right;">
                <button type="submit" name="import_manifest" class="btn-save">🚀 Jalankan Import</button>
            </div>
        </form>
    </div>
</div>

<!-- Tab Content: Import Event -->
<div id="tab-import_event" class="tab-content">
    <div class="section-header">
        <h2 class="section-title">📅 IMPORT KONFIGURASI EVENT</h2>
    </div>
    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
        <p style="color: var(--text-secondary); margin-bottom: 20px;">
            Unggah file XML konfigurasi untuk mengatur data Event, Daftar Kafilah (Wilayah), dan Role Staff.
        </p>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">File XML Konfigurasi</label>
                <input type="file" name="xml_event" class="form-input" accept=".xml" required>
            </div>
            <div style="text-align: right;">
                <button type="submit" name="import_event" class="btn-save">🚀 Jalankan Import</button>
            </div>
        </form>
    </div>
</div>
</div>

<style>
    .source-option {
        background: var(--bg-surface);
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .source-option:hover {
        border-color: var(--primary);
        background: var(--bg-card-hover);
    }

    .source-option.active {
        border-color: var(--primary);
        background: rgba(16, 185, 129, 0.1);
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
</style>

<!-- Modal Form -->
<div class="modal-overlay" id="userModal">
    <div class="modal-box">
        <h3 class="modal-title" id="modalTitle">➕ Tambah User</h3>
        <form method="post" action="">
            <input type="hidden" name="crud" id="crud" value="tambah">
            <input type="hidden" name="crudid" id="crudid" value="">
            <input type="hidden" name="user_type" id="user_type" value="adminprov">

            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" id="input_username" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" id="input_nama" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="text" name="password" id="input_password" class="form-input" required>
            </div>

            <div class="form-group" id="nik_group" style="display: none;">
                <label class="form-label">NIK (16 Digit)</label>
                <input type="text" name="nik" id="input_nik" class="form-input" maxlength="16">
            </div>

            <div class="form-group" id="kabkota_group" style="display: none;">
                <label class="form-label">Kabupaten/Kota</label>
                <select name="kab_kota" id="input_kab_kota" class="form-select">
                    <option value="">-- Pilih Kab/Kota --</option>
                    <?php
                    mysqli_data_seek($kabupatenList, 0);
                    while ($row = mysqli_fetch_array($kabupatenList)):
                    ?>
                        <option value="<?php echo htmlspecialchars($row['kab_kota']); ?>">
                            <?php echo htmlspecialchars($row['kab_kota']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn-save">💾 Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Hidden form for delete -->
<form id="deleteForm" method="post" style="display: none;">
    <input type="hidden" name="crud" value="hapus">
    <input type="hidden" name="crudid" id="delete_id">
    <input type="hidden" name="user_type" value="">
</form>

<script>
    function showTab(tabName) {
        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));

        // Show selected tab
        const tabEl = document.getElementById('tab-' + tabName);
        if (tabEl) tabEl.classList.add('active');
        event.target.classList.add('active');
    }

    function tambahUser(userType) {
        document.getElementById('crud').value = 'tambah';
        document.getElementById('crudid').value = '';
        document.getElementById('user_type').value = userType;
        document.getElementById('input_username').value = '';
        document.getElementById('input_nama').value = '';
        document.getElementById('input_password').value = '';
        document.getElementById('input_kab_kota').value = '';
        document.getElementById('input_nik').value = '';

        const labels = {
            'adminprov': '👑 Tambah Admin Provinsi',
            'adminkabko': '🏛️ Tambah Admin Kab/Ko',
            'panitera': '⚖️ Tambah Panitera',
            'hafiz': '📖 Tambah Hafiz'
        };
        document.getElementById('modalTitle').innerText = labels[userType] || 'Tambah User';

        // Visibility logic
        document.getElementById('kabkota_group').style.display = (userType === 'adminkabko' || userType === 'hafiz') ? 'block' : 'none';
        document.getElementById('nik_group').style.display = (userType === 'hafiz') ? 'block' : 'none';

        document.getElementById('userModal').style.display = 'flex';
    }

    function editUser(id, type, username, nama, password, kab_kota, nik = '') {
        document.getElementById('crud').value = 'edit';
        document.getElementById('crudid').value = id;
        document.getElementById('user_type').value = type;
        document.getElementById('input_username').value = username;
        document.getElementById('input_nama').value = nama;
        document.getElementById('input_password').value = password;
        document.getElementById('input_kab_kota').value = kab_kota || '';
        document.getElementById('input_nik').value = nik || '';

        const labels = {
            'adminprov': '✏️ Edit Admin Provinsi',
            'adminkabko': '✏️ Edit Admin Kab/Ko',
            'panitera': '✏️ Edit Panitera',
            'hafiz': '✏️ Edit Hafiz'
        };
        document.getElementById('modalTitle').innerText = labels[type] || 'Edit User';

        // Visibility logic
        document.getElementById('kabkota_group').style.display = (type === 'adminkabko' || type === 'hafiz') ? 'block' : 'none';
        document.getElementById('nik_group').style.display = (type === 'hafiz') ? 'block' : 'none';

        document.getElementById('userModal').style.display = 'flex';
    }

    function hapusUser(id) {
        if (confirm('Yakin ingin menghapus user ini?')) {
            document.getElementById('delete_id').value = id;
            document.getElementById('deleteForm').submit();
        }
    }

    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    // Close modal when clicking outside
    document.getElementById('userModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Select source option for mushaf settings
    function selectSource(element, value) {
        // Remove active class from all options
        document.querySelectorAll('.source-option').forEach(el => el.classList.remove('active'));
        // Add active class to selected option
        element.classList.add('active');
        // Check the corresponding radio button
        document.querySelector('input[name="mushaf_source"][value="' + value + '"]').checked = true;
    }
</script>