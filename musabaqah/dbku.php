<?php

    include "koneksi.php";

    function execute($query){
        return mysqli_query($GLOBALS['koneksi'],$query);
    }

    function getonedata($query){
        //getone kolom and one row
        $data=execute($query);
        if(mysqli_num_rows($data)==0){
            return "";
        }else{
            $row=mysqli_fetch_array($data);
            return $row[0];
        }
    }



    function savevalue($arr){

        for($i=0;$i<count($arr);$i++){
            $arr[$i]=str_replace("'","\'",$arr[$i]);
            // $arr[$i]=$arr[$i] . " hehehe";
        }
        // $tmp2=implode(",",$arr);
        // $tmp2=str_replace("'","\'",$tmp2);
        // $arr=explode(",",$tmp2);
        return $arr;
    }
    function dbupdate($tabel,$arrkolom,$arrvalue,$where){
        //for sql inject
        $arrvalue=savevalue($arrvalue);

        $tmp="";
        for ($i=0; $i < count($arrkolom); $i++) { 
            $tmp.="$arrkolom[$i] = '$arrvalue[$i]',";
        }
        $tmp=substr($tmp,0,strlen($tmp)-1);
        $query="update $tabel set $tmp $where";
        //update aaa set k='',k2='' where id=0;
        // die($query);
        execute($query);
    }

    function dbinsert($tabel,$arrkolom,$arrvalue){
        //for sql inject
        $arrvalue=savevalue($arrvalue);

        $kolom=implode(',',$arrkolom);
        $value=implode("','",$arrvalue);
        $value="'$value'";
        $query="insert into $tabel($kolom) values($value)";
        // die($query);
        execute($query);
    }

    function dbdelete($tabel,$id){
        $query="delete from $tabel where id=$id";
        execute($query);
    }

    function getdata($query){
        return execute($query);
        //sama seperti execute, mungkin dideprecate
    }

    function getonebaris($query){
        $data=execute($query);
        return mysqli_fetch_array($data);//ambil yg pertama
    }


?>

