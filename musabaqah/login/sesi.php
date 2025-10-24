<?php



    function setlogin($iduser){
        session_start();
        $_SESSION['islogin']=["iduser"=>$iduser];    
        session_write_close();    
    }
    function setlogout(){
        session_start();
        unset($_SESSION['islogin']);       
        session_write_close();    
    }

    function islogined(){
        session_start();
        $istrue=isset($_SESSION['islogin']);
        session_write_close();
        return $istrue;    
    }
    function getiduser(){
        session_start();
        $iduser=$_SESSION['islogin']["iduser"];
        session_write_close();
        return $iduser;

    }
?>