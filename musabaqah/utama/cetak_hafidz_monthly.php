<?php
include_once __DIR__ . "/../dbku.php";
// print monthly summary by kab_kota
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
$data=getdata("select kab_kota, count(*) as total from hafidz where DATE_FORMAT(tanggal_lahir,'%Y-%m') = '$bulan' group by kab_kota");
?>
<head></head>
<div class=judul>
    <h1>Cetak Rekap Huffadz Bulanan - <?php echo $bulan;?></h1>
</div>
<div class=box>
    <table class=table>
        <tr><th>#</th><th>Kab/Kota</th><th>Jumlah</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $row['kab_kota'];?></td>
            <td><?php echo $row['total'];?></td>
        </tr>
        <?php } ?>
    </table>
</div>
