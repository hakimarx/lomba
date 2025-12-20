<?php
include 'musabaqah/dbku.php';
$res = getdata("SHOW TABLES LIKE '%huffadz%'");
while ($r = mysqli_fetch_array($res)) echo $r[0] . "\n";
$res = getdata("SHOW TABLES LIKE '%insentif%'");
while ($r = mysqli_fetch_array($res)) echo $r[0] . "\n";
