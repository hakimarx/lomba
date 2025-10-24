<?php

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
    if(isset($_POST['crud'])){
        $mode=$_POST['crud'];
        $crudid=$_POST['crudid'];
        if($mode=="hapus") hapus($crudid);
        if($mode=="tambah") update();
        if($mode=="edit") update($crudid);
    }

?>