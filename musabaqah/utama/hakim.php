<?php

function ekse($query)
{
    include_once __DIR__ . "/../koneksi.php";
    mysqli_query($koneksi, $query) or die("ada error");
}
function hapus($id)
{
    $query = "delete from hakim where id=$id";
    ekse($query);
}

function cekfile($param, $id)
{
    if ($_FILES[$param]['tmp_name'] != "") {
        $tujuan = "../assets/documents/hakim/$param" . "_" . "$id.pdf";
        $tmp_name = $_FILES[$param]['tmp_name'];
        move_uploaded_file($tmp_name, $tujuan);
        return $tujuan;
    } else {
        return "";
    }
}
function update($id = "")
{

    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $idbidang = $_GET['idbidang'] ?? 0;
    if ($id == "") {
        dbinsert("hakim", ["nama", "nik", "idbidang"], [$nama, $nik, $idbidang]);
    } else {

        $ktp = cekfile("ktp", $id);
        $npwp = cekfile("npwp", $id);
        $bukurek = cekfile("bukurek", $id);
        $sertifikatkejuaraan = cekfile("sertifikatkejuaraan", $id);
        $sertifikatpelatihanhakim = cekfile("sertifikatpelatihanhakim", $id);
        $sertifikatmenjadihakim = cekfile("sertifikatmenjadihakim", $id);

        dbupdate(
            "hakim",
            ["nama", "nik", "ktp", "npwp", "bukurek", "sertifikatkejuaraan", "sertifikatpelatihanhakim", "sertifikatmenjadihakim"],
            [$nama, $nik, $ktp, $npwp, $bukurek, $sertifikatkejuaraan, $sertifikatpelatihanhakim, $sertifikatmenjadihakim],
            "where id=$id"
        );

        $idhakim = $id;

        if (isset($_POST['keahlians'])) {
            $keahlians = $_POST['keahlians'];
            //delete dulu
            execute("delete from hakim_keahlian where idhakim=$idhakim");
            foreach ($keahlians as $item) {
                dbinsert(
                    "hakim_keahlian",
                    ["idhakim", "idkeahlian"],
                    [$idhakim, $item]
                );
            }
        }
    }
}

function getkeahlian($id)
{
    $hasil = "";
    $data = getdata("select * from hakim_keahlian where idhakim=$id");
    while ($rowkeahlian = mysqli_fetch_array($data)) {
        $idkeahlian = $rowkeahlian['idkeahlian'];
        $keahlian = getonedata("select nama from keahlian where id=$idkeahlian");
        $hasil .= "$keahlian, ";
    }
    return $hasil;
}
function tampil()
{
    $idbidang = $GLOBALS['idbidang'] ?? 0;
    if (!$idbidang) {
        echo "<tr><td colspan='11' class='center'>Silakan pilih bidang terlebih dahulu.</td></tr>";
        return;
    }
    $query = "select * from hakim where idbidang=$idbidang";
    $mquery = getdata($query);
    while ($row = mysqli_fetch_array($mquery)) {
        $id = $row['id'];
        $nama = $row['nama'];
        $nik = $row['nik'];
        $ktp = $row['ktp'];
        if ($ktp != "") $ktp = "<a class='file-link' target='_blank' href='$ktp'>📄 KTP</a>";
        $npwp = $row['npwp'];
        if ($npwp != "") $npwp = "<a class='file-link' target='_blank' href='$npwp'>📄 NPWP</a>";
        $bukurek = $row['bukurek'];
        if ($bukurek != "") $bukurek = "<a class='file-link' target='_blank' href='$bukurek'>📄 Rek</a>";
        $sertifikatkejuaraan = $row['sertifikatkejuaraan'];
        if ($sertifikatkejuaraan != "") $sertifikatkejuaraan = "<a class='file-link' target='_blank' href='$sertifikatkejuaraan'>📄 Kejuaraan</a>";
        $sertifikatpelatihanhakim = $row['sertifikatpelatihanhakim'];
        if ($sertifikatpelatihanhakim != "") $sertifikatpelatihanhakim = "<a class='file-link' target='_blank' href='$sertifikatpelatihanhakim'>📄 Pelatihan</a>";
        $sertifikatmenjadihakim = $row['sertifikatmenjadihakim'];
        if ($sertifikatmenjadihakim != "") $sertifikatmenjadihakim = "<a class='file-link' target='_blank' href='$sertifikatmenjadihakim'>📄 Srt Hakim</a>";
        else $sertifikatmenjadihakim = "<span class='missing-file'>-</span>";

        // Group documents
        $dokUtama = "";
        if ($ktp) $dokUtama .= $ktp . " ";
        if ($npwp) $dokUtama .= $npwp . " ";
        if ($bukurek) $dokUtama .= $bukurek;
        if (!$dokUtama) $dokUtama = "<span class='missing-file'>Belum ada dokumen</span>";

        $sertifikat = "";
        if ($sertifikatkejuaraan) $sertifikat .= $sertifikatkejuaraan . " ";
        if ($sertifikatpelatihanhakim) $sertifikat .= $sertifikatpelatihanhakim . " ";
        if ($sertifikatmenjadihakim) $sertifikat .= $sertifikatmenjadihakim;
        if (!$sertifikat) $sertifikat = "<span class='missing-file'>Belum ada sertifikat</span>";

        print("<tr>
                <td id=tdnama$id><strong>$nama</strong></td>
                <td id=tdnik$id style='font-family: monospace;'>$nik</td>
                <td>$dokUtama</td>
                <td>$sertifikat</td>
                <td>" . getkeahlian($id) . "</td>
                <td>
                    <div style='display: flex; gap: 5px;'>
                        <button class='modern-btn modern-btn-secondary' style='padding: 6px 12px; font-size: 0.8rem;' onclick='edit($id)'>✏️</button>
                        <button class='modern-btn' style='padding: 6px 12px; font-size: 0.8rem; background: #ef4444;' onclick='hapus($id)'>🗑️</button>
                    </div>
                </td>
            </tr>\n");
    }
}

?>
<?php

//print_r($_POST);
if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") update();
    if ($mode == "edit") update($crudid);
}

$idbidang = $_GET['idbidang'] ?? 0;
if ($idbidang) {
    $bidang = getonedata("select nama from bidang where id=$idbidang");
} else {
    $bidang = "(Semua Bidang)";
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
        gi("edokumen").style.display = "none";
        document.getElementById("crud").value = "tambah";
        gi("nama").value = "";
        gi("nik").value = "";
        popup(true);
    }

    function edit(id) {
        gi("edokumen").style.display = "unset";
        document.getElementById("crud").value = "edit";
        document.getElementById("crudid").value = id;

        gi("nama").value = gi("tdnama" + id).innerText;
        gi("nik").value = gi("tdnik" + id).innerText;

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
    <style>
        .hakim-container {
            padding: 20px;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            background: rgba(16, 185, 129, 0.1);
            color: var(--primary-light);
            border-radius: 4px;
            font-size: 0.8rem;
            text-decoration: none;
            transition: all 0.2s;
            margin-bottom: 5px;
            /* Added for spacing between links */
        }

        .file-link:hover {
            background: var(--primary);
            color: white;
        }

        .missing-file {
            color: var(--text-muted);
            font-style: italic;
            font-size: 0.8rem;
            display: block;
            /* Ensure it takes full width */
            margin-bottom: 5px;
            /* Added for spacing */
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .full-width {
            grid-column: span 2;
        }

        .document-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            background: rgba(15, 23, 42, 0.3);
            padding: 15px;
            border-radius: 8px;
            margin-top: 10px;
        }

        .doc-item {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .doc-item label {
            font-size: 0.75rem;
            color: var(--text-secondary);
        }
    </style>
</head>

<div class="hakim-container vmaxwidth centermargin">
    <div class="modern-card">
        <div class="judul" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">
            <h1 style="margin:0;">⚖️ DATA HAKIM: <span style="color: var(--primary-light);"><?php echo $bidang ?></span></h1>
            <button class="modern-btn modern-btn-primary" onclick="tambah()">➕ TAMBAH HAKIM</button>
        </div>

        <div style="overflow:auto">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>HAKIM</th>
                        <th>NIK</th>
                        <th>DOKUMEN UTAMA</th>
                        <th>SERTIFIKAT</th>
                        <th>KEAHLIAN</th>
                        <th class="thtombol">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php tampil(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal" id="popup">
    <div class="box" style="max-width: 700px;">
        <form action="" method="post" name="formcrud" enctype="multipart/form-data">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">

            <div class="isian">
                <div class="isi">
                    <h3 id="formTitle">📝 TAMBAH DATA HAKIM</h3>

                    <div class="form-grid">
                        <div class="doc-item">
                            <label>Nama Lengkap Hakim</label>
                            <input type="text" name="nama" id="nama" placeholder="Contoh: Dr. Ahmad" required>
                        </div>
                        <div class="doc-item">
                            <label>Nomor Induk Kependudukan (NIK)</label>
                            <input type="text" name="nik" id="nik" placeholder="16 Digit NIK" required>
                        </div>

                        <div class="full-width" id="edokumen">
                            <label>Pilih Keahlian (Multi-select)</label>
                            <select name="keahlians[]" id="keahlians" multiple style="height: 100px;">
                                <?php
                                $data = getdata("select * from keahlian");
                                while ($row = mysqli_fetch_array($data)) {
                                    echo "<option value=$row[id]>$row[nama]</option>";
                                }
                                ?>
                            </select>
                            <small style="color: var(--text-muted); margin-bottom: 10px; display: block;">Tahan Ctrl atau Shift untuk memilih lebih dari satu.</small>

                            <label style="margin-top: 15px; display: block; font-weight: 600; color: var(--primary-light);">📁 UPLOAD DOKUMEN (PDF)</label>
                            <div class="document-grid">
                                <div class="doc-item">
                                    <label>KTP</label>
                                    <input type="file" name="ktp" accept="application/pdf">
                                </div>
                                <div class="doc-item">
                                    <label>NPWP</label>
                                    <input type="file" name="npwp" accept="application/pdf">
                                </div>
                                <div class="doc-item">
                                    <label>Buku Rekening</label>
                                    <input type="file" name="bukurek" accept="application/pdf">
                                </div>
                                <div class="doc-item">
                                    <label>Sertifikat Kejuaraan</label>
                                    <input type="file" name="sertifikatkejuaraan" accept="application/pdf">
                                </div>
                                <div class="doc-item">
                                    <label>Sertifikat Pelatihan</label>
                                    <input type="file" name="sertifikatpelatihanhakim" accept="application/pdf">
                                </div>
                                <div class="doc-item">
                                    <label>Sertifikat Menjadi Hakim</label>
                                    <input type="file" name="sertifikatmenjadihakim" accept="application/pdf">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer">
                    <button type="button" class="modern-btn modern-btn-secondary" onclick="popup(false)">BATAL</button>
                    <button type="submit" class="modern-btn modern-btn-primary">SIMPAN DATA</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Update tampil logic to use modern elements
    function tampilJS() {
        // This is a placeholder, the PHP tampil() already outputs TRs
    }

    // Override the PHP output slightly for better icons
    document.addEventListener("DOMContentLoaded", () => {
        const rows = document.querySelectorAll(".modern-table tbody tr");
        rows.forEach(row => {
            const cells = row.querySelectorAll("td");
            // Grouping files for cleaner look if needed
        });
    });
</script>