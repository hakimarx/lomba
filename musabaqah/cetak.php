<?php


function setbidang()
{

    $rowcetak = $GLOBALS['rowcetak'];
    $databidang = getdata("select * from bidang where idgolongan=$rowcetak[idgolongan]");
    while ($rowbidang = mysqli_fetch_array($databidang)) {
?>
        <table class="table2">
            <tbody>
                <tr>
                    <td colspan="2" style="    text-align: center;"><?php echo $rowbidang['nama'] ?></td>
                </tr>
                <tr>
                    <td>Dewan Hakim</td>
                    <td>nilai</td>
                </tr>

                <tr>
                    <?php
                    $query = "select 
                                    nilai_bidang.*,
                                    hakim.nama as hakim,
                                    hakim.idbidang,
                                    bidang.nama as bidang

                                    from nilai_bidang
                                    
                                    inner join hakim on hakim.id=nilai_bidang.idhakim
                                    inner join bidang on bidang.id=idbidang";

                    $query .= " where idnilai=$rowcetak[idnilai] and idbidang=$rowbidang[id]";

                    $datanilai = getdata($query);
                    while ($rowdatanilai = mysqli_fetch_array($datanilai)) {
                        echo "<tr>";
                        echo "<td>$rowdatanilai[hakim]</td>";
                        echo "<td>$rowdatanilai[nilai]</td>";
                        echo "</tr>";
                    }
                    ?>
                </tr>

                <tr>
                    <td colspan="2" style="    text-align: center;">**0.00</td>
                </tr>
            </tbody>
        </table>

<?php
    }
}



?>
<?php


if (!isset($_GET['idnilai']) || empty($_GET['idnilai'])) {
    die("Error: Parameter 'idnilai' is required. Please access this page with ?idnilai=<id>");
}

$idnilai = $_GET['idnilai'];
//cetak
$query = "select 
                
                view_nilai.idgolongan,
                idnilai,
                nomor,
                ketua,
                sekretaris,
                peserta.nama as peserta,
                golongan.nama as golongan,
                event.nama as event,
                cabang.nama as cabang,
                babak.nama as babak,
                kafilah.nama as kafilah,
                logopenyelenggara,
                tingkat,
                lokasi,
                logoacara,kota
                
                from
                view_nilai
                inner join peserta on peserta.id=idpeserta
                inner join golongan on golongan.id=view_nilai.idgolongan
                inner join event on event.id=view_nilai.idevent
                inner join cabang on cabang.id=view_nilai.idcabang
                inner join babak on babak.id=golongan.idbabak
                inner join kafilah on kafilah.id=peserta.idkafilah
                where idnilai=$idnilai
                ";

$rowcetak = getonebaris($query);
?>
<hr>
tanda ** = data masih statis (belum ambil dari database)

<hr>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        padding: 11px;
    }

    .cetak img {
        border: 1px solid;
        width: 55px;
        margin: 8px;
    }

    .cetak .table2 td,
    .cetak .table2 tr {
        border: 1px solid;
    }

    .cetak table {
        border-collapse: collapse;
    }

    .cetak td {
        padding: 3px;
    }

    .row3 {
        display: flex;
        gap: 11px;
    }

    .row3 table {
        flex: 1;
    }
</style>


<div class="cetak">
    <div class=row1 style="border-bottom: 1px solid;display: flex;padding-bottom: 9px;">
        <div>
            <img src="<?php echo $rowcetak["logopenyelenggara"]; ?>" alt="">
        </div>
        <div style="    flex: 1;    text-align: center;">
            <div><?php echo $rowcetak['event'] ?></div>
            <div><?php echo $rowcetak['tingkat'] ?> **TAHUN 2025</div>
            <div><?php echo $rowcetak['lokasi'] ?></div>
        </div>
        <div>
            <img src="<?php echo $rowcetak['logoacara']; ?>" alt="">
        </div>
    </div>

    <div class=row2>
        <div style="text-align: center;margin: 21px 0;">
            <div style="    font-size: 22px;">JURNAL CETAK</div>
            <div><?php echo $rowcetak['babak'] ?></div>
        </div>
        <div style="    display: flex;">
            <div style="    flex: 1;">

                <table>
                    <tbody>
                        <tr>
                            <td style="width: 169px;">Cabang/Golongan</td>
                            <td>:</td>
                            <td><?php echo "$rowcetak[cabang] - $rowcetak[golongan]"; ?></td>
                        </tr>
                        <tr>
                            <td>Peserta</td>
                            <td>:</td>
                            <td><?php echo "$rowcetak[nomor] - $rowcetak[peserta]"; ?></td>
                        </tr>
                        <tr>
                            <td>Asal</td>
                            <td>:</td>
                            <td><?php echo $rowcetak["kafilah"]; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div>
                <div
                    style="border: 1px solid;font-size: 45px;text-align: center;width: 111px;/* aspect-ratio: 1/1; */align-content: center;">
                    **0.00</div>
            </div>
        </div>
    </div>

    <div class=row3 style="    margin: 17px 0 28px;">
        <?php setbidang() ?>
    </div>

    <div class=row4>
        <table>
            <tbody>
                <tr>
                    <td style="    width: 234px;"></td>
                    <td><?php echo $rowcetak['kota'] ?>, **04 agustus 2025</td>
                </tr>
                <tr>
                    <td>KETUA</td>
                    <td>SEKRETARIS</td>
                </tr>
                <tr style="height: 58px;"></tr>
                <tr>
                    <td style=""><?php echo $rowcetak['ketua'] ?></td>
                    <td><?php echo $rowcetak['sekretaris'] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>