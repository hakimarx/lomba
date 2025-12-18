<?php
    include __DIR__ . "/../../global/class/fungsi.php";
    include __DIR__ . "/../dbku.php";
    include __DIR__ . "/sesi.php";
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php

    if(!empty($_POST['username'])){
        $username=$_POST['username'];
        $passwd=$_POST['passwd'];

        $query="select * from user where username='$username' and password='$passwd' limit 1";
        $datauser=getdata($query);
        if(mysqli_num_rows($datauser)==1){
            $rowuser=mysqli_fetch_array($datauser);
            setlogin($rowuser['id']);
            header("location:../?page=utama");
        }else{
            alert("login gagal");
        }
    }
 ?>

<style>
    * {
        box-sizing: border-box;
        padding: 0;
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
    }

    .login {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    :root {
        --warna1: green;
    }

    .center-text {
        text-align: center;
    }

    .login input {
        width: 100%;
        margin-bottom: 16px;
        border-radius: 2px;
        padding: 5px;
    }

    .login .judul{
        color:white;
    }
    .box {
        border: 2px solid var(--warna1);
        background-color: white;
        /* animation-name: anim; */
        /* animation-duration: .3s; */
        width: 444px;
        /* height: 15em; */
        max-width: calc(100% - 11px);
    }

    .login button {
        background-color: var(--warna1);
        color: white;
        padding: 11px;
        width: 100%;
        border: none;
    }

    @keyframes anim {
        0% {
            transform: scale(0)
        }

        100% {
            transform: scale(1)
        }
    }

    .judul {
        background-color: var(--warna1);
        text-align: center;
        font-size: 33px;
        padding: 16px;
    }

    .isi {
        padding: 11px;
    }

    form {
        margin: 0;
    }
    .info {
        border: 2px solid #ff002d;
        padding: 11px;
        background-color: pink;
    }
</style>

<div class="login">
    <div class="box">
        <div class="judul">user login
        </div>
        <div class="isi">
            <form action="" method="post">
    <div class=info>
                    adminprov,adminprov<br>
                    operator,operator
                </div>
                username
                <input type="text" name="username" required value="adminprov">
                password
                <input name="passwd" required value="adminprov">
                <button type="submit">login</button>
            </form>
        </div>
    </div>
</div>
<head></head>