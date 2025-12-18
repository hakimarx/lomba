<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
// dashboard available to adminprov and adminkabko
openfor(["adminprov","adminkabko"]);

$data=getdata("select kab_kota, count(*) as total from hafidz group by kab_kota");
$chartData = [];
mysqli_data_seek($data,0);
while($r=mysqli_fetch_array($data)){
    $chartData[$r['kab_kota']] = intval($r['total']);
}
// monthly summary
$month = date('Y-m');
$monthlyData = getdata("select DATE_FORMAT(created_at,'%Y-%m') as bulan, count(*) as total from hafidz_reports group by bulan order by bulan desc limit 12");

?>
<head></head>
<div class=judul>
    <h1>Dashboard Huffadz</h1>
</div>
<div class=box>
    <h3>Jumlah hafidz per kab/kota</h3>
    <table class=table>
        <tr><th>Kab/Kota</th><th>Jumlah</th></tr>
        <?php while($row=mysqli_fetch_array($data)){ ?>
        <tr>
            <td><?php echo $row['kab_kota'];?></td>
            <td><?php echo $row['total'];?></td>
        </tr>
        <?php } ?>
    </table>
    <div style='margin-top:20px'>
        <h3>Grafik: Jumlah hafidz per kab/kota</h3>
        <canvas id='chartHafidz' width='600' height='300'></canvas>
    </div>
    <div style='margin-top:20px'>
        <h3>Laporan Bulanan (Laporan Harian)</h3>
        <table class=table>
            <tr><th>Bulan</th><th>Jumlah Laporan</th></tr>
            <?php while($rowm=mysqli_fetch_array($monthlyData)){ ?>
                <tr><td><?php echo $rowm['bulan'];?></td><td><?php echo $rowm['total'];?></td></tr>
            <?php } ?>
        </table>
        <div style="margin-top:10px"><a class="button" href='?page=utama&page2=cetak_hafidz_monthly&bulan=<?php echo date("Y-m");?>'>Cetak Rekap Bulanan</a></div>
    </div>
    <div style="margin-top:20px">
        <a class="button" href="?page=utama&page2=cetak_hafidz">Cetak Rekap</a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = <?php echo json_encode($chartData);?>;
    const ctx = document.getElementById('chartHafidz').getContext('2d');
    const labels = Object.keys(chartData);
    const dataVals = Object.values(chartData);
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{ label: 'Jumlah Hafidz', data: dataVals, backgroundColor: 'rgba(54, 162, 235, 0.6)' }]
        }
    });
</script>
