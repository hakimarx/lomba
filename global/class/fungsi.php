<?php
    function alert($kalimat){
        print("<script>xalert('$kalimat')</script>");
    }

    function islocalhost(){
        return $urlhost=$_SERVER['HTTP_HOST']=="localhost";
    }

    function fillselect($query,$value,$nama){
        // setpenilaian("select * from penilaian","id","nama");
        $data=getdata($query);
        while($row=mysqli_fetch_array($data)){
            echo "<option value=$row[$value]>$row[$nama]</option>";
        }
    }


?>