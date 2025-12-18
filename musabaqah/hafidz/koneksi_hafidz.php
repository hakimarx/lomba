<?php
// Koneksi khusus untuk database huffadz
$urlhost = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';

if ($urlhost != "vx2f.cloud") {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "huffadz";
} else {
    $host = "localhost";
    $user = "vx2f9948_user";
    $pass = "vx2f9948_pass";
    $db = "vx2f9948_huffadz";
}

try {
    $koneksi = mysqli_connect($host, $user, $pass, $db);
} catch (Throwable $e) {
    $koneksi = false;
    $err = $e->getMessage();
}

if (!$koneksi) {
    if (!isset($err)) $err = mysqli_connect_error();
    die("Database connection failed: " . $err);
}

$GLOBALS['koneksi'] = $koneksi;
