<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov","adminkabko"]);

$query = "select s.*, h.nama as nama_hafidz, h.kab_kota from hafidz_saran s left join hafidz h on s.idhafidz=h.id order by s.tanggal desc";
$data = getdata($query);
?>
<head></head>
<div class=judul>
    <h1>Rekapitulasi Saran dan Masukan Hafidz</h1>
</div>
<div class=box>
    <table class=table>
        <tr><th>#</th><th>Tanggal</th><th>Hafidz</th><th>Kab/Kota</th><th>Saran</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['tanggal'];?></td>
                <td><?php echo $row['nama_hafidz'];?></td>
                <td><?php echo $row['kab_kota'];?></td>
                <td><?php echo nl2br($row['saran']);?></td>
            </tr>
        <?php } ?>
    </table>
    <div style="margin-top:20px">
        <button class="button" onclick="window.print()">Cetak</button>
    </div>
</div>
