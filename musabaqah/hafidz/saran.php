<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/dbku_hafidz.php";
include __DIR__ . "/sesi.php";

if (!islogined_hafidz()) {
    header("location:login.php");
    exit;
}

$idhafidz = getid_hafidz();

if (!empty($_POST['saran'])) {
    $saran = $_POST['saran'];
    $tanggal = date('Y-m-d');
    $query = "insert into hafidz_saran (idhafidz,tanggal,saran) values($idhafidz,'$tanggal','$saran')";
    execute($query);
    alert('Saran dan masukan berhasil dikirim. Terima kasih.');
    header('location:saran.php');
    exit;
}

$data = getdata("select * from hafidz_saran where idhafidz=$idhafidz order by tanggal desc");

?>

<head></head>
<div class=judul>
    <h1>Saran dan Masukan</h1>
</div>
<div class=box>
    <p>Silakan tuliskan saran dan masukan Anda untuk kemajuan program ini.</p>
    <form action="" method="post">
        <textarea name="saran" required style="width:100%; height:100px;" placeholder="Tulis saran Anda di sini..."></textarea>
        <br><br>
        <button type="submit">Kirim Saran</button>
    </form>
    <div style="margin-top:20px">
        <h3>Riwayat Saran Anda</h3>
        <table class=table>
            <tr>
                <th>Tanggal</th>
                <th>Saran</th>
            </tr>
            <?php while ($row = mysqli_fetch_array($data)) { ?>
                <tr>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo nl2br($row['saran']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <div style="margin-top:20px">
        <a href="profile.php">Kembali ke Profile</a>
    </div>
</div>