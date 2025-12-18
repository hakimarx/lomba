<?php
include_once __DIR__ . "/../dbku.php";
include_once __DIR__ . "/../../global/class/userrole.php";
openfor(["adminprov","adminkabko"]);

$query = "select * from hafidz where no_rekening is not null and no_rekening != '' order by kab_kota, nama";
$data = getdata($query);
?>
<head></head>
<div class=judul>
    <h1>Rekapitulasi Nomor Rekening Hafidz</h1>
</div>
<div class=box>
    <table class=table>
        <tr><th>#</th><th>Kab/Kota</th><th>Nama Hafidz</th><th>Nama Bank</th><th>No Rekening</th><th>Atas Nama</th></tr>
        <?php $i=1; while($row=mysqli_fetch_array($data)){ ?>
            <tr>
                <td><?php echo $i++;?></td>
                <td><?php echo $row['kab_kota'];?></td>
                <td><?php echo $row['nama'];?></td>
                <td><?php echo $row['nama_bank'];?></td>
                <td><?php echo $row['no_rekening'];?></td>
                <td><?php echo $row['atas_nama_rekening'];?></td>
            </tr>
        <?php } ?>
    </table>
    <div style="margin-top:20px">
        <button class="button" onclick="window.print()">Cetak</button>
        <button class="button" onclick="exportToExcel()">Export Excel</button>
    </div>
</div>
<script>
function exportToExcel() {
    // Simple export function
    let html = document.querySelector(".table").outerHTML;
    let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
    let link = document.createElement("a");
    link.download = "rekap_rekening_hafidz.xls";
    link.href = url;
    link.click();
}
</script>
