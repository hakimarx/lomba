<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov","adminkabko"]);

$query = "select r.*, h.nama as nama_hafidz, h.kab_kota from hafidz_reports r left join hafidz h on r.idhafidz=h.id order by r.tanggal desc";
$data = getdata($query);
?>
<head></head>
<div class=judul>
    <h1>Hafidz Laporan Harian (Admin View)</h1>
</div>
<div class=box>
    <table class=table>
        <tr><th>#</th><th>Hari</th><th>Tanggal</th><th>Hafidz</th><th>Kab/Kota</th><th>Kegiatan</th><th>Materi</th><th>Bukti</th><th>Paraf</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['hari'];?></td>
                <td><?php echo $row['tanggal'];?></td>
                <td><?php echo $row['nama_hafidz'];?></td>
                <td><?php echo $row['kab_kota'];?></td>
                <td><?php echo nl2br($row['kegiatan']);?></td>
                <td><?php echo nl2br($row['materi']);?></td>
                <td><?php if($row['bukti']) echo "<a target=_blank href='../../$row[bukti]'>lihat</a>";?></td>
                <td><?php if($row['paraf']) echo "<a target=_blank href='../../$row[paraf]'>lihat</a>";?></td>
            </tr>
        <?php } ?>
    </table>
</div>
