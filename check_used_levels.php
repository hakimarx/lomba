<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, 'SELECT DISTINCT iduser_level FROM user');
while ($r = mysqli_fetch_assoc($q)) {
    echo $r['iduser_level'] . "\n";
}
