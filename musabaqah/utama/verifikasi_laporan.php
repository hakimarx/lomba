<?php
openfor(['adminprov', 'adminkabko', 'role1']);
$iduser_logged = getiduser();
$role_logged = getonedata("SELECT role FROM view_user WHERE iduser=$iduser_logged");
$kab_kota_logged = getonedata("SELECT kab_kota FROM user WHERE id=$iduser_logged");

// Handle Verification
if (isset($_POST['verif_action'])) {
    $id_laporan = $_POST['id_laporan'];
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];
    $now = date('Y-m-d H:i:s');

    execute("UPDATE laporan_harian SET 
        status_verifikasi='$status', 
        catatan_verifikator='$catatan', 
        tgl_verifikasi='$now', 
        verifikator_id=$iduser_logged 
        WHERE id=$id_laporan");

    echo "<script>alert('Berhasil diverifikasi!'); window.location.href='?page=utama&page2=verifikasi_laporan';</script>";
}

// Build Query
if ($role_logged == 'adminprov' || $role_logged == 'role1') {
    $where = "1=1";
} else {
    // Admin Kab/Ko only sees their region
    $where = "u.kab_kota='$kab_kota_logged'";
}

$query = "SELECT l.*, u.nama, u.username, u.kab_kota, u.nik 
          FROM laporan_harian l 
          JOIN user u ON l.iduser = u.id 
          WHERE $where AND l.status_verifikasi='Pending' 
          ORDER BY l.tanggal ASC";
$data_pending = getdata($query);
?>

<div class="modern-card">
    <div class="section-header">
        <h2 class="section-title">⚖️ Verifikasi Laporan Harian</h2>
        <span class="badge badge-prov"><?php echo mysqli_num_rows($data_pending); ?> Menunggu Verifikasi</span>
    </div>

    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Hafiz</th>
                    <th>Wilayah</th>
                    <th>Tanggal</th>
                    <th>Kegiatan / Detail</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($data_pending) > 0): ?>
                    <?php while ($row = mysqli_fetch_array($data_pending)): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($row['nama']); ?></strong><br>
                                <span style="font-size: 0.75rem; color: var(--text-muted);">NIK: <?php echo htmlspecialchars($row['nik']); ?></span>
                            </td>
                            <td><?php echo htmlspecialchars($row['kab_kota']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td>
                                <div style="font-weight: 600; color: var(--primary-light);"><?php echo htmlspecialchars($row['kegiatan']); ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo htmlspecialchars($row['detail']); ?></div>
                            </td>
                            <td>
                                <button class="action-btn btn-edit" onclick="openVerifModal(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nama']); ?>')">✅ Verifikasi</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 60px; color: var(--text-muted);">
                            Tidak ada laporan yang menunggu verifikasi. 👍
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Verification Modal -->
<div class="modal-overlay" id="verifModal">
    <div class="modal-box">
        <h3 class="modal-title">✅ Verifikasi Laporan</h3>
        <p style="color: var(--text-secondary); margin-bottom: 20px;">Laporan dari: <strong id="hafizName" style="color: var(--text-primary);"></strong></p>

        <form method="post" action="">
            <input type="hidden" name="id_laporan" id="id_laporan">
            <input type="hidden" name="verif_action" value="1">

            <div class="form-group">
                <label class="form-label">Keputusan</label>
                <select name="status" class="form-select" required>
                    <option value="Approved">Setujui Laporan</option>
                    <option value="Rejected">Tolak Laporan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Catatan (Opsional)</label>
                <textarea name="catatan" class="form-input" style="height: 80px;" placeholder="Alasan jika ditolak atau catatan tambahan..."></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeVerifModal()">Batal</button>
                <button type="submit" class="btn-save">💾 Simpan Verifikasi</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifModal(id, name) {
        document.getElementById('id_laporan').value = id;
        document.getElementById('hafizName').innerText = name;
        document.getElementById('verifModal').style.display = 'flex';
    }

    function closeVerifModal() {
        document.getElementById('verifModal').style.display = 'none';
    }
</script>