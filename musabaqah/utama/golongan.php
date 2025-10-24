<?php

    // print_r($_POST);

    $idcabang=$_GET['idcabang'];
    if($idcabang=="") die("ada error");

    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }
    
    function ekse($query){
        execute($query);
    }
    function hapus($id){
        $query="delete from golongan where id=$id";
        ekse($query);
    }

    function update($id=""){
        $nama=$_POST['nama'];
        $idcabang=$GLOBALS['idcabang'];
        $idbabak=$_POST['idbabak'];
        $waktupersiapan=$_POST['waktupersiapan'];
        $waktupenilaian=$_POST['waktupenilaian'];
        $waktumenjelang=$_POST['waktumenjelang'];
        $waktuhabis=$_POST['waktuhabis'];
        $isbobot=$_POST['isbobot'];
        $isunsur=$_POST['isunsur'];
        $istopik=$_POST['istopik'];
        $ispewaktu=$_POST['ispewaktu'];
        $kodemenu_link=$_POST['kodemenu_link'];
        
        $arrkey= ["nama","idbabak","idcabang",
                    "waktupersiapan","waktupenilaian",
                    "waktumenjelang","waktuhabis","isbobot","isunsur","istopik","ispewaktu","kodemenu_link"];
        $arrvalue=[$nama,$idbabak,$idcabang,
                    $waktupersiapan,$waktupenilaian,
                    $waktumenjelang,$waktuhabis,$isbobot,$isunsur,$istopik,$ispewaktu,$kodemenu_link];

        if($id==""){
                dbinsert("golongan",$arrkey,$arrvalue);
        }else{

            dbupdate("golongan",$arrkey,$arrvalue," where id=$id");
            
        }
    }

    function tampil($idcabang){
        //$query="select * from golongan where idcabang=$idcabang";
        $query="select *,
        (select nama from menu_link where kode=golongan.kodemenu_link) as namamenu_link,
        (select nama from babak where id=golongan.idbabak) as babak,
        (select count(1) from peserta where idgolongan=golongan.id) as jmlpeserta,
        (select count(1) from bidang where idgolongan=golongan.id) as jmlbidang from golongan
        
        where idcabang=$idcabang";
        $mquery=getdata($query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $idbabak=$row['idbabak'];
            $babak=$row['babak'];
            $nama=$row['nama'];
            $waktupersiapan=$row['waktupersiapan'];
            $waktupenilaian=$row['waktupenilaian'];
            $waktumenjelang=$row['waktumenjelang'];
            $waktuhabis=$row['waktuhabis'];
            $jmlpeserta=$row['jmlpeserta'];
            $jmlbidang=$row['jmlbidang'];
            $isbobot=$row['isbobot'];
            $isunsur=$row['isunsur'];
            $istopik=$row['istopik'];
            $ispewaktu=$row['ispewaktu'];

            print("<tr>
                <td id=tdnama$id>$nama</td>
                <td id=tdidbabak$id id2=$idbabak>$babak</td>
                <td id=tdwaktupersiapan$id>$waktupersiapan</td>
                <td id=tdwaktupenilaian$id>$waktupenilaian</td>
                <td id=tdwaktumenjelang$id>$waktumenjelang</td>
                <td id=tdwaktuhabis$id>$waktuhabis</td>
                <td id=tdjmlpeserta$id><div class=badge>$jmlpeserta</div><a href=?page=utama&page2=peserta&idgolongan=$id>manage</a></td>
                <td id=tdjmlbidang$id><div class=badge>$jmlbidang</div><a href=?page=utama&page2=bidang&idgolongan=$id>manage</a></td>
                <td id=tdisbobot$id>$isbobot</td>
                <td id=tdisunsur$id>$isunsur</td>
                <td id=tdistopik$id>$istopik</td>
                <td id=tdispewaktu$id>$ispewaktu</td>
                <td id=tdkodemenu_link$id info=$row[kodemenu_link]>$row[namamenu_link]</td>
                <td><input class='button padding' type=button onclick='edit($id)' value=edit></td>
                <td><input class='button padding' type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>


<script>

    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) {
            return;
        }
        gi("crud").value = "hapus";
        gi("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if(isopen){
            gi("popup").style.display="flex";
        }else{
            gi("popup").style.display="none";
        }
        
    }

    function tambah(){
        gi("crud").value="tambah";

        gi("nama").value="";
        gi("idbabak").value="";
        gi("waktupersiapan").value="";
        gi("waktupenilaian").value="";
        gi("waktumenjelang").value="";
        gi("waktuhabis").value="";
        gi("isbobot").value="";
        gi("isunsur").value="";
        gi("istopik").value="";
        gi("ispewaktu").value="";
        gi("kodemenu_link").value="";

        popup(true);        
    }
    function edit(id){
        gi("crud").value="edit";
        gi("crudid").value=id;
        gi("idbabak").value=gi("tdidbabak" + id).getAttribute("id2");
        gi("nama").value=gi("tdnama" + id).innerText;
        gi("waktupersiapan").value=gi("tdwaktupersiapan"+id).innerText;
        gi("waktupenilaian").value=gi("tdwaktupenilaian"+id).innerText;
        gi("waktumenjelang").value=gi("tdwaktumenjelang"+id).innerText;
        gi("waktuhabis").value=gi("tdwaktuhabis"+id).innerText;
        gi("isbobot").value=gi("tdisbobot"+id).innerText;
        gi("isunsur").value=gi("tdisunsur"+id).innerText;
        gi("istopik").value=gi("tdistopik"+id).innerText;
        gi("ispewaktu").value=gi("tdispewaktu"+id).innerText;
        // gi("kodemenu_link").value=gi("tdkodemenu_link"+id).innerText;
        gi("kodemenu_link").value=gi("tdkodemenu_link"+id).getAttribute("info");
        popup(true);

    }

    function cek(){
        if(gi("waktumenjelang").value>=gi("waktupenilaian").value){
            xalert("waktu menjelang harus kurang dari waktu penilaian");
            return false;        
        }else{
            return true;
        }
    }

</script>


<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>



<div class=judul>
    <h1>golongan</h1>
    <input class="button panel padding" type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
       		<th>golongan</th>
       		<th>babak</th>
       		<th>waktu persiapan</th>
       		<th>waktu penilaian</th>
       		<th>waktu menjelang habis</th>
       		<th>waktu habis</th>
       		<th>jumlah peserta</th>
       		<th>jumlah bidang</th>
       		<th>isbobot</th>
       		<th>isunsur</th>
       		<th>istopik</th>
       		<th>ispewaktu</th>
       		<th>kodemenulink</th>
            <th>edit</th>
            <th>hapus</th>
        </tr>
        <?php tampil($idcabang);?>
    </table>
</div>


<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    golongan
                    <input type="text" id=nama name="nama" required value>
                    babak
                    <select name="idbabak" id=idbabak required>
                        <option value="">-- pilih babak --</option>
                        <?php
                            include_once "dbku.php";
                            $data=getdata("select * from babak");
                            while($row=mysqli_fetch_array($data)){
                                $idbabak=$row['id'];
                                $nama=$row['nama'];
                                print "<option value=$idbabak>$nama</option>\n";
                            }
                        ?>
                    </select>
                    isbobot
                    <input type="number" name="isbobot" id="isbobot" min=0 max=1>
                    ispewaktu
                    <input type="number" name="isunsur" id="isunsur" min=0 max=1>
                    istopik
                    <input type="number" name="istopik" id="istopik" min=0 max=1>
                    ispewaktu
                    <input type="number" name="ispewaktu" id="ispewaktu" min=0 max=1>
                    waktu persiapan<input type="number" name="waktupersiapan" id="waktupersiapan" required>
                    waktu penilaian<input type="number" name="waktupenilaian" id="waktupenilaian" required>
                    waktu menjelang habis<input type="number" name="waktumenjelang" id="waktumenjelang" required>
                    waktu habis<input type="number" name="waktuhabis" id="waktuhabis" required>
                    <select name="kodemenu_link" id="kodemenu_link" required>
                        <option value="">-- pilih --</option>
                        <?php
                            $data=getdata("select kode,nama from menu_link");
                            while($row=mysqli_fetch_array($data)){
                                echo "<option value=$row[kode]>$row[nama]</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" name="cmdsimpan" value="simpan" onclick="return cek();">
                </div>
            </div>
        </form>

    </div>
</div>
