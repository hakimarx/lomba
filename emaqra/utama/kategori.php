<?php
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") tambah();
        if($mode=="edit") edit($crudid);
    }
    
    function ekse($query){
        include "koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from kategori where id=$id";
        ekse($query);
    }

    function tambah(){
        $nama=$_POST['nama'];
        $juzawal=$_POST['juzawal'];
        $juzakhir=$_POST['juzakhir'];
        $jmlsoal=$_POST['jmlsoal'];
        $query="insert into kategori(nama,juzawal,juzakhir,jmlsoal) values('$nama','$juzawal','$juzakhir','$jmlsoal')";
        ekse($query);
    }

    function edit($id){
    	$nama=$_POST['nama'];
        $juzawal=$_POST['juzawal'];
        $juzakhir=$_POST['juzakhir'];
        $jmlsoal=$_POST['jmlsoal'];
        $query="update kategori set nama='$nama',juzawal='$juzawal',juzakhir='$juzakhir',jmlsoal='$jmlsoal' where id=$id";
        ekse($query);
    }

    function tampil(){
        include "koneksi.php";
        $query="select * from kategori ";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            $juzawal=$row['juzawal'];
            $juzakhir=$row['juzakhir'];
            $jmlsoal=$row['jmlsoal'];
            print("<tr>
                <td class=padding id=tdnama$id>$nama</td>
                <td id=tdjuzawal$id>$juzawal</td>
                <td id=tdjuzakhir$id>$juzakhir</td>
                <td id=tdjmlsoal$id>$jmlsoal</td>
                <td><input class='button padding' type=button onclick='edit($id)' value=edit></td>
                <td><input class='button padding' type=button value=hapus onclick='hapus($id);'></td>
            </tr>\n");
        }
    }

?>

<script>

    function hapus(id) {

        xconfirm("yakin ingin menghapus data ?",()=>{
            document.getElementById("crud").value = "hapus";
            document.getElementById("crudid").value = id;
            document.forms['formcrud'].submit();
        });
    }

    function tambah(){
        document.getElementById("crud").value="tambah";

        gi("nama").value="";
        gi("juzawal").value="";
        gi("juzakhir").value="";
        gi("jmlsoal").value="";
        popup(true);        
    }
    function edit(id){
        document.getElementById("crud").value="edit";
        document.getElementById("crudid").value=id;

        gi("nama").value=gi("tdnama"+id).innerText;
        gi("juzawal").value=gi("tdjuzawal"+id).innerText;
        gi("juzakhir").value=gi("tdjuzakhir"+id).innerText;
        gi("jmlsoal").value=gi("tdjmlsoal"+id).innerText;

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/popup.css">
    <script src="../global/js/popup.js"></script>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class="vmaxwidth centermargin">
    <h1>kategori</h1>
    <input type="button" class="button padding" value="tambah data" onclick="tambah()" style="  margin-bottom: 11px;">
    <table class="table widthfull">
        <tr class="fontsize2">
       		<th class=padding>kategori</th>
            <th>juz awal</th>
            <th>juz akhir</th>
            <th>jumlah soal</th>
            <th>edit</th>
            <th>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>



<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <div class="isian">
                <div class="isi">
                    <input type="hidden" name="crud" id="crud">
                    <input type="hidden" name="crudid" id="crudid">
                    kategori
                    <input type="text" name="nama" id="nama" required>
                    juz awal
                    <input type="number" name="juzawal" id="juzawal" value=0 required>
                    juz akhir
                    <input type="number" name="juzakhir" id="juzakhir" value=0 required>
                    jumlah soal
                    <input type="number" name="jmlsoal" id="jmlsoal" value=0 required>
                </div>
                <div class="footer">
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" name="cmdsimpan" value="simpan">
                </div>
            </div>
        </form>

    </div>
</div>
