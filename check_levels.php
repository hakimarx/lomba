<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, 'SELECT * FROM user_level');
if (!$q) {
    echo "ERROR: " . mysqli_error($koneksi) . "\n";
} else {
    while ($r = mysqli_fetch_assoc($q)) {
        echo $r['id'] . ":" . $r['nama'] . "\n";
    }
}
