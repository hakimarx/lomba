<?php
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT * FROM user_level");
while ($r = mysqli_fetch_assoc($q)) {
    echo $r['id'] . " -> " . $r['nama'] . "\n";
}
