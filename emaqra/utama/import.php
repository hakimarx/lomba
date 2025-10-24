<?php
function tuliskedb($vfile){
    require "../global/class/excelku.php";
    execute("delete from soal");
    $idbidang=getonedata("select min(id) from bidang");

    $array=exceltoarray($vfile);
    foreach ($array as $row) {
        $soal=$row[0];
        $jawaban=$row[1];
        $q="insert into soal(idbidang,soal,jawaban) values ('$idbidang','$soal','$jawaban')";
        // die($q);
        dbinsert("soal",['idbidang','soal','jawaban'],["$idbidang","$soal","$jawaban"]);
        // execute($q);
    }
    alert("import data selesai");

}

function setdata(){
    $data=getdata("select * from soal");
    while($row=mysqli_fetch_array($data)){
        print("<tr><td>$row[soal]</td><td>$row[jawaban]</td></tr>\n");
    }
    }
?>

<?php

    $tujuan="./_fake/hapus.hapus";

    if(isset($_FILES['vname'])){
        $asal=$_FILES['vname']['tmp_name'];
        // die($asal);
        move_uploaded_file($asal,$tujuan);
        tuliskedb($tujuan);
        // print_r($_FILES);
    }

?>

<style>
    form{
        display: flex;
        justify-content: space-between;
    }
    [type=file]{
       font-size: 22px;
    }
    .import{
        width: 100%;
        overflow:auto;
        height:777px;
    }
    td{
        white-space: unset!important;
    }
</style>

<div class="import vmaxwidth centermargin">
    <h1>impport soal</h1>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="vname" required="" accept=".xlsx">
    <input class="button padding" name="xx" type="submit" value="import sekarang">
  </form>
  <div class="panel">
    <i><b>note:data yang lama akan ditimpa dengan data yang baru</b></i>
  </div>
  <table class=table>
    <tr>
      <th class="padding">soal</th>
      <th>jawaban</th>
    </tr>
    <?php setdata();?>
  </table>

</div>