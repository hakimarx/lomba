<?php
  function gethalbyayat($nosurat,$noayat){
    //   $query="select * from mushaf_halaman where surat=$nosurat and ayat<=$noayat order by ayat desc limit 1;";
    $query="select * from (
                select * from mushaf_halaman
                where surat<=$nosurat
                and ayat<=$noayat
                ) as x
                order by halaman desc limit 1";
    $row=getonebaris($query);
    if($row['surat']!=$nosurat){
        $query2="select * from (
                    select * from mushaf_halaman
                    where surat<$nosurat
                    ) as x
                    order by halaman desc limit 1";
        $row2=getonebaris($query2);
        $hasil=$row2['halaman'];

    }else{
        $hasil=$row['halaman'];
    }

    return $hasil;

  }

  function gethalbyjuz($juz){
    // if($juzz==1) return 1;
    // return ($juzz*20)-18;
    return getonedata("select halaman from mushaf_halaman where juz='$juz' limit 1");
  }
  function getjuzbyhal($hal){
    // if ($hal==1) return 1;
    // if($hal>=602) return 30;
    // return (floor(($hal-2) / 20))+1;
    return getonedata("select juz from mushaf_halaman where halaman='$hal'");
  }

  function sethalaman(){
    return;
    for($i=1;$i<=604;$i++){
      echo "<option value=$i>$i</option>\n";
    }
  }

  function setsurat(){
    $data=getdata("select * from mushaf_surat");
    while($row=mysqli_fetch_array($data)){
      echo "<option value=$row[urut] jmlayat=$row[jmlayat]>
              $row[nama] ($row[urut])
            </option>\n";
    }
  }

  function setjuzz(){
    for ($i=1; $i <=30 ; $i++) { 
      echo "<option value=$i>$i</option>\n";
    }
  }

?>

<?php

  if(isset($_GET['halaman'])){
      $halaman=$_GET['halaman'];
      $juzz=getjuzbyhal($halaman);
  }else{
      $halaman=1;
      $juzz=1;
  }

  if(isset($_GET['nosurat'])){
      $nosurat=$_GET["nosurat"];
      $noayat=$_GET["noayat"];

      $halaman=gethalbyayat($nosurat,$noayat);  
      $juzz=getjuzbyhal($halaman);
  }

  if(isset($_GET['juzz'])){
    $juzz=$_GET['juzz'];
    $halaman=gethalbyjuz($juzz);
  
  }


  $sesudah=$halaman+1;
  $sebelum=$halaman-1;
  if($sesudah>604) $sesudah=1;
  if($sebelum<=0) $sebelum=604;

  $jenis=$_GET['jenis'];
  if($jenis=="indonesia"){
    $link="../assets/mushaf/indonesia/$halaman.png";
  }else{
    $link="../assets/mushaf/madinah/$halaman.jpg";
  }


?>

<script>
function juz2() {
    let tmp = gi("juz2").value;
    if (tmp == "") return;
    juz(tmp);
}

function hal2() {
    let tmp = gi("hal2").value;
    if (tmp == "") return;
    hal(tmp);
}

function zoom(nilai) {
    vzoom += nilai;
    gi("img").style.width = vzoom + "%";
}

function hal(nilai) {
    let link = "?page=mushaf&jenis="+jenis+"&halaman=" + nilai;
    document.location = link;
}

function setayat(max) {

    let tmp = "";
    tmp += '<option value="">-- pilih ayat --</option>';
    for (let i = 1; i <= max; i++) {
        tmp += "<option>" + i + "</option>";
    }

    let eayat = gi("eayat");
    eayat.innerHTML = tmp;
}

function pilihsurat(o) {
    let index = o.options.selectedIndex;
    let jmlayat = o.options[index].getAttribute("jmlayat");
    setayat(jmlayat);
}

function changeayat() {
    let tmp = gi("eayat");
    let nayat = tmp.options[tmp.options.selectedIndex].value;
    if (nayat == "") {
        alert("pilih ayat !!");
        return;
    }

    let tmp2 = gi("esurat");
    let nsurat = tmp2.options[tmp2.options.selectedIndex].value;
    let link = "?page=mushaf&jenis="+jenis+"&nosurat=" + nsurat + "&noayat=" + nayat;
    document.location = link;

}

function juz(nilai) {
    let link = "?page=mushaf&jenis="+jenis+"&juzz=" + nilai;
    document.location = link;
}

function showmenu() {
    let tmp;
    if (qs(".bawah").style.display != "none") {
        tmp = "none";
    } else {
        tmp = "";
    }
    qs(".bawah").style.display = tmp;
    qs(".tombol").style.display = tmp;
}
</script>

<script>
let queryString;
let urlParams;

let jenis; 
let vzoom = 70;
// let vzoom = 64;
document.addEventListener("DOMContentLoaded", () => {
    queryString=window.location.search;
    urlParams=new URLSearchParams(queryString);
    jenis=urlParams.get('jenis');
    // alert(jenis); 
    qs("img").style.width = vzoom + "%";
})
</script>


<style>
* {
    text-align: center;
}

.mushaf .halaman>a,
.mushaf .halaman>div {
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: brown;
    padding: 27px 0;
}

img {
    width: 0;
}

select,
option {
    color: black;
    border-radius: 5px;
    border: 2px solid rgb(201 201 201);
    color: rgb(94 94 94);
}

.mushaf {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.atas {
    background-color: green;
    font-size: 19px;
    padding: 11px;
    display: flex;
}


.kiri {
    flex: 1;
    display: grid;
    place-content: center;
}

.tengah {
    flex: 1;
    overflow: auto;
}

.bawah {
    background-color: red;
    display: flex;
    justify-content: center;
    gap: 5px;
    padding: 5px;
    bottom: 0;
}

.pilihan {}

.tombol {
    /* background-color: blue; */
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: center;
    gap: 5px;
    position: fixed;
    top: 0;
    left: 5px;
    /* right:5px; */
}

.bawah input,
.bawah select {
    height: 33px;
}

.kiri,
.bulat {
    color: white;
}

.bulat {
    width: 49px;
    aspect-ratio: 1/1;
    background-color: orange;
    text-decoration: none;
    font-size: 22px;
    cursor: pointer;
    display: grid;
    place-content: center;
}

@media (max-width:768px) {
    .bawah {
        flex-direction: column;
    }

    select {
        width: 100%;
    }
}


.menu {
    background-color: black;
    color: white;
}

.menu>div {}

.hide {
    padding: 3px;
}

.bawah [type="button"] {
    padding: 0 10px;
}

.bawah [type=number] {
    flex: 1;
}
</style>

<div class="mushaf">
    <div class="atas">
        <div class="kiri">
            <?php echo "halaman $halaman, juz $juzz";?>
            <!-- |  Al-Baqarah (2) : 38-->
        </div>
        <div class="kanan">
            <input class="hide button padding" type="button" value="hide menu" onclick=showmenu()>
        </div>
    </div>

    <div class="tengah">
        <div class="tombol">
            <a class="bulat circle" href="?page=mushaf&jenis=<?php echo $jenis;?>&halaman=<?php echo $sesudah;?>">></a>
            <span class="bulat circle" onclick=zoom(5)>+</span>
            <span class="bulat circle" onclick=zoom(-5)>-</span>
            <a class="bulat circle" href="?page=mushaf&jenis=<?php echo $jenis;?>&halaman=<?php echo $sebelum;?>"><</a>

        </div>
        <div class="gambar">
            <?php  echo  "<img id=img src='$link'>";?>
        </div>
    </div>

    <div class="bawah nonex">
        <div class="halaman2 flex">
            <input type="number" placeholder="hlm" min=1 max=604 id=hal2>
            <input type="button" value="pilih" onclick=hal2()>
        </div>

        <div class="juzz2 flex">
            <input type="number" placeholder="juz" min=1 max=30 id=juz2>
            <input type="button" value="pilih" onclick=juz2()>

        </div>

        <div class="pilihan halaman none">
            <select name="aaa" id="" onchange=hal(this.value)>
                <option value="">-- pilih halaman --</option>
                <?php sethalaman(); ?>
            </select>
        </div>

        <div class="pilihan juzz none">
            <select onchange=juz(this.value)>
                <option value="">-- pilih juzz --</option>
                <?php setjuzz();?>
            </select>
        </div>

        <div class=pilihan>
            <select name="" id="esurat" onchange=pilihsurat(this)>
                <option value="">-- pilih surat --</option>
                <?php setsurat();?>
            </select>

            <select id="eayat" onchange=changeayat()>
                <option value="">-- pilih ayat --</option>
            </select>

        </div>
    </div>

</div>