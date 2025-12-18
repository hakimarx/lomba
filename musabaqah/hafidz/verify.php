<?php
include __DIR__ . "/../../global/class/fungsi.php";
include __DIR__ . "/dbku_hafidz.php";

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $hafidz = getonebaris("select * from hafidz where verification_token='$token'");

    if ($hafidz) {
        execute("update hafidz set email_verified=1, verification_token=NULL where id=" . $hafidz['id']);
        alert("Email berhasil diverifikasi! Akun Anda sekarang menunggu approval dari Admin Kab/Ko.");
        header("location:login.php");
        exit;
    } else {
        alert("Token verifikasi tidak valid.");
        header("location:login.php");
        exit;
    }
} else {
    header("location:login.php");
    exit;
}
