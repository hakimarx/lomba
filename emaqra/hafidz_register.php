<?php
include "../musabaqah/dbku.php";
include "../musabaqah/hafidz/sesi.php";
include "../global/class/fungsi.php";

if(islogined_hafidz()){
    header("location:../lomba/musabaqah/hafidz/profile.php");
    exit;
}

if(!empty($_POST['username'])){
    $username=$_POST['username'];
    $password=$_POST['password'];
    $nama=$_POST['nama'];
    $telepon=$_POST['telepon'];

    $query="insert into hafidz (username,password,nama,telepon) values('$username','$password','$nama','$telepon')";
    execute($query);
    $idhafidz = mysqli_insert_id($GLOBALS['koneksi']);
    setlogin_hafidz($idhafidz);
    header("location:../lomba/musabaqah/hafidz/profile.php");
    exit;
}
?>
<head></head>
<div class=judul>
    <h1>Register Hafidz</h1>
</div>
<div class=box>
    <form action="" method="post">
        username<input type="text" name="username" required>
        password<input type="password" name="password" required>
        nama<input type="text" name="nama" required>
        telepon<input type="text" name="telepon">
        <button type="submit">Register</button>
    </form>
</div>
