<?php

    function simpandata(){

        // print_r($_POST);die();

        //insert master
        $idpeserta=$_POST['idpeserta'];
        $idgolongan=$GLOBALS['idgolongan'];
        $query="insert into nilai (idpeserta,idgolongan) values('$idpeserta','$idgolongan')";
        execute($query);

        //insert detail
        // $idnilai=-99;
        $idnilai=mysqli_insert_id($GLOBALS['koneksi']);

        $vdata=$_POST['vdata'];
        $vdata=json_decode($vdata,true);

        $arritem=$vdata["root"];
        foreach ($arritem as $items) {
            $idhakim=$items['idhakim'];
            $nilai=$items['nilai'];
            $query="insert into nilai_bidang 
                    (idnilai,idhakim,nilai) values
                    ($idnilai,$idhakim,$nilai);";
            // echo $query."\n";
            execute($query);
        }

        // $query="";
        // execute($query);

        alert("tersimpan");

    }
?>

<?php

        $idgolongan=$_GET['idgolongan'];
        $rowgolongan=getonebaris("select *,
            (select nama from cabang where id=golongan.idcabang) as cabang,
            (select nama from babak where id=golongan.idbabak) as babak from golongan where id=$idgolongan");

        if(isset($_POST['vdata'])){
            simpandata();
        }


?>


<script>
    function tutupmodal() {
        let aa = qs("#myDialog iframe");
        let aa2 = aa.contentWindow.document.getElementById("hahaha");
        let idpeserta=aa2.value;
        qs("[name=idpeserta]").value=idpeserta;
        let index=aa2.selectedIndex;
        let teks=aa2.options[index].text;
        gi("enomor").innerText = teks;
        vmodal.close();
    }



    function isdiisi(){
                //is peserta  kosong      
        if(qs("[name=idpeserta]").value==""){
            alert("peserta tidak boleh kosong");
            return false;
        }
        //is nilai kosong
        let vnilais=document.querySelectorAll(".vnilai");
        let isdiisi=true;
        for (const item of vnilais) {
            if (item.value =="") {
                isdiisi=false;
                break; 
            }
        }

        if(!isdiisi){
            alert("semua nilai harus diinput");
            return;
        }

        return true;
    }
    function simpandata(){

        if(!isdiisi()) return;

        let evdata=qs("[name=vdata")
        let odata={};
        let evnilai=document.querySelectorAll(".vnilai");

        odata.root=[];

        evnilai.forEach(e => {
            idhakim=e.getAttribute("nidhakim");
            nilai=e.value;
            odata.root.push({idhakim:idhakim,nilai:nilai});
        });
        odata=JSON.stringify(odata)
        // cl(odata)
        evdata.value=odata;

        document.forms.formsimpan.submit();
    }

</script>

<script>

    let vmodal;
    document.addEventListener("DOMContentLoaded", () => {
        vmodal = gi("myDialog");
    })
</script>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .nilai {
        color: white;
        background-color: #020202;
    }

    .nilai table {
        color: white;
    }

    :root {
        --vradius: 3px;
        --vpadding: 10px;
        --vgap: 11px;
    }

    .card .isi {
        padding: var(--vpadding);
    }

    .c1 {
        background-color: red;
    }

    .c2 {
        background-color: green;
    }

    .c3 {
        background-color: blue;
    }

    .c4 {
        background-color: orange;
    }

    .c5 {
        background-color: pink;
    }


    .row1 {
        text-align: center;
        padding: var(--vpadding);
        border-radius: var(--vradius);
    }

    .row3 {
        display: flex;
        gap: 11px;
    }

    .row3 .kiri {
        flex: 1;
        border: 1px solid gray;
        padding: var(--vpadding);
        display: flex;
        gap: var(--vgap);
    }

    .card {
        border: 1px solid;
        border-radius: 5px;
    }

    .card .judul {
        border-bottom: 1px solid;
        padding: var(--vpadding);
    }

    .row3 .kanan {
        flex-basis: 222px;
        display: flex;
        flex-direction: column;
        gap: 11px;
    }

    table {
        border-collapse: collapse;
    }

    td {
        border: 1px solid gray;
        padding: 11px;
    }

    .row2 {
        display: flex;
        background-color: gray;
        padding: var(--vpadding);
        border-radius: var(--vradius);
    }

    .row2 .col1 {
        flex: 1;
    }

    .row2 .col3,
    .row2 .col2 {
        flex-basis: 254px;
    }

    .notif {
        background-color: red;
        color: white;
        border-radius: 3px;
        width: 22px;
        aspect-ratio: 1/1;
        text-align: center;
        align-content: center;
    }

    .klas2 {
        display: flex;
    }

    .klas2>div:nth-child(1) {
        padding: var(--vpadding);
    }

    .klas2>div:nth-child(2) {
        background-color: black;
        border-radius: var(--vradius);
        flex: 1;
        padding: var(--vpadding);
    }

    .bawah{
        display: flex;
        border-top:1px solid gray;
    }

        button {
        color: black;
        padding: 11px;
    }

    .modal {
        background-color: red;
    }

    dialog {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: black;
        color: white;
        border: 1px solid yellow;
        width: 222px;
        aspect-ratio: 1/1;
    }

    dialog .flex {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
    }
    .atas {
        padding: var(--vpadding);
        display: flex;
        flex-direction: column;
        gap: var(--vgap);
    }

    .bawah>div {
        padding: var(--vpadding);
        border-right: 1px solid gray;
    }
    .notif2 {
        display: inline;
        background-color: gray;
        color: black;
        border-radius: 2px;
        padding: 2px;
    }
    
    .nilai [type="number"] {
        background-color: black;
        border: 1px solid gray;
        color:white;
    }
</style>

<div class="nilai">
    <div class="atas">
        <div class="row1 c3"><?php echo "$rowgolongan[cabang] - $rowgolongan[nama]" ?></div>
        <div class="row2">
            <div class="col1  klas2">
                <div>babak</div>
                <div><?php echo "$rowgolongan[babak]";?></div>
            </div>
            <div class="col2 klas2">
                <div>nomor peserta</div>
                <div id=enomor></div>
            </div>
            <div class="col3 klas2">
                <div>nilai</div>
                <div></div>
            </div>
        </div>



    <form action="" name=formsimpan method=post>
        <input type="hidden" name="vdata">
        <input type="hidden" name="idpeserta">
    </form>

        <div class="row3">
            <div class="kiri">
                <?php
                $databidang=getdata("select * from bidang where idgolongan=$idgolongan");
                while($rowbidang=mysqli_fetch_array($databidang)){
                    $idbidang=$rowbidang['id'];
            ?>

                <table>
                    <tr>
                        <td colspan="2">
                            <?php echo $rowbidang['nama']?>
                            <div class="notif"><?php echo $rowbidang['max'] ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td>dewan hakim</td>
                        <td>nilai</td>
                    </tr>
                    <?php
                        $datahakim=getdata("select * from hakim where idbidang=$idbidang");
                        while($rowhakim=mysqli_fetch_array($datahakim)){
                            echo "<tr>";
                            echo "<td>$rowhakim[nama]</td>";
                            echo "<td><input class=vnilai type='number'  value='' nidhakim=$rowhakim[id] ></td>";
                            echo "</tr>";
                        }
                    ?>
                    <tr>
                        <td colspan="2">**</td>
                    </tr>
                </table>

                <?php
            }
        ?>
            </div>

            <div class="kanan">
                <div class="card">
                    <div class="judul">judul</div>
                    <div class="isi">isi</div>
                </div>
                <div class="card">
                    <div class="judul">judul</div>
                    <div class="isi">isi</div>
                </div>
                <div class="card">
                    <div class="judul">judul</div>
                    <div class="isi">isi</div>
                </div>
            </div>
        </div>

    </div>
    <div class="bawah">
    </div>
</div>
<br>

<input class="panel button padding" type="button" value="pilih peserta" onclick=vmodal.showModal();>
<input  class="panel button padding" type="button" value="simpan data" onclick=simpandata();>

<dialog id="myDialog">
    <div class="flex">
        <iframe src="?page=pesertacari" frameborder="0" width="100%" height="100%"></iframe>
        <div>
            <input type="button" value="pilih" onclick="tutupmodal()" ;>
        </div>

    </div>
</dialog>



