<?php
    function setdata(){
        $data=getdata("select nomor,nik,nama,(select nama from gender where id=peserta.idgender) as gender from peserta order by nomor");
        while($row=mysqli_fetch_array($data)){
            echo "<tr>
                    <td>$row[nomor]</td>
                    <td>$row[nik]</td>
                    <td>$row[nama]</td>
                    <td>$row[gender]</td>
                </tr>";
        }


    }

    function acaknomor(){
        //siap2 nomor
        $nrow=getonedata("select count(1) from peserta");
        $arrnomor=[];
        for($i=1;$i<=$nrow;$i++){
            array_push($arrnomor,"000$i");
        }
        shuffle($arrnomor);
        // print_r($arrnomor);

        //get id
        $arrid=[];
        $data=getdata("select id from peserta");
        while($row=mysqli_fetch_array($data)){
            array_push($arrid,$row['id']);
        }
        // alert("berhasil acak nomor");
        // print_r($arrid);
        //update acak
        for($i=0;$i<count($arrid);$i++){
            $query="update peserta set nomor='$arrnomor[$i]' where id=$arrid[$i]";
            execute($query);
            // echo "$query<br>";
        }
    }
?>


<?php
    // print_r($_POST);
    if(isset($_POST['acak'])){
        acaknomor();
    }
?>

<script>
    function acak(){
        document.forms.form1.submit();
    }
</script>

<form action="" method="post" name=form1>
    <input type="hidden" name="acak">
</form>

<div class="vmaxwidth centermargin">
    <input class="panel button padding" type="button" value="acak nomor" onclick=acak()>

    <table class="table widthfull">
        <tr>
            <th>nomor</th>
            <th>nik</th>
            <th>nama</th>
            <th>gender</th>
        </tr>
        <?php
            setdata();
        ?>
    </table>
</div>
