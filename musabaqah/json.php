<?php

        include __DIR__ . "/dbku.php";

        if(isset($_GET['idcabang'])){
            header("Content-Type: application/json; charset=UTF-8");
            $idcabang=$_GET['idcabang'];
            $data=getdata("select id,nama from golongan where idcabang=$idcabang");
            $hasil=mysqli_fetch_all($data);
            echo json_encode($hasil);
        }
?>