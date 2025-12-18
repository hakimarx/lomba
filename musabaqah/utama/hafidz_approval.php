<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminkabko","adminprov"]);

$iduser = getiduser();
$user = getonebaris("select * from user where id=$iduser");
$user_kab_kota = $user['kab_kota'] ?? '';
$role = $user['level'] ?? ''; // Assuming level name is fetched or I need to join. 
// Actually userrole.php might set $role global.
// Let's check userrole.php if I could, but I'll rely on the query.
$role_name = getonedata("select nama from user_level where id=".$user['iduser_level']);

if($role_name == 'admin prov'){
    // Admin Prov sees all pending? Or maybe just for monitoring.
    // Prompt says "admin kab/ko ... approve ... sesuai daerah".
    // I'll allow Admin Prov to see all pending for now.
    $query = "select * from hafidz where status='pending' order by kab_kota, nama";
} else {
    if(empty($user_kab_kota)){
        echo "<div class='box'>Anda belum memiliki wilayah Kab/Kota. Hubungi Admin Provinsi.</div>";
        exit;
    }
    $query = "select * from hafidz where status='pending' and kab_kota='$user_kab_kota' order by nama";
}

if(isset($_POST['approve_id'])){
    $id = $_POST['approve_id'];
    // Verify ownership if not admin prov
    if($role_name != 'admin prov'){
        $check = getonedata("select count(*) from hafidz where id=$id and kab_kota='$user_kab_kota'");
        if($check < 1) die("Unauthorized access to this hafidz.");
    }
    
    execute("update hafidz set status='aktif' where id=$id");
    alert("Hafidz berhasil disetujui.");
    header("location:hafidz_approval.php");
    exit;
}

$data = getdata($query);
?>
<head></head>
<div class=judul>
    <h1>Approval Registrasi Hafidz <?php echo $user_kab_kota ? "($user_kab_kota)" : ""; ?></h1>
</div>
<div class=box>
    <?php if(mysqli_num_rows($data) == 0){ ?>
        <p>Tidak ada registrasi pending.</p>
    <?php } else { ?>
    <table class=table>
        <tr><th>#</th><th>Nama</th><th>Kab/Kota</th><th>Asal</th><th>Telepon</th><th>Aksi</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['nama'];?></td>
                <td><?php echo $row['kab_kota'];?></td>
                <td><?php echo $row['asal'];?></td>
                <td><?php echo $row['telepon'];?></td>
                <td>
                    <form method="post" onsubmit="return confirm('Setujui hafidz ini?');">
                        <input type="hidden" name="approve_id" value="<?php echo $row['id'];?>">
                        <button type="submit" class="button">Approve</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php } ?>
</div>
