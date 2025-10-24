<?php

    //print_r($_POST);


    $thispage="?page=utama&page2=home";
    $query="select * from event where aktif=1;";
    $data=getdata($query);
    if(mysqli_num_rows($data)==0){
        die ("<h1>belum ada event yang aktif</h1>");
    }else{
        $rowevent=mysqli_fetch_array($data);
        $event=$rowevent['nama'];
        $atas="<p style='flex:1'>event sekarang = $event</p>";
    }

?>


<?php
    $judul="";
    $bawah="";
    if(isset($_GET['idcabang'])){
        $idcabang=$_GET['idcabang'];
        $query="select * from golongan where idcabang=$idcabang";
        $data=getdata($query);


        while($row=mysqli_fetch_array($data)){
            $idgolongan=$row['id'];
            $golongan=$row['nama'];
            $bawah.="<p>><a href=$thispage&idgolongan=$idgolongan>$golongan</a></p>\n";
        }
    }elseif(isset($_GET['idgolongan'])){
        $idgolongan=$_GET['idgolongan'];
        $judul="";
        $bawah="<p><a target=blank href=?page=nilai_input&idgolongan=$idgolongan>Mulai Penilaian Lomba</a></p>";

        
}elseif($event!=""){
        $bawah="";

        $query="select * from cabang";
        $data=getdata($query);
        while($row=mysqli_fetch_array($data)){
            $cabang=$row['nama'];
            $idcabang=$row['id'];
            $bawah.="<p>> <a href=$thispage&idcabang=$idcabang>$cabang</a></p>\n";
        }

    }


?>


<style>
    .atas{
        text-align:center;
        font-size:33px;
        display: flex;
        justify-content: space-around;
    }
    hr{
        margin:22px 0;
    }

    .isi p {
        font-size: 22px;
        margin: 34px 0;
    }

    .isi{
        margin: 0 33px;
    }
    .home a{
        text-decoration:none;
    }

</style>
<head></head>


<div class="home">

    <div class="atas">
        <div>
            <?php
                $link=getonedata("select logopenyelenggara from event where id=$rowevent[id]");
                //die($link);
            ?>
            <img src="<?php echo $link;?>" alt="" width=55px>
        </div>
        <?php echo $atas;?>
        <div>
            <?php
                $link=getonedata("select logoacara from event where id=$rowevent[id]");
                //die($link);
            ?>
            <img src="<?php echo $link;?>" alt="" width=55px>
        </div>
        <hr>
    </div>
    <div class="bawah">
        <div class="judul">
            <?php echo $judul;?>
        </div>
        <div class="isi">
    <div class="breadcumb">
        <div><a href="?page=utama&page2=home">home</a></div>
        <?php
            if(isset($_GET['idcabang'])) {
                $idcabang=$_GET['idcabang'];
                include_once "dbku.php";
                $cabang=getonedata("select nama from cabang where id=$idcabang");
                print "<div><a href=?page=utama&page2=home&idcabang=$idcabang>$cabang</a></div>";
            }
        ?>
        <?php
            if(isset($_GET['idgolongan'])) {
                include_once "dbku.php";
                $idcabang=getonedata("select idcabang from golongan where id=$idgolongan");
                $cabang=getonedata("select nama from cabang where id=$idcabang");
                print "<div><a href=?page=utama&page2=home&idcabang=$idcabang>$cabang</a></div>";

                $idgolongan=$_GET['idgolongan'];
                $golongan=getonedata("select nama from golongan where id=$idgolongan");
                print "<div><a href=?page=utama&page2=home&idgolongan=$idgolongan>$golongan</a></div>";
            }
        ?>

    </div>
   
            <?php echo $bawah;?>
        </div>
    </div>

</div>
