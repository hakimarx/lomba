

<?php

$judul="";
$isi="";
if(isset($_POST['query'])){
    $query=$_POST['query'];
    include "../../musabaqah/dbku.php";

    $data=getdata($query);
    $jmlkolom=mysqli_num_fields($data);
    for($i=0;$i<$jmlkolom;$i++){
        $item="judul$i";
        $judul.="<th>$item</th>";
    }
 


    while($row=mysqli_fetch_array($data)){
        $isi.="<tr>";
        for($i=0;$i<$jmlkolom;$i++){
            $item=$row[$i];
            $isi.="<td>$item</td>";
        }
        $isi.="</tr>\n";
    }


}

?>

<style>
    *{
        font-face:arial;
        margin:0;
        padding:0;
        box-sizing:border-box;
        text-align:center;
    }
    body{
        padding:22px;
    }
    th,td{
        border:1px solid;
        padding:11px;
    }
    table{
        border-collapse:collapse;
    }
    hr{
        margin:22px 0;
    }
    button{
        padding:11px;
        width: 111px;
    }

    input{
        padding:11px;
        width: 77%;
    }
</style>


<form action="" method=post>
    <input type="text" name="query" id="" required placeholder="masukkan query">
    <button value="lihat tabel">lihat</button>
</form>
<hr>

<table>

<tr>
    <?php echo $judul;?>
</tr>
<?php echo $isi;?>

</table>
