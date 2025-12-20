<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT * FROM user WHERE username='adminprov'");
$r = mysqli_fetch_assoc($q);
print_r($r);
