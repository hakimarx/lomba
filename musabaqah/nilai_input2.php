<?php
    function fillpeserta(){
        $data=getdata("select * from peserta");
        while($row=mysqli_fetch_array($data)){
            echo "<option value=$row[id]>$row[nama]</option>";
        }
    }

    function showbidang($idgolongan){
        $databidang=getdata("select * from bidang where idgolongan=$idgolongan");
        while($rowbidang=mysqli_fetch_array($databidang)){
            $bidang=$rowbidang['nama'];
            $idbidang=$rowbidang['id'];
            ?>
            <table class="table">
                <tr>
                    <th colspan=2><?php echo $bidang?></td>
                </tr>
                <tr>
                    <td>hakim</td>
                    <td>nilai</td>

                </tr>
                <?php fillhakim($idbidang);?>
            </table>
            <?php
            // echo $bidang;
        }
    }

    function fillhakim($idbidang){
        $datahakim=getdata("select * from hakim where idbidang=$idbidang");
        while($rowhakim=mysqli_fetch_array($datahakim)){
            $namahakim=$rowhakim['nama'];
            echo "<tr>
                    <td>$namahakim</td>
                    <td><input type=text></td>
                </tr>\n";
        }
    }
?>

<?php
    $idgolongan=$_GET['idgolongan'];
    $viewg=getonebaris("select * from view_golongan where idgolongan=$idgolongan");

    $idcabang=$viewg['idcabang'];
    $idbabak=$viewg['idbabak'];

    $cabang=getonedata("select nama from cabang where id=$idcabang");
    $golongan=getonedata("select nama from golongan where id=$idgolongan");
    $babak=getonedata("select nama from babak where id=$idbabak");

?>

<script>
    function simpan(){
        if(selectpeserta.value==""){
            xalert("pilih peserta !");
            return;
            
        }
    }
</script>


<div class="panel">
    <div class="borderred padding centergrid inlineblock">
        <?php echo "$cabang,$golongan,$babak"?>;
    </div>


</div>

<select id="selectpeserta">
    <option value="">-- pilih peserta --</option>
    <?php echo fillpeserta()?>
</select>

<hr>

<div class="flex gap padding">
    <?php showbidang($idgolongan);?>
</div>


<input class="button padding panel" type="button" value="simpan data" onclick=simpan()>
