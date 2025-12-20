<?php
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT u.username, u.iduser_level, l.nama as level_name FROM user u JOIN user_level l ON u.iduser_level=l.id WHERE u.username='adminprov'");
$r = mysqli_fetch_assoc($q);
echo "Username: " . $r['username'] . "\n";
echo "Level ID: " . $r['iduser_level'] . "\n";
echo "Level Name: " . $r['level_name'] . "\n";
