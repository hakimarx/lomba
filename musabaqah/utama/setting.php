<?php


die("for future use");

function getdata(){
    include __DIR__ . "/../koneksi.php";
    $query="select * from setting";
    $data=mysqli_query($koneksi,$query);
    $row=mysqli_fetch_array($data);
    $waktu1=$row['waktu1'];
    $waktu2=$row['waktu2'];
    $jmljuri=$row['jmljuri'];
    $kata="<td id=tdwaktu1>$waktu1</td>
        <td id=tdwaktu2>$waktu2</td>
        <td id=tdjmljuri>$jmljuri</td>
        <td><input type=button value=edit onclick='edit()'</td>";

    return $kata;
 
}

if(isset($_POST['cmdsimpan'])){
    $waktu1=$_POST['waktu1'];
    $waktu2=$_POST['waktu2'];
    $jmljuri=$_POST['jmljuri'];

    $query="update setting set waktu1='$waktu1',waktu2='$waktu2',jmljuri='$jmljuri'";
    include __DIR__ . "/../koneksi.php";
    include __DIR__ . "/dbku.php";
    execute($koneksi,$query);
}

//print_r($_POST);
?>

<script>
    function edit(){
        gi("waktu1").value=gi("tdwaktu1").innerText;
        gi("waktu2").value=gi("tdwaktu2").innerText;
        gi("jmljuri").value=gi("tdjmljuri").innerText;
        popup(true);
    }
    function popup(isopen){
        if(isopen){
            document.getElementById("popup").style.display="flex";
        }else{
            document.getElementById("popup").style.display="none";

        }
    }
</script>
<head></head>

<h2>pengaturan</h2>
<div style="overflow:auto">
<table>
    <tr>
        <th>waktu 1</th>
        <th>waktu 2</th>
        <th>jumlah juri</th>
        <th>edit</th>
    </tr>
    <tr>
        <?php echo getdata();?>
    </tr>
</table>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post">
            waktu1
            <input type="number" name="waktu1" id="waktu1" required>
            waktu2
            <input type="number" name="waktu2" id="waktu2" required>
            jumlah juri
            <input type="number" name="jmljuri" id="jmljuri" required>
            <div class=tombol>
                <input type="button" value="batal" onclick=popup(false)>
                <input type="submit" name="cmdsimpan" value="simpan">
            </div>
        </form>
    </div>
</div>
