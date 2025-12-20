<?php
include 'musabaqah/dbku.php';
function desc($tbl)
{
    global $koneksi;
    echo "--- $tbl ---\n";
    $q = mysqli_query($koneksi, "DESCRIBE $tbl");
    while ($r = mysqli_fetch_assoc($q)) echo $r['Field'] . "|" . $r['Type'] . "|" . $r['Extra'] . "\n";
}
desc('user_level');
desc('user');
