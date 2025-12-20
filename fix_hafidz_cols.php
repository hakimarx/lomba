<?php
include 'musabaqah/dbku.php';
$cols_to_add = [
    'jumlah_hafalan_juz' => 'INT DEFAULT 0',
    'periode' => 'VARCHAR(50)',
    'tahun_anggaran' => 'VARCHAR(10)',
    'nama_lengkap' => 'VARCHAR(255)',
    'desa_kelurahan' => 'VARCHAR(255)',
    'status_mengajar' => 'VARCHAR(50)',
    'lembaga_tahfidz' => 'VARCHAR(255)',
    'nama_pembimbing' => 'VARCHAR(255)',
    'tahun_khatam' => 'INT',
    'no_sertifikat_tahfidz' => 'VARCHAR(255)',
    'nama_lembaga_mengajar' => 'VARCHAR(255)'
];

$res = getdata('DESCRIBE hafidz');
$existing = [];
while ($r = mysqli_fetch_assoc($res)) $existing[] = $r['Field'];

foreach ($cols_to_add as $col => $type) {
    if (!in_array($col, $existing)) {
        echo "Adding column: $col\n";
        execute("ALTER TABLE hafidz ADD COLUMN $col $type");
    } else {
        echo "Column already exists: $col\n";
    }
}
echo "Finished adding columns.\n";
