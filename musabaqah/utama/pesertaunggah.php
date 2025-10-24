
<?php


$idpeserta=$_GET['idpeserta'];
    function getlink($id,$namafile){
        if($namafile=="foto") {
            return "../assets/documents/foto/peserta$id"."_$namafile.jpg";
        }
        return "../assets/documents/pdf/peserta$id"."_$namafile.pdf";
    }

    $arr=array("foto","mandat","kk","akte","piagam","cv");
    foreach ($arr as $item) {
        //echo $item;
        cekupload($item);
    }


    function cekupload($nama){
        if(isset($_FILES['file_'.$nama])){
            $tmp_name=$_FILES['file_'.$nama]['tmp_name'];
            if($tmp_name=="") return;
            $tujuan=getlink($GLOBALS['idpeserta'],$nama);
            if(!move_uploaded_file($tmp_name,$tujuan)){
                die("ada error");
            }
        }

    }

?>

<form method="post" enctype="multipart/form-data">
    foto
    <input type="file" name="file_foto" accept="image/*">
    mandat
    <input type="file" name="file_mandat" accept="application/pdf">
    kk
    <input type="file" name="file_kk" accept="application/pdf">
    akte
    <input type="file" name="file_akte" accept="application/pdf">
    piagam
    <input type="file" name="file_piagam" accept="application/pdf">
    cv
    <input type="file" name="file_cv" accept="application/pdf">
    <br><br><br><hr>
    <input type="submit" value="upload semuanya">
</form>

