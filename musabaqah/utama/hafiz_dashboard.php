<?php
openfor(['hafiz']);
$iduser = getiduser();
$rowuser = getonebaris("SELECT * FROM user WHERE id=$iduser");
$nik = $rowuser['nik'];

// Fetch Hafiz Data (Identity Link)
$hafidzData = getonebaris("SELECT * FROM hafidz WHERE nik='$nik'");

// Stats
$totalReports = getonedata("SELECT COUNT(*) FROM laporan_harian WHERE iduser=$iduser");
$approvedReports = getonedata("SELECT COUNT(*) FROM laporan_harian WHERE iduser=$iduser AND status_verifikasi='Approved'");
$pendingReports = getonedata("SELECT COUNT(*) FROM laporan_harian WHERE iduser=$iduser AND status_verifikasi='Pending'");
?>

<div class="stats-cards">
    <div class="stat-card">
        <div class="stat-number"><?php echo $totalReports; ?></div>
        <div class="stat-label">Total Laporan</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #10b981;"><?php echo $approvedReports; ?></div>
        <div class="stat-label">Disetujui</div>
    </div>
    <div class="stat-card">
        <div class="stat-number" style="color: #fbbf24;"><?php echo $pendingReports; ?></div>
        <div class="stat-label">Menunggu</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
    <!-- Profile Status -->
    <div class="modern-card" style="padding: 24px;">
        <h3 style="color: var(--text-primary); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            👤 Status Profil & Identitas
        </h3>
        <div style="display: flex; flex-direction: column; gap: 12px;">
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                <span style="color: var(--text-secondary);">Nama:</span>
                <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($rowuser['nama']); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                <span style="color: var(--text-secondary);">NIK:</span>
                <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($nik ?: '-'); ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                <span style="color: var(--text-secondary);">Wilayah:</span>
                <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($rowuser['kab_kota'] ?: '-'); ?></strong>
            </div>
        </div>
        <?php if (!$hafidzData): ?>
            <div style="margin-top: 20px; padding: 12px; background: rgba(239, 68, 68, 0.1); border: 1px solid #f87171; border-radius: 8px; color: #f87171; font-size: 0.9rem;">
                ⚠️ Data pendaftaran tidak ditemukan. Pastikan NIK Anda sudah terdaftar oleh Admin.
            </div>
        <?php endif; ?>
    </div>

    <!-- Incentive Status -->
    <div class="modern-card" style="padding: 24px;">
        <h3 style="color: var(--text-primary); margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            💰 Status Insentif & Seleksi
        </h3>
        <?php if ($hafidzData): ?>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <span style="color: var(--text-secondary);">Status Seleksi:</span>
                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">LULUS</span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <span style="color: var(--text-secondary);">Status Insentif:</span>
                    <?php if ($hafidzData['is_insentif_prov'] || $hafidzData['is_insentif_kabko']): ?>
                        <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #10b981;">AKTIF</span>
                    <?php else: ?>
                        <span class="badge" style="background: rgba(245, 158, 11, 0.2); color: #fbbf24;">PROSES</span>
                    <?php endif; ?>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 8px;">
                    <span style="color: var(--text-secondary);">Hafalan:</span>
                    <strong style="color: var(--text-primary);"><?php echo $hafidzData['jumlah_hafalan_juz'] ?: '0'; ?> JUZ</strong>
                </div>
            </div>
        <?php else: ?>
            <p style="color: var(--text-muted); text-align: center; padding: 20px;">Menunggu data dari sistem...</p>
        <?php endif; ?>
    </div>
</div>

<div class="modern-card" style="margin-top: 24px; padding: 24px;">
    <h3 style="color: var(--text-primary); margin-bottom: 20px;">📅 Pengumuman Kegiatan</h3>
    <div style="padding: 16px; background: rgba(59, 130, 246, 0.1); border-left: 4px solid #3b82f6; border-radius: 4px;">
        <div style="font-weight: 600; color: #60a5fa; margin-bottom: 4px;">Informasi Terbaru</div>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin: 0;">Silakan lengkapi laporan harian Anda untuk menjaga status keaktifan insentif. Laporan divalidasi setiap akhir bulan oleh Admin Kab/Ko.</p>
    </div>
</div>