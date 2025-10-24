<?php
    //print_r($_POST);

    $idgolongan=$_GET['idgolongan'];
    if($idgolongan=="") die("ada error");

    include "koneksi.php";
    $query="select *,
        (select count(1) from peserta2 where idgolongan=golongan.id) as jmlpeserta,
        (select count(1) from bidang where idgolongan=golongan.id) as jmlbidang from golongan
        where id=$idgolongan";
    $data=mysqli_query($koneksi,$query);
    $rowgolongan=mysqli_fetch_array($data);

    if(isset($_POST["simpandata"])){
        include "koneksi.php";
        $idpeserta=$_POST['idpeserta'];
        $nilai1=$_POST['nilai1'];
        $nilai2=$_POST['nilai2'];
        $nilai3=$_POST['nilai3'];
        $query="insert into nilai(idpeserta,nilai1,nilai2,nilai3) values('$idpeserta','$nilai1','$nilai2','$nilai3')";
        mysqli_query($koneksi,$query) or die("ada error");
        msgbox("data tersimpan");
    }

    function getinfo(){
        include "koneksi.php";
        include_once "dbku.php";
        $rowgolongan=$GLOBALS['rowgolongan'];
        $event=getonedata("select nama from event where aktif=1;");
        $babak=getonedata("select nama from babak where id=".$rowgolongan['idbabak']);
        $cabang=getonedata("select nama from cabang where id='".$rowgolongan['idcabang']."'");
        $golongan=$rowgolongan['nama'];
        $waktupersiapan=$rowgolongan['waktupersiapan'];
        $waktupenilaian=$rowgolongan['waktupenilaian'];
        $waktumenjelang=$rowgolongan['waktumenjelang'];
        $waktuhabis=$rowgolongan['waktuhabis'];

                return "<table>
                            <tr><th>judul1</th><th>judul2</th></tr>

                            <tr>
                                <td>event</td>
                                <td>$event</td>
                            </tr>
                            <tr>
                                <td>babak</td>
                                <td>$babak</td>
                            </tr>
                            <tr>
                                <td>cabang</td>
                                <td>$cabang</td>
                            </tr>
                            <tr>
                                <td>golongan</td>
                                <td>$golongan</td>
                            </tr>
                            <tr>
                                <td>waktu persiapan</td>
                                <td id=ewaktupersiapan value='$waktupersiapan'>$waktupersiapan</td>
                            </tr>
                            <tr>
                                <td>waktu penilaian</td>
                                <td id=ewaktupenilaian value='$waktupenilaian'>$waktupenilaian</td>
                            </tr>
                            <tr>
                                <td>waktu menjelang habis</td>
                                <td id=ewaktumenjelang value='$waktumenjelang'>$waktumenjelang</td>
                            </tr>
                            <tr>
                                <td>waktu habis</td>
                                <td id=ewaktuhabis value='$waktuhabis'>$waktuhabis</td>
                            </tr>
                        </table>";
    }

    function getpeserta(){
/*        include "koneksi.php";
        $query="select * from peserta";
        $data=mysqli_query($koneksi,$query);
 */
        include_once "dbku.php";
        $data=getdata("select *,(select nama from peserta where id=peserta2.idpeserta) as nama from peserta2");
        while($row=mysqli_fetch_array($data)){
            $idpeserta=$row['idpeserta'];
            $nama=$row['nama'];
            echo "<option value='$idpeserta'>$nama</option>\n";
        }
    }

?>

<script>
    function hitung() {
        alert("penghitungan nilai akhir disable sementara");
        return;
        let nilai1 = document.getElementById("nilai1").value;
        let nilai2 = document.getElementById("nilai2").value;
        let nilai3 = document.getElementById("nilai3").value;
        let total = parseInt(nilai1) + parseInt(nilai2) + parseInt(nilai3);
        let nilaiakhir = document.getElementById("nilaiakhir");
        nilaiakhir.innerText = total;
    }

    function simpan() {
        alert("disable sementara");
        return;
        if (!confirm("yakin simpan data?")) return;
        document.forms.formsimpan.submit();
    }

    let etable;
    let emulai;

    let eaudio1;
    let eaudio2;
    let eaudio3;
    let eaudio4;
    document.addEventListener("DOMContentLoaded", () => {
        etable = document.querySelector(".penilaian>div:nth-child(2)");
        emulai = gi("emulai");

        eaudio1 = gi("eaudio1");
        eaudio2 = gi("eaudio2");
        eaudio3 = gi("eaudio3");
        eaudio4 = gi("eaudio4");

    })

    function enablehakim(istrue) {
        if (istrue) {
            etable.style.display = "unset";
            emulai.style.display = "unset";
            clearInterval(ianim);

        } else {
            etable.style.display = "none";
            emulai.style.display = "none";
            ianim = setInterval(anim, 500);
        }
    }

</script>

<script name="jstimer">

    let ianim;
    let istrue = 1;
    let vbulat;

    let ewaktupersiapan;
    let ewaktupenilaian;
    let ewaktumenjelang;
    let ewaktuhabis;

    let ewaktupersiapan2;
    let ewaktupenilaian2;
    let ewaktumenjelang2;
    let ewaktuhabis2;

    let vwaktupersiapan;
    let vwaktupenilaian;
    let vwaktumenjelang;
    let vwaktuhabis;

    document.addEventListener("DOMContentLoaded", () => {
        ewaktupersiapan = gi("ewaktupersiapan");
        ewaktupenilaian = gi("ewaktupenilaian");
        ewaktumenjelang = gi("ewaktumenjelang");
        ewaktuhabis = gi("ewaktuhabis");

        ewaktupersiapan2 = gi("ewaktupersiapan2");
        ewaktupenilaian2 = gi("ewaktupenilaian2");
        ewaktumenjelang2 = gi("ewaktumenjelang2");
        ewaktuhabis2 = gi("ewaktuhabis2");

        vwaktupersiapan = parseInt(ewaktupersiapan.getAttribute("value"));
        vwaktupenilaian = parseInt(ewaktupenilaian.getAttribute("value"));
        vwaktumenjelang = parseInt(ewaktumenjelang.getAttribute("value"));
        vwaktuhabis = parseInt(ewaktuhabis.getAttribute("value"));
    });

    function anim() {
        istrue = !istrue;
        if (!istrue) {
            vbulat.style.transform = "scale(.3)";
        } else {
            vbulat.style.transform = "scale(1)";

        }
    }

    function pindahbulat(kelas) {
        qs(".bulat1").style.display = "none";
        qs(".bulat2").style.display = "none";
        qs(".bulat3").style.display = "none";
        qs(".bulat4").style.display = "none";

        qs(kelas).style.display = "block";
        vbulat = qs(kelas);

    }

    function hitungtimer() {

        enablehakim(false);
        let detik = 0;
        let detikx = 0;

        ewaktupersiapan2.innerText = "";
        ewaktupenilaian2.innerText = "";
        ewaktumenjelang2.innerText = "";
        ewaktuhabis2.innerText = "";

        let ipersiapan;
        let ipenilaian;
        let imenjelang;
        let ihabis;

        //mulai
        playsound1();
        detik = vwaktupersiapan;
        ewaktupersiapan2.innerText = formatwaktu(detik);
        ipersiapan = setInterval(fpersiapan, 1000);
        pindahbulat(".bulat1");

        function fpersiapan() {
            detik -= 1;
            ewaktupersiapan2.innerText = formatwaktu(detik);
            if (detik <= 0) {
                clearInterval(ipersiapan);

                detik = vwaktupenilaian;
                ewaktupenilaian2.innerText = formatwaktu(detik);
                ipenilaian = setInterval(fpenilaian, 1000);
                playsound2();
                pindahbulat(".bulat2");
            }
        }

        function fpenilaian() {
            detik -= 1;
            ewaktupenilaian2.innerText = formatwaktu(detik);
            if (detik == vwaktumenjelang) {
                cl("testst");
                detikx = vwaktumenjelang;
                ewaktumenjelang2.innerText = formatwaktu(detikx);
                imenjelang = setInterval(fmenjelang, 1000);
                playsound3();
                pindahbulat(".bulat3");

            }
            if (detik <= 0) {
                clearInterval(ipenilaian);
            }
        }

        function fmenjelang() {
            detikx -= 1;
            ewaktumenjelang2.innerText = formatwaktu(detikx);
            if (detikx <= 0) {
                clearInterval(imenjelang);
                detik = vwaktuhabis;
                ewaktuhabis2.innerText = formatwaktu(detik);
                ihabis = setInterval(fhabis, 1000);
                playsound4();
                pindahbulat(".bulat4");

            }
        }

        function fhabis() {
            detik -= 1;
            ewaktuhabis2.innerText = formatwaktu(detik);
            if (detik <= 0) {
                clearInterval(ihabis);
                cl("timer selesai");
                enablehakim(true);
                playsound5();
            }
        }

    }

    function formatwaktu(vdetik) {
        let menit = Math.floor(vdetik / 60);
        let detik = vdetik % 60;

        if (menit <= 10) {
            menit = "0" + menit;
        }
        if (detik <= 10) {
            detik = "0" + detik;
        }

        kata = menit + ":" + detik;
        return kata;
    }


</script>

<head></head>
<style>
    .box{
        border:1px solid;
    }
    .box .judul{
        border-bottom:1px solid;
    }
    .box>div:nth-child(1) {
        background-color: #eda8a8;
    }

    .nilai {
        display: flex;
        gap: 11px;
    }

    .box>div {
        padding: 11px;
    }

    .tengah {
        flex: 1;
    }

    .kiri,
    .kanan {
        flex-basis: 222px;
    }

    .kanan {
        display: flex;
        flex-direction: column;
        gap: 11px;
    }

    .bulat {
        border-radius: 50%;
        width: 31px;
        aspect-ratio: 1/1;
        transition: all .5s;
        display: none;
        border: 1px solid black;
    }

    .bulat1 {
        background-color: orange;
    }

    .bulat2 {
        background-color: green;
    }

    .bulat3 {
        background-color: yellow;
    }

    .bulat4 {
        background-color: red;
    }

    iframe{
        width: 100%;
        height:100%;
        border:1px solid;
        border-radius:2px;
    }
</style>

<h1>penilaian</h1>

<form action="" method="post" name=formsimpan>
    <div class="nilai">
        <div class="kiri">
            <div class="box penilaian">
                <div class=judul>penilaian</div>
                <div class=isi>

                    <?php
                        include_once "dbku.php";
                        $data=getdata("select * from bidang where idgolongan=$idgolongan");
                        while($row=mysqli_fetch_array($data)){
                            $namabidang=$row['nama'];
                            print "<h6>$namabidang</h6>";
                            print "<table class=table>";
                            print "<tr><th>hakim</th><th>nilai</th><tr>";

                            $datahakim=getdata("select * from hakim where idbidang=".$row['id']);
                            while($rowhakim=mysqli_fetch_array($datahakim)){
                                $hakim=$rowhakim['nama'];
                                print "<tr>
                                            <td>$hakim</td>
                                            <td><input></td>
                                        </tr>";

                            }
                            print "</table>";
                        }
                    ?>      
                    <br>
                    <input type="button" name="simpandata" value="rekap/simpan" onclick=simpan()>

                </div>
            </div>
            <div class="box nakhir">
                <div>nilai akhir</div>
                <div id="nilaiakhir">0</div>
            </div>
        </div>
        <div class="tengah">
            <!-- <iframe src="../emaqra">

            </iframe> -->
        </div>
        <div class="kanan">
            <div class="box timer">
                <div>timer</div>
                <div>
                    <table>
                        <tr>
                            <th>judul1</th>
                            <th>judul2</th>
                            <th>judul3</th>
                        </tr>
                        <tr>
                            <td>
                                waktu persiapan
                            </td>
                            <td id=ewaktupersiapan2></td>
                            <td>
                                <div class="bulat bulat1"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>waktu penilaian</td>
                            <td id=ewaktupenilaian2></td>
                            <td>
                                <div class="bulat bulat2"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>waktu menjelang habis</td>
                            <td id=ewaktumenjelang2></td>
                            <td>
                                <div class="bulat bulat3"></div>
                            </td>
                        </tr>
                        <tr>
                            <td>overtime</td>
                            <td id=ewaktuhabis2></td>
                            <td>
                                <div class="bulat bulat4"></div>
                            </td>
                        </tr>
                    </table><br>

                    <input id=emulai type="button" value="mulai" onclick="hitungtimer()">


                </div>
            </div>
            <div class="box peserta">
                <div>peserta</div>
                <div>
                    <select name="idpeserta" id="" required>
                        <?php getpeserta();?>
                    </select>
                </div>
            </div>
            <div class="box info">
                <div>info</div>
                <div>
                    <?php echo getinfo();?>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="audio">
    <audio id="eaudio1">
        <source src="../assets/sounds/ding-101492.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio2">
        <source src="../assets/sounds/metal-beaten-sfx-230501.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio3">
        <source src="../assets/sounds/metal-whoosh-hit-4-201906.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio4">
        <source src="../assets/sounds/sword-drawing-1-35903.mp3" type="audio/mpeg">
    </audio>
</div>


<script name=jsaudio>
    function playsound1() {
        eaudio1.play();
    }
    function playsound2() {
        eaudio2.play();
    }
    function playsound3() {
        eaudio3.play();
    }
    function playsound4() {
        eaudio4.play();
    }
    function playsound5() {
        eaudio2.play();
    }
</script>