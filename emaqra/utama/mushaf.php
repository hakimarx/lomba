<?php

$jenis=$_GET['jenis'];
header("location:?page=mushaf&jenis=$jenis");
die("");
          //generate javascript
          include "koneksi.php";
          $query = mysqli_query($koneksi, "SELECT * from daftarsurah;") ;

          $kalimat="";
          while($data = mysqli_fetch_array($query)){
            $vnosurat=$data['nosurat']; 
            $vnama=$data['nama'];
            $vjumlahayat=$data['akhir'];
            $kalimat.="[$vnosurat,\"$vnama\", $vjumlahayat],\n";
          }
          //print($kalimat);
  ?>


<head></head>



<style>
  .mushaf{
    display: flex;
    flex-direction: column;
    border: 1px solid;
    margin: auto;
    padding: 17px;
    border: 1px solid;
    border-radius: 5px;
}
  .mushaf>div {
    flex: 1;
    padding-bottom: 17px;
  }

  .mushaf select,.mushaf button {
    width: 100%;
  }

  .mushaf button{
    background-color: green;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 11px;
    border: 4px solid pink;
  }
</style>

<h1>mushaf</h1>
<form action="" method="get" target="_blank">
  <input type="hidden" name="page" value="mushaf">
  <div class="mushaf" >
    <!-- kiri -->
        <div>
          <h3>pilih surat</h3>
          <select name="nosurat" id="vselect" onchange="ubah(this)">
            <option value="coba">coba</option>
          </select>
        </div>


    <!-- tengah-->
      <div>
        <h3>pilih ayat</h3>
        <select name="noayat" id="vselect2">
          <option value="coba">coba</option>
        </select>
      </div>
    <!--kanan-->
      <div>
      <input type="submit" value="lihat mushaf" style="
    width: 100%;
">
      </form>
      </div>
    </div>
  </form>



<script>
	let vdata = [
//		[1,"surat1", 3,7],
//		[2,"suraat9",4, 6],
//ygbaru 3 data		[2,"suraat9",4],
    <?php
      echo $kalimat;
    ?>
		];

		window.onload=function(){
      let vselect= document.getElementById('vselect');
      let vselect2= document.getElementById('vselect2');

      let kalimat="";
      for (let item of vdata){
        kalimat+="<option value='" + item[0]  + "'>" + item[1] + "</option>\n";
      };
      vselect.innerHTML=kalimat;
      ubah(vselect2);
      console.log(vselect);

	}

	
	function ubah(x){
		let index=x.selectedIndex;
		let vjumlahayat=vdata[index][2];	
		
		vselect2.innerHTML="";
		for (let i=1;i<=vjumlahayat;i++){
			vselect2.innerHTML+="<option value=" + i + ">" + i + "</option>";
		}
	}

	
</script>
