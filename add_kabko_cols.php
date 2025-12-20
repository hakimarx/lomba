<?php
include 'musabaqah/dbku.php';
$cols_to_add = [
    'is_insentif_kabko' => 'TINYINT DEFAULT 0',
    'status_insentif_kabko' => 'VARCHAR(50) DEFAULT "Pending"',
    'catatan_insentif_kabko' => 'TEXT',
    'tgl_insentif_kabko' => 'DATETIME',
    'verifikator_insentif_kabko' => 'INT'
];

$res = getdata('DESCRIBE hafidz');
$existing = [];
while ($r = mysqli_fetch_assoc($res)) $existing[] = $r['Field'];

foreach ($cols_to_add as $col => $type) {
    if (!in_array($col, $existing)) {
        echo "Adding column: $col\n";
        execute("ALTER TABLE hafidz ADD COLUMN $col $type");
    }
}
echo "Finished.\n";
