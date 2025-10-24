<?php
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }
    
    function ekse($query){
        include "koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from babak where id=$id";
        ekse($query);
    }

    function update($id=""){
    	$nama=$_POST['nama'];
        if($id==""){    //tambah
            $query="insert into babak(nama) values('$nama')";
        }else{          //edit
            $query="update babak set nama='$nama' where id=$id";
        }
        ekse($query);
    }

    function tampil(){
        include "koneksi.php";
        $query="select * from babak ";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $nama=$row['nama'];
            print("<tr>
                <td id=tdnama$id>$nama</td>
                <td><input class='button padding' type=button onclick='edit($id)' value=edit></td>
                <td><input class='button padding' type=button value=hapus onclick='hapus($id);'></td>
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

        gi("nama").value=gi("tdnama" + id).innerText;

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class="vmaxwidth centermargin">
    <h1>data babak</h1>
    <input class="panel button padding" type="button" value="tambah data" onclick=tambah()>
    <table class="table widthfull">
        <tr>
            <th>babak</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>


<style>
</style>

<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    babak
                    <input type="text" name="nama" id="nama">
                </div>
                <div class=footer>
                    <input class='button padding' type="button" value="batal" onclick=popup(false)>
                    <input class='button padding' type="submit" value="simpan">
                </div>
                
            </div>
        </form>

    </div>
</div>