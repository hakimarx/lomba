
<?php
$data=array(
 // array("","","","",""),
 // array("qiraat","nama1","nama2","link1","link2"),
    array("MUSHAF IMAM NAFI'","qolun","warsy","01qolun.pdf","02warsy.pdf"),
    array("MUSHAF IMAM IBNU KATSIR","al-bazzi","qunbul","03albazzi.pdf","04qunbul.pdf"),
    array("MUSHAF IMAM ABU 'AMR","ad-duri","as-susi","05adduri.pdf","06assusi.pdf"),
    array("MUSHAF IMAM IBNU 'AMIR","hisyam","ibnu dzakwan","07hisyam.pdf","08ibnudzakwan.pdf"),
    array("MUSHAF IMAM 'ASHIM","syubah","hafsh","09syubah.pdf","10hafsh.pdf"),
    array("MUSHAF IMAM HAMZAH","kholaf","khollad","11kholaf.pdf","12khollad.pdf"),
    array("MUSHAF IMAM ALI AL-KISAI","abul harits","hafshadduri","13abulharits.pdf","14hafshadduri.pdf"),
    array("MUSHAF IMAM ABU JA'FAR","ibnu wardan","ibnu jammaz","15ibnuwardan.pdf","16ibnujammaz.pdf"),
    array("MUSHAF IMAM YA'CUB","ruwais","rawh","17ruwais.pdf","18rawh.pdf"),
    array("MUSHAF IMAM KHOLAF","idris","ishaq","19idris.pdf","20ishaq.pdf"),
//    array("KAIDAH QIRAAT TUJUH","","","",""),
);

$id=$_GET['id'];
$qiraat=$data[$id][0];
$nama1=$data[$id][1];
$nama2=$data[$id][2];
$link1=$data[$id][3];
$link2=$data[$id][4];

$id=$_GET['id'];
$idnext=$id+1;
if($idnext>=count($data)) $idnext=0;
$href="?id=".$idnext;
?>




<script>

    let scrollbox;
    document.addEventListener("DOMContentLoaded",()=>{
        scrollBox = document.getElementById('aaa');    

        scrollBox.addEventListener('scroll', function() {
            // Your code to execute on scroll within the div
            console.log('Div scrolled!');
    });

})
</script>


<style>
    *{
        margin:0;
        padding:0;
        box-sizing:border-box;
    }
    .error::after {
        content: "tidak bisa menampilkan pdf, kemungkinan file tidak ada/dibukak lewat localhost";
    }

    embed {
        border: 2px solid blue;
        flex:1;
    }
    .qiraat{
        flex-direction: column;
        height: 100%;
        color:white;
    }

    .judul{
        display:flex;
        background-color:black;
        padding:5px;
    }
    .judul>div{
        flex:1;
        text-align:center;  
    }
    form{
        display:inline;
    }
   a {
    background-color: blue;
    color: white;
    text-decoration: none;
    border-radius: 2px;
    padding: 5px 15px;
    display: inline-block;
}
</style>

<div class="qiraat flex">
    <div class="judul">
        <div><?php echo $nama1?> (atas)</div>
        <div><?php echo $qiraat?> <a href="<?php echo $href;?>">selanjutnya >></a> 
    </div>
        <div><?php echo $nama2?>  (bawah)</div>
    </div>
    <embed id=aaa type="application/pdf" src="../assets/qiraat/<?php echo $link1; ?>" height="100%" width="100%">
        <div class="error"></div>
    </embed>
    <embed type="application/pdf" src="../assets/qiraat/<?php echo $link2; ?>" height="100%" width="100%">
        <div class="error"></div>
    </embed>

</div>
