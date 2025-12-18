<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/dbku_hafidz.php";
include __DIR__ . "/sesi.php";

if (!islogined_hafidz()) {
    header("location:login.php");
    exit;
}

$idhafidz = getid_hafidz();

if (!empty($_POST['kegiatan'])) {
    $tanggal = $_POST['tanggal'];
    $hari = $_POST['hari'];
    $kegiatan = $_POST['kegiatan'];
    $materi = $_POST['materi'];

    $bukti = null;
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] == 0) {
        // ensure directory exists
        $dir = '../../assets/documents/foto/hafidz';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $fn = time() . "_" . basename($_FILES['bukti']['name']);
        $dest = "$dir/" . $fn;
        move_uploaded_file($_FILES['bukti']['tmp_name'], $dest);
        $bukti = "assets/documents/foto/hafidz/" . $fn;
    }

    $paraf = null;
    if (isset($_FILES['paraf']) && $_FILES['paraf']['error'] == 0) {
        $dir = '../../assets/documents/paraf/hafidz';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $fn = time() . "_paraf_" . basename($_FILES['paraf']['name']);
        $dest = "$dir/" . $fn;
        move_uploaded_file($_FILES['paraf']['tmp_name'], $dest);
        $paraf = "assets/documents/paraf/hafidz/" . $fn;
    }

    $query = "insert into hafidz_reports (idhafidz,tanggal,hari,kegiatan,materi,bukti,paraf) values($idhafidz,'$tanggal','$hari','$kegiatan','$materi','" . ($bukti ? addslashes($bukti) : '') . "','" . ($paraf ? addslashes($paraf) : '') . "')";
    execute($query);
    header('location:harian.php');
    exit;
}

$data = getdata("select * from hafidz_reports where idhafidz=$idhafidz order by tanggal desc");

?>

<head></head>
<div class=judul>
    <h1>Laporan Harian</h1>
</div>
<div class=box>
    <form action="" method="post" enctype="multipart/form-data">
        <label>Hari</label>
        <select name="hari" required>
            <option value="">-- Pilih Hari --</option>
            <option value="Senin">Senin</option>
            <option value="Selasa">Selasa</option>
            <option value="Rabu">Rabu</option>
            <option value="Kamis">Kamis</option>
            <option value="Jumat">Jumat</option>
            <option value="Sabtu">Sabtu</option>
            <option value="Minggu">Minggu</option>
        </select>

        <label>Tanggal</label>
        <input type="date" name="tanggal" required>

        <label>Kegiatan</label>
        <textarea name="kegiatan" required placeholder="Deskripsi kegiatan hari ini"></textarea>

        <label>Materi</label>
        <textarea name="materi" placeholder="Materi yang dipelajari"></textarea>

        <label>Bukti Kegiatan (Foto)</label>
        <input type="file" name="bukti" accept="image/*">

        <label>Paraf (Upload Gambar Paraf)</label>
        <input type="file" name="paraf" accept="image/*">

        <button type="submit">Tambah Laporan</button>
    </form>
    <div style="margin-top:20px">
        <h3>Riwayat Laporan</h3>
        <table class=table>
            <tr>
                <th>Hari</th>
                <th>Tanggal</th>
                <th>Kegiatan</th>
                <th>Materi</th>
                <th>Bukti</th>
                <th>Paraf</th>
            </tr>
            <?php while ($row = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td><?php echo $row['hari']; ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo nl2br($row['kegiatan']); ?></td>
                    <td><?php echo nl2br($row['materi']); ?></td>
                    <td><?php if ($row['bukti']) { ?><a target=_blank href="../../<?php echo $row['bukti']; ?>">lihat</a><?php } ?></td>
                    <td><?php if ($row['paraf']) { ?><a target=_blank href="../../<?php echo $row['paraf']; ?>">lihat</a><?php } ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>