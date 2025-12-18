<?php
    function filltd($idgolongan){
        $data=getdata("select
        (select nama from gender where id=peserta.idgender) as gender,
        (select nama from wilayah where id=peserta.idwilayah) as wilayah,
        (select nama from kafilah where id=peserta.idkafilah) as kafilah,
        peserta.* from peserta where idgolongan='$idgolongan'");
        while($row=mysqli_fetch_array($data)){
            $vid=$row['id'];

            echo "<div class=none>
                    <div id=tdidgender$vid>$row[idgender]</div>
                    <div id=tdidwilayah$vid>$row[idwilayah]</div>
                    <div id=tdidkafilah$vid>$row[idkafilah]</div>
            </div>";

            echo "<tr>
                    <td><input class='button padding' type=button value=edit onclick=update($vid)></td>
                    <td><input class='button padding' type=button value=hapus onclick=hapus($vid)></td>
                    <td id=tdnik$vid>$row[nik]</td>
                    <td id=tdnama$vid>$row[nama]</td>
                    <td>$row[gender]</td>
                    <td>$row[wilayah]</td>
                    <td>$row[kafilah]</td>
                    <td>$row[alamat]</td>
                    <td>$row[kota]</td>
                    <td>$row[pob]</td>
                    <td>$row[dob]</td>
                    <td>$row[rt]</td>
                    <td>$row[rw]</td>
                    <td>$row[kelurahan]</td>
                    <td>$row[kecamatan]</td>
                    <td>$row[provinsi]</td>
                    <td>$row[kontak]</td>

                    </td>
                </tr>";
        }
    }

    function update($id){
        $idgolongan=$_GET['idgolongan'];

        $idgender=$_POST['idgender'];
        $idwilayah=$_POST['idwilayah'];
        $idkafilah=$_POST['idkafilah'];
        $nik=$_POST['nik'];
        $nama=$_POST['nama'];
        $alamat=$_POST['alamat'];
        $kota=$_POST['kota'];
        $pob=$_POST['pob'];
        $dob=$_POST['dob'];
        $rt=$_POST['rt'];
        $rw=$_POST['rw'];
        $kelurahan=$_POST['kelurahan'];
        $kecamatan=$_POST['kecamatan'];
        $provinsi=$_POST['provinsi'];
        $kontak=$_POST['kontak'];


        
        if($id==""){
            $q="insert into peserta 
            (idgolongan,idgender,idwilayah,idkafilah,nik,nama,
            alamat,kota,pob,dob,rt,rw,kelurahan,kecamatan,provinsi,kontak) values 
            ('$idgolongan','$idgender','$idwilayah','$idkafilah','$nik','$nama'
            ,'$alamat','$kota','$pob','$dob','$rt','$rw','$kelurahan','$kecamatan','$provinsi','$kontak')";
        }else{
            $q="";
        }
        execute($q);
    }

    function hapus($id){
        $q="delete from peserta where id=$id";
        execute($q);
    }
?>

<?php
    
    // cek simpan
    if(isset($_POST['update'])){
        update($_POST['update']);
    }
    //hapus
    if(isset($_POST['hapus'])){
        hapus($_POST['hapus']);
    }
?>

<script>
    function hapus(id){
        xconfirm("yakin hapus data?",()=>{
            formhapus.hapus.value=id;
            formhapus.submit();
        });
    }
    function update(id=""){
        modalshow();
        if(id==""){
            formupdate.nik.value="";
            formupdate.nama.value="";
            formupdate.idgender.value="";
            formupdate.idwilayah.value="";
            formupdate.idkafilah.value="";
            formupdate.alamat.value="";
            formupdate.kota.value="";
            formupdate.pob.value="";
            formupdate.dob.value="";
            formupdate.rt.value="";
            formupdate.rw.value="";
            formupdate.kelurahan.value="";
            formupdate.kecamatan.value="";
            formupdate.provinsi.value="";
            formupdate.kontak.value="";


        }else{
            formupdate.nik.value=this["tdnik"+id].innerText;
            formupdate.nama.value=this["tdnama"+id].innerText;
            formupdate.idgender.value=this["tdidgender"+id].innerText;
            formupdate.idwilayah.value=this["tdidwilayah"+id].innerText;
            formupdate.idkafilah.value=this["tdidkafilah"+id].innerText;
            formupdate.alamat.value=this["alamat"+id].innerText;
            formupdate.kota.value=this["kota"+id].innerText;
            formupdate.pob.value=this["pob"+id].innerText;
            formupdate.dob.value=this["dob"+id].innerText;
            formupdate.rt.value=this["rt"+id].innerText;
            formupdate.rw.value=this["rw"+id].innerText;
            formupdate.kelurahan.value=this["kelurahan"+id].innerText;
            formupdate.kecamatan.value=this["kecamatan"+id].innerText;
            formupdate.provinsi.value=this["provinsi"+id].innerText;
            formupdate.kontak.value=this["kontak"+id].innerText;


        }
    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>


<!-- lihat data -->

<input class="panel button padding" type="button" value="tambah" onclick="update();">

<table class="table widthfull">
    <tr>
        <th>edit</th>
        <th>hapus</th>
        <th>nik</th>
        <th>nama</th>
        <th>gender</th>
        <th>wilayah</th>
        <th>kafilah</th>
        <th>alamat</th>
        <th>kota </th>
        <th>pob</th>
        <th>dob</th>
        <th>rt</th>
        <th>rw</th>
        <th>kelurahan</th>
        <th>kecamatan</th>
        <th>provinsi</th>
        <th>kontak</th>

    </tr>
    <?php echo filltd($_GET['idgolongan']); ?>
</table>

<!-- isi data  -->
 

<div class="modal">
    <div class="box">
        <form action="" method="post" name="formupdate">
            <input type="hidden" name="update">
            <div class="isian border card">
                <div class="isi">
                        gender
                        <select name="idgender" required>
                            <option value="">-- pilih --</option>
                            <?php filloption("select * from gender","id","nama");?>
                        </select>
                        wilayah
                        <select name="idwilayah" required>
                            <option value="">-- pilih --</option>
                            <?php filloption("select * from wilayah","id","nama");?>
                        </select>
                        kafilah
                        <select name="idkafilah" required>
                            <option value="">-- pilih --</option>
                            <?php filloption("select * from kafilah","id","nama");?>
                        </select>

                        nik
                        <input type="text" name="nik" required>
                        nama
                        <input type="text" name="nama" required>

                        alamat
                        <input type="text" name="alamat" required>
                        kota 
                        <input type="text" name="kota" required>

                        pob
                        <input type="text" name="pob" required>
                        dob
                        <input type="date" name="dob" required>
                        rt
                        <input type="text" name="rt" required>
                        rw
                        <input type="text" name="rw" required>
                        kelurahan
                        <input type="text" name="kelurahan" required>
                        kecamatan
                        <input type="text" name="kecamatan" required>
                        provinsi
                        <input type="text" name="provinsi" required>
                        kontak
                        <input type="text" name="kontak" required>

                </div>
                <div class="footer">
                    <input class="button padding" type="button" value="batal" onclick="modalhide()">
                    <input class="button padding" type="submit" value="simpan">
                </div>
            </div>
        </form>
    </div>
</div>


<form name=formhapus method="post">
    <input type="hidden" name="hapus">
</form>