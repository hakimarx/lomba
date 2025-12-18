<?php
    function hapus($id){
        $query="delete from panitera where id=$id";
        execute($query);
    }

    function update($id){
        $nik=$_POST['nik'];
        $nama=$_POST['nama'];
        $alamat=$_POST['alamat'];
        $telepon=$_POST['telepon'];
        $pengalaman=$_POST['pengalaman'];
        if($id==""){
            $query="insert into panitera(nik,nama,alamat,telepon,pengalaman) 
            values('$nik','$nama','$alamat','$telepon','$pengalamn')";
        }else{

            $npwp="";
            $ktp="";
            if($_FILES['npwp']['tmp_name']!=""){
                $tmp_name=$_FILES['npwp']['tmp_name'];
                $tujuan="../assets/documents/panitera/npwp_$id.pdf";
                move_uploaded_file($tmp_name,$tujuan);
                $npwp=$tujuan;
            }
            if($_FILES['ktp']['tmp_name']!=""){
                $tmp_name=$_FILES['ktp']['tmp_name'];
                $tujuan="../assets/documents/panitera/ktp_$id.pdf";
                move_uploaded_file($tmp_name,$tujuan);
                $ktp=$tujuan;
            }

            $query="update panitera set 
                nik='$nik',
                nama='$nama',
                alamat='$alamat',
                telepon='$telepon',
                pengalaman='$pengalaman',
                npwp='$npwp',
                ktp='$ktp' where id=$id";
        }
        execute($query);
    }

    function tampil(){
        include_once __DIR__ . "/../koneksi.php";
        $query="select * from panitera ";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nik=$row['nik'];
            $nama=$row['nama'];
            $alamat=$row['alamat'];
            $telepon=$row['telepon'];
            $pengalaman=$row['pengalaman'];
            $npwp=$row['npwp'];
            $ktp=$row['ktp'];

            if($npwp!=""){
                $npwp="<a target=_blank href='$npwp'>npwp</a>";
            }
            if($ktp!=""){
                $ktp="<a target=_blank href='$ktp'>ktp</a>";
            }

            print("<tr>
                <td id=tdnik$id>$nik</td>
                <td id=tdnama$id>$nama</td>
                <td id=tdalamat$id>$alamat</td>
                <td id=tdtelepon$id>$telepon</td>
                <td id=tdpengalaman$id>$pengalaman</td>
                <td id=npwp$id>$npwp</td>
                <td id=ktp$id>$ktp</td>
                <td><input type=button onclick='edit($id)' value=edit></td>
                <td><input type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>


<?php
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }

    
    openfor(["role3","role2"]);

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
        gi("dokumen").style.display="none";
        gi("crud").value="tambah";

        gi("nik").value="";
        gi("nama").value="";
        gi("alamat").value="";

        popup(true);        
    }
    function edit(id){
        gi("dokumen").style.display="unset";
        gi("crud").value="edit";
        gi("crudid").value=id;

        gi("nik").value=gi("tdnik"+id).innerText;
        gi("nama").value=gi("tdnama"+id).innerText;
        gi("alamat").value=gi("tdalamat"+id).innerText;
        gi("telepon").value=gi("tdtelepon"+id).innerText;
        gi("pengalaman").value=gi("tdpengalaman"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data panitera</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
       	    <th>nik</th>
            <th>nama</th>
            <th>alamat</th>
            <th>telepon</th>
            <th>pengalaman</th>
            <th>npwp</th>
            <th>ktp</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>


<style>
</style>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud enctype="multipart/form-data">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">

            <div class="isi">
                nik<input type="text" name="nik" id="nik" required>
                nama<input type="text" name="nama" id="nama" required>
                alamat<input type="text" name="alamat" id="alamat" required>
                telepon<input type="text" name="telepon" id="telepon" required>
                pengalaman<input type="text" name="pengalaman" id="pengalaman" required>
                <div id="dokumen">
                    npwp<input type="file" name="npwp" id="npwp" accept="application/pdf">
                    ktp<input type="file" name="ktp" id="ktp" accept="application/pdf">
                </div>
            </div>

            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>
