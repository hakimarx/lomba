<?php
    function ensure_session_hafidz(){
        if(session_status() === PHP_SESSION_NONE){
            if(!headers_sent()){
                session_start();
            }else{
                @session_start();
            }
        }
    }
    function setlogin_hafidz($idhafidz){
        ensure_session_hafidz();
        $_SESSION['islogin_hafidz']=["idhafidz"=>$idhafidz];
        session_write_close();
    }
    function setlogout_hafidz(){
        ensure_session_hafidz();
        unset($_SESSION['islogin_hafidz']);
        session_write_close();
    }
    function islogined_hafidz(){
        ensure_session_hafidz();
        $istrue=isset($_SESSION['islogin_hafidz']);
        session_write_close();
        return $istrue;
    }
    function getid_hafidz(){
        ensure_session_hafidz();
        $idhafidz=$_SESSION['islogin_hafidz']["idhafidz"];
        session_write_close();
        return $idhafidz;
    }
?>
