<?php
openfor(['hafiz']);
$iduser = getiduser();

// Handle File Upload
if (isset($_POST['upload_dokumen'])) {
    $jenis = $_POST['jenis_dokumen'];
    $keterangan = $_POST['keterangan'];

    if (isset($_FILES['dokumen_file']) && $_FILES['dokumen_file']['error'] == 0) {
        $dir = '../../assets/documents/hafiz';
        if (!file_exists($dir)) mkdir($dir, 0777, true);

        $fileName = time() . "_" . basename($_FILES['dokumen_file']['name']);
        $filePath = "$dir/$fileName";

        if (move_uploaded_file($_FILES['dokumen_file']['tmp_name'], $filePath)) {
            $dbPath = "assets/documents/hafiz/$fileName";
            $cols = ["iduser", "jenis_dokumen", "file_path", "keterangan"];
            $vals = [$iduser, $jenis, $dbPath, $keterangan];
            dbinsert("dokumen", $cols, $vals);
            echo "<script>alert('Dokumen berhasil diunggah!');</script>";
        }
    }
}

// Get Documents
$docs = getdata("SELECT * FROM dokumen WHERE iduser=$iduser ORDER BY created_at DESC");
?>

<div class="modern-card">
    <div class="section-header">
        <h2 class="section-title">📁 Dokumen & Berkas Saya</h2>
        <button class="btn-add" onclick="toggleUpload()">➕ Unggah Dokumen</button>
    </div>

    <div id="uploadForm" style="display: none; background: var(--bg-surface); padding: 24px; border-radius: 12px; border: 1px solid var(--border-color); margin-bottom: 24px;">
        <h3 style="color: var(--text-primary); margin-bottom: 16px;">Unggah Berkas Baru</h3>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label class="form-label">Jenis Dokumen</label>
                <select name="jenis_dokumen" class="form-select" required>
                    <option value="KTP">KTP</option>
                    <option value="KK">Kartu Keluarga (KK)</option>
                    <option value="Piagam">Piagam/Sertifikat</option>
                    <option value="SPJ">Laporan SPJ (PDF)</option>
                    <option value="Buku Rekening">Buku Rekening</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Pilih File (JPG/PNG/PDF)</label>
                <input type="file" name="dokumen_file" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan Singkat</label>
                <input type="text" name="keterangan" class="form-input" placeholder="Contoh: Piagam Hafalan 30 Juz">
            </div>
            <div style="text-align: right; gap: 10px; display: flex; justify-content: flex-end;">
                <button type="button" class="btn-cancel" onclick="toggleUpload()">Batal</button>
                <button type="submit" name="upload_dokumen" class="btn-save">🚀 Unggah Sekarang</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Jenis</th>
                    <th>Nama File</th>
                    <th>Keterangan</th>
                    <th>Tanggal Unggah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($docs) > 0): ?>
                    <?php while ($row = mysqli_fetch_array($docs)): ?>
                        <tr>
                            <td><span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;"><?php echo htmlspecialchars($row['jenis_dokumen']); ?></span></td>
                            <td style="font-size: 0.85rem; color: var(--text-secondary);"><?php echo basename($row['file_path']); ?></td>
                            <td><?php echo htmlspecialchars($row['keterangan'] ?: '-'); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                            <td>
                                <a href="../<?php echo $row['file_path']; ?>" target="_blank" class="action-btn btn-edit" style="text-decoration: none;">👁️ Lihat</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada dokumen yang diunggah.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleUpload() {
        const f = document.getElementById('uploadForm');
        f.style.display = f.style.display === 'none' ? 'block' : 'none';
    }
</script>