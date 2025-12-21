<?php
openfor(['hafiz']);
$iduser = getiduser();
$rowuser = getonebaris("SELECT * FROM user WHERE id=$iduser");
$nik = $rowuser['nik'];

// Handle Form Submission
if (isset($_POST['simpan_laporan'])) {
    $tanggal = $_POST['tanggal'];
    $kegiatan = $_POST['kegiatan'];
    $detail = $_POST['detail'];

    $cols = ["iduser", "tanggal", "kegiatan", "detail", "status_verifikasi"];
    $vals = [$iduser, $tanggal, $kegiatan, $detail, 'Pending'];
    dbinsert("laporan_harian", $cols, $vals);
    echo "<script>alert('Laporan berhasil dikirim!'); window.location.href='?page=utama&page2=hafiz_laporan';</script>";
}

// Get Reports
$laporan = getdata("SELECT * FROM laporan_harian WHERE iduser=$iduser ORDER BY tanggal DESC");
?>

<div class="modern-card">
    <div class="section-header">
        <h2 class="section-title">📝 Laporan Kegiatan Harian</h2>
        <button class="btn-add" onclick="toggleForm()">➕ Tambah Laporan</button>
    </div>

    <!-- Form Tambah (Hidden by default) -->
    <div id="formLaporan" style="display: none; background: var(--bg-surface); padding: 20px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 24px;">
        <h3 style="color: var(--text-primary); margin-bottom: 16px;">Input Kegiatan Baru</h3>
        <form method="post" action="">
            <div class="form-group">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-input" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Kegiatan</label>
                <select name="kegiatan" class="form-select" required>
                    <option value="Muroja'ah Mandiri">Muroja'ah Mandiri</option>
                    <option value="Setoran Hafalan">Setoran Hafalan</option>
                    <option value="Mengajar Al-Qur'an">Mengajar Al-Qur'an</option>
                    <option value="Kegiatan Keagamaan">Kegiatan Keagamaan</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Detail Kegiatan / Surah & Ayat</label>
                <textarea name="detail" class="form-input" style="height: 100px;" placeholder="Contoh: Muroja'ah Juz 1 - 5, lancar." required></textarea>
            </div>
            <div style="text-align: right; gap: 10px; display: flex; justify-content: flex-end;">
                <button type="button" class="btn-cancel" onclick="toggleForm()">Batal</button>
                <button type="submit" name="simpan_laporan" class="btn-save">🚀 Kirim Laporan</button>
            </div>
        </form>
    </div>

    <!-- History Table -->
    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kegiatan</th>
                    <th>Detail</th>
                    <th>Status</th>
                    <th>Verifikator</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($laporan) > 0): ?>
                    <?php while ($row = mysqli_fetch_array($laporan)): ?>
                        <tr>
                            <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['kegiatan']); ?></strong></td>
                            <td style="max-width: 300px;"><?php echo htmlspecialchars($row['detail']); ?></td>
                            <td>
                                <?php if ($row['status_verifikasi'] == 'Pending'): ?>
                                    <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">⏳ Menunggu</span>
                                <?php elseif ($row['status_verifikasi'] == 'Approved'): ?>
                                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">✅ Disetujui</span>
                                <?php else: ?>
                                    <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171;">❌ Ditolak</span>
                                    <?php if ($row['catatan_verifikator']): ?>
                                        <div style="font-size: 0.75rem; color: #f87171; margin-top: 4px;">Ref: <?php echo htmlspecialchars($row['catatan_verifikator']); ?></div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                if ($row['tgl_verifikasi']) {
                                    echo date('d/m/Y', strtotime($row['tgl_verifikasi']));
                                } else {
                                    echo "-";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada laporan kegiatan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleForm() {
        const f = document.getElementById('formLaporan');
        f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }
</script>