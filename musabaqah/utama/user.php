<?php
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov"]);
    function ekse($query){
        include_once __DIR__ . "/../koneksi.php";
        mysqli_query($koneksi,$query) or die("ada error");
    }
    function hapus($id){
        $query="delete from user where id=$id";
        ekse($query);
    }

    function update($id=""){
    	//$nama=$_POST['nama'];

        $vpost=array();
        foreach ($GLOBALS['kolomtrue'] as $item) {
            array_push($vpost,$_POST[$item]);            
        }

        if($id==""){    //tambah
            $query="insert into user (";
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

        }else{          //edit
            $query="update user set ";
            for($i=0;$i<count($vpost);$i++){
                $query.=$GLOBALS['kolomtrue'][$i]."='".$vpost[$i]."',";
            }
            $query=substr($query, 0,strlen($query)-1);
            $query.=" where id=$id";
        }
        //die($query);
        ekse($query);
    }

    function tampil($query){
        include_once __DIR__ . "/../koneksi.php";
        $kolomtrue=$GLOBALS['kolomtrue'];
        $kolomfake=$GLOBALS['kolomfake'];
        $mquery=mysqli_query($koneksi,$query);

        $arrth=$kolomfake;
        //th
        echo "<tr>";
        foreach ($arrth as $item) {
            echo "<th>$item</th>\n";
        }
        echo "<th class='thtombol'>edit</th>";
        echo "<th class='thtombol'>hapus</th>";
        echo "</tr>";

        //td
        while($row=mysqli_fetch_array($mquery)){
            $id=$row['id'];
            echo "<tr>";

            for ($i=0; $i < count($kolomtrue); $i++) {
                $tdid="td".$id.$kolomtrue[$i];
                $valuefake=$row[$kolomfake[$i]];
                $valuetrue= $row[$kolomtrue[$i]];
               echo "<td id='$tdid' nilai='$valuetrue'>$valuefake</td>\n";
            }

            echo "  <td><input type=button onclick='edit($id)' value=edit></td>";
            echo "  <td><input type=button value=hapus onclick='hapus($id);'></td>";
            echo "</tr>\n";
        }
    }

?>

<?php
    $querytabel="select *,(select nama from user_level where id=user.iduser_level) as level from user";
    $kolomtrue=["username","nama","iduser_level","password"];
    //$kolomfake=$kolomtrue;
    $kolomfake=["username","nama","level","password"];

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
    function hapus(id) {
        if (!confirm("yakin ingin menghapus data?")) return;

        gi("crud").value = "hapus";
        gi("crudid").value = id;
        document.forms['formcrud'].submit();
    }

    function popup(isopen) {
        if (isopen) {
            gi("popup").style.display = "flex";
        } else {
            gi("popup").style.display = "none";
        }

    }

    function tambah() {
        gi("crud").value = "tambah";

        vkolom.forEach((item)=>{
            qs("[name='" + item + "'").value="";
        });

        popup(true);
    }
    function edit(id) {
        gi("crud").value = "edit";
        gi("crudid").value = id;

        vkolom.forEach((item)=>{
            qs("[name='" + item + "'").value=gi("td"+id+item).getAttribute("nilai");
        });

        popup(true);

    }
</script>

<head>
    <link rel="stylesheet" href="../global/css/isian.css">
</head>

<div class="vmaxwidth centermargin">
    <h1>data user</h1>
    <input class="button padding panel" type="button" value="tambah data" onclick=tambah()>
    <table class=table>
        <?php tampil($querytabel);?>
    </table>
</div>

<div class="modal" id=popup>
    <div class="box">
        <form action="" method="post" name=formcrud>
            <input type="hidden" name="crud" id="crud">
            <input type="hidden" name="crudid" id="crudid">
            <div class="isian">
                <div class="isi">
                    username<input type="text" name="username" required>
                    nama<input type="text" name="nama" required>
                    level <select name="iduser_level" required>
                        <option value="">-- pilih level --</option>
                        <?php
                            $data=getdata("select * from user_level");
                            while($row=mysqli_fetch_array($data)){
                                $value=$row['id'];
                                $label=$row['nama'];
                                echo "<option value='$value'>$label</option>";
                            }
                        ?>
                    </select>
                    password<input type="text" name="password" required>
                </div>
               <div class=footer>
                    <input class="button padding" type="button" value="batal" onclick=popup(false)>
                    <input class="button padding" type="submit" value="simpan">
                </div>
 
            </div>
            </form>

    </div>
</div>