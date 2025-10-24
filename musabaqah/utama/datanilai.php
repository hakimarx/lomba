<style>
    .datanilai{
        overflow:auto;
    }
</style>

<head></head>
<h1>data nilai (view only)</h1>

<div class=datanilai>
<table>
    <tr>
        <th>event</th>
        <th>cabang</th>
        <th>golongan</th>
        <th>peserta</th>
        <th>nilai1</th>
        <th>nilai2</th>
        <th>nilai3</th>
        <th>nilai total</th>
    </tr>
    <tr>
        <?php
            function getdata(){
                include "koneksi.php";
                $query="select *,
                (select nama from event where id=idevent) as event,
                (select nama from event where id=idcabang) as cabang,
                (select nama from event where id=idgolongan) as golongan,
                (select nama from peserta where id=idpeserta) as nama,
                (nilai1+nilai2+nilai3) as nilaitotal from nilai;";


                $data=mysqli_query($koneksi,$query);
                while($row=mysqli_fetch_array($data)){
                    $event=$row['event'];
                    $cabang=$row['cabang'];
                    $golongan=$row['golongan'];
                    $peserta=$row['nama'];
                    $nilai1=$row['nilai1'];
                    $nilai2=$row['nilai2'];
                    $nilai3=$row['nilai3'];
                    $nilaitotal=$row['nilaitotal'];
                    echo "<tr>
                            <td>$event</td>
                            <td>$cabang</td>
                            <td>$golongan</td>
                            <td>$peserta</td>
                            <td>$nilai1</td>
                            <td>$nilai2</td>
                            <td>$nilai3</td>
                            <td>$nilaitotal</td>
                            </tr>\n";
                }
            }

            getdata();
        ?>
    </tr>
</table>
</div>