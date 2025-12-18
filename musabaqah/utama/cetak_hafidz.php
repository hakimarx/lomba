<?php
include_once __DIR__ . "/../dbku.php";
// simple rekap print of hafidz
$data=getdata("select * from hafidz order by kab_kota,nama");
?>
<head></head>
<div class=judul>
    <h1>Cetak Rekap Huffadz</h1>
</div>
<div class=box>
    <table class=table>
        <tr><th>#</th><th>Nama</th><th>NIK</th><th>Kab/Kota</th><th>Telepon</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
        <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo $row['nama'];?></td>
            <td><?php echo $row['nik'];?></td>
            <td><?php echo $row['kab_kota'];?></td>
            <td><?php echo $row['telepon'];?></td>
        </tr>
        <?php } ?>
    </table>
</div>
