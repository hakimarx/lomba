<?php
    $urlhost=$_SERVER['HTTP_HOST'];

    if($urlhost!="vx2f.cloud"){
        $host = "localhost";
        $user = "root";
        $pass = "";
        $db = "xmaqra";
    }else{
        $host = "localhost";
        $user = "vx2f9948_user";
        $pass = "vx2f9948_pass";
        $db = "vx2f9948_db";
    }

    $koneksi = mysqli_connect($host, $user, $pass, $db);
?>

