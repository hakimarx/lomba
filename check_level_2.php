<?php
include 'musabaqah/dbku.php';
$q = mysqli_query($koneksi, "SELECT * FROM user_level WHERE id=2");
$r = mysqli_fetch_assoc($q);
print_r($r);
