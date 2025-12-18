<?php

//print_r($_POST);
$idevent = $_GET['idevent'];
if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") update();
    if ($mode == "edit") update($crudid);
}

function hapus($id)
{
    $query = "delete from cabang where id=$id";
    execute($query);
}


function update($id = "")
{
    $idevent = $GLOBALS['idevent'];
    $idpenilaian = $_POST['idpenilaian'];
    $nama = $_POST['nama'];
    if ($id == "") {
        $query = "insert into cabang (nama,idevent,idpenilaian) values('$nama','$idevent','$idpenilaian')";
    } else {
        $query = "update cabang set nama='$nama',idpenilaian='$idpenilaian' where id=$id";
    }
    // die($query);
    execute($query);
}
function tampil()
{
    global $koneksi;
    $idevent = $GLOBALS['idevent'];
    include_once __DIR__ . "/../koneksi.php";
    $query = "select *,(select nama from penilaian where id=cabang.idpenilaian) as penilaian,(SELECT count(1) FROM golongan where idcabang=cabang.id) as jmlgolongan from cabang where idevent=$idevent";

    $mquery = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_array($mquery)) {
        $id = $row['id'];
        $nama = $row['nama'];
        $penilaian = $row['penilaian'];
        $jmlgolongan = $row['jmlgolongan'];
        print("<tr>
                <td id='tdnama$id'>$nama</td>
                <td id='tdidpenilaian$id' info='$row[idpenilaian]'>$penilaian</td>
                <td><div class=badge>$jmlgolongan</div> <a href=?page=utama&page2=golongan&idcabang=$id>manage</a></td>
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
        gi("nama").value = "";
        gi("idpenilaian").value = "";
        popup(true);
    }

    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        gi("nama").value = gi("tdnama" + id).innerText;
        gi("idpenilaian").value = gi("tdidpenilaian" + id).getAttribute("info");

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>
<div class="centermargin vmaxwidth">
    <h1>data cabang</h1>
    <input class="button padding panel" type="button" value="tambah data" onclick=tambah()>
    <table class="table widthfull">
        <tr>
            <th>cabang</th>
            <th>penilaian</th>
            <th>jumlah golongan</th>
            <th class=thtombol>edit</th>
            <th class=thtombol>hapus</th>
        </tr>
        <?php tampil(); ?>
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
                    cabang
                    <input type="text" name="nama" id="nama" required>
                    penilaian
                    <select name="idpenilaian" id="idpenilaian" required>
                        <option value="">-- pilih --</option>
                        <?php fillselect("select * from penilaian", "id", "nama"); ?>
                    </select>
                </div>
                <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" name="cmdsimpan" value="simpan">
                </div>
            </div>
        </form>

    </div>
</div>