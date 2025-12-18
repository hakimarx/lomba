<?php
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
        $query="delete from wilayah where id=$id";
        ekse($query);
    }

    function update($id=""){
    	$nama=$_POST['nama'];
        if($id==""){    //tambah
            $query="insert into wilayah(nama) values('$nama')";
        }else{          //edit
            $query="update wilayah set nama='$nama' where id=$id";
        }
        ekse($query);
    }

    function tampil(){
        include_once __DIR__ . "/../koneksi.php";
        $query="select * from wilayah";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            print("<tr>
                <td id=tdnama$id>$nama</td>
                <td><input type=button onclick='edit($id)' value=edit></td>
                <td><input type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>

<script>

    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) return;

        document.getElementById("crud").value = "hapus";
        document.getElementById("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if (isopen) {
            document.getElementById("popup").style.display = "flex";
        } else {
            document.getElementById("popup").style.display = "none";
        }

    }

    function tambah() {
        document.getElementById("crud").value = "tambah";
        gi("nama").value="";
        popup(true);
    }
    function edit(id) {
        document.getElementById("crud").value = "edit";
        document.getElementById("crudid").value = id;

        gi("nama").value=gi("tdnama"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data wilayah</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table>
        <tr>
            <th>wilayah</th>
            <th>edit</th>
            <th>nama</th>
        </tr>
        <?php tampil();?>
    </table>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
	    nama    
            <input type="text" name="nama" id="nama" required>

            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>