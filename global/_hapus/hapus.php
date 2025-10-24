<?php


function bufferr(){
    // Disable output buffering 
    ob_end_flush(); 
    ob_implicit_flush(true); 
    

    for($i=0;$i<3;$i++){
        echo "cobaz kalimat ke-$i<br>\n";
        flush();
        sleep(1);
    }

    echo "<hr>selesai";



}

?>

<?php

require "../dbku.php";

for ($i=0; $i < 33; $i++) { 
    // $q="insert into soal (idbidang,soal,jawaban) values ('5911','soal ke $i','jawaban ke $i')";
    // echo "$q<br>";
    // execute($q);
}
?>