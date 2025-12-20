<?php
function setdata()
{
    // Fetch from golongan where jmlsoal > 0 (indicates Qur'anic category)
    $data = getdata("SELECT * FROM golongan WHERE jmlsoal > 0 ORDER BY nama");
    while ($row = mysqli_fetch_array($data)) {
        echo "<option value='$row[id]'>$row[nama] ( JUZ $row[juzawal] - $row[juzakhir]) ($row[jmlsoal] SOAL)</option>";
    }
}
?>

<?php
if (isset($_GET['json'])) {
    require("../../musabaqah/dbku.php");
    //dapatkan parameter
    $idkategori = $_GET['json'];
    $row = getonebaris("SELECT * FROM golongan WHERE id=$idkategori");
    $juzawal = $row["juzawal"];
    $juzakhir = $row["juzakhir"];
    $jmlsoal = $row["jmlsoal"];

    //mutasyabihat
    echo "<div class='item mutasyabihat'>surat(ayat)</div>";
    //biasa
    //dapatkan data
    $q = "SELECT urut FROM mushaf
            WHERE juz BETWEEN $juzawal AND $juzakhir
            AND mutasyabihat='0'
            AND urut NOT IN 
            (SELECT urutmushaf FROM mhq WHERE idkategori=$idkategori)
            ORDER BY RAND()";

    //tampilkan dan insert
    for ($i = 0; $i < $jmlsoal - 1; $i++) {
        $urut = getonedata($q);
        //cek ketersediaan
        if ($urut == "") {
            // die("data habis");
            // reset data
            execute("DELETE FROM mhq WHERE idkategori='$idkategori'");
            $i--; //ulangi
        } else {
            // isi data acak
            execute("INSERT INTO mhq(idkategori,urutmushaf) VALUES ('$idkategori','$urut')");
            //ambil surat dan ayat
            $row = getonebaris("select * from mushaf where urut='$urut'");
            $surat = $row['surat'];
            $suratnama = getonedata("select nama from mushaf_surat where urut=$surat");
            $ayat = $row['ayat'];
            echo "<div class='item biasa' onclick=bukalink('?page=mushaf&jenis=madinah&nosurat=$surat&noayat=$ayat')>$suratnama ($ayat)</div>";
        }
    }
    die();
}

?>

<script>
    function bukalink(url) {
        window.open(url, '_blank');
    }

    function acak() {
        let index = eoption.options.selectedIndex;
        if (index == 0) {
            xalert("pilih cabang lomba");
            return;
        }
        let idkategori = eoption.options[index].value;
        // cl(index);
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            qs(".hasil").innerHTML = this.responseText;
        }
        xmlhttp.open("GET", "../emaqra/utama/tilawah2.php?json=" + idkategori);
        xmlhttp.send();
        // alert("teracak");
    }
</script>

<script>
    let eoption;
    document.addEventListener("DOMContentLoaded", () => {
        eoption = gi("eselect");
        // alert(eoption);
    })
</script>

<style>
    .hasil {
        justify-content: center;
        padding: 20px;
    }

    .item {
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        text-align: center;
        width: 150px;
        aspect-ratio: 1/1;
        cursor: pointer;
        justify-content: center;
        padding: 15px;
        border-radius: 12px;
        background: var(--bg-card);
        color: var(--text-primary);
        font-weight: 600;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-md);
    }

    .item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg), var(--shadow-glow);
        border-color: var(--primary);
    }

    .item.biasa {
        border-color: var(--primary);
        background: rgba(16, 185, 129, 0.1);
    }

    .item.mutasyabihat {
        border-color: var(--accent);
        background: rgba(139, 92, 246, 0.1);
    }
</style>


<div class="box vmaxwidth gap centermargin">
    <h1>TILAWAH</h1>
    <div class="panel flex gap flexcolumn">
        pilih cabang lomba
        <select class="select padding" id="eselect">
            <option value="">-- pilih --</option>
            <?php setdata(); ?>
        </select>
        <input class="modern-btn modern-btn-primary" type="button" value="🎲 ACAK SOAL" onclick=acak() style="width: 100%; margin-top: 10px;">
    </div>
    <div class="hasil flex-wrap flex gap">
    </div>
</div>