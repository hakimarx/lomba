<?php
// Hafidz CRUD admin page for adminprov / adminkabko
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
// allow only adminprov or adminkabko
// allow only adminprov or adminkabko
openfor(["adminprov", "adminkabko"]);

$iduser = getiduser();
$user = getonebaris("select * from user where id=$iduser");
$user_kab_kota = $user['kab_kota'] ?? '';
$role_name = getonedata("select nama from user_level where id=" . $user['iduser_level']);

// Handle Deceased Report
if (isset($_POST['action']) && $_POST['action'] == 'lapor_meninggal') {
    $id = $_POST['id'];
    $tanggal = $_POST['tanggal_meninggal'];

    $bukti = null;
    if (isset($_FILES['bukti_meninggal']) && $_FILES['bukti_meninggal']['error'] == 0) {
        $dir = '../../assets/documents/bukti_meninggal';
        if (!file_exists($dir)) mkdir($dir, 0777, true);
        $fn = time() . "_" . basename($_FILES['bukti_meninggal']['name']);
        $dest = "$dir/" . $fn;
        move_uploaded_file($_FILES['bukti_meninggal']['tmp_name'], $dest);
        $bukti = "assets/documents/bukti_meninggal/" . $fn;
    }

    execute("update hafidz set status='meninggal', tanggal_meninggal='$tanggal', bukti_meninggal='$bukti' where id=$id");
    alert("Laporan meninggal berhasil disimpan.");
    header("location:hafidz.php");
    exit;
}

// Handle Transfer Request
if (isset($_POST['action']) && $_POST['action'] == 'pindah_kabko') {
    $id = $_POST['id'];
    $tujuan = $_POST['tujuan_pindah'];
    execute("update hafidz set status='pindah_request', tujuan_pindah='$tujuan' where id=$id");
    alert("Permintaan pindah berhasil dikirim.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

// Handle KabKo Incentive Verification
if (isset($_POST['action']) && $_POST['action'] == 'verifikasi_kabko') {
    $id = $_POST['id'];
    $status = $_POST['status_insentif_kabko'];
    $catatan = mysqli_real_escape_string($GLOBALS['koneksi'], $_POST['catatan_insentif_kabko']);
    execute("update hafidz set status_insentif_kabko='$status', catatan_insentif_kabko='$catatan', tgl_insentif_kabko=NOW(), verifikator_insentif_kabko=$iduser where id=$id");
    alert("Status verifikasi Kab/Ko diperbarui.");
    header("location:?page=utama&page2=hafidz");
    exit;
}

function hapus($id)
{
    $vtabel = $GLOBALS['vtabel'];
    dbdelete($vtabel, $id);
}

function update($id = "")
{
    $vtabel = $GLOBALS['vtabel'];
    $vpost = array();
    foreach ($GLOBALS['kolomtrue'] as $item) {
        array_push($vpost, $_POST[$item]);
    }

    if ($id == "") {    //tambah
        // record created_by (do not mutate global column array)
        $insertCols = $GLOBALS['kolomtrue'];
        $insertVals = $vpost;
        $insertCols[] = 'created_by';
        $insertVals[] = getiduser();
        dbinsert($vtabel, $insertCols, $insertVals);
    } else {          //edit
        dbupdate($vtabel, $GLOBALS['kolomtrue'], $vpost, "where id=$id");
    }
}

function showtabel()
{
    echo "<table class=table>";
    setth();
    settd();
    echo "</table>";
}
function setth()
{
    $arrth = $GLOBALS['vth'];

    echo "<tr>";
    foreach ($arrth as $item) {
        echo "<th>$item</th>\n";
    }
    echo "<th>Nilai Wawasan</th>";
    echo "<th>Nilai Hafalan</th>";
    echo "<th class='thtombol'>edit</th>";
    echo "<th class='thtombol'>hapus</th>";
    echo "<th>Aksi Lain</th>";
    echo "</tr>";
}

function settd()
{
    $query = $GLOBALS['querytabel'];
    $kolomtrue = $GLOBALS['kolomtrue'];
    $kolomfake = $GLOBALS['kolomfake'];
    $data = getdata($query);
    while ($row = mysqli_fetch_array($data)) {
        $id = $row['id'];
        echo "<tr>";

        for ($i = 0; $i < count($kolomtrue); $i++) {
            $tdid = "td" . $id . $kolomtrue[$i];
            $valuefake = $row[$kolomfake[$i]];
            $valuetrue = $row[$kolomtrue[$i]];
            echo "<td id='$tdid' nilai='$valuetrue'>$valuefake</td>\n";
        }

        // get latest scores
        $lastWawasan = getonedata("select nilai from hafidz_nilai where idhafidz=$id and jenis='wawasan' order by tanggal desc limit 1");
        $lastHafalan = getonedata("select nilai from hafidz_nilai where idhafidz=$id and jenis='hafalan' order by tanggal desc limit 1");
        echo "<td>" . ($lastWawasan ? $lastWawasan : '-') . "</td>";
        echo "<td>" . ($lastHafalan ? $lastHafalan : '-') . "</td>";
        echo "<td><input type=button onclick='edit($id)' value=edit></td>";
        echo "<td><input type=button value=hapus onclick='hapus($id);'></td>";
        echo "<td>
                    <button class='btn btn-warning' onclick='laporMeninggal($id, \"$row[nama]\")'>Meninggal</button>
                    <button class='btn btn-primary' onclick='pindahKabko($id, \"$row[nama]\")'>Pindah</button>
                    " . ($row['is_insentif_kabko'] ? "<button class='btn btn-success' onclick='verifKabko($id, \"$row[nama]\")'>Verif KabKo</button>" : "") . "
                </td>";
        echo "</tr>\n";
    }
}
?>

<?php
$vtabel = "hafidz";
$filter_kabko = isset($_GET['filter_kabko']) ? $_GET['filter_kabko'] : '';
$where = "";
if ($role_name == 'admin prov') {
    $where = "1=1";
} else {
    $where = "kab_kota='$user_kab_kota'";
}
if ($filter_kabko !== '') {
    $where .= " AND is_insentif_kabko='$filter_kabko'";
}
$querytabel = "select * from $vtabel where $where";

$kolomtrue = ["tahun_anggaran", "periode", "asal", "nik", "nama", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "umur", "jk", "alamat", "rt", "rw", "desa", "desa_kelurahan", "kecamatan", "kab_kota", "jumlah_hafalan_juz", "lembaga_tahfidz", "nama_pembimbing", "tahun_khatam", "no_sertifikat_tahfidz", "status_mengajar", "nama_lembaga_mengajar", "tmt_mengajar", "telepon", "keterangan", "lulus", "is_insentif_kabko", "status_insentif_kabko", "username", "password", "idkafilah", "nama_bank", "no_rekening", "atas_nama_rekening"];
$kolomfake = $kolomtrue;
$vth = ["Thn Anggaran", "Periode", "Asal", "NIK", "Nama", "Nama Lengkap", "TMPT Lahir", "Tgl Lahir", "Umur", "JK", "Alamat", "RT", "RW", "Desa", "Desa/Kel", "Kec", "Kab/Kota", "Jml Juz", "Lembaga Tahfidz", "Pembimbing", "Thn Khatam", "No Sertifikat", "Stat Mengajar", "Lembaga Mengajar", "TMT Mengajar", "Telepon", "Keterangan", "Lulus", "Insentif KabKo", "Stat Insentif", "Username", "Password", "ID Kafilah", "Nama Bank", "No Rekening", "Atas Nama"];

$jskolom = 'let vkolom=["' . implode('","', $kolomtrue) . '"]';

if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") update();
    if ($mode == "edit") update($crudid);
}

?>

<script>
    <?php echo $jskolom; ?>;
</script>
<script src="../global/js/crud.js"></script>


<head></head>

<div class=judul>
    <h1>📖 data <?php echo $vtabel; ?></h1>
    <div style="display:flex; gap:10px; align-items:center;">
        <select onchange="window.location.href='?page=utama&page2=hafidz&filter_kabko='+this.value" class="padding">
            <option value="">-- Semua (Insentif) --</option>
            <option value="1" <?php echo $filter_kabko == '1' ? 'selected' : ''; ?>>Ya (Insentif Kab Ko)</option>
            <option value="0" <?php echo $filter_kabko == '0' ? 'selected' : ''; ?>>Tidak</option>
        </select>
        <input class="button padding panel" type="button" value="+ tambah data" onclick=tambah()>
    </div>
</div>
<div style="overflow:auto">
    <?php showtabel(); ?>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isi">
                tahun anggaran<input type="text" name="tahun_anggaran">
                periode<input type="text" name="periode">
                asal<input type="text" name="asal">
                nik<input type="text" name="nik">
                nama panggilan<input type="text" name="nama">
                nama lengkap<input type="text" name="nama_lengkap" required>
                tempat lahir<input type="text" name="tempat_lahir">
                tanggal lahir<input type="date" name="tanggal_lahir">
                umur<input type="text" name="umur">
                jk<input type="text" name="jk">
                alamat<input type="text" name="alamat">
                rt<input type="text" name="rt">
                rw<input type="text" name="rw">
                desa panggilan<input type="text" name="desa">
                desa/kelurahan<input type="text" name="desa_kelurahan">
                kecamatan<input type="text" name="kecamatan">
                kabupaten/kota<input type="text" name="kab_kota">
                jumlah hafalan juz<input type="number" name="jumlah_hafalan_juz" min="1" max="30">
                lembaga tahfidz<input type="text" name="lembaga_tahfidz">
                nama pembimbing<input type="text" name="nama_pembimbing">
                tahun khatam<input type="number" name="tahun_khatam">
                no sertifikat tahfidz<input type="text" name="no_sertifikat_tahfidz">
                status mengajar<select name="status_mengajar">
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
                nama lembaga mengajar<input type="text" name="nama_lembaga_mengajar">
                tmt mengajar<input type="date" name="tmt_mengajar">
                insentif kab ko<select name="is_insentif_kabko">
                    <option value="0">Tidak</option>
                    <option value="1">Ya</option>
                </select>
                status insentif kab ko<input type="text" name="status_insentif_kabko" readonly>
                telepon<input type="text" name="telepon">
                keterangan<textarea name="keterangan"></textarea>
                lulus<select name="lulus">
                    <option value="0">No</option>
                    <option value="1">Yes</option>
                </select>
                username<input type="text" name="username">
                password<input type="text" name="password">
                nama bank<input type="text" name="nama_bank">
                no rekening<input type="text" name="no_rekening">
                atas nama rekening<input type="text" name="atas_nama_rekening">
                idkafilah<select name="idkafilah">
                    <option value=''>-- pilih --</option>
                    <?php
                    $data = getdata("select * from kafilah");
                    while ($row = mysqli_fetch_array($data)) {
                        echo "<option value=$row[id]>$row[nama]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" value="simpan">
            </div>
        </form>

    </div>
</div>
</div>
</div>

<!-- Modal Lapor Meninggal -->
<div class="popup" id="popupMeninggal" style="display:none;">
    <div class="box">
        <h3>Lapor Hafidz Meninggal</h3>
        <p id="namaMeninggal"></p>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="lapor_meninggal">
            <input type="hidden" name="id" id="idMeninggal">
            Tanggal Meninggal <input type="date" name="tanggal_meninggal" required><br>
            Bukti (JPG/PDF) <input type="file" name="bukti_meninggal" required><br>
            <div class="tombol">
                <input type="button" value="Batal" onclick="document.getElementById('popupMeninggal').style.display='none'">
                <input type="submit" value="Simpan">
            </div>
        </form>
    </div>
</div>

<!-- Modal Pindah Kab/Ko -->
<div class="popup" id="popupPindah" style="display:none;">
    <div class="box">
        <h3>Request Pindah Kab/Ko</h3>
        <p id="namaPindah"></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="pindah_kabko">
            <input type="hidden" name="id" id="idPindah">
            Tujuan Kab/Kota <input type="text" name="tujuan_pindah" required placeholder="Nama Kab/Kota Tujuan"><br>
            <div class="tombol">
                <input type="button" value="Batal" onclick="document.getElementById('popupPindah').style.display='none'">
                <input type="submit" value="Kirim Request">
            </div>
        </form>
    </div>
</div>

<!-- Modal Verifikasi KabKo -->
<div class="popup" id="popupVerifKabko" style="display:none;">
    <div class="box">
        <h3>Verifikasi Insentif Kab/Ko</h3>
        <p id="namaVerifKabko"></p>
        <form action="" method="post">
            <input type="hidden" name="action" value="verifikasi_kabko">
            <input type="hidden" name="id" id="idVerifKabko">
            Status <select name="status_insentif_kabko" required>
                <option value="Pending">Pending</option>
                <option value="Diterima">Diterima</option>
                <option value="Ditolak">Ditolak</option>
            </select><br><br>
            Catatan <textarea name="catatan_insentif_kabko" rows="3"></textarea><br>
            <div class="tombol">
                <input type="button" value="Batal" onclick="document.getElementById('popupVerifKabko').style.display='none'">
                <input type="submit" value="Simpan Verifikasi">
            </div>
        </form>
    </div>
</div>

<script>
    function laporMeninggal(id, nama) {
        document.getElementById('idMeninggal').value = id;
        document.getElementById('namaMeninggal').innerText = nama;
        document.getElementById('popupMeninggal').style.display = 'flex';
    }

    function pindahKabko(id, nama) {
        document.getElementById('idPindah').value = id;
        document.getElementById('namaPindah').innerText = nama;
        document.getElementById('popupPindah').style.display = 'flex';
    }

    function verifKabko(id, nama) {
        document.getElementById('idVerifKabko').value = id;
        document.getElementById('namaVerifKabko').innerText = nama;
        document.getElementById('popupVerifKabko').style.display = 'flex';
    }
</script>