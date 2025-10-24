<?php
    function setvar($rowgolongan){
        echo "
            let vwaktupersiapan=$rowgolongan[waktupersiapan];
            let vwaktupenilaian=$rowgolongan[waktupenilaian];
            let vwaktumenjelang=$rowgolongan[waktumenjelang];
            let vwaktuhabis=$rowgolongan[waktuhabis];
        ";
    }

    function getlink($idgolongan){
        $kode=getonedata("select kodemenu_link from golongan where id=$idgolongan");
        $link=getonedata("select link from menu_link where kode='$kode'");
        return $link;
    }
?>

<?php
    $idgolongan=$_GET['idgolongan'];
    $rowgolongan=getonebaris("select * from golongan where id=$idgolongan");
?>

<script>

    function enablehakim(){
        
    }

    function anim() {
        istrue = !istrue;
        if (!istrue) {
            vbulat.style.transform = "scale(.3)";
        } else {
            vbulat.style.transform = "scale(1)";

        }
    }

    function pindahbulat(kelas) {
        return;
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

        <?php setvar($rowgolongan);?>

    document.addEventListener("DOMContentLoaded", () => {
        ewaktupersiapan = gi("ewaktupersiapan");
        ewaktupenilaian = gi("ewaktupenilaian");
        ewaktumenjelang = gi("ewaktumenjelang");
        ewaktuhabis = gi("ewaktuhabis");

        ewaktupersiapan2 = gi("ewaktupersiapan2");
        ewaktupenilaian2 = gi("ewaktupenilaian2");
        ewaktumenjelang2 = gi("ewaktumenjelang2");
        ewaktuhabis2 = gi("ewaktuhabis2");

    });


</script>


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

<script name=jsaudio2>
    let eaudio1,eaudio2,eaudio3,eaudio4;
    document.addEventListener("DOMContentLoaded",()=>{
        eaudio1=gi("eaudio1");
        eaudio2=gi("eaudio2");
        eaudio3=gi("eaudio3");
        eaudio4=gi("eaudio4");
    })

</script>


<style>
.timer{
    display:flex;
    height:100%;
}
.kiri {
    flex: 45;
    display: flex;
    flex-direction: column;
    padding: 11px;
}
.kanan{
    flex:100;
}
iframe{
    width: 100%;
    height:100%;
}

.bulat {
    border: 1px solid black;
    border-radius: 50%;
    width: 51px;
    aspect-ratio: 1/1;
    display:inline-block;
}
.kuning{
    background-color:yellow;
}
.hijau{
    background-color:green;
}
.merah{
    background-color:red;
}
th{
    width: 50%;
}
td{
    text-align:center;
}
</style>


<div class="timer">
    <div class="kiri">
        <table class=table>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <tr>
                <td><div class="bulat kuning"></div></td>
                <td><div id="ewaktupersiapan2"></div></td>
            </tr>
            <tr>
                <td><div class="bulat hijau"></div></td>
                <td><div id="ewaktupenilaian2"></div></td>
            </tr>
            <tr>
                <td><div class="bulat merah"></div></td>
                <td><div id="ewaktumenjelang2"></div></td>
            </tr>
            <tr>
                <td><div class="bulat merah"></div></td>
                <td><div id="ewaktuhabis2"></div></td>
            </tr>
        </table>

        <div id="ewaktupersiapan"></div>
        <div id="ewaktupenilaian"></div>
        <div id="ewaktumenjelang"></div>
        <div id="ewaktuhabis"></div>


        <input class="button panel padding" type="button" value="mulai" onclick=hitungtimer()>

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


    </div>
    <div class="kanan">
        <!-- <iframe src="../emaqra/?page=mushaf" frameborder="0"></iframe> -->
        <iframe src="<?php echo getlink($idgolongan); ?>" frameborder="0"></iframe>
         
    </div>
</div>
