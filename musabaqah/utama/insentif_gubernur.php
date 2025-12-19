<?php
    /**
     * Pendataan Huffadz Penerima Insentif Gubernur Jawa Timur
     * CRUD admin page for adminprov / adminkabko
     */
    include_once __DIR__ . "/../dbku.php";
    include_once __DIR__ . "/../../global/class/userrole.php";
    
    // Only allow adminprov or adminkabko
    openfor(["adminprov","adminkabko"]);

    $iduser = getiduser();
    $user = getonebaris("select * from user where id=$iduser");
    $user_kab_kota = $user['kab_kota'] ?? '';
    $role_name = getonedata("select nama from user_level where id=".$user['iduser_level']);
    $is_admin_prov = ($role_name == 'admin prov');

    $vtabel = "huffadz_insentif_gubernur";
    $tahun_filter = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Handle form submission
    if(isset($_POST['action'])){
        $action = $_POST['action'];
        
        if($action == 'tambah' || $action == 'edit'){
            $data = [
                'tahun_anggaran' => $_POST['tahun_anggaran'],
                'periode' => $_POST['periode'],
                'nik' => $_POST['nik'],
                'nama_lengkap' => $_POST['nama_lengkap'],
                'tempat_lahir' => $_POST['tempat_lahir'],
                'tanggal_lahir' => $_POST['tanggal_lahir'],
                'jenis_kelamin' => $_POST['jenis_kelamin'],
                'alamat' => $_POST['alamat'],
                'rt' => $_POST['rt'],
                'rw' => $_POST['rw'],
                'desa_kelurahan' => $_POST['desa_kelurahan'],
                'kecamatan' => $_POST['kecamatan'],
                'kab_kota' => $_POST['kab_kota'],
                'no_hp' => $_POST['no_hp'],
                'email' => $_POST['email'],
                'jumlah_hafalan_juz' => $_POST['jumlah_hafalan_juz'],
                'lembaga_tahfidz' => $_POST['lembaga_tahfidz'],
                'nama_pembimbing' => $_POST['nama_pembimbing'],
                'tahun_khatam' => $_POST['tahun_khatam'],
                'no_sertifikat_tahfidz' => $_POST['no_sertifikat_tahfidz'],
                'status_mengajar' => $_POST['status_mengajar'],
                'nama_lembaga_mengajar' => $_POST['nama_lembaga_mengajar'],
                'tmt_mengajar' => $_POST['tmt_mengajar'],
                'nama_bank' => $_POST['nama_bank'],
                'no_rekening' => $_POST['no_rekening'],
                'atas_nama_rekening' => $_POST['atas_nama_rekening']
            ];
            
            $kol = array_keys($data);
            $val = array_values($data);
            
            if($action == 'tambah'){
                $kol[] = 'created_by';
                $val[] = $iduser;
                dbinsert($vtabel, $kol, $val);
                $msg = "Data berhasil ditambahkan!";
            } else {
                $id = $_POST['id'];
                dbupdate($vtabel, $kol, $val, "where id=$id");
                $msg = "Data berhasil diperbarui!";
            }
            echo "<script>alert('$msg'); window.location.href='?page=utama&page2=insentif_gubernur&tahun=$tahun_filter';</script>";
            exit;
        }
        
        if($action == 'hapus'){
            $id = $_POST['id'];
            dbdelete($vtabel, $id);
            echo "<script>alert('Data berhasil dihapus!'); window.location.href='?page=utama&page2=insentif_gubernur&tahun=$tahun_filter';</script>";
            exit;
        }
        
        if($action == 'verifikasi_kabko' && !$is_admin_prov){
            $id = $_POST['id'];
            $status = $_POST['status_verifikasi'];
            $catatan = mysqli_real_escape_string($GLOBALS['koneksi'], $_POST['catatan_verifikasi']);
            execute("UPDATE $vtabel SET status_verifikasi='$status', catatan_verifikasi='$catatan', tanggal_verifikasi_kabko=NOW(), verifikator_kabko=$iduser WHERE id=$id");
            echo "<script>alert('Status verifikasi diperbarui!'); window.location.href='?page=utama&page2=insentif_gubernur&tahun=$tahun_filter';</script>";
            exit;
        }
        
        if($action == 'verifikasi_provinsi' && $is_admin_prov){
            $id = $_POST['id'];
            $status = $_POST['status_verifikasi'];
            $catatan = mysqli_real_escape_string($GLOBALS['koneksi'], $_POST['catatan_verifikasi']);
            execute("UPDATE $vtabel SET status_verifikasi='$status', catatan_verifikasi='$catatan', tanggal_verifikasi_provinsi=NOW(), verifikator_provinsi=$iduser WHERE id=$id");
            echo "<script>alert('Status verifikasi provinsi diperbarui!'); window.location.href='?page=utama&page2=insentif_gubernur&tahun=$tahun_filter';</script>";
            exit;
        }
    }

    // Query data based on role
    if($is_admin_prov){
        $querytabel = "SELECT * FROM $vtabel WHERE tahun_anggaran='$tahun_filter' ORDER BY kab_kota, nama_lengkap";
    } else {
        $querytabel = "SELECT * FROM $vtabel WHERE tahun_anggaran='$tahun_filter' AND kab_kota='$user_kab_kota' ORDER BY nama_lengkap";
    }

    // Get summary stats
    $total = getonedata("SELECT COUNT(*) FROM $vtabel WHERE tahun_anggaran='$tahun_filter'" . ($is_admin_prov ? '' : " AND kab_kota='$user_kab_kota'"));
    $pending = getonedata("SELECT COUNT(*) FROM $vtabel WHERE tahun_anggaran='$tahun_filter' AND status_verifikasi='Pending'" . ($is_admin_prov ? '' : " AND kab_kota='$user_kab_kota'"));
    $diterima = getonedata("SELECT COUNT(*) FROM $vtabel WHERE tahun_anggaran='$tahun_filter' AND status_verifikasi='Diterima'" . ($is_admin_prov ? '' : " AND kab_kota='$user_kab_kota'"));

    // Get list of available years
    $years = getdata("SELECT DISTINCT tahun_anggaran FROM $vtabel ORDER BY tahun_anggaran DESC");
?>

<style>
    .stats-container {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        min-width: 180px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .stat-card.pending { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
    .stat-card.approved { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
    .stat-card h3 { margin: 0; font-size: 32px; }
    .stat-card p { margin: 5px 0 0; opacity: 0.9; }
    
    .filter-bar {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
    }
    
    .table-container {
        overflow-x: auto;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    table.insentif {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }
    table.insentif th {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 12px 8px;
        text-align: left;
        font-weight: 600;
        position: sticky;
        top: 0;
    }
    table.insentif td {
        padding: 10px 8px;
        border-bottom: 1px solid #eee;
    }
    table.insentif tr:hover { background: #f5f5f5; }
    
    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }
    .badge-pending { background: #ffeaa7; color: #d68910; }
    .badge-kabko { background: #81ecec; color: #00796b; }
    .badge-provinsi { background: #a29bfe; color: #5b4cdb; }
    .badge-diterima { background: #55efc4; color: #00a65a; }
    .badge-ditolak { background: #ff7675; color: #c0392b; }
    
    .btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 12px;
        transition: all 0.3s;
    }
    .btn-primary { background: #667eea; color: white; }
    .btn-success { background: #00b894; color: white; }
    .btn-warning { background: #fdcb6e; color: #2d3436; }
    .btn-danger { background: #e74c3c; color: white; }
    .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.2); }
    
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .modal-box {
        background: white;
        padding: 30px;
        border-radius: 15px;
        max-width: 700px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }
    .modal-box h2 {
        margin-top: 0;
        color: #1e3c72;
        border-bottom: 2px solid #667eea;
        padding-bottom: 10px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 5px;
        color: #555;
    }
    .form-group input, .form-group select, .form-group textarea {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
    }
    .form-group.full-width { grid-column: 1 / -1; }
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
</style>

<div class="judul">
    <h1>🏆 Pendataan Huffadz Penerima Insentif Gubernur Jatim</h1>
    <p style="color:#666;">Tahun Anggaran: <?php echo $tahun_filter; ?></p>
</div>

<!-- Stats Cards -->
<div class="stats-container">
    <div class="stat-card">
        <h3><?php echo $total ?: 0; ?></h3>
        <p>Total Huffadz</p>
    </div>
    <div class="stat-card pending">
        <h3><?php echo $pending ?: 0; ?></h3>
        <p>Menunggu Verifikasi</p>
    </div>
    <div class="stat-card approved">
        <h3><?php echo $diterima ?: 0; ?></h3>
        <p>Sudah Diterima</p>
    </div>
</div>

<!-- Filter Bar -->
<div class="filter-bar">
    <label><strong>Filter Tahun:</strong></label>
    <select onchange="window.location.href='?page=utama&page2=insentif_gubernur&tahun='+this.value">
        <?php for($y=date('Y'); $y>=2020; $y--): ?>
            <option value="<?php echo $y; ?>" <?php echo $y==$tahun_filter?'selected':''; ?>><?php echo $y; ?></option>
        <?php endfor; ?>
    </select>
    
    <button class="btn btn-primary" onclick="openModal('add')">+ Tambah Data Huffadz</button>
    <button class="btn btn-success" onclick="window.print()">🖨️ Cetak</button>
</div>

<!-- Data Table -->
<div class="table-container">
    <table class="insentif">
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama Lengkap</th>
                <th>Kab/Kota</th>
                <th>No HP</th>
                <th>Hafalan</th>
                <th>Bank</th>
                <th>No Rekening</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $data = getdata($querytabel);
                $no = 1;
                while($row = mysqli_fetch_array($data)):
                    $badge_class = 'badge-pending';
                    if($row['status_verifikasi'] == 'Diverifikasi Kabko') $badge_class = 'badge-kabko';
                    if($row['status_verifikasi'] == 'Diverifikasi Provinsi') $badge_class = 'badge-provinsi';
                    if($row['status_verifikasi'] == 'Diterima') $badge_class = 'badge-diterima';
                    if($row['status_verifikasi'] == 'Ditolak') $badge_class = 'badge-ditolak';
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nik']; ?></td>
                <td><strong><?php echo $row['nama_lengkap']; ?></strong></td>
                <td><?php echo $row['kab_kota']; ?></td>
                <td><?php echo $row['no_hp']; ?></td>
                <td><?php echo $row['jumlah_hafalan_juz']; ?> Juz</td>
                <td><?php echo $row['nama_bank']; ?></td>
                <td><?php echo $row['no_rekening']; ?></td>
                <td><span class="badge <?php echo $badge_class; ?>"><?php echo $row['status_verifikasi']; ?></span></td>
                <td>
                    <button class="btn btn-primary" onclick='editData(<?php echo json_encode($row); ?>)'>Edit</button>
                    <button class="btn btn-warning" onclick='openVerifikasi(<?php echo $row["id"]; ?>, "<?php echo $row["nama_lengkap"]; ?>")'>Verifikasi</button>
                    <button class="btn btn-danger" onclick='hapusData(<?php echo $row["id"]; ?>)'>Hapus</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal Form Tambah/Edit -->
<div class="modal-overlay" id="modalForm">
    <div class="modal-box">
        <h2 id="modalTitle">Tambah Data Huffadz</h2>
        <form method="POST" id="formData">
            <input type="hidden" name="action" id="formAction" value="tambah">
            <input type="hidden" name="id" id="editId">
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Tahun Anggaran *</label>
                    <select name="tahun_anggaran" required>
                        <?php for($y=date('Y'); $y>=2020; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo $y==$tahun_filter?'selected':''; ?>><?php echo $y; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Periode</label>
                    <select name="periode">
                        <option value="Semester 1">Semester 1</option>
                        <option value="Semester 2">Semester 2</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>NIK *</label>
                    <input type="text" name="nik" id="nik" maxlength="16" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" required>
                </div>
                
                <div class="form-group">
                    <label>Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir">
                </div>
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir">
                </div>
                
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" id="no_hp">
                </div>
                
                <div class="form-group full-width">
                    <label>Alamat</label>
                    <textarea name="alamat" id="alamat" rows="2"></textarea>
                </div>
                
                <div class="form-group">
                    <label>RT</label>
                    <input type="text" name="rt" id="rt" maxlength="5">
                </div>
                <div class="form-group">
                    <label>RW</label>
                    <input type="text" name="rw" id="rw" maxlength="5">
                </div>
                
                <div class="form-group">
                    <label>Desa/Kelurahan</label>
                    <input type="text" name="desa_kelurahan" id="desa_kelurahan">
                </div>
                <div class="form-group">
                    <label>Kecamatan</label>
                    <input type="text" name="kecamatan" id="kecamatan">
                </div>
                
                <div class="form-group">
                    <label>Kabupaten/Kota *</label>
                    <input type="text" name="kab_kota" id="kab_kota" required value="<?php echo $is_admin_prov ? '' : $user_kab_kota; ?>" <?php echo $is_admin_prov ? '' : 'readonly'; ?>>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email">
                </div>
                
                <!-- Data Hafalan -->
                <div class="form-group full-width"><h4 style="margin:10px 0;color:#667eea;">📖 Data Hafalan</h4></div>
                
                <div class="form-group">
                    <label>Jumlah Hafalan (Juz)</label>
                    <input type="number" name="jumlah_hafalan_juz" id="jumlah_hafalan_juz" min="1" max="30" value="30">
                </div>
                <div class="form-group">
                    <label>Tahun Khatam</label>
                    <input type="number" name="tahun_khatam" id="tahun_khatam" min="1990" max="<?php echo date('Y'); ?>">
                </div>
                
                <div class="form-group">
                    <label>Lembaga Tahfidz</label>
                    <input type="text" name="lembaga_tahfidz" id="lembaga_tahfidz">
                </div>
                <div class="form-group">
                    <label>Nama Pembimbing</label>
                    <input type="text" name="nama_pembimbing" id="nama_pembimbing">
                </div>
                
                <div class="form-group">
                    <label>No Sertifikat Tahfidz</label>
                    <input type="text" name="no_sertifikat_tahfidz" id="no_sertifikat_tahfidz">
                </div>
                <div class="form-group">
                    <label>Status Mengajar</label>
                    <select name="status_mengajar" id="status_mengajar">
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Nama Lembaga Mengajar</label>
                    <input type="text" name="nama_lembaga_mengajar" id="nama_lembaga_mengajar">
                </div>
                <div class="form-group">
                    <label>TMT Mengajar</label>
                    <input type="date" name="tmt_mengajar" id="tmt_mengajar">
                </div>
                
                <!-- Data Bank -->
                <div class="form-group full-width"><h4 style="margin:10px 0;color:#667eea;">🏦 Data Rekening Bank</h4></div>
                
                <div class="form-group">
                    <label>Nama Bank</label>
                    <select name="nama_bank" id="nama_bank">
                        <option value="">-- Pilih Bank --</option>
                        <option value="Bank Jatim">Bank Jatim</option>
                        <option value="BRI">BRI</option>
                        <option value="BNI">BNI</option>
                        <option value="Mandiri">Mandiri</option>
                        <option value="BCA">BCA</option>
                        <option value="BSI">BSI</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>No Rekening</label>
                    <input type="text" name="no_rekening" id="no_rekening">
                </div>
                
                <div class="form-group">
                    <label>Atas Nama Rekening</label>
                    <input type="text" name="atas_nama_rekening" id="atas_nama_rekening">
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-warning" onclick="closeModal()">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi -->
<div class="modal-overlay" id="modalVerifikasi">
    <div class="modal-box" style="max-width:500px;">
        <h2>Verifikasi Data Huffadz</h2>
        <p id="verifikasiNama"></p>
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $is_admin_prov ? 'verifikasi_provinsi' : 'verifikasi_kabko'; ?>">
            <input type="hidden" name="id" id="verifikasiId">
            
            <div class="form-group">
                <label>Status Verifikasi</label>
                <select name="status_verifikasi" required>
                    <?php if(!$is_admin_prov): ?>
                        <option value="Diverifikasi Kabko">✓ Diverifikasi Kab/Ko</option>
                        <option value="Ditolak">✗ Ditolak</option>
                    <?php else: ?>
                        <option value="Diverifikasi Provinsi">✓ Diverifikasi Provinsi</option>
                        <option value="Diterima">✓✓ Diterima (Final)</option>
                        <option value="Ditolak">✗ Ditolak</option>
                    <?php endif; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label>Catatan Verifikasi</label>
                <textarea name="catatan_verifikasi" rows="3" placeholder="Catatan opsional..."></textarea>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-warning" onclick="document.getElementById('modalVerifikasi').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-success">Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Form Hapus -->
<form id="formHapus" method="POST" style="display:none;">
    <input type="hidden" name="action" value="hapus">
    <input type="hidden" name="id" id="hapusId">
</form>

<script>
function openModal(mode) {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('formAction').value = 'tambah';
    document.getElementById('modalTitle').innerText = 'Tambah Data Huffadz';
    document.getElementById('formData').reset();
}

function closeModal() {
    document.getElementById('modalForm').style.display = 'none';
}

function editData(row) {
    document.getElementById('modalForm').style.display = 'flex';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('editId').value = row.id;
    document.getElementById('modalTitle').innerText = 'Edit Data Huffadz: ' + row.nama_lengkap;
    
    // Fill form fields
    const fields = ['nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'rt', 'rw', 'desa_kelurahan', 'kecamatan', 'kab_kota', 'no_hp', 'email', 'jumlah_hafalan_juz', 'tahun_khatam', 'lembaga_tahfidz', 'nama_pembimbing', 'no_sertifikat_tahfidz', 'status_mengajar', 'nama_lembaga_mengajar', 'tmt_mengajar', 'nama_bank', 'no_rekening', 'atas_nama_rekening'];
    fields.forEach(f => {
        const el = document.getElementById(f);
        if(el && row[f] !== null) el.value = row[f];
    });
}

function openVerifikasi(id, nama) {
    document.getElementById('modalVerifikasi').style.display = 'flex';
    document.getElementById('verifikasiId').value = id;
    document.getElementById('verifikasiNama').innerText = 'Nama: ' + nama;
}

function hapusData(id) {
    if(confirm('Yakin ingin menghapus data ini?')) {
        document.getElementById('hapusId').value = id;
        document.getElementById('formHapus').submit();
    }
}

// Close modal on outside click
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if(e.target === this) this.style.display = 'none';
    });
});
</script>
