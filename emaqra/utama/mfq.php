<?php

  function settombol(){
    for ($i=0;$i<=14;$i++){
      print("<input class='button bggreen' type=button onclick='lihatsoal($i)' value='soal ke $i'>");
    }

  }

function getsoal(){
  $q="SELECT * FROM soal order by rand() limit 15";
  $data=getdata($q);

  $kalimat="";
  while($row=mysqli_fetch_array($data)){
      $vsoal=$row["soal"];
      $vjawaban=$row["jawaban"];
      $kalimat.="['$vsoal','$vjawaban'],<enter>";
  }

  $vsoal=$kalimat;
  $vsoal = str_replace(["\r\n", "\r", "\n"], "\\n",$vsoal);
  $vsoal = str_replace(["<enter>"], "\n",$vsoal);
  return $vsoal;
}

?>

<?php

if(isset($_POST['getacak'])){
  alert("soal telah teracak");
}

?>

<script>
    function lihatsoal(index) {
    let soal = vsoal[index][0];
    let jawaban = vsoal[index][1];

    esoal.innerText = soal;
    ejawaban.innerText = jawaban
    ejawaban.style.visibility = "hidden";

  }


</script>

<script>

  let eacak;
  let esoal;
  let ejawaban;

  document.addEventListener("DOMContentLoaded", () => {
    eacak = document.getElementById("eacak");
    esoal = document.getElementById("esoal");
    ejawaban = document.getElementById("ejawaban");
    elihatjawaban = document.getElementById("elihatjawaban");
    elihatjawaban.onclick = () => {
      ejawaban.style.visibility = "unset";
    }

  })

  const vsoal = [
/*      ["soal1","jawaban1"],["soal2","jawaban2"],["soal3","jawaban3"],
      ["soal4","jawaban4"],["soal5","jawaban5"],["soal6","jawaban6"],
      ["soal7","jawaban7"],["soal8","jawaban8"],["soal9","jawaban9"],
      ["soal10","jawaban10"],["soal11","jawaban11"],["soal12","jawaban12"],
      ["soal13","jawaban13"],["soal14","jawaban14"],["soal15","jawaban15"],
*/ 
<?php 
  print getsoal();
?>

];

</script>

<style>
  * {
    box-sizing: border-box;
  }

  .border {
    border: 1px solid;
  }

  :root {
    --vgap: 11px;
    --vpadding: 11px;
  }

  .mfq .tombol,
  .mfq .soal {
    margin-bottom: var(--vpadding);
  }

.mfq .judul {
    background-color: blue;
    color: white;
    display: flex;
    justify-content: space-between;
}
  .mfq .isi,
  .mfq .judul {
    padding: var(--vpadding);
  }

  .mfq .isi {
    min-height: 111px;
    font-size: 33px;
  }

  .gruptombol button {
    background-color: red;
    color: white;
    border: none;
    border-radius: 3px;
    padding: 11px;
    flex-basis: 88px;
    flex-shrink: 0;
  }

  #eacak {
    background-color: orange;
    border: none;
    color: white;
    border-radius: 5px;
  }

  .gruptombol [type=button] {
    padding: 11px;
}
</style>

<div class="vmaxwidth centermargin">
  <h1>mfq</h1>
    <div class="mfq flex gap">

    <div class="flexgrow">
      <div class="soal border">
        <div class="judul">soal</div>
        <div class="isi" id="esoal"></div>
      </div>

      <div class="jawaban border">
        <div class="judul" style="position: relative;">
          <div style="align-content: center;">jawaban</div>
          <input class="button bgred" type="button" id="elihatjawaban" value="lihat jawaban">
        </div>
        <div class="isi" id="ejawaban"></div>
      </div>

    </div>


    <div class="tombol">
      <form action="" method="post">
          <input class="widthfull padding" type=submit name=getacak id="eacak" value="acak soal">
      </form>
      <div class="gruptombol flex flexcolumn gap border padding">
        <?php settombol();?>
      </div>
    </div>
  </div>
</div>

