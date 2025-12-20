<?php
error_reporting(0);
include 'musabaqah/dbku.php';
$res = mysqli_query($koneksi, "SHOW TABLES");
while ($r = mysqli_fetch_array($res)) {
    if (strpos($r[0], 'huffadz') !== false || strpos($r[0], 'insentif') !== false || strpos($r[0], 'hafidz') !== false) {
        echo $r[0] . "\n";
    }
}
