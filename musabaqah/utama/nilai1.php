<?php
//digunakan di nilai0.php
//tidak bisa disatukan karena nilai0. mengandung  menu utama
    include "../dbku.php";
?>

<?php
    if(isset($_GET['idcabang'])){
    // header("Content-Type: application/json; charset=UTF-8");
        $idcabang=$_GET['idcabang'];
        $data=getdata("select id,nama from golongan where idcabang=$idcabang");
        $a=mysqli_fetch_all($data);
        echo json_encode($a);
    }
?>

