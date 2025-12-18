<?php
// start session before any output
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
//cek logout
if(isset($_GET['logout'])){
    $_SESSION['admin']="";
    header("location:index.php");
}

//cek sudah login
if($_SESSION['admin']==""){
    header("location:index.php");
}

//print "login me";

if(isset($_GET['page2'])){
    $page2=$_GET['page2'];
}else{
    $page2="admin2";
}


?>
<head></head>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --padding: 11px;
    }

    .kiri {
        background-color: rgb(0, 0, 0);
        flex-basis: 160px;
        padding: var(--padding);
        display: flex;
        flex-direction: column;
        color: white;
    }

    a{
        color: white;
        text-decoration: none;
    }
    a:hover{
        background-color: cyan;
    }
    .kanan {
        background-color: white;
        flex: 1;
        padding: 22px;
    }

    .admin {
        display: flex;
        height: 100%;
        flex-direction:column;
    }

    .kiri>* {
    border-bottom: 1px solid yellow;
    padding: 11px;
}
</style>
<!--
        <button>ubah password</button>
-->

<div class="admin">
    <div class="kiri">
        <a href="?page=admin/admin&page2=user">data user</a>
        <a href="?page=admin/admin&page2=kategori">kategori</a>
        <a href="?page=admin/admin&page2=import">import soal</a>
        <a href="?page=admin/admin&page2=peserta">peserta</a>
        <a href="?page=admin/admin&page2=hakim">hakim</a>
        <a href="?page=admin/admin&page2=panitera">panitera</a>
        <a href="?page=admin/admin&logout">logout</a>
    </div>
    <div class="kanan">
        <?php
        if($page2!=""){
            include("$page2.php");

        }
?>

    </div>
</div>