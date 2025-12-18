<?php

    function hapus($id){
            $vtabel=$GLOBALS['vtabel'];
            $query="delete from $vtabel where id=$id";
            execute($query);
        }

    function update($id=""){
        $vtabel=$GLOBALS['vtabel'];

        $vpost=array();
        foreach ($GLOBALS['kolomtrue'] as $item) {
            array_push($vpost,$_POST[$item]);            
        }

        if($id==""){    //tambah
            $query="insert into $vtabel (";
            for($i=0;$i<count($vpost);$i++){
                $query.=$GLOBALS['kolomtrue'][$i].",";
            }
            $query=substr($query, 0,strlen($query)-1);
            $query.=")";


            $query.=" values(";
            for($i=0;$i<count($vpost);$i++){
                $query.="'".$vpost[$i]."',";
            }
            $query=substr($query, 0,strlen($query)-1);
            $query.=")";
            execute($query);

        }else{          //edit
            $query="update $vtabel set ";
            for($i=0;$i<count($vpost);$i++){
                $query.=$GLOBALS['kolomtrue'][$i]."='".$vpost[$i]."',";
            }
            $query=substr($query, 0,strlen($query)-1);
            $query.=" where id=$id";
            execute($query);

            //logo penyelenggara
            if(isset($_FILES['logopenyelenggara']['tmp_name'])){
                $tmp_name=$_FILES['logopenyelenggara']['tmp_name'];
                $tujuan="../musabaqah/gambarevent/logopenyelenggara$id.jpg";
                if(move_uploaded_file($tmp_name,"$tujuan")){
                    $query="update event set logopenyelenggara='$tujuan' where id=$id";
                    execute($query);
                } else die("ada error");
            }
            //logo acara
            if(isset($_FILES['logoacara']['tmp_name'])){
                $tmp_name=$_FILES['logoacara']['tmp_name'];
                $tujuan="../musabaqah/gambarevent/logoacara$id.jpg";
                if(move_uploaded_file($tmp_name,"$tujuan")){
                    $query="update event set logoacara='$tujuan' where id=$id";
                    execute($query);
                } else die("ada error");
            }


        }
    }

    function showtabel($vth){
        echo "<table class=table>";
        setth($vth);
        settd();
        echo "</table>";
    }
    function setth($vth){
        $arrth=$vth;

        echo "<tr>";
            foreach ($arrth as $item) {
                echo "<th>$item</th>\n";
            }
            echo "<th>logo penyelenggara</th>";
            echo "<th>logo acara</th>";
            echo "<th class='thtombol'>aktif</th>";
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

                $linklogopenyelenggara="belum diset";
                $linklogoacara="belum diset";

                $tmp=$row['logopenyelenggara'];
                if($tmp!=""){
                    $linklogopenyelenggara="<img width=44px src='$tmp'>";
                }
                $tmp=$row['logoacara'];
                if($tmp!=""){
                    $linklogoacara="<img width=44px src='$tmp'>";
                }

                echo "<td>$linklogopenyelenggara</td>";
                echo "<td>$linklogoacara</td>";
                
                echo "<td>$row[aktif] <input class='button padding' type=button value='set aktif' onclick=setaktif($id)></td>";
                echo "<td><input class='button padding' type=button onclick='edit2($id)' value=edit></td>";
                echo "<td><input class='button padding' type=button value=hapus onclick='hapus($id);'></td>";
            echo "</tr>\n";
        }
    }
?>

<?php

    //print_r($_POST);
    $vtabel="event";
    $querytabel="select * from $vtabel";
    $kolomtrue=["nama","tingkat","seri","lokasi","mulai","selesai","batasumur","kota"];
    $kolomfake=$kolomtrue;
    $vth=$kolomfake;

    $jskolom='let vkolom=["'.implode('","',$kolomtrue).'"];';
    
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }

    if(isset($_POST['setaktif'])){
        $tmp=$_POST['setaktif'];
        $query="update event set aktif=0";
        execute($query);
        $query="update event set aktif=1 where id=$tmp";
        execute($query);
        alert("set aktif berhasil");
    }

?>

<script>
    <?php echo $jskolom;?>
</script>
<script src="../global/js/crud.js"></script>

<script>
    function setaktif(id){
        qs("[name=setaktif]").value=id;
        document.forms.formsetaktif.submit();
    }
    function tambah2(){
        gi("logo").style.display="none";
        tambah();
    }
    function edit2(id){
        gi("logo").style.display="unset";
        edit(id);
    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>


<style>
    [type=file]{
        border:1px solid gray;
        width: 100%;
    }
</style>

<form action="" method="post" name=formsetaktif>
    <input type="hidden" name="setaktif">
</form>

<div class="centermargin">
    <h1>data <?php echo $vtabel;?></h1>
    <input  class="panel button padding" type="button" value="tambah data" onclick=tambah2()>
    <div style="overflow:auto">
        <?php showtabel($vth);?>
    </div>

</div>




<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud  enctype="multipart/form-data">
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    nama<input type="text" name="nama" required>
                    tingkat<input type="text" name="tingkat" required>
                    seri<input type="number" name="seri" required>
                    lokasi<input type="text" name="lokasi" required>
                    mulai<input type="date" name="mulai" required>
                    selesai<input type="date" name="selesai" required>
                    batas umur<input type="date" name="batasumur" required>
                    kota<input type="text" name="kota" required>
                    <!-- <div id="logo">
                        logo penyelenggara<input type="file" name="logopenyelenggara"  accept="image/*">
                        logo acara<input type="file" name="logoacara" accept="image/*"> 
                    </div> -->
                </div>
                <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" value="simpan">
                </div>
                
            </div>
        </form>

    </div>
</div>