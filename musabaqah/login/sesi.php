<?php



    function ensure_session(){
        if(session_status() === PHP_SESSION_NONE){
            if(!headers_sent()){
                session_start();
            }else{
                @session_start();
            }
        }
    }
    function setlogin($iduser){
        ensure_session();
        $_SESSION['islogin']=["iduser"=>$iduser];    
        session_write_close();    
    }
    function setlogout(){
        ensure_session();
        unset($_SESSION['islogin']);       
        session_write_close();    
    }

    function islogined(){
        ensure_session();
        $istrue=isset($_SESSION['islogin']);
        session_write_close();
        return $istrue;    
    }
    function getiduser(){
        ensure_session();
        if(isset($_SESSION['islogin']) && isset($_SESSION['islogin']["iduser"])){
            $iduser=$_SESSION['islogin']["iduser"];
            session_write_close();
            return $iduser;
        }
        session_write_close();
        return null;
    }
?>