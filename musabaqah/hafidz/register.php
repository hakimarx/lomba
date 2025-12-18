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
    $nama = $_POST['nama'];
    $telepon = $_POST['telepon'];
    $email = $_POST['email'];
    $kab_kota = $_POST['kab_kota'];

    // Generate verification token
    $token = bin2hex(random_bytes(16));

    // insert into hafidz table with status pending
    $query = "insert into hafidz (username,password,nama,telepon,email,kab_kota,verification_token,status) values('$username','$password','$nama','$telepon','$email','$kab_kota','$token','pending')";
    execute($query);

    // Send verification email (simplified - in production use proper mail library)
    $verify_link = "http://" . $_SERVER['HTTP_HOST'] . "/lomba/musabaqah/hafidz/verify.php?token=$token";
    $subject = "Verifikasi Email Hafidz";
    $message = "Klik link berikut untuk verifikasi email Anda: $verify_link";
    @mail($email, $subject, $message);

    alert("Registrasi berhasil! Silakan cek email Anda untuk verifikasi. Setelah verifikasi, akun Anda akan menunggu approval dari Admin Kab/Ko.");
    header("location:login.php");
    exit;
}
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>
<div class=judul>
    <h1>Register Hafidz</h1>
</div>
<div class=box>
    <form action="" method="post">
        username<input type="text" name="username" required>
        password<input type="password" name="password" required>
        email<input type="email" name="email" required placeholder="email@example.com">
        nama lengkap<input type="text" name="nama" required>
        telepon<input type="text" name="telepon" required>
        kab/kota<input type="text" name="kab_kota" required placeholder="Nama Kab/Kota Anda">
        <button type="submit">Register</button>
    </form>
    <div style="margin-top:10px">
        <a href="login.php">Sudah punya akun? Login di sini</a>
    </div>
</div>