<?php

function openfor($arrrole){
    // die("sdsd");
    $iduser=getiduser();
    $rowuser=getonebaris("select * from view_user where iduser=$iduser");
    $roleuser=$rowuser["role"];

    $isada=false;
    foreach ($arrrole as $item) {
        if($item==$roleuser){
            $isada=true;
            break;
        }
    }

    if(!$isada){
        die("akun anda tidak bisa mengakses halaman ini");
    }
}

?>