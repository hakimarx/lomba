<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov","adminkabko"]);

if(isset($_POST['save_test'])){
    $idhafidz = intval($_POST['idhafidz']);
    $jenis = $_POST['jenis'];
    $nilai = floatval($_POST['nilai']);
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];
    $idpenilai = getiduser();
    execute("insert into hafidz_nilai (idhafidz,jenis,nilai,keterangan,tanggal,idpenilai) values($idhafidz,'$jenis',$nilai,'".addslashes($keterangan)."','$tanggal',$idpenilai)");
    header('location: ?page=utama&page2=hafidz_nilai');
    exit;
}

$data = getdata("select n.*, h.nama as nama_hafidz from hafidz_nilai n left join hafidz h on n.idhafidz=h.id order by tanggal desc");
?>
<head></head>
<div class=judul>
    <h1>Input Hafidz Test Scores</h1>
</div>
<div class=box>
    <form action="" method="post">
        hafidz <select name="idhafidz" required>
            <option value="">-- pilih --</option>
            <?php $dh=getdata("select * from hafidz order by nama"); while($rh=mysqli_fetch_array($dh)){ echo "<option value=$rh[id]>$rh[nama]</option>";} ?>
        </select>
        jenis <select name="jenis"><option value="wawasan">Wawasan Kebangsaan</option><option value="hafalan">Hafalan Quran</option></select>
        nilai <input type="number" step="0.01" name="nilai" required>
        tanggal <input type="date" name="tanggal" required>
        keterangan <textarea name="keterangan"></textarea>
        <button type="submit" name="save_test">Simpan</button>
    </form>
    <div style="margin-top:20px">
        <h3>Riwayat Penilaian</h3>
        <table class=table>
            <tr><th>#</th><th>Tanggal</th><th>Hafidz</th><th>Jenis</th><th>Nilai</th><th>Keterangan</th><th>Penilai</th></tr>
            <?php $i=1; while($row=mysqli_fetch_array($data)){ $pen_nama = getonedata("select nama from user where id=$row[idpenilai]"); ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['tanggal'];?></td>
                <td><?php echo $row['nama_hafidz'];?></td>
                <td><?php echo $row['jenis'];?></td>
                <td><?php echo $row['nilai'];?></td>
                <td><?php echo $row['keterangan'];?></td>
                <td><?php echo $pen_nama;?></td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
