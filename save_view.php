<?php
include 'musabaqah/dbku.php';
$res = mysqli_query($koneksi, "SHOW CREATE VIEW view_user");
$r = mysqli_fetch_array($res);
file_put_contents('view_user_def.txt', $r[1]);
echo "Definition saved to view_user_def.txt\n";
