<?php
openfor(['adminprov', 'adminkabko', 'role1']);
$iduser_logged = getiduser();
$role_logged = getonedata("SELECT role FROM view_user WHERE iduser=$iduser_logged");
$kab_kota_logged = getonedata("SELECT kab_kota FROM user WHERE id=$iduser_logged");

if ($role_logged == 'adminprov' || $role_logged == 'role1') {
    $where = "1=1";
} else {
    $where = "u.kab_kota='$kab_kota_logged'";
}

// Summary Query
$summary = getdata("SELECT u.nama, u.kab_kota, u.nik, 
                    COUNT(l.id) as total_laporan,
                    SUM(CASE WHEN l.status_verifikasi='Approved' THEN 1 ELSE 0 END) as disetujui,
                    SUM(CASE WHEN l.status_verifikasi='Pending' THEN 1 ELSE 0 END) as pending
                    FROM user u
                    LEFT JOIN laporan_harian l ON u.id = l.iduser
                    WHERE u.iduser_level=104 AND $where
                    GROUP BY u.id
                    ORDER BY total_laporan DESC");

?>

<div class="modern-card">
    <div class="section-header">
        <h2 class="section-title">📊 Rekapitulasi Laporan Harian</h2>
        <div style="display: flex; gap: 10px;">
            <button class="btn-save" onclick="window.print()">🖨️ Cetak Rekap</button>
        </div>
    </div>

    <div class="table-container">
        <table class="user-table">
            <thead>
                <tr>
                    <th>Nama Hafiz</th>
                    <th>NIK</th>
                    <th>Kab/Kota</th>
                    <th>Total Laporan</th>
                    <th>Disetujui</th>
                    <th>Pending</th>
                    <th>Prosentase</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($summary)): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($row['nama']); ?></strong></td>
                        <td><?php echo htmlspecialchars($row['nik']); ?></td>
                        <td><?php echo htmlspecialchars($row['kab_kota']); ?></td>
                        <td><?php echo $row['total_laporan']; ?></td>
                        <td><span style="color: #10b981; font-weight: 600;"><?php echo $row['disetujui']; ?></span></td>
                        <td><span style="color: #fbbf24; font-weight: 600;"><?php echo $row['pending']; ?></span></td>
                        <td>
                            <?php
                            $pct = $row['total_laporan'] > 0 ? round(($row['disetujui'] / $row['total_laporan']) * 100) : 0;
                            ?>
                            <div style="width: 100px; height: 8px; background: var(--bg-surface); border-radius: 4px; overflow: hidden;">
                                <div style="width: <?php echo $pct; ?>%; height: 100%; background: #10b981;"></div>
                            </div>
                            <span style="font-size: 0.75rem; color: var(--text-muted);"><?php echo $pct; ?>% Valid</span>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>