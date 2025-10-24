<?php
    $q="select
    idnilai,
    event.nama as event,
    cabang.nama as cabang,
    golongan.nama as golongan,
    peserta.nama as peserta
    from view_nilai
    inner join event on event.id=idevent
    inner join cabang on cabang.id=idcabang
    inner join golongan on golongan.id=idgolongan
    inner join peserta on peserta.id=idpeserta";
    $data=getdata($q);
    echo "<div style='overflow:auto;height:333px'><table class=table>
            <tr>
                <th>lihat nilai</th>
                <th>event</th>
                <th>cabang</th>
                <th>golongan</th>
                <th>peserta</th>
            </tr>";
        while($row=mysqli_fetch_array($data)){
            echo "<tr>
                    <td><a target=_blank href='?page=cetak&idnilai=$row[idnilai]'>lihat nilai</a></td>
                    <td>$row[event]</td>
                    <td>$row[cabang]</td>
                    <td>$row[golongan]</td>
                    <td>$row[peserta]</td>
            </tr>";
        }
    echo "</table></div>";
?>
