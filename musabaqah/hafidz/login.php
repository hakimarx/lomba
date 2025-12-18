<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/dbku_hafidz.php";
include __DIR__ . "/sesi.php";

if (islogined_hafidz()) {
    header("location:profile.php");
    exit;
}

if (!empty($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "select * from hafidz where username='$username' and password='$password' limit 1";
    $data = getdata($query);
    if (mysqli_num_rows($data) == 1) {
        $row = mysqli_fetch_array($data);

        // Check email verification
        if ($row['email_verified'] != 1) {
            alert('Email Anda belum diverifikasi. Silakan cek email Anda.');
            exit;
        }

        // Check approval status
        if ($row['status'] == 'pending') {
            alert('Akun Anda masih menunggu approval dari Admin Kab/Ko.');
            exit;
        }

        setlogin_hafidz($row['id']);
        header("location:profile.php");
        exit;
    } else {
        alert('login gagal');
    }
}
?>

<head></head>
<div class=judul>
    <h1>Login Hafidz</h1>
</div>
<div class=box>
    <form action="" method="post">
        username<input type="text" name="username" required>
        password<input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
    <div style="margin-top:10px">
        <a href="register.php">Belum punya akun? Daftar di sini</a>
    </div>
</div>