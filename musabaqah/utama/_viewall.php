

<script>
    function toggle(){
        var coll = document.getElementsByClassName("collapsible");
        var i;
        isopen=!isopen;
        for (i = 0; i < coll.length; i++) {
            var e=coll[i].nextElementSibling;
                if (isopen) {
                    e.style.display = "flex";
                } else {
                    e.style.display = "none";
                }
        }

    }
</script>

<script>

let isopen=0;

document.addEventListener("DOMContentLoaded",()=>{
    var coll = document.getElementsByClassName("collapsible");
    var i;

    for (i = 0; i < coll.length; i++) {

        coll[i].nextElementSibling.style.display="none";
        coll[i].addEventListener("click", function() {
            var content = this.nextElementSibling;
            if (content.style.display === "none") {
                content.style.display = "flex";
            } else {
                content.style.display = "none";
            }
        });
    }

})

</script>

<style>
    :root{
        --vgap:5px;
    }
    .kotak {
        border: 1px solid gray;
    }

    .judul {
        border-bottom: 1px solid gray;
    }

    .judul,
    .isi {
        padding: 5px;
    }
    .event>.judul{
        background-color:purple;
        color:white;
    }
    .cabang>.judul {
        background-color: pink;
    }

    .golongan>.judul {
        background-color: orange;
    }

    .peserta>.judul {
        background-color: green;
        color: white;
    }

    .bidang>.judul {
        background-color: blue;
        color: white;
    }

    .hakim>.judul {
        background-color: aquamarine;
    }

    .golongan>.isi {
        display: flex;
        gap:var(--vgap);
    }

    .viewall{
        display:flex;
        flex-direction:column;
        gap:var(--vgap);
    }

    .bidang,.peserta{
        flex-shrink:0;
        flex-basis:222px;

    }
    .bidang-group {
        flex: 1;
        display: flex;
        flex-wrap: wrap;
        gap:var(--vgap);
    }
    .cabang>.isi,.event>.isi {
        display: flex;
        gap: var(--vgap);
        flex-direction: column;
    }

    .collapsible{
        /* display:none !important; */
    }

    [type=button]  {
        width: 144px;
    }
</style>

<head></head>


<div class='viewall vmaxwidth centermargin'>
    <div style="text-align:right">
        <input class="button padding" type="button" value="collapse/hide all" onclick=toggle()>
    </div>
    <?php
        $dataevent=getdata("select * from event");
        while($rowevent=mysqli_fetch_array($dataevent)){
            ?>
            <div class="event kotak">
                <div class="judul collapsible"><?php echo $rowevent['nama'];?></div>
                <div class="isi">
                <?php
                    $datacabang=getdata("select * from cabang where idevent=$rowevent[id]");
                    while($rowcabang=mysqli_fetch_array($datacabang)){
                        $cabang=$rowcabang['nama'];
                        ?>

                    <div class='cabang kotak'>
                        <div class='judul collapsible'><?php echo $rowcabang['nama'] ?></div>
                        <div class='isi'>
                            <?php
                                $datagolongan=getdata("select * from golongan where idcabang=$rowcabang[id]");
                                while($rowgolongan=mysqli_fetch_array($datagolongan)){
                                    $golongan=$rowgolongan['nama'];
                                    ?>

                            <div class='golongan kotak'>
                                <div class='judul collapsible'><?php echo $golongan;?></div>
                                <div class='isi'>
                                    <div class='peserta kotak'>
                                        <div class='judul'>
                                            peserta
                                        </div>
                                        <div class='isi'>
                                            <?php
                                                $datapeserta=getdata("select * from peserta where idgolongan=$rowgolongan[id]");
                                                while($rowpeserta=mysqli_fetch_array($datapeserta)){
                                                    $peserta=$rowpeserta['nama'];
                                                    echo "<div>$peserta</div>";
                                                }
                                            ?>
                                        </div>
                                    </div>

                                    <div class="bidang-group">

                                    <?php
                                        $databidang=getdata("select * from bidang where idgolongan=$rowgolongan[id]");
                                        while($rowbidang=mysqli_fetch_array($databidang)){
                                            $bidang=$rowbidang['nama'];
                                    ?>

                                        <div class='bidang kotak'>
                                            <div class='judul'><?php echo $bidang ;?></div>
                                            <div class='isi'>
                                                <div class='hakim kotak'>
                                                    <div class='judul'>hakim</div>
                                                    <div class='isi'>

                                                        <?php
                                                            $datahakim=getdata("select * from hakim where idbidang=$rowbidang[id]");
                                                            while($rowhakim=mysqli_fetch_array($datahakim)){
                                                                $hakim=$rowhakim['nama'];
                                                                echo "<div>$hakim</div>";

                                                            }
                                                        ?>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        }
                                    ?>
                                    </div>           

                                </div>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
</div>