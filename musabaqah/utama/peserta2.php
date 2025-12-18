<?php
    //print_r($_POST);
    if(!isset($_GET['idgolongan'])) die("ada error");
    $idgolongan=$_GET['idgolongan'];

    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
    }
    
    function ekse($query){
        include_once __DIR__ . "/../koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from peserta2 where id=$id";
        ekse($query);
    }

    function update($id=""){
    	$idpesertas=$_POST['idpeserta'];
        $idgolongan=$GLOBALS['idgolongan'];
        if($id==""){    //tambah
            foreach ($idpesertas as $item) {
                
                $query="insert into peserta2(idpeserta,idgolongan) values('$item','$idgolongan');";
                ekse($query);
            }
        }
    }

    function tampil(){
        $idgolongan=$GLOBALS['idgolongan'];

        include_once __DIR__ . "/../koneksi.php";
        $query="select *,(select nama from peserta where id=peserta2.idpeserta) as nama from peserta2 where idgolongan=$idgolongan";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            print("<tr>
                <td id=tdnama$id>$nama</td>
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
        //_ubah_ ge("nama").value="";
        popup(true);
    }
    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        //_ubah_ ge("nama").value=ge("tdnama"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data peserta2</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>
<div style="overflow:auto">
    <table class=table>
        <tr>
            <th>nama</th>
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
	    
            <select name="idpeserta[]" id="" multiple>
                <?php
                    $data=getdata("select * from peserta");
                    while($row=mysqli_fetch_array($data)){
                        $idpeserta=$row['id'];
                        $nama=$row['nama'];
                        print "<option value=$idpeserta>$nama</option>";                        
                    }
                ?>
            </select>
            <br><br><h6>*gunakan ctrl/shift untuk memilih banyak</h6><br>
            <div class=tombol>
            	<input type="button" value="batal" onclick=popup(false)>
            	<input type="submit" value="simpan">
	    </div>
        </form>

    </div>
</div>