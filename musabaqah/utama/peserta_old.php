<?php
//masih digunakan, karena ada upload dolumen dst
    //print_r($_POST);

    $folderfoto="../assets/images/";
    function getlinkfoto($id){
        return $folderfoto="../assets/documents/foto/peserta$id"."_foto.jpg";
    }

    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapusdata($crudid);
        if($mode=="tambah") tambahdata();
        if($mode=="edit") tambahdata($crudid);
    }
    function hapusdata($id){
        $query="delete from peserta where id=$id";
        execute($query);
        //hapus foto
        unlink(getlinkfoto($id));
    }
    function tambahdata($id=""){
        $nik=$_POST['nik'];
        $idwilayah=$_POST['idwilayah'];
        $nama=$_POST['nama'];
        $alamat=$_POST['alamat'];
        if($id==""){
            $query="insert into peserta(idwilayah,nik,nama,alamat) values('$idwilayah','$nik','$nama','$alamat')";
        }else{
            $query="update peserta set idwilayah='$idwilayah',nik='$nik',nama='$nama',alamat='$alamat' where id=$id";
        }
        execute($query);
    }

    function getdokumen($id){
        $arr=array("mandat","kk","akte","piagam","cv");
        $return="";
        foreach ($arr as $item) {
            $link="../assets/documents/pdf/peserta$id"."_$item.pdf";
            if(file_exists($link)){
                $return.="<a class=aa href=$link target=_blank>$item</a><br>";
            }else{
                $return.=$item." (kosong)<br>";
            }
        }
        return $return;
    }

    function tampildata(){
        $query="select *,(select nama from wilayah where id=idwilayah) as wilayah from peserta";
        $mquery=getdata($query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nik=$row['nik'];
            $idwilayah=$row['idwilayah'];
            $wilayah=$row['wilayah'];
            $nama=$row['nama'];
            $alamat=$row['alamat'];
            $dokumen=getdokumen($id);
            echo("<tr>
                <td><img style='width:88px;height:auto' width=0 height=0 src='".getlinkfoto($id)."'></td>
                <td>$dokumen</td>
                <td id=tdnik$id>$nik</td>
                <td id=tdwilayah$id info=$idwilayah>$wilayah</td>
                <td id=tdnama$id>$nama</td>
                <td id=tdalamat$id>$alamat</td>
                <td><a href=?page=utama&page2=pesertaunggah&idpeserta=$id>unggah</a></td>
                <td><input type=button onclick='edit($id)' value=edit></td>
                <td><input type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

    function getwilayah(){
        $query="select * from wilayah";
        $mquery=getdata($query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            echo("<option value='$id'>$nama</option>\n");
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
        if(isopen){
            gi("popup").style.display="flex";
        }else{
            gi("popup").style.display="none";
        }
        
    }

    function tambah(){
        gi("crud").value="tambah";

        gi("nik").value="";
        gi("nama").value="";
        gi("alamat").value="";
        gi("wilayah").value="";

        popup(true);        
    }
    function edit(id){
        gi("crud").value="edit";
        gi("crudid").value=id;
        gi("nik").value=gi("tdnik"+id).innerText;
        gi("nama").value=gi("tdnama"+id).innerText;
        gi("alamat").value=gi("tdalamat"+id).innerText;
        gi("wilayah").value=gi("tdwilayah"+id).getAttribute("info");
        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data peserta</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
            <th>foto</th>
            <th>dokumen</th>
            <th>nik</th>
            <th>wilayah</th>
            <th>nama</th>
            <th>alamat</th>
            <th class=thtombol>dokumen</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampildata();?>
    </table>
</div>


<style>
    .aa{
        background-color:unset;
        text-decoration:underline;
        color:blue;
        padding:unset;
        border-radius:unset;
    }
</style>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud enctype="multipart/form-data">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            nik
            <input type="text" name="nik" placeholder="input nik" required id=nik>
            wilayah
            <select name="idwilayah" id="wilayah" required><?php echo getwilayah();?></select>
            nama<input type="text" name="nama" placeholder="input nama" required id=nama>
            alamat<input type="text" name="alamat" placeholder="input alamat" required id=alamat>
            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>

