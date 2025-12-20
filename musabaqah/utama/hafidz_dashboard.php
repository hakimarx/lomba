<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
// dashboard available to adminprov and adminkabko
openfor(["adminprov", "adminkabko", "role1", "role2"]);

// Get current user info
$iduser = getiduser();
$currentUser = getonebaris("SELECT * FROM view_user WHERE iduser=$iduser");
$user_kabkota = $currentUser['kab_kota'] ?? '';

// Statistics queries with error handling
$total_hafidz = 0;
$total_laki = 0;
$total_perempuan = 0;
$total_lulus = 0;
$total_laporan = 0;
$total_kabkota = 0;
$data_perkab = null;

try {
    if ($role == 'adminprov' || $role == 'role1') {
        $total_hafidz = getonedata("SELECT COUNT(*) FROM hafidz") ?? 0;
        // Try jk first, if fails use 0
        @$total_laki = getonedata("SELECT COUNT(*) FROM hafidz WHERE jk='L'") ?? 0;
        @$total_perempuan = getonedata("SELECT COUNT(*) FROM hafidz WHERE jk='P'") ?? 0;
        @$total_lulus = getonedata("SELECT COUNT(*) FROM hafidz WHERE lulus='1'") ?? 0;
        @$total_laporan = getonedata("SELECT COUNT(*) FROM hafidz_reports") ?? 0;
        @$total_kabkota = getonedata("SELECT COUNT(DISTINCT kab_kota) FROM hafidz WHERE kab_kota IS NOT NULL AND kab_kota != ''") ?? 0;
        @$data_perkab = getdata("SELECT kab_kota, COUNT(*) as total FROM hafidz WHERE kab_kota IS NOT NULL AND kab_kota != '' GROUP BY kab_kota ORDER BY total DESC");
    } else {
        $total_hafidz = getonedata("SELECT COUNT(*) FROM hafidz WHERE kab_kota='$user_kabkota'") ?? 0;
        @$total_laki = getonedata("SELECT COUNT(*) FROM hafidz WHERE jk='L' AND kab_kota='$user_kabkota'") ?? 0;
        @$total_perempuan = getonedata("SELECT COUNT(*) FROM hafidz WHERE jk='P' AND kab_kota='$user_kabkota'") ?? 0;
        @$total_lulus = getonedata("SELECT COUNT(*) FROM hafidz WHERE lulus='1' AND kab_kota='$user_kabkota'") ?? 0;
        @$total_laporan = getonedata("SELECT COUNT(*) FROM hafidz_reports hr JOIN hafidz h ON hr.id_hafidz=h.id WHERE h.kab_kota='$user_kabkota'") ?? 0;
        $data_perkab = null;
    }
} catch (Exception $e) {
    // Silently continue with 0 values
}

// Monthly reports
$monthlyData = getdata("SELECT DATE_FORMAT(created_at,'%Y-%m') as bulan, COUNT(*) as total FROM hafidz_reports GROUP BY bulan ORDER BY bulan DESC LIMIT 12");

// Chart data
$chartData = [];
if ($data_perkab) {
    mysqli_data_seek($data_perkab, 0);
    while ($r = mysqli_fetch_array($data_perkab)) {
        $chartData[$r['kab_kota']] = intval($r['total']);
    }
    mysqli_data_seek($data_perkab, 0);
}
?>

<style>
    .huffadz-dashboard {
        padding: 0;
    }

    .dashboard-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
        border-radius: 16px;
        padding: 32px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '🕌';
        position: absolute;
        right: 32px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 80px;
        opacity: 0.15;
    }

    .dashboard-header h1 {
        color: white;
        font-size: 2em;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .dashboard-header p {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1em;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--bg-card, #1e293b);
        border: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }

    .stat-card .icon {
        font-size: 2.5em;
        margin-bottom: 12px;
    }

    .stat-card .number {
        font-size: 2.5em;
        font-weight: 700;
        color: var(--primary-light, #34d399);
        line-height: 1;
    }

    .stat-card .label {
        color: var(--text-secondary, rgba(255, 255, 255, 0.7));
        font-size: 0.9em;
        margin-top: 8px;
    }

    .stat-card.gold {
        background: linear-gradient(135deg, #92400e 0%, #b45309 100%);
        border: none;
    }

    .stat-card.gold .number {
        color: #fcd34d;
    }

    .stat-card.gold .label {
        color: rgba(255, 255, 255, 0.8);
    }

    .stat-card.green {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        border: none;
    }

    .stat-card.green .number {
        color: #6ee7b7;
    }

    .stat-card.green .label {
        color: rgba(255, 255, 255, 0.8);
    }

    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .dashboard-card {
        background: var(--bg-card, #1e293b);
        border: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        border-radius: 16px;
        padding: 24px;
    }

    .dashboard-card h3 {
        color: var(--text-primary, #fff);
        font-size: 1.2em;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
    }

    .data-table th {
        background: var(--bg-surface, rgba(15, 23, 42, 0.6));
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: var(--text-secondary, rgba(255, 255, 255, 0.7));
        font-size: 0.85em;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table td {
        padding: 12px 16px;
        border-top: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        color: var(--text-primary, #fff);
    }

    .data-table tr:hover td {
        background: var(--bg-card-hover, rgba(255, 255, 255, 0.05));
    }

    .badge-count {
        display: inline-block;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85em;
        font-weight: 600;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: var(--bg-surface, rgba(15, 23, 42, 0.6));
        border-radius: 4px;
        overflow: hidden;
        margin-top: 8px;
    }

    .progress-bar .fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
        border-radius: 4px;
        transition: width 0.5s ease;
    }

    .quick-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .action-btn {
        padding: 12px 24px;
        background: var(--bg-surface, rgba(15, 23, 42, 0.6));
        border: 1px solid var(--border-color, rgba(255, 255, 255, 0.1));
        border-radius: 10px;
        color: var(--text-primary, #fff);
        text-decoration: none;
        font-size: 0.9em;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-btn:hover {
        background: rgba(16, 185, 129, 0.1);
        border-color: var(--primary, #10b981);
        color: var(--primary-light, #34d399);
    }

    .action-btn.primary {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
    }

    .action-btn.primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.3);
    }

    .chart-container {
        position: relative;
        height: 300px;
    }

    .gender-stats {
        display: flex;
        gap: 20px;
        margin-top: 16px;
    }

    .gender-item {
        flex: 1;
        text-align: center;
        padding: 16px;
        background: var(--bg-surface, rgba(15, 23, 42, 0.6));
        border-radius: 12px;
    }

    .gender-item .icon {
        font-size: 2em;
        margin-bottom: 8px;
    }

    .gender-item .count {
        font-size: 1.5em;
        font-weight: 700;
        color: var(--primary-light, #34d399);
    }

    .gender-item .label {
        color: var(--text-secondary);
        font-size: 0.85em;
    }
</style>

<div class="huffadz-dashboard">
    <!-- Header -->
    <div class="dashboard-header">
        <h1>📊 Dashboard Huffadz</h1>
        <p>Sistem Pendataan Huffadz <?php echo ($role == 'adminprov' || $role == 'role1') ? 'Jawa Timur' : $user_kabkota; ?></p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="icon">📚</div>
            <div class="number"><?php echo number_format($total_hafidz); ?></div>
            <div class="label">Total Hafidz</div>
        </div>

        <div class="stat-card gold">
            <div class="icon">✅</div>
            <div class="number"><?php echo number_format($total_lulus); ?></div>
            <div class="label">Lulus Verifikasi</div>
        </div>

        <div class="stat-card">
            <div class="icon">📝</div>
            <div class="number"><?php echo number_format($total_laporan); ?></div>
            <div class="label">Laporan Kegiatan</div>
        </div>

        <?php if ($role == 'adminprov' || $role == 'role1'): ?>
            <div class="stat-card">
                <div class="icon">🏛️</div>
                <div class="number"><?php echo $total_kabkota; ?></div>
                <div class="label">Kabupaten/Kota</div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Gender Statistics -->
    <div class="dashboard-card" style="margin-bottom: 24px;">
        <h3>👥 Statistik Gender</h3>
        <div class="gender-stats">
            <div class="gender-item">
                <div class="icon">👨</div>
                <div class="count"><?php echo number_format($total_laki); ?></div>
                <div class="label">Laki-laki</div>
            </div>
            <div class="gender-item">
                <div class="icon">👩</div>
                <div class="count"><?php echo number_format($total_perempuan); ?></div>
                <div class="label">Perempuan</div>
            </div>
        </div>
    </div>

    <div class="dashboard-grid">
        <!-- Chart -->
        <div class="dashboard-card">
            <h3>📈 Grafik per Kabupaten/Kota</h3>
            <div class="chart-container">
                <canvas id="chartHafidz"></canvas>
            </div>
        </div>

        <!-- Monthly Reports -->
        <div class="dashboard-card">
            <h3>📅 Laporan Bulanan</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Laporan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    mysqli_data_seek($monthlyData, 0);
                    while ($rowm = mysqli_fetch_array($monthlyData)):
                    ?>
                        <tr>
                            <td><?php echo $rowm['bulan']; ?></td>
                            <td><span class="badge-count"><?php echo $rowm['total']; ?></span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if ($role == 'adminprov' || $role == 'role1'): ?>
        <!-- Data per Kab/Ko -->
        <div class="dashboard-card">
            <h3>🏛️ Data per Kabupaten/Kota</h3>
            <div style="overflow-x: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Kabupaten/Kota</th>
                            <th>Jumlah Hafidz</th>
                            <th>Persentase</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($data_perkab, 0);
                        $max_total = $total_hafidz > 0 ? $total_hafidz : 1;
                        while ($row = mysqli_fetch_array($data_perkab)):
                            $percentage = round(($row['total'] / $max_total) * 100, 1);
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['kab_kota']); ?></td>
                                <td><span class="badge-count"><?php echo $row['total']; ?></span></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div class="progress-bar" style="flex: 1;">
                                            <div class="fill" style="width: <?php echo $percentage; ?>%;"></div>
                                        </div>
                                        <span style="color: var(--text-secondary); font-size: 0.85em;"><?php echo $percentage; ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Quick Actions -->
    <div class="dashboard-card">
        <h3>⚡ Aksi Cepat</h3>
        <div class="quick-actions">
            <a href="?page=utama&page2=hafidz" class="action-btn primary">📚 Kelola Data Hafidz</a>
            <a href="?page=utama&page2=hafidz_reports" class="action-btn">📝 Lihat Laporan</a>
            <a href="?page=utama&page2=hafidz_nilai" class="action-btn">✅ Penilaian</a>
            <a href="?page=utama&page2=cetak_hafidz" class="action-btn">🖨️ Cetak Rekap</a>
            <a href="?page=utama&page2=cetak_hafidz_monthly&bulan=<?php echo date('Y-m'); ?>" class="action-btn">📊 Rekap Bulanan</a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = <?php echo json_encode($chartData); ?>;
    if (Object.keys(chartData).length > 0) {
        const ctx = document.getElementById('chartHafidz').getContext('2d');
        const labels = Object.keys(chartData);
        const dataVals = Object.values(chartData);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Hafidz',
                    data: dataVals,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            color: 'rgba(255, 255, 255, 0.8)'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    },
                    y: {
                        ticks: {
                            color: 'rgba(255, 255, 255, 0.7)'
                        },
                        grid: {
                            color: 'rgba(255, 255, 255, 0.1)'
                        }
                    }
                }
            }
        });
    }
</script>