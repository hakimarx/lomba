<?php

require "../dbku.php";

$juz=1;
$halaman=1;

$data=getdata("select * from _deprecated_mutasyabihat");
while($row=mysqli_fetch_array($data)){
    $nosurat=$row['nosurat'];
    $ayat=$row['ayat'];
    $q="update mushaf set mutasyabihat=1 where surat='$nosurat' and ayat='$ayat';";
    // execute($q);
    echo "$q\n<br>";
}

print "done";

?>