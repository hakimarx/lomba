<?php
include 'musabaqah/dbku.php';
$res = mysqli_query($koneksi, "SHOW CREATE VIEW view_user");
$r = mysqli_fetch_array($res);
echo $r[1];
