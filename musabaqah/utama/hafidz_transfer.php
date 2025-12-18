<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov"]);

if(isset($_POST['approve_id'])){
    $id = $_POST['approve_id'];
    $hafidz = getonebaris("select * from hafidz where id=$id");
    if($hafidz && $hafidz['status'] == 'pindah_request' && !empty($hafidz['tujuan_pindah'])){
        $tujuan = $hafidz['tujuan_pindah'];
        execute("update hafidz set kab_kota='$tujuan', status='aktif', tujuan_pindah=NULL where id=$id");
        alert("Transfer berhasil disetujui. Hafidz sekarang terdaftar di $tujuan.");
        header("location:hafidz_transfer.php");
        exit;
    }
}

$query = "select * from hafidz where status='pindah_request' order by kab_kota, nama";
$data = getdata($query);
?>
<head></head>
<div class=judul>
    <h1>Approval Pindah Kab/Kota</h1>
</div>
<div class=box>
    <?php if(mysqli_num_rows($data) == 0){ ?>
        <p>Tidak ada request pindah.</p>
    <?php } else { ?>
    <table class=table>
        <tr><th>#</th><th>Nama</th><th>Kab/Kota Asal</th><th>Tujuan Pindah</th><th>Aksi</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['nama'];?></td>
                <td><?php echo $row['kab_kota'];?></td>
                <td><?php echo $row['tujuan_pindah'];?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Setujui kepindahan ini?');">
                        <input type="hidden" name="approve_id" value="<?php echo $row['id'];?>">
                        <button type="submit" class="button">Approve Transfer</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>
