<?php

// print_r($_POST);

$idcabang = $_GET['idcabang'];
if ($idcabang == "") die("ada error");

if (isset($_POST['crud'])) {
    $mode = $_POST['crud'];
    $crudid = $_POST['crudid'];
    if ($mode == "hapus") hapus($crudid);
    if ($mode == "tambah") update();
    if ($mode == "edit") update($crudid);
}

function ekse($query)
{
    execute($query);
}
function hapus($id)
{
    $query = "delete from golongan where id=$id";
    ekse($query);
}

function update($id = "")
{
    $nama = $_POST['nama'];
    $idcabang = $GLOBALS['idcabang'];
    $idbabak = $_POST['idbabak'];
    $waktupersiapan = $_POST['waktupersiapan'];
    $waktupenilaian = $_POST['waktupenilaian'];
    $waktumenjelang = $_POST['waktumenjelang'];
    $waktuhabis = $_POST['waktuhabis'];
    $isbobot = $_POST['isbobot'];
    $isunsur = $_POST['isunsur'];
    $istopik = $_POST['istopik'];
    $ispewaktu = $_POST['ispewaktu'];
    $kodemenu_link = $_POST['kodemenu_link'];

    // New scoring settings
    $metode_nilai = $_POST['metode_nilai'] ?? 'rata-rata';
    $interval_aktif = isset($_POST['interval_aktif']) ? 1 : 0;
    $interval_terluar = isset($_POST['interval_terluar']) ? 1 : 0;
    $jumlah_hakim = $_POST['jumlah_hakim'] ?? 5;
    $pisah_gender = isset($_POST['pisah_gender']) ? 1 : 0;
    $suara_penanda = $_POST['suara_penanda'] ?? 'beep';
    $tutup_otomatis = isset($_POST['tutup_otomatis']) ? 1 : 0;
    $juzawal = $_POST['juzawal'] ?? 0;
    $juzakhir = $_POST['juzakhir'] ?? 0;
    $jmlsoal = $_POST['jmlsoal'] ?? 0;

    $arrkey = [
        "nama",
        "idbabak",
        "idcabang",
        "waktupersiapan",
        "waktupenilaian",
        "waktumenjelang",
        "waktuhabis",
        "isbobot",
        "isunsur",
        "istopik",
        "ispewaktu",
        "kodemenu_link",
        "metode_nilai",
        "interval_aktif",
        "interval_terluar",
        "jumlah_hakim",
        "pisah_gender",
        "suara_penanda",
        "tutup_otomatis",
        "juzawal",
        "juzakhir",
        "jmlsoal"
    ];
    $arrvalue = [
        $nama,
        $idbabak,
        $idcabang,
        $waktupersiapan,
        $waktupenilaian,
        $waktumenjelang,
        $waktuhabis,
        $isbobot,
        $isunsur,
        $istopik,
        $ispewaktu,
        $kodemenu_link,
        $metode_nilai,
        $interval_aktif,
        $interval_terluar,
        $jumlah_hakim,
        $pisah_gender,
        $suara_penanda,
        $tutup_otomatis,
        $juzawal,
        $juzakhir,
        $jmlsoal
    ];

    if ($id == "") {
        dbinsert("golongan", $arrkey, $arrvalue);
    } else {

        dbupdate("golongan", $arrkey, $arrvalue, " where id=$id");
    }
}

function tampil($idcabang)
{
    $query = "select *,
        (select nama from menu_link where kode=golongan.kodemenu_link) as namamenu_link,
        (select nama from babak where id=golongan.idbabak) as babak,
        (select count(1) from peserta where idgolongan=golongan.id) as jmlpeserta,
        (select count(1) from bidang where idgolongan=golongan.id) as jmlbidang from golongan
        
        where idcabang=$idcabang";
    $mquery = getdata($query);
    while ($row = mysqli_fetch_array($mquery)) {
        $id = $row['id'];
        $idbabak = $row['idbabak'];
        $babak = $row['babak'];
        $nama = $row['nama'];
        $waktupersiapan = $row['waktupersiapan'];
        $waktupenilaian = $row['waktupenilaian'];
        $waktumenjelang = $row['waktumenjelang'];
        $waktuhabis = $row['waktuhabis'];
        $jmlpeserta = $row['jmlpeserta'];
        $jmlbidang = $row['jmlbidang'];
        $isbobot = $row['isbobot'];
        $isunsur = $row['isunsur'];
        $istopik = $row['istopik'];
        $ispewaktu = $row['ispewaktu'];

        // New settings
        $metode_nilai = $row['metode_nilai'] ?? 'rata-rata';
        $interval_aktif = $row['interval_aktif'] ?? 0;
        $interval_terluar = $row['interval_terluar'] ?? 0;
        $jumlah_hakim = $row['jumlah_hakim'] ?? 5;
        $pisah_gender = $row['pisah_gender'] ?? 0;
        $suara_penanda = $row['suara_penanda'] ?? 'beep';
        $tutup_otomatis = $row['tutup_otomatis'] ?? 1;

        print("<tr>
                <td id=tdnama$id>$nama</td>
                <td id=tdidbabak$id id2=$idbabak>$babak</td>
                <td id=tdjmlpeserta$id><div class=badge>$jmlpeserta</div><a href=?page=utama&page2=peserta&idgolongan=$id>manage</a></td>
                <td id=tdjmlbidang$id><div class=badge>$jmlbidang</div><a href=?page=utama&page2=bidang&idgolongan=$id>manage</a></td>
                <td>
                    <button class='btn-setting' onclick='showSettings($id)'>⚙️ Pengaturan</button>
                </td>
                <td><input class='button padding' type=button onclick='edit($id)' value=edit></td>
                <td><input class='button padding' type=button value=hapus onclick='hapus($id);'></td>
                <td style='display:none' id=tdwaktupersiapan$id>$waktupersiapan</td>
                <td style='display:none' id=tdwaktupenilaian$id>$waktupenilaian</td>
                <td style='display:none' id=tdwaktumenjelang$id>$waktumenjelang</td>
                <td style='display:none' id=tdwaktuhabis$id>$waktuhabis</td>
                <td style='display:none' id=tdisbobot$id>$isbobot</td>
                <td style='display:none' id=tdisunsur$id>$isunsur</td>
                <td style='display:none' id=tdistopik$id>$istopik</td>
                <td style='display:none' id=tdispewaktu$id>$ispewaktu</td>
                <td style='display:none' id=tdkodemenu_link$id info=$row[kodemenu_link]>$row[namamenu_link]</td>
                <td style='display:none' id=tdmetode_nilai$id>$metode_nilai</td>
                <td style='display:none' id=tdinterval_aktif$id>$interval_aktif</td>
                <td style='display:none' id=tdinterval_terluar$id>$interval_terluar</td>
                <td style='display:none' id=tdjumlah_hakim$id>$jumlah_hakim</td>
                <td style='display:none' id=tdpisah_gender$id>$pisah_gender</td>
                <td style='display:none' id=tdsuara_penanda$id>$suara_penanda</td>
                <td style='display:none' id=tdtutup_otomatis$id>$tutup_otomatis</td>
                <td style='display:none' id=tdjuzawal$id>$row[juzawal]</td>
                <td style='display:none' id=tdjuzakhir$id>$row[juzakhir]</td>
                <td style='display:none' id=tdjmlsoal$id>$row[jmlsoal]</td>
            </tr>\n");
    }
}

?>


<script>
    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) {
            return;
        }
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

        gi("nama").value = "";
        gi("idbabak").value = "";
        gi("waktupersiapan").value = "30";
        gi("waktupenilaian").value = "10";
        gi("waktumenjelang").value = "60";
        gi("waktuhabis").value = "30";
        gi("kodemenu_link").value = "";

        // New settings defaults - using correct IDs for checkboxes
        document.getElementById("metode_rata").checked = true;
        gi("interval_aktif").checked = false;
        gi("interval_terluar").checked = false;
        gi("jumlah_hakim").value = "5";
        gi("pisah_gender").checked = false;
        gi("isbobot").checked = false;
        gi("isunsur").checked = false;
        gi("istopik").checked = false;
        gi("ispewaktu").checked = true;
        gi("suara_penanda").value = "beep";
        gi("tutup_otomatis").checked = true;
        gi("juzawal").value = "0";
        gi("juzakhir").value = "0";
        gi("jmlsoal").value = "0";

        popup(true);
    }

    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;
        gi("idbabak").value = gi("tdidbabak" + id).getAttribute("id2");
        gi("nama").value = gi("tdnama" + id).innerText;
        gi("waktupersiapan").value = gi("tdwaktupersiapan" + id).innerText;
        gi("waktupenilaian").value = gi("tdwaktupenilaian" + id).innerText;
        gi("waktumenjelang").value = gi("tdwaktumenjelang" + id).innerText;
        gi("waktuhabis").value = gi("tdwaktuhabis" + id).innerText;
        gi("isbobot").value = gi("tdisbobot" + id).innerText;
        gi("isunsur").value = gi("tdisunsur" + id).innerText;
        gi("istopik").value = gi("tdistopik" + id).innerText;
        gi("ispewaktu").value = gi("tdispewaktu" + id).innerText;
        gi("kodemenu_link").value = gi("tdkodemenu_link" + id).getAttribute("info");

        // New settings
        var metodeVal = gi("tdmetode_nilai" + id).innerText;
        if (metodeVal === "median") {
            document.getElementById("metode_median").checked = true;
        } else {
            document.getElementById("metode_rata").checked = true;
        }
        gi("interval_aktif").checked = gi("tdinterval_aktif" + id).innerText == "1";
        gi("interval_terluar").checked = gi("tdinterval_terluar" + id).innerText == "1";
        gi("jumlah_hakim").value = gi("tdjumlah_hakim" + id).innerText;
        gi("pisah_gender").checked = gi("tdpisah_gender" + id).innerText == "1";
        gi("suara_penanda").value = gi("tdsuara_penanda" + id).innerText;
        gi("tutup_otomatis").checked = gi("tdtutup_otomatis" + id).innerText == "1";
        gi("juzawal").value = gi("tdjuzawal" + id).innerText;
        gi("juzakhir").value = gi("tdjuzakhir" + id).innerText;
        gi("jmlsoal").value = gi("tdjmlsoal" + id).innerText;

        popup(true);

    }

    function showSettings(id) {
        edit(id);
        // Auto-scroll to settings section
        setTimeout(() => {
            document.querySelector('.settings-section').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }, 100);
    }

    function cek() {
        if (gi("waktumenjelang").value >= gi("waktupenilaian").value) {
            xalert("waktu menjelang harus kurang dari waktu penilaian");
            return false;
        } else {
            return true;
        }
    }
</script>


<head>
    <link rel="stylesheet" href="../global/css/isian.css">
    <style>
        .btn-setting {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.85em;
            transition: all 0.2s ease;
        }

        .btn-setting:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .settings-section {
            background: rgba(30, 41, 59, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
            margin-top: 20px;
        }

        .settings-section h4 {
            color: var(--primary-light);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }

        .setting-card {
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            padding: 16px;
        }

        .setting-card h5 {
            color: var(--text-primary);
            font-size: 0.9em;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .checkbox-item input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary);
        }

        .checkbox-item label {
            color: var(--text-secondary);
            font-size: 0.9em;
            cursor: pointer;
        }

        .radio-group {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .radio-item {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .radio-item input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary);
        }

        .radio-item label {
            color: var(--text-secondary);
            font-size: 0.9em;
            cursor: pointer;
        }

        .timer-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .timer-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .timer-item label {
            font-size: 0.8em;
            color: var(--text-muted);
        }

        .timer-item input,
        .timer-item select {
            padding: 8px 12px;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            color: var(--text-primary);
            font-size: 0.9em;
        }

        .timer-item input:focus,
        .timer-item select:focus {
            outline: none;
            border-color: var(--primary);
        }

        .light-indicator {
            display: inline-block;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-left: 8px;
        }

        .light-yellow {
            background: #fbbf24;
            box-shadow: 0 0 8px #fbbf24;
        }

        .light-green {
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }

        .light-red {
            background: #ef4444;
            box-shadow: 0 0 8px #ef4444;
        }
    </style>
</head>



<div class=judul>
    <h1>Golongan</h1>
    <input class="button panel padding" type="button" value="tambah data" onclick=tambah()>
</div>

<div style="overflow:auto">
    <table class=table>
        <tr>
            <th>Golongan</th>
            <th>Babak</th>
            <th>Jumlah Peserta</th>
            <th>Jumlah Bidang</th>
            <th>Pengaturan</th>
            <th>Edit</th>
            <th>Hapus</th>
        </tr>
        <?php tampil($idcabang); ?>
    </table>
</div>


<div class="modal" id=popup>
    <div class="box" style="max-width: 800px; max-height: 90vh; overflow-y: auto;">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    <h3 style="margin-bottom: 20px; color: var(--primary-light);">📝 Data Golongan</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label>Nama Golongan</label>
                            <input type="text" id=nama name="nama" required value>
                        </div>
                        <div>
                            <label>Babak</label>
                            <select name="idbabak" id=idbabak required>
                                <option value="">-- pilih babak --</option>
                                <?php
                                include_once "dbku.php";
                                $data = getdata("select * from babak");
                                while ($row = mysqli_fetch_array($data)) {
                                    $idbabak = $row['id'];
                                    $nama = $row['nama'];
                                    print "<option value=$idbabak>$nama</option>\n";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                        <div>
                            <label>Menu Link</label>
                            <select name="kodemenu_link" id="kodemenu_link" required>
                                <option value="">-- pilih --</option>
                                <?php
                                $data = getdata("select kode,nama from menu_link");
                                while ($row = mysqli_fetch_array($data)) {
                                    echo "<option value=$row[kode]>$row[nama]</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label>Jumlah Hakim per Bidang</label>
                            <input type="number" name="jumlah_hakim" id="jumlah_hakim" min=1 max=20 value="5">
                        </div>
                    </div>

                    <!-- Scoring Settings Section -->
                    <div class="settings-section">
                        <h4>⚙️ Pengaturan Penilaian</h4>

                        <div class="settings-grid">
                            <!-- Metode Nilai -->
                            <div class="setting-card">
                                <h5>📊 Metode Nilai</h5>
                                <div class="radio-group">
                                    <div class="radio-item">
                                        <input type="radio" name="metode_nilai" id="metode_rata" value="rata-rata" checked>
                                        <label for="metode_rata">Rata-rata</label>
                                    </div>
                                    <div class="radio-item">
                                        <input type="radio" name="metode_nilai" id="metode_median" value="median">
                                        <label for="metode_median">Median (Nilai Tengah)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Pengaturan Interval -->
                            <div class="setting-card">
                                <h5>📏 Pengaturan Interval</h5>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interval_aktif" id="interval_aktif">
                                    <label for="interval_aktif">Gunakan nilai interval</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="interval_terluar" id="interval_terluar">
                                    <label for="interval_terluar">Buang nilai terluar (tertinggi & terendah)</label>
                                </div>
                            </div>

                            <!-- Kategori -->
                            <div class="setting-card">
                                <h5>👥 Kategori</h5>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="pisah_gender" id="pisah_gender">
                                    <label for="pisah_gender">Pisahkan berdasarkan gender</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="istopik" id="istopik" value="1">
                                    <label for="istopik">Ada topik lomba</label>
                                </div>
                            </div>

                            <!-- Opsi Lainnya -->
                            <div class="setting-card">
                                <h5>🎛️ Opsi Lainnya</h5>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="isbobot" id="isbobot" value="1">
                                    <label for="isbobot">Gunakan bobot nilai</label>
                                </div>
                                <div class="checkbox-item">
                                    <input type="checkbox" name="isunsur" id="isunsur" value="1">
                                    <label for="isunsur">Gunakan unsur penilaian</label>
                                </div>
                            </div>
                            <!-- Kategori MHQ -->
                            <div class="setting-card">
                                <h5>📖 Pengaturan MHQ</h5>
                                <div class="timer-grid">
                                    <div class="timer-item">
                                        <label>Juz Awal</label>
                                        <input type="number" name="juzawal" id="juzawal" value="0">
                                    </div>
                                    <div class="timer-item">
                                        <label>Juz Akhir</label>
                                        <input type="number" name="juzakhir" id="juzakhir" value="0">
                                    </div>
                                    <div class="timer-item">
                                        <label>Jumlah Soal</label>
                                        <input type="number" name="jmlsoal" id="jmlsoal" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timer Settings Section -->
                    <div class="settings-section">
                        <h4>⏱️ Pengaturan Timer</h4>

                        <div class="checkbox-item" style="margin-bottom: 16px;">
                            <input type="checkbox" name="ispewaktu" id="ispewaktu" value="1" checked>
                            <label for="ispewaktu">Gunakan pewaktu (timer/countdown)</label>
                        </div>

                        <div class="timer-grid">
                            <div class="timer-item">
                                <label>⏳ Persiapan (detik) <span class="light-indicator light-yellow"></span></label>
                                <input type="number" name="waktupersiapan" id="waktupersiapan" min=0 value="30" placeholder="Lampu Kuning">
                            </div>
                            <div class="timer-item">
                                <label>✅ Penilaian (menit) <span class="light-indicator light-green"></span></label>
                                <input type="number" name="waktupenilaian" id="waktupenilaian" min=1 value="10" placeholder="Lampu Hijau">
                            </div>
                            <div class="timer-item">
                                <label>⚠️ Menjelang habis (detik) <span class="light-indicator light-yellow"></span></label>
                                <input type="number" name="waktumenjelang" id="waktumenjelang" min=0 value="60" placeholder="Lampu Kuning">
                            </div>
                            <div class="timer-item">
                                <label>🛑 Waktu habis (detik) <span class="light-indicator light-red"></span></label>
                                <input type="number" name="waktuhabis" id="waktuhabis" min=0 value="30" placeholder="Lampu Merah">
                            </div>
                            <div class="timer-item">
                                <label>🔊 Suara Penanda</label>
                                <select name="suara_penanda" id="suara_penanda">
                                    <option value="beep">Beep</option>
                                    <option value="bell">Lonceng</option>
                                    <option value="chime">Chime</option>
                                    <option value="none">Tanpa Suara</option>
                                </select>
                            </div>
                            <div class="timer-item">
                                <label>🔄 Tutup Otomatis</label>
                                <div class="checkbox-item" style="margin-top: 8px;">
                                    <input type="checkbox" name="tutup_otomatis" id="tutup_otomatis" checked>
                                    <label for="tutup_otomatis">Ya, tutup timer otomatis</label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class=footer style="margin-top: 24px;">
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" name="cmdsimpan" value="simpan" onclick="return cek();" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                </div>
            </div>
        </form>

    </div>
</div>