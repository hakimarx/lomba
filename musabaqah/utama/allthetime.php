<?php
    function setdata($query,$limit,$offset){
        // $query2="$query" . " limit $limit offset $offset";
        // die($query2);
        $query2=$query;
        $data=getdata($query2);
        $fieldinfo = mysqli_fetch_fields($data);

        echo "<tr>";
            foreach ($fieldinfo as $val) {
                echo "<th>$val->name</th>";
            }
        echo "</tr>";
        $nkolom=mysqli_num_fields($data);
        while($row=mysqli_fetch_array($data)){
            echo "<tr>";
                for($i=0;$i<$nkolom;$i++){
                    echo "<td>$row[$i]</td>";
                }
            echo "</tr>";
        }
    }
?>

    <?php
        // print_r($_POST);
        $jumlah=-1;
        if(isset($_POST['query'])){
            $query=$_POST['query'];
            // die($query);
            // $jumlah=getonedata("select count(1) from ($query)");
            $jumlah=111;//getonedata("select count(1) from ($query)");

            $limit=5;
            if(!isset($_GET['hal'])){
                $current=1;
            }else{
                $current=$_GET['hal'];
            }
            $offset=$limit * ($current-1);
            $before=$current-1;
            if($before<=0) $before=1;
            $next=$current+1;
            $last=ceil($jumlah/$limit);
        }else{
            $query="";
        }

    ?>

<script>
 
 function f1(query){
    qs("[name=query]").value=query
    document.forms.formsubmit.submit();

 }
    function peserta(){
        f1("select (select nama from event where id=view_golongan.idevent) as event,peserta.nik,peserta.nama from peserta inner join view_golongan on view_golongan.idgolongan=peserta.idgolongan");
    }
    function fake(){
        f1("select * from _hapus");
    }
    function hakim(){
        f1("select nik,nama,idbidang,(select nama from bidang where id=hakim.idbidang) as bidang from hakim");
    }
    function nilai_bidang(){
        f1("select * from nilai_bidang");
    }
  
</script>

<head></head>


<style>
    .all{
        display: flex;
        flex-direction: column;
        gap: 22px;
    }
    .all a{
        border-radius:2px;
        text-decoration:none;
        color:white;
        background-color:black;
        padding:11px;
        display:inline-block;
    }
    .tombol{
        margin-top:11px;
    }
</style>

<div class="all vmaxwidth centermargin">
    <div class="row1">pilih data</div>
    
    
    <div class="pilih">
        <input class="button padding" type="button" value="peserta" onclick=peserta()>
        <input class="button padding" type="button" value="hakim" onclick=hakim()>
        <input class="button padding" type="button" value="nilai bidang" onclick=nilai_bidang()>
    </div>


    <div class="data">
        <?php
            if($query!=""){

        ?>
        <table class="table widthfull">
            <?php 
                echo setdata($query,$limit,$offset);
            ?>
        </table>
        <!-- <div class="tombol">
            <a href="?page=utama&page2=allthetime&hal=1">first</a>
            <a href="?page=utama&page2=allthetime&hal=<?php echo $before ?>">before</a>
            <a href="?page=utama&page2=allthetime&hal=<?php echo $next ?>">next</a>
            <a href="?page=utama&page2=allthetime&hal=<?php echo $last ?>">last</a>
        </div> -->
        <?php
            }
        ?>
    </div>

</div>

<form method=post name=formsubmit action="?page=utama&page2=allthetime">
    <input type="hidden" name="query">
</form>