<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/dbku_hafidz.php";
include __DIR__ . "/sesi.php";

if (!islogined_hafidz()) {
    header("location:login.php");
    exit;
}

$idhafidz = getid_hafidz();
$row = getonebaris("select * from hafidz where id=$idhafidz");

if (!empty($_POST['nama'])) {
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $no_rekening = $_POST['no_rekening'];
    $nama_bank = $_POST['nama_bank'];
    $atas_nama_rekening = $_POST['atas_nama_rekening'];

    $query = "update hafidz set nama='$nama',telepon='$telepon', no_rekening='$no_rekening', nama_bank='$nama_bank', atas_nama_rekening='$atas_nama_rekening' where id=$idhafidz";
    execute($query);
    alert('profile updated');
    header('location:profile.php');
    exit;
}

?>

<head></head>
<div class=judul>
    <h1>Profile Hafidz</h1>
</div>
<div class=box>
    <form action="" method="post">
        nama<input type="text" name="nama" required value="<?php echo $row['nama']; ?>">
        telepon<input type="text" name="telepon" value="<?php echo $row['telepon']; ?>">

        <h3>Data Rekening</h3>
        Nama Bank<input type="text" name="nama_bank" placeholder="Contoh: BSI, BRI, BCA" value="<?php echo isset($row['nama_bank']) ? $row['nama_bank'] : ''; ?>">
        Nomor Rekening<input type="text" name="no_rekening" value="<?php echo isset($row['no_rekening']) ? $row['no_rekening'] : ''; ?>">
        Atas Nama Rekening<input type="text" name="atas_nama_rekening" value="<?php echo isset($row['atas_nama_rekening']) ? $row['atas_nama_rekening'] : ''; ?>">

        <button type="submit">Save</button>
    </form>
    <div style="margin-top:20px">
        <a href="harian.php">Laporan Harian</a> |
        <a href="saran.php">Saran & Masukan</a> |
        <a href="logout.php">Logout</a>
    </div>
</div>