<?php
if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") tambah();
    if ($mode == "edit") edit($crudid);
}

function ekse($query)
{
    include_once __DIR__ . "/../../musabaqah/dbku.php";
    execute($query);
}
function hapus($id)
{
    $query = "DELETE FROM golongan WHERE id=$id";
    ekse($query);
}

function tambah()
{
    $nama = strtoupper($_POST['nama']);
    $juzawal = $_POST['juzawal'];
    $juzakhir = $_POST['juzakhir'];
    $jmlsoal = $_POST['jmlsoal'];
    $query = "INSERT INTO golongan (nama, juzawal, juzakhir, jmlsoal, idcabang, idbabak, jumlah_hakim) VALUES ('$nama', '$juzawal', '$juzakhir', '$jmlsoal', 1, 1, 5)";
    ekse($query);
}

function edit($id)
{
    $nama = strtoupper($_POST['nama']);
    $juzawal = $_POST['juzawal'];
    $juzakhir = $_POST['juzakhir'];
    $jmlsoal = $_POST['jmlsoal'];
    $query = "UPDATE golongan SET nama='$nama', juzawal='$juzawal', juzakhir='$juzakhir', jmlsoal='$jmlsoal' WHERE id=$id";
    ekse($query);
}

function tampil()
{
    include_once __DIR__ . "/../../musabaqah/dbku.php";
    $query = "SELECT * FROM golongan WHERE jmlsoal > 0 ORDER BY nama";
    $mquery = getdata($query);
    while ($row = mysqli_fetch_array($mquery)) {
        $id = $row['id'];
        $nama = $row['nama'];
        $juzawal = $row['juzawal'];
        $juzakhir = $row['juzakhir'];
        $jmlsoal = $row['jmlsoal'];
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

        xconfirm("yakin ingin menghapus data ?", () => {
            document.getElementById("crud").value = "hapus";
            document.getElementById("crudid").value = id;
            document.forms['formcrud'].submit();
        });
    }

    function tambah() {
        document.getElementById("crud").value = "tambah";

        gi("nama").value = "";
        gi("juzawal").value = "";
        gi("juzakhir").value = "";
        gi("jmlsoal").value = "";
        popup(true);
    }

    function edit(id) {
        document.getElementById("crud").value = "edit";
        document.getElementById("crudid").value = id;

        gi("nama").value = gi("tdnama" + id).innerText;
        gi("juzawal").value = gi("tdjuzawal" + id).innerText;
        gi("juzakhir").value = gi("tdjuzakhir" + id).innerText;
        gi("jmlsoal").value = gi("tdjmlsoal" + id).innerText;

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/popup.css">
    <script src="../global/js/popup.js"></script>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class="vmaxwidth centermargin">
    <h1>KATEGORI MHQ</h1>
    <input type="button" class="modern-btn modern-btn-primary" value="➕ TAMBAH KATEGORI" onclick="tambah()" style="margin-bottom: 20px;">
    <table class="table widthfull">
        <tr class="fontsize2">
            <th class=padding>kategori</th>
            <th>juz awal</th>
            <th>juz akhir</th>
            <th>jumlah soal</th>
            <th>edit</th>
            <th>hapus</th>
        </tr>
        <?php tampil(); ?>
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