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
        $query="delete from soal where id=$id";
        ekse($query);
    }

    function update($id=""){
    	$soal=$_POST['soal'];
    	$jawaban=$_POST['jawaban'];

        if($id==""){    //tambah
            $query="insert into soal(soal,jawaban) values('$soal','$jawaban')";
        }else{          //edit
            $query="update soal set soal='$soal',jawaban='$jawaban' where id=$id";
        }
        ekse($query);
    }

    function tampil(){
        include_once __DIR__ . "/../koneksi.php";
        $query="select * from soal ";
        $mquery=mysqli_query($koneksi,$query);
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            $soal=$row['soal'];
            $jawaban=$row['jawaban'];
            print("<tr>
                <td id=tdsoal$id>$soal</td>
                <td id=tdjawaban$id>$jawaban</td>
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
        gi("soal").value="";
        gi("jawaban").value="";
        popup(true);
    }
    function edit(id) {
        document.getElementById("crud").value = "edit";
        document.getElementById("crudid").value = id;

        gi("soal").value=gi("tdsoal"+id).innerText;
        gi("jawaban").value=gi("tdjawaban"+id).innerText;

        popup(true);

    }
</script>

<head></head>

<div class=judul>
    <h1>data soal</h1>
    <input type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table>
        <tr>
            <th>soal</th>
            <th>jawaban</th>
            <th>edit</th>
            <th>hapus</th>
        </tr>
        <?php tampil();?>
    </table>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            soal<input type="text" name="soal" id="soal" required>
            jawaban<input type="text" name="jawaban" id="jawaban" required>

            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>