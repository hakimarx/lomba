<?php
    include_once __DIR__ . "/../dbku.php";
    include_once __DIR__ . "/../../global/class/userrole.php";
    // only adminprov can manage admin kab/ko
    openfor(["adminprov"]);

    function hapus($id){
        $vtabel=$GLOBALS['vtabel'];
        dbdelete($vtabel,$id);
    }

    function update($id=""){
        $vtabel=$GLOBALS['vtabel'];
        $vpost=array();
        foreach ($GLOBALS['kolomtrue'] as $item) {
            array_push($vpost,$_POST[$item]);            
        }
        // ensure user level is set to adminkabko (id 101)
        for($i=0;$i<count($GLOBALS['kolomtrue']);$i++){
            if($GLOBALS['kolomtrue'][$i]=='iduser_level'){
                $vpost[$i]=101;
                break;
            }
        }

        if($id==""){    //tambah
            dbinsert($vtabel,$GLOBALS['kolomtrue'],$vpost);
        }else{          //edit
            dbupdate($vtabel,$GLOBALS['kolomtrue'],$vpost,"where id=$id");
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
    // manage only adminkabko users
    $vtabel="user";
    $querytabel="select *,(select nama from user_level where id=user.iduser_level) as level from user where iduser_level=101";
    $kolomtrue=["username","nama","iduser_level","password","kab_kota"];
    $kolomfake=["username","nama","level","password","kab_kota"];
    $vth=["username","nama","level","password","Kab/Kota"];

    $jskolom='let vkolom=["'.implode('","',$kolomtrue).'"]';

    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }

?>

<script>
    <?php echo $jskolom;?>;
</script>
<script src="../global/js/crud.js"></script>


<head></head>

<div class=judul>
    <h1>Admin Kab/Kota Management</h1>
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
        username<input type="text" name="username">
        nama<input type="text" name="nama">
        level <select name="iduser_level"><option value=101>admin kab ko</option></select>
        password<input type="text" name="password">
        kab/kota<input type="text" name="kab_kota" placeholder="Nama Kab/Kota (Sesuai data Hafidz)">
    </div>
    <div class=tombol>
            <input type="button" value="batal" onclick=popup(false)>
            <input type="submit" value="simpan">
    </div>
        </form>

    </div>
</div>
