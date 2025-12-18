<?php
    function setdata(){
        $data=getdata("select * from kategori");
        while($row=mysqli_fetch_array($data)){
            echo "<option value='$row[id]'>$row[nama] ( $row[juzawal] - $row[juzakhir]) ($row[jmlsoal] soal)</option>";
        }
    }
?>

<?php
    if(isset($_GET['json'])){
        require("../../musabaqah/dbku.php");
        //dapatkan parameter
        $idkategori=$_GET['json'];
        $row=getonebaris("select * from kategori where id=$idkategori");
        $juzawal=$row["juzawal"];
        $juzakhir=$row["juzakhir"];
        $jmlsoal=$row["jmlsoal"];

        //mutasyabihat
        echo "<div class='item mutasyabihat'>surat(ayat)</div>";
        //biasa
        //dapatkan data
        $q="select urut from mushaf
            where juz between $juzawal and $juzakhir
            and mutasyabihat='0'
            and urut not in 
            (select urutmushaf from mhq where idkategori=$idkategori)
            order by rand()";

        //tampilkan dan insert
        for ($i=0; $i<$jmlsoal-1; $i++) {
            $urut=getonedata($q);
            //cek ketersediaan
            if($urut==""){
                // die("data habis");
                // reset data
                execute("delete from mhq where idkategori='$idkategori'");
                $i--;//ulangi
            }else{
                // isi data acak
                execute("insert into mhq(idkategori,urutmushaf) values('$idkategori','$urut')");
                //ambil surat dan ayat
                $row=getonebaris("select * from mushaf where urut='$urut'");
                $surat=$row['surat'];
                $suratnama=getonedata("select nama from mushaf_surat where urut=$surat");
                $ayat=$row['ayat'];
                echo "<div class='item biasa' onclick=bukalink('?page=mushaf&jenis=madinah&nosurat=$surat&noayat=$ayat')>$suratnama ($ayat)</div>";
            }
        }
        die();
    }

?>

<script>
    function bukalink(url){
        window.open(url, '_blank');
    }
    function acak(){
        let index=eoption.options.selectedIndex;
        if(index==0){
            xalert("pilih cabang lomba");
            return;
        }
        let idkategori=eoption.options[index].value;
        // cl(index);
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            qs(".hasil").innerHTML=this.responseText;
        }
        xmlhttp.open("GET", "utama/tilawah2.php?json=" + idkategori);
        xmlhttp.send();
        // alert("teracak");
    }
</script>

<script>
    let eoption;
    document.addEventListener("DOMContentLoaded",()=>{
        eoption=gi("eselect");
        // alert(eoption);
    })
</script>

<style>
.hasil {
    justify-content:center;
}
.item{
    border: 2px solid red;
    display: flex;
    align-items: center;
    text-align: center;
    width: 108px;
    aspect-ratio: 1/1;
    cursor: pointer;
    justify-content: center;
    padding: 11px;

}
.item.biasa {
    background-color: rgb(255 208 208);
}
.item.biasa:hover{
    background-color: rgb(255 226 98);
}

.item.mutasyabihat {
    border: 2px solid #ff0000;
    background-color: hsl(28deg 82.13% 55.35%);
}

</style>


<div class="box vmaxwidth gap centermargin">
    <h1>tilawah</h1>
    <div class="panel flex gap flexcolumn">
        pilih cabang lomba
        <select class="select padding" id="eselect">
            <option value="">-- pilih --</option>
            <?php setdata();?>
        </select>
        <input class="button padding" type="button" value="acak soal" onclick=acak()>
    </div>
    <div class="hasil flex-wrap flex gap">
    </div>
</div>
