<head></head>


<select name="" id="hahaha">
    <?php
        $data=getdata("select * from peserta");
        while($row=mysqli_fetch_array($data)){
            echo "<option value='$row[id]'>$row[nomor] - $row[nama]</option>\n";
        }
    ?>
    
</select>