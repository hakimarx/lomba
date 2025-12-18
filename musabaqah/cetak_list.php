<?php
include_once __DIR__ . "/dbku.php";
include_once __DIR__ . "/../global/class/fungsi.php";
?>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../global/css/ku.css">
    <link rel="stylesheet" href="../global/css/web.css">
</head>
<div style="max-width: 1200px; margin: 50px auto; padding: 20px;">
    <h1>Cetak Nilai</h1>
    <p>Pilih nilai yang ingin dicetak:</p>
    <table class="table" style="width: 100%; margin-top: 20px;">
        <tr>
            <th>No</th>
            <th>Nomor Peserta</th>
            <th>Nama Peserta</th>
            <th>Cabang</th>
            <th>Event</th>
            <th>Aksi</th>
        </tr>
        <?php
        $query = "SELECT 
            view_nilai.idnilai,
            nomor,
            peserta.nama as peserta,
            cabang.nama as cabang,
            event.nama as event
            FROM view_nilai
            INNER JOIN peserta ON peserta.id=idpeserta
            INNER JOIN cabang ON cabang.id=view_nilai.idcabang
            INNER JOIN event ON event.id=view_nilai.idevent
            ORDER BY event.nama, cabang.nama, nomor";

        $data = getdata($query);
        $no = 1;
        if (mysqli_num_rows($data) == 0) {
            echo "<tr><td colspan='6' style='text-align:center'>Belum ada data nilai</td></tr>";
        } else {
            while ($row = mysqli_fetch_array($data)) {
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td>{$row['nomor']}</td>";
                echo "<td>{$row['peserta']}</td>";
                echo "<td>{$row['cabang']}</td>";
                echo "<td>{$row['event']}</td>";
                echo "<td><a href='cetak.php?idnilai={$row['idnilai']}' target='_blank' class='button padding'>Cetak</a></td>";
                echo "</tr>";
                $no++;
            }
        }
        ?>
    </table>
</div>