<?php
    include "../../musabaqah/dbku.php";

    function setdata(){
        if(!isset($_GET['table'])) return;

        $tabel=$_GET['table'];
        $data=getdata("select * from $tabel");
        $fieldinfo = mysqli_fetch_fields($data);
        $nkolom=0;

        //judul
        $judul="<tr>";
        foreach ($fieldinfo as $item) {
            $judul.="<th>$item->name</th>";
            $nkolom++;
        }
        $judul.="</tr>";

        //isi
        $isi="";

        while($row=mysqli_fetch_array($data)){
            $isi.="<tr>";
            for($i=0;$i<$nkolom;$i++){
                $item=$row[$i];
                $isi.="<td>$item</td>";
            }
            $isi.="</tr>\n";
        }
        echo "displaying '$tabel'<hr>";
        echo "<table class=table>
                $judul
                $isi
                </table>
                ";

    }

?>
	<link rel="stylesheet" href="../css/tema.css?4">


<script>

    function ubah(o){
        let kata=o.options[o.selectedIndex].text;
        document.getElementById("vinput").value=kata;
        document.forms.fsimpan.submit();
        //console.log();
    }

</script>
<style>
    * {
        font-face: arial;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        text-align: center;
    }

    body {
        padding: 22px;
    }

    th,
    td {
        border: 1px solid;
        padding: 11px;
    }

    table {
        border-collapse: collapse;
    }

    hr {
        margin: 22px 0;
    }

    button {
        padding: 11px;
        width: 111px;
    }

    input {
        padding: 11px;
        width: 77%;
    }
</style>
<form name=fsimpan action="" method=get>
    <input type="hidden" name="table" id=vinput>
</form>
<div style="display: flex;gap: 11px;">
    <div style="flex-basis: 125px;">
        pilih table
        <select style="width: 100%;height: 444px;" size="4" onchange="ubah(this);">
            <?php
                $db=$GLOBALS['db'];
                $query="select * from information_schema.tables where table_schema='$db'";
                $data=getdata($query);
                while($row=mysqli_fetch_array($data)){
                    $kata=$row[2];
                    echo "<option>$kata</option>";
                }
            ?>

        </select>
    </div>
    <div style="flex: 1;overflow: auto;" >
        <?php setdata(); ?>
    </div>
</div>