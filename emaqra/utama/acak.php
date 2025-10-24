

<script>
    document.addEventListener("DOMContentLoaded", () => {
        kalimatinput = gi("kalimatinput");
        kalimathasil = gi("kalimathasil");
        vkalimatreset = gi("kalimatreset");
        vkalimatacak = gi("kalimatacak");
        vkalimatstop = gi("kalimatstop");

        angkainput = gi("angkainput");
        angkainput2 = gi("angkainput2");
        angkahasil = gi("angkahasil");

        vangkareset = gi("angkareset");
        vangkaacak = gi("angkaacak");
        vangkastop = gi("angkastop");

        vkalimatreset.onclick = kalimatreset;
        vkalimatacak.onclick = kalimatacak;
        vkalimatstop.onclick = kalimatstop;

        vangkareset.onclick = angkareset;
        vangkaacak.onclick = angkaacak;
        vangkastop.onclick = angkastop;

        setdisplay(vangkastop, false);
        setdisplay(vkalimatstop, false);

        kalimatinput.value="";
        kalimatinput.value+="ini kalimat pertama\r\n";
        kalimatinput.value+="ini kalimat kedua\r\n";
        kalimatinput.value+="ini kalimat ketiga\r\n";
        kalimatinput.value+="ini kalimat keempat\r\n";
        kalimatinput.value+="ini kalimat kelima\r\n";

    })
</script>


<script>
    let vinterval2;
    let vinterval;

    let vangkareset;
    let vangkaacak;
    let vangkastop;
    let angkainput;
    let angkainput2;
    let angkahasil;

    let vkalimatreset;
    let vkalimatacak;
    let vkalimatstop;
    let kalimatinput;
    let kalimathasil;

    function setdisplay(e, istrue) {
        if (istrue) {
            e.style.display="unset";
        } else {
            e.style.display="none";
        }
    }
    function kalimatreset() {
        kalimatinput.value = "";
        kalimathasil.value = "";
    }

    function kalimatacak() {
        //kalimatinput.value="kali 1\nkali2\nkali 3\n kali4";

        if (kalimatinput.value == "") return;
        setdisplay(vkalimatreset, false);
        setdisplay(vkalimatacak, false);
        setdisplay(vkalimatstop, true);
        let kalimat = kalimatinput.value;
        const arr = kalimat.split("\n");
        let min = 0;
        let max = arr.length;

        vinterval = window.setInterval(() => {
            let hasil = arr[randint(min, max)];
            if (hasil != undefined) {
                kalimathasil.value = hasil;
            }
        }, 111);
    }
    function kalimatstop() {
        setdisplay(vkalimatreset, true);
        setdisplay(vkalimatacak, true);
        setdisplay(vkalimatstop, false);
        clearInterval(vinterval);
    }

    function angkareset() {
        angkainput.value = "";
        angkainput2.value = "";
        angkahasil.innerText="";
    }

    function angkaacak() {
        if (angkainput.value == "") return;
        setdisplay(vangkareset, false);
        setdisplay(vangkaacak, false);
        setdisplay(vangkastop, true);

        min = parseInt(angkainput.value);
        max = parseInt(angkainput2.value);
        vinterval2 = window.setInterval(() => {
            let tmp=randint(min, max);
            angkahasil.innerText=tmp;
        }, 111);
    }
    function angkastop() {
        setdisplay(vangkareset, true);
        setdisplay(vangkaacak, true);
        setdisplay(vangkastop, false);
        clearInterval(vinterval2);
    }

    function randint(min, max) {
        return Math.floor(min + Math.random() * (max - min + 1));
    }

</script>


<head></head>
<style>
    #angkahasil{
        font-size:6em;
    }

    #kalimatinput{
        /* width: 555px; */
        height:111px;
    }
    textarea{
        width: 100%;
        height: 111px;
    }
</style>

<div class="acak">
    <div class="vmaxwidth centermargin">
        <h1>acak</h1>


        <div class="tab">
            <div class="judul">
                <div class="item">acak angka</div>
                <div class="item">acak kalimat</div>
            </div>
            <div class="isi">
                <div class="item">
                <div class="panel flex flexcolumn gap border padding">
                    <input class="padding" id="angkainput" type="number" placeholder="masukkan angka pertama" value=111>
                    <input class="padding" id="angkainput2" type="number" placeholder="masukkan angka kedua" value=222>
                    <div>
                        <input class="button padding" type=button id="angkareset" value=reset>
                        <input class="button padding" type=button id="angkaacak" value=acak>
                        <input class="button padding" type=button id="angkastop" value=stop>
                    </div>
                </div>
                <div class="padding border center" id="angkahasil" >555</div>

                </div>
                <div class="item">
                    <div class="border panel padding flex gap flexcolumn">
                        <textarea id="kalimatinput" placeholder="Isikan kalimat yang akan diacak, pisahkan dengan Enter">
                        </textarea>
                        <div>
                            <input class="button padding" type=button id="kalimatreset" value=reset>
                            <input class="button padding" type=button id="kalimatacak" value=acak>
                            <input class="button padding" type=button id="kalimatstop" value=stop>
                        </div>
                    </div>
                    <textarea id="kalimathasil" contenteditable="true" placeholder="hasil acak kalimat"></textarea>

                </div>
            </div>
        </div>


    </div>

</div>