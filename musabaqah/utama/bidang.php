<?php

    //print_r($_POST);
    if(!isset($_GET['idgolongan'])) die("ada error");
    $idgolongan=$_GET['idgolongan'];


    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }
    
    function ekse($query){
        include_once __DIR__ . "/../koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from bidang where id=$id";
        ekse($query);
    }

    function update($id=""){
        $idgolongan=$GLOBALS['idgolongan'];
    	$nama=$_POST['nama'];
        if($id==""){    //tambah
            $query="insert into bidang(nama,idgolongan) values('$nama','$idgolongan')";
        }else{          //edit
            $query="update bidang set nama='$nama' where id=$id";
        }
        ekse($query);
    }

    function tampil(){
        $idgolongan=$GLOBALS['idgolongan'];
        include_once __DIR__ . "/../koneksi.php";
        $query="select *,
        (select count(1) from hakim where idbidang=bidang.id) as jmlhakim
          from bidang where idgolongan=$idgolongan";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            $jmlhakim=$row['jmlhakim'];
            print("<tr>
                <td id=tdnama$id>$nama</td>
                <td><div class=notif>$jmlhakim</div><a href=?page=utama&page2=hakim&idbidang=$id>manage</a></td>
                <td><input type=button onclick='edit($id)' value=edit></td>
                <td><input type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>

<script>

    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) return;

        gi("crud").value = "hapus";
        gi("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if (isopen) {
            gi("popup").style.display = "flex";
        } else {
            gi("popup").style.display = "none";
        }

    }

    function tambah() {
        gi("crud").value = "tambah";
        gi("nama").value="";
        popup(true);
    }
    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        gi("nama").value=gi("tdnama"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data bidang</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
            <th>bidang</th>
            <th>hakim</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
	        bidang<input type="text" name="nama" id="nama" required>

            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>