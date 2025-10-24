
<?php
    function getcabang(){
        $data=getdata("select id,nama from cabang");
        while($row=mysqli_fetch_array($data)){
            echo "<option value='$row[id]'>$row[nama]</option>";
        }
    }
?>

<script>
    function isigolongan(o){
        egolongantd.innerHTML="";
        let index=o.options.selectedIndex
        let idcabang=o.options[index].value;
        if(idcabang=="") return;
        let data=getdata("utama/nilai1.php?idcabang="+idcabang);
        isigolongan2(data);
    }

    function isigolongan2(data){
        data=JSON.parse(data);
        data.forEach(element => {
            vid=element[0];
            vvalue=element[1];
            egolongantd.innerHTML+="<option value=" + vid + ">" + vvalue+ "</option>";
        });
    }

    function getdata(url) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("GET", url, false);
        xhttp.send();
        return xhttp.responseText;
    }

    function cekinput(){
        let egolongan=qs("[name=egolongan]");
        let index=egolongan.options.selectedIndex;
        if(index==0){
            xalert("pilih golongan");
            return false;
        }else{
            return true;
        }

    }

    function bukalink(jenis){
        if(!cekinput()) return;
        let link;
        if(jenis==1){
            link="?page=nilai_input&idgolongan="
        }else{
            link="?page=timer&idgolongan=";
        }
        link+=getidgolongan();
        // cl("xxx "+link);
            // document.location=link;
        window.open(link, '_blank')

    }

    function getidgolongan(){
        let e=qs("[name=egolongan]");
        return e.options[e.options.selectedIndex].value;
    }

</script>


<script>
    let egolongan;
    document.addEventListener("DOMContentLoaded",()=>{
        egolongan=gi("egolongan");
    })
</script>


<style>
    .box{
        width: 555px;
    }
</style>

<div class="marginauto box flex flexcolumn border gap padding">
pilih cabang

<select class="padding" onchange=isigolongan(this) name=ecabang>
    <option value="">-- pilih --</option>
    <?php
        getcabang();
    ?>
</select>
pilih golongan

<select class="padding" name="egolongan">
    <option value="">-- pilih --</option>
    <div id=egolongantd></div>
</select>

<input class="button padding" type="button" value="mulai penilaian" onclick=bukalink(1)>

<input class="button padding" type="button" value="show timer" onclick=bukalink(2)>

</div>
