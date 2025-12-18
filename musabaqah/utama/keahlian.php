<?php


    $vtabel="keahlian";
    $querytabel="select * from $vtabel";
    $kolomtrue=["nama"];
    $kolomfake=$kolomtrue;
    $vth=$kolomfake;

    $jskolom='let vkolom=["'.implode('","',$kolomtrue).'"];';

    include __DIR__ . "/../../global/class/crud.php"; 
    

?>

<script>
    <?php echo $jskolom;?>
</script>
<script src="../global/js/crud.js"></script>


<head>
<link rel="stylesheet" href="../global/css/isian.css">

</head>

<div class="centermargin vmaxwidth">
    <h1>data <?php echo $vtabel;?></h1>
    <input class="panel button padding" type="button" value="tambah data" onclick=tambah()>
    <?php showtabel();?>
</div>

<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    nama<input type="text" name="nama" required>
                </div>
                <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" value="simpan">
                </div>

            </div>
        </form>

    </div>
</div>