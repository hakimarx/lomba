<?php
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT username, iduser_level FROM user WHERE username='adminprov'");
$r = mysqli_fetch_assoc($q);
echo "User: " . $r['username'] . " Level: " . $r['iduser_level'] . "\n";
