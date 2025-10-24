<?php


    $vtabel="_hapuskategori";
    $querytabel="select * from $vtabel";
    $kolomtrue=["nama"];
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
    <input type="button" value="tambah data" onclick=tambah()>
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


        </div>
        <div class=tombol>
            	<input type="button" value="batal" onclick=popup(false)>
            	<input type="submit" value="simpan">
	    </div>
        </form>

    </div>
</div>