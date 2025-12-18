<?php

    function ekse($query){
        include_once __DIR__ . "/../koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from hakim where id=$id";
        ekse($query);
    }

    function cekfile($param,$id){
        if($_FILES[$param]['tmp_name']!=""){
                $tujuan="../assets/documents/hakim/$param"."_"."$id.pdf";
                $tmp_name=$_FILES[$param]['tmp_name'];
                move_uploaded_file($tmp_name,$tujuan);
                return $tujuan;
        }else{
            return "";
        }

    }
    function update($id=""){

        $nama=$_POST['nama'];
    	$nik=$_POST['nik'];
        if($id==""){
            dbinsert("hakim",["nama","nik","idbidang"],[$nama,$nik,$_GET['idbidang']]);
        }else{

            $ktp=cekfile("ktp",$id);          
            $npwp=cekfile("npwp",$id);
            $bukurek=cekfile("bukurek",$id);
            $sertifikatkejuaraan=cekfile("sertifikatkejuaraan",$id);
            $sertifikatpelatihanhakim=cekfile("sertifikatpelatihanhakim",$id);
            $sertifikatmenjadihakim=cekfile("sertifikatmenjadihakim",$id);
            
             dbupdate("hakim",
                ["nama","nik","ktp","npwp","bukurek","sertifikatkejuaraan","sertifikatpelatihanhakim","sertifikatmenjadihakim"],
                [$nama,$nik,$ktp,$npwp,$bukurek,$sertifikatkejuaraan,$sertifikatpelatihanhakim,$sertifikatmenjadihakim],
                "where id=$id");

            $idhakim=$id;

            if(isset($_POST['keahlians'])){
                $keahlians=$_POST['keahlians'];
                //delete dulu
                execute("delete from hakim_keahlian where idhakim=$idhakim");
                foreach ($keahlians as $item) {
                    dbinsert("hakim_keahlian",
                            ["idhakim","idkeahlian"],
                            [$idhakim,$item]);
                }

            }

        }   


    }

    function getkeahlian($id){
        $hasil="";
        $data=getdata("select * from hakim_keahlian where idhakim=$id");
        while($rowkeahlian=mysqli_fetch_array($data)){
            $idkeahlian=$rowkeahlian['idkeahlian'];
            $keahlian=getonedata("select nama from keahlian where id=$idkeahlian");
            $hasil.="$keahlian, ";
        }
        return $hasil;
    }
    function tampil(){
        $idbidang=$GLOBALS['idbidang'];
        $query="select * from hakim where idbidang=$idbidang";
        $mquery=getdata($query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            $nik=$row['nik'];
            $ktp=$row['ktp'];
            if($ktp!="") $ktp="<a target=_blank href='$ktp'>ktp</a>";
            $npwp=$row['npwp'];
            if($npwp!="") $npwp="<a target=_blank href='$npwp'>npwp</a>";
            $bukurek=$row['bukurek'];
            if($bukurek!="") $bukurek="<a target=_blank href='$bukurek'>bukurek</a>";
            $sertifikatkejuaraan=$row['sertifikatkejuaraan'];
            if($sertifikatkejuaraan!="") $sertifikatkejuaraan="<a target=_blank href='$sertifikatkejuaraan'>sertifikatkejuaraan</a>";
            $sertifikatpelatihanhakim=$row['sertifikatpelatihanhakim'];
            if($sertifikatpelatihanhakim!="") $sertifikatpelatihanhakim="<a target=_blank href='$sertifikatpelatihanhakim'>sertifikatpelatihanhakim</a>";
            $sertifikatmenjadihakim=$row['sertifikatmenjadihakim'];
            if($sertifikatmenjadihakim!="") $sertifikatmenjadihakim="<a target=_blank href='$sertifikatmenjadihakim'>sertifikatmenjadihakim</a>";
            
            print("<tr>
                <td id=tdnama$id>$nama</td>
                <td id=tdnik$id>$nik</td>
                <td>$ktp</td>
                <td>$npwp</td>
                <td>$bukurek</td>
                <td>$sertifikatkejuaraan</td>
                <td>$sertifikatpelatihanhakim</td>
                <td>$sertifikatmenjadihakim</td>
                <td>". getkeahlian($id) ." </a></td>
                <td><input type=button onclick='edit($id)' value=edit></td>
                <td><input type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>
<?php

    //print_r($_POST);
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }
    
    $idbidang=$_GET['idbidang'];
    $bidang=getonedata("select nama from bidang where id=$idbidang");

?>

<script>

    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) {
            return;
        }
        document.getElementById("crud").value = "hapus";
        document.getElementById("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if(isopen){
            document.getElementById("popup").style.display="flex";
        }else{
            document.getElementById("popup").style.display="none";
        }
        
    }

    function tambah(){
        gi("edokumen").style.display="none";
        document.getElementById("crud").value="tambah";
        gi("nama").value="";
        gi("nik").value="";
        popup(true);        
    }
    function edit(id){
        gi("edokumen").style.display="unset";
        document.getElementById("crud").value="edit";
        document.getElementById("crudid").value=id;

        gi("nama").value=gi("tdnama"+id).innerText;
        gi("nik").value=gi("tdnik"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data hakim bidang <?php  echo $bidang ?></h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
       		<th>hakim</th>
       		<th>nik</th>
       		<th>ktp</th>
       		<th>npwp</th>
       		<th>bukurek</th>
       		<th>sertifikatkejuaraan</th>
       		<th>sertifikatpelatihanhakim</th>
       		<th>sertifikatmenjadihakim</th>
            <th class=thtombol>keahlian</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud enctype="multipart/form-data">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isi">
                hakim<input type="text" name="nama" id="nama" required>
                nik<input type="text" name="nik" id="nik" required>
                <div id="edokumen">
                    <div>pilih keahlian (shift / ctrl) buat multi select</div>
                    <select name="keahlians[]" id="" multiple>
                        <?php
                            $data=getdata("select * from keahlian");
                            while($row=mysqli_fetch_array($data)){
                                echo "<option value=$row[id]>$row[nama]</option>";
                            }
                        ?>
                    </select>
                    ktp<input type="file" name="ktp" id="" accept="application/pdf">
                    npwp<input type="file" name="npwp" id="" accept="application/pdf">
                    bukurek<input type="file" name="bukurek" id="" accept="application/pdf">
                    sertifikatkejuaraan<input type="file" name="sertifikatkejuaraan" id="" accept="application/pdf">
                    sertifikatpelatihanhakim<input type="file" name="sertifikatpelatihanhakim" id="" accept="application/pdf">
                    sertifikatmenjadihakim<input type="file" name="sertifikatmenjadihakim" id="" accept="application/pdf">

                </div>

            </div>
            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>

