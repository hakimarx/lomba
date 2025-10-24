<?php


    $vtabel="user_level";
    $querytabel="select * from $vtabel";
    $kolomtrue=["nama","iduser_role"];
    $kolomfake=$kolomtrue;
    $vth=$kolomfake;

    $jskolom='let vkolom=["'.implode('","',$kolomtrue).'"];';

    include "../global/class/crud.php"; 
    

?>

<script>
    <?php echo $jskolom;?>
</script>
<script src="../global/js/crud.js"></script>


<head></head>

<div class=judul>
    <h1>data <?php echo $vtabel;?></h1>
    <input class="button padding panel" type="button" value="tambah data" onclick=tambah()>
</div>
<div style="overflow:auto">
    <?php showtabel();?>
</div>

<div class="popup" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
	    <div class="isi">
    	    nama<input type="text" name="nama" required>
    	    user role
            <select name="iduser_role" required>
                <option value="">-- pilih --</option>
                <?php
                    $data=getdata("select * from user_role");
                    while($row=mysqli_fetch_array($data)){
                        echo "<option value=$row[id]>$row[nama]</option>";
                    }
                ?>
            </select>
        </div>
        <div class=tombol>
            	<input type="button" value="batal" onclick=popup(false)>
            	<input type="submit" value="simpan">
	    </div>
        </form>

    </div>
</div>