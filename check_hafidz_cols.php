<?php
include 'musabaqah/dbku.php';
$cols = ['jumlah_hafalan_juz', 'periode', 'tahun_anggaran', 'nama_lengkap', 'desa_kelurahan', 'status_mengajar', 'lembaga_tahfidz', 'nama_pembimbing', 'tahun_khatam', 'no_sertifikat_tahfidz', 'nama_lembaga_mengajar'];
$existing = [];
$res = getdata('DESCRIBE hafidz');
while ($r = mysqli_fetch_assoc($res)) $existing[] = $r['Field'];

echo "Existing columns: " . implode(', ', $existing) . "\n\n";

$missing = array_diff($cols, $existing);
if (empty($missing)) {
    echo "All required columns exist.\n";
} else {
    echo "Missing columns: " . implode(', ', $missing) . "\n";
}
