<?php
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT username, iduser_level FROM user LIMIT 10");
while ($r = mysqli_fetch_assoc($q)) {
    echo $r['username'] . ":" . $r['iduser_level'] . "\n";
}
