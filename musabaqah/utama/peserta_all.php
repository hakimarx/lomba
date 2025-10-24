<?php
    function fillpeserta(){
        $data=getdata("select id,nama from peserta");
        while($row=mysqli_fetch_array($data)){
            echo "<option value=$row[id]>$row[nama]</option>";
        }
    }

    function filltable($idpeserta){
        $q="select 
            idnilai,
            peserta.nama as peserta,
            event.nama as event,
            cabang.nama as cabang,
            golongan.nama as golongan
            from view_nilai
            inner join peserta on peserta.id=idpeserta
            inner join event on event.id=view_nilai.idevent
            inner join cabang on cabang.id=idcabang
            inner join golongan on golongan.id=view_nilai.idgolongan
            where idpeserta=$idpeserta
            group by view_nilai.idevent";
        $data=getdata($q);
        while($row=mysqli_fetch_array($data)){
            echo "<tr>
                <td>$row[event]</td>
                <td>$row[cabang]</td>
                <td>$row[golongan]</td>
                <td><a target=_blank href=?page=cetak&idnilai=$row[idnilai]>lihat nilai</a></td>
                </tr>";
        }
    }

?>


<script>
    function pilih(e){
        let index=e.options.selectedIndex;
        let idpeserta=e.options[index].value;
        qs("[name=idpeserta]").value=idpeserta;
        document.forms.form1.submit();
    }
</script>


<style>
.tmp{
    width: 222px!important;
}
</style>

<div class="tmp vmaxwidth centermargin">
    <h4>peserta</h4>
    <select class="padding widthfull " onchange=pilih(this)>
        <option value="">-- pilih --</option>
        <?php fillpeserta();?>
    </select>
</div>

<form name=form1 method="post">
    <input type="hidden" name="idpeserta">
</form>




<?php
    if(!isset($_POST['idpeserta'])){
        return;
    }
    $idpeserta=$_POST['idpeserta'];
    $row=getonebaris("select * from peserta where id=$idpeserta");
?>

nama : <?php echo $row['nama'];?>
<hr>
event yg pernah diikuti
<hr>

<table class=table>
    <tr>
        <th>event</th>
        <th>cabang</th>
        <th>golongan</th>
        <th>nilai</th>
    </tr>
    <?php filltable($idpeserta);?>
</table>

