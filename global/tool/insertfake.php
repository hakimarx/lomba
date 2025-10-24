<?php
    include "../../musabaqah/dbku.php";
    function stt($kata){
        $GLOBALS['status'].="$kata<br>";
    }
?>
<?php
    function dbinsert2($tabel,$kolom,$arrvalue,$indexacak=-1,$nloop=1){
        $arrvaluetrue=$arrvalue;
        for($i=1;$i<=$nloop;$i++){
            if($indexacak>=0){
                $acak=rand(1111,9999);
                $arrvaluetrue[$indexacak]=$arrvalue[$indexacak]." $acak";
            }
            $value=implode("','",$arrvaluetrue);

            $query="insert into $tabel ($kolom) values ('$value')";
            stt($query);
            // execute($query);
            $arrkolom=explode(",",$kolom);
            dbinsert($tabel,$arrkolom,$arrvaluetrue);
        }
    }

    function insertnilai(){
        $datapeserta=getdata("select id from peserta limit 4");
        $arrpeserta=mysqli_fetch_all($datapeserta);

        stt("--nilai--");
        $datagolongan=getdata("select id from golongan");
        // echo mysqli_num_rows($datagolongan);
        while($rowgolongan=mysqli_fetch_array($datagolongan)){
            $idgolongan=$rowgolongan['id'];
            foreach ($arrpeserta as $item) {
                $idpeserta=$item[0];
                dbinsert2("nilai","idpeserta,idgolongan",["$idpeserta","$idgolongan"]);
            }
        }


    }
    function insertnilaibidang(){
        //nilai bidang

        $data=getdata("select * from nilai");
        while($rownilai=mysqli_fetch_array($data)){
            $idnilai=$rownilai['id'];
            $idgolongan=$rownilai['idgolongan'];
            // $databidang=getdata("select id from bidang where idgolongan=$idgolongan");
            $datahakim=getdata("select id from hakim where idbidang in (select id from bidang where idgolongan=$idgolongan)");
            $idhakim=-1;
            while($rowhakim=mysqli_fetch_array($datahakim)){
                $idhakim=$rowhakim['id'];
                $nilai=random_int(11,33);
                dbinsert2("nilai_bidang","idnilai,idhakim,nilai",["$idnilai","$idhakim","$nilai"]);
            }
        }

    }

    function insertfake(){
        $GLOBALS['status']='';
        dbinsert2("penilaian","nama",["penilaian"],0,4);
        dbinsert2("babak","nama",["babak"],0,4);
        dbinsert2("kafilah","nama",["kafilah"],0,4);

        $idwilayah=getonedata("select min(id) from wilayah");//khusus
        $idgender=getonedata("select min(id) from gender");//khusus
        $idpenilaian=getonedata("select min(id) from penilaian");
        $idbabak=getonedata("select min(id) from babak");
        $idkafilah=getonedata("select min(id) from kafilah");

        for($i=1;$i<=4;$i++){
            dbinsert2("event","nama",["event"],0);
            $idevent=mysqli_insert_id($GLOBALS['koneksi']);

            $ideventx=getonedata("select min(id) from event");
            execute("update event set aktif=1 where id=$ideventx");

            for($i2=1;$i2<=4;$i2++){
                dbinsert2("cabang","nama,idevent,idpenilaian",["cabang",$idevent,$idpenilaian],0);
                $idcabang=mysqli_insert_id($GLOBALS['koneksi']);
                for($i3=1;$i3<=4;$i3++){
                    dbinsert2("golongan","nama,idcabang,idbabak,waktupersiapan,waktupenilaian,waktumenjelang,waktuhabis",["golongan",$idcabang,$idbabak,5,5,4,3],0);
                    $idgolongan=mysqli_insert_id($GLOBALS['koneksi']);
                    for($i4=1;$i4<=4;$i4++){
                        dbinsert2("peserta","nama,idgolongan,idwilayah,idgender,idkafilah",["peserta",$idgolongan,$idwilayah,$idgender,$idkafilah],0);

                        dbinsert2("bidang","nama,idgolongan",["bidang",$idgolongan],0);
                        $idbidang=mysqli_insert_id($GLOBALS['koneksi']);
                        for($i5=1;$i5<=4;$i5++){
                            dbinsert2("hakim","nama,idbidang",["hakim",$idbidang],0);
                        }

                    }

                }
            }
        }

        insertnilai();
        insertnilaibidang();
        stt("-- insert fake selesai --");
    }

    function insertfake2025(){

        $GLOBALS['status']='';

        dbinsert2("penilaian","nama",["penjurian1"]);
        dbinsert2("penilaian","nama",["penjurian2"]);
        dbinsert2("penilaian","nama",["penjurian3"]);
        dbinsert2("penilaian","nama",["cerdas-cermat"]);

        dbinsert2("babak","nama",["penyisihan"]);
        dbinsert2("babak","nama",["semi final"]);
        dbinsert2("babak","nama",["final"]);

        dbinsert2("kafilah","nama",["kafilah"],0,4);

        $idwilayah=getonedata("select min(id) from wilayah");//khusus
        $idgender=getonedata("select min(id) from gender");//khusus
        $idpenilaian=getonedata("select min(id) from penilaian");
        $idbabak=getonedata("select min(id) from babak");
        $idkafilah=getonedata("select min(id) from kafilah");

        dbinsert2("event","nama",["Musabaqah Tilawatil Quran Regional - 2025"]);


        // $file="../../assets/manifest/Jawa Timur - Musabaqah Tilawatil Qur'an - 2025.xml";
        $file="../../assets/manifest/Nasional - Seleksi Tilawatil Qur'an - 2020.xml";
        $xml=simplexml_load_file($file) or die("Error: Cannot create object");
        $arrcabang=$xml->Datas->Cabang;

        $idevent=getonedata("select max(id) from event");
        execute("update event set aktif=1 where id=$idevent");

        foreach ($arrcabang as $key) {
            $cabang=$key['name'];
            dbinsert2("cabang","nama,idevent,idpenilaian",[$cabang,$idevent,$idpenilaian]);
            $idcabang=mysqli_insert_id($GLOBALS['koneksi']);
            $arrgolongan=$key->Golongan;
            foreach ($arrgolongan as $key2) {
                $golongan=$key2['name'];
                $arrbidang=$key2->Bidang;
                // dbinsert2("golongan","nama,idcabang,idbabak",["$golongan","$idcabang","$idbabak"]);
                dbinsert2("golongan","nama,idcabang,idbabak,waktupersiapan,waktupenilaian,waktumenjelang,waktuhabis",[$golongan,$idcabang,$idbabak,5,5,4,3]);
                $idgolongan=mysqli_insert_id($GLOBALS['koneksi']);

                for($i4=1;$i4<=4;$i4++){
                    dbinsert2("peserta","nama,idgolongan,idwilayah,idgender,idkafilah",["peserta",$idgolongan,$idwilayah,$idgender,$idkafilah],0);
                }


                foreach ($arrbidang as $key3) {
                    $bidang=$key3['name'];
                    // $q="$cabang -> $golongan -> $bidang";
                    dbinsert2("bidang","idgolongan,nama",[$idgolongan,$bidang]);
                    $idbidang=mysqli_insert_id($GLOBALS['koneksi']);
                    for($i5=1;$i5<=4;$i5++){
                        dbinsert2("hakim","nama,idbidang",["hakim",$idbidang],0);
                    }
                }

            }

        }
        insertnilai();
        insertnilaibidang();
        stt("--insert manifest selesai--");

    }
?>

<?php
    //area hapus
    function dbdelete2($tabel){
        $query="delete from $tabel";
        execute($query);
    }
    function hapusdata($arrtable){
        $GLOBALS['status']='';
        foreach ($arrtable as $item) {
            stt("deleting '$item'");
            dbdelete2($item);
        }

        stt("-- delete data selesai--");
    }
    
    function viewtable($arrtable){
        $tmp=implode(",",$arrtable);
        echo $tmp;
    }

?>

<?php
    // print_r($_POST);
    $status="";
    $arrtable=[];
    //transaksi
    array_push($arrtable,"nilai_bidang","nilai","hakim","bidang","peserta","golongan","cabang","event");
    //master
    array_push($arrtable,"penilaian","babak","kafilah");

    if(isset($_POST['kirimdata'])) {
        $mode=$_POST['kirimdata'];
        if($mode==1){
            hapusdata($arrtable);
        }

        if($mode==2){
            insertfake();
        }

        if($mode==3){
            insertfake2025();
        }

    }

?>

<script>

    function pilih(mode){
        if(mode==1){
            if(!confirm("yakin ingin hapus data?")){
                return;
            }
        }
        earea.value="please wait...";
        document.querySelector("[name=kirimdata").value=mode;
        document.forms.form1.submit();
    }
</script>

<script>
    let earea;
    document.addEventListener("DOMContentLoaded",()=>{
        earea=document.getElementById("earea");
    })

</script>

insert data fake, dengan langkah2
<ol>
    <li>akan mengosongi table dibawah ini</li>
    <li><?php viewtable($arrtable) ?></li>
    <li>membuat data fake baru</li>
</ol>
<hr>

<form action="" method="post" name=form1>
    <input type="hidden" name="kirimdata">
    <input type="button" value="hapus data" onclick=pilih(1)>
    <input type="button" value="insert random fake" onclick=pilih(2)>
    <input type="button" value="insert fake manifest 2025" onclick=pilih(3)>
</form>

<div id=earea style="border:1px solid black;width: 726px; height: 297px;overflow:auto">
    <?php echo $status;?>
</div>


