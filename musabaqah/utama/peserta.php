<?php

    function hapus($id){
            $vtabel=$GLOBALS['vtabel'];
            dbdelete($vtabel,$id);
        }

    function update($id=""){
        $vtabel=$GLOBALS['vtabel'];

        $vpost=array();
        $vpostkolom=$GLOBALS['kolomtrue'];
        array_push($vpostkolom,"idgolongan");
        foreach ($GLOBALS['kolomtrue'] as $item) {
            array_push($vpost,$_POST[$item]);            
        }
        array_push($vpost,$GLOBALS['idgolongan']);

        if($id==""){    //tambah
            dbinsert("$vtabel",
                $vpostkolom,
                $vpost
                );

        }else{          //edit
            dbupdate("$vtabel",
                $vpostkolom,
                $vpost
                ,"where id=$id");
        }
    }

    function showtabel(){
        echo "<table class=table>";
        setth();
        settd();
        echo "</table>";
    }
    function setth(){
        $arrth=$GLOBALS['vth'];

        echo "<tr>";
            foreach ($arrth as $item) {
                echo "<th>$item</th>\n";
            }
            echo "<th class='thtombol'>edit</th>";
            echo "<th class='thtombol'>hapus</th>";
        echo "</tr>";
   

    }

    function settd(){
        $query=$GLOBALS['querytabel'];
        $kolomtrue=$GLOBALS['kolomtrue'];
        $kolomfake=$GLOBALS['kolomfake'];
        $data=getdata($query);
        while($row=mysqli_fetch_array($data)){
            $id=$row['id'];
            echo "<tr>";

                for ($i=0; $i < count($kolomtrue); $i++) {
                    $tdid="td".$id.$kolomtrue[$i];
                    $valuefake=$row[$kolomfake[$i]];
                    $valuetrue= $row[$kolomtrue[$i]];
                echo "<td id='$tdid' nilai='$valuetrue'>$valuefake</td>\n";
                }

                echo "<td><input type=button onclick='edit($id)' value=edit></td>";
                echo "<td><input type=button value=hapus onclick='hapus($id);'></td>";
            echo "</tr>\n";
        }
    }
?>

<?php

    $idgolongan=$_GET['idgolongan'];

    $vtabel="peserta";
    $querytabel="select *,
                (select nama from gender where id=peserta.idgender) as gender,
                (select nama from kafilah where id=peserta.idkafilah) as kafilah,
                (select nama from wilayah where id=peserta.idwilayah) as wilayah
                 from $vtabel where idgolongan=$idgolongan";
    $kolomtrue=["nama","nomor","idwilayah","idgender","idkafilah"];
    $kolomfake=["nama","nomor","wilayah","gender","kafilah"];
    $vth=$kolomfake;

    $jskolom='let vkolom=["'.implode('","',$kolomtrue).'"];';

    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }

    

?>

<script>
    <?php echo $jskolom;?>
</script>
<script src="../global/js/crud.js"></script>


<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class=judul>
    <h1>data <?php echo $vtabel;?></h1>
    <input class="button padding panel" type="button" value="tambah data" onclick=tambah()>
</div>
<div style="overflow:auto">
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
                    nomor<input type="text" name="nomor" required>
                    wilayah
                    <select name="idwilayah" required>
                        <option value="">-- pilih --</option>
                        <?php
                            $data=getdata("select * from wilayah");
                            while($row=mysqli_fetch_array($data)){
                                echo "<option value=$row[id]>$row[nama]</option>";
                            }
                        ?>
                    </select>
                    gender
                    <select name="idgender" required>
                        <option value="">-- pilih --</option>
                        <?php
                            $data=getdata("select * from gender");
                            while($row=mysqli_fetch_array($data)){
                                echo "<option value=$row[id]>$row[nama]</option>";
                            }
                        ?>
                    </select>

                    kafilah
                    <select name="idkafilah" required>
                        <option value="">-- pilih --</option>
                        <?php
                            $data=getdata("select * from kafilah");
                            while($row=mysqli_fetch_array($data)){
                                echo "<option value=$row[id]>$row[nama]</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" value="simpan">
                </div>
            </div>
        </form>
    </div>
</div>