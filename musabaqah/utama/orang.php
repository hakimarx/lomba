<?php


$kolomtrue = array("nama", "pob", "dob", "idgender", "idjob", "idkafilah", "kontak");

if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") update();
    if ($mode == "edit") update($crudid);
}

function ekse($query)
{
    global $koneksi;
    include_once __DIR__ . "/../koneksi.php";
    mysqli_query($koneksi, $query) or die("ada error");
}
function hapus($id)
{
    $query = "delete from orang where id=$id";
    ekse($query);
}

function update($id = "")
{
    //$nama=$_POST['nama'];

    $vpost = array();
    foreach ($GLOBALS['kolomtrue'] as $item) {
        array_push($vpost, $_POST[$item]);
    }

    if ($id == "") {    //tambah
        //$query="insert into orang(_ubah_) values()";
        $query = "insert into orang (";
        for ($i = 0; $i < count($vpost); $i++) {
            $query .= $GLOBALS['kolomtrue'][$i] . ",";
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ")";


        $query .= " values(";
        for ($i = 0; $i < count($vpost); $i++) {
            $query .= "'" . $vpost[$i] . "',";
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= ")";
    } else {          //edit
        $query = "update orang set ";
        for ($i = 0; $i < count($vpost); $i++) {
            $query .= $GLOBALS['kolomtrue'][$i] . "='" . $vpost[$i] . "',";
        }
        $query = substr($query, 0, strlen($query) - 1);
        $query .= " where id=$id";
    }
    //die($query);
    ekse($query);
}

function tampil()
{
    global $koneksi;
    include_once __DIR__ . "/../koneksi.php";
    $kolomtrue = $GLOBALS['kolomtrue'];
    //$query="select * from orang ";
    $query = "select *,(select nama from gender where id=orang.idgender) as gender,(select nama from job where id=orang.idjob) as job,(select nama from kafilah where id=orang.idkafilah) as kafilah from orang";
    $mquery = mysqli_query($koneksi, $query);

    $kolomfake = array("nama", "pob", "dob", "gender", "job", "kafilah", "kontak");
    $arrth = $kolomfake;
    //th
    echo "<tr>";
    foreach ($arrth as $item) {
        echo "<th>$item</th>\n";
    }
    echo "<th class='thtombol'>edit</th>";
    echo "<th class='thtombol'>hapus</th>";
    echo "</tr>";

    //td
    while ($row = mysqli_fetch_array($mquery)) {
        $id = $row['id'];
        echo "<tr>";

        for ($i = 0; $i < count($kolomtrue); $i++) {
            $tdid = "td" . $id . $kolomtrue[$i];
            $valuefake = $row[$kolomfake[$i]];
            $valuetrue = $row[$kolomtrue[$i]];
            echo "<td id='$tdid' nilai='$valuetrue'>$valuefake</td>\n";
        }

        echo "  <td><input type=button onclick='edit($id)' value=edit></td>";
        echo "  <td><input type=button value=hapus onclick='hapus($id);'></td>";
        echo "</tr>\n";
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

        let xx = ["nama", "pob", "dob", "idgender", "idjob", "idkafilah", "kontak"];
        xx.forEach((item) => {
            qs("[name='" + item + "'").value = "";
        });

        popup(true);
    }

    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        let xx = ["nama", "pob", "dob", "idgender", "idjob", "idkafilah", "kontak"];
        xx.forEach((item) => {
            qs("[name='" + item + "'").value = gi("td" + id + item).getAttribute("nilai");
        });

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class="vmaxwidth centermargin">
    <h1>data orang</h1>
    <input class="button padding panel" type="button" value="tambah data" onclick=tambah()>
    <table class=table>
        <?php tampil(); ?>
    </table>
</div>

<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">

        </form>
        <div class="isian">
            <div class="isi">
                nama<input type="text" name="nama" required>
                pob<input type="text" name="pob" required>
                dob<input type="date" name="dob" required>

                gender
                <select name="idgender" required>
                    <option value="">-- pilih gender --</option>
                    <?php
                    $data = getdata("select * from gender");
                    while ($row = mysqli_fetch_array($data)) {
                        $label = $row['nama'];
                        $vid = $row['id'];
                        echo "<option value=$vid>$label</option>";
                    }
                    ?>
                </select>


                job
                <select name="idjob" required>
                    <option value="">-- pilih job --</option>
                    <?php
                    $data = getdata("select * from job");
                    while ($row = mysqli_fetch_array($data)) {
                        $label = $row['nama'];
                        $vid = $row['id'];
                        echo "<option value=$vid>$label</option>";
                    }
                    ?>
                </select>

                kafilah
                <select name="idkafilah" id="" required>
                    <option value="">-- pilih kafilah --</option>
                    <?php
                    $data = getdata("select * from kafilah");
                    while ($row = mysqli_fetch_array($data)) {
                        $label = $row['nama'];
                        $vid = $row['id'];
                        echo "<option value=$vid>$label</option>";
                    }
                    ?>
                </select>
                kontak<input type="text" name="kontak" required>

            </div>
            <div class=footer>
                <input class="button padding" type="button" value="batal" onclick=popup(false)>
                <input class="button padding" type="submit" value="simpan">
            </div>

        </div>
    </div>
</div>