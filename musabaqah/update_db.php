<?php
include "koneksi.php";

// Add columns to hafidz table
$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS no_rekening VARCHAR(50)";
if ($conn->query($query) === TRUE) {
    echo "Column no_rekening added successfully<br>";
} else {
    echo "Error adding column no_rekening: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS nama_bank VARCHAR(100)";
if ($conn->query($query) === TRUE) {
    echo "Column nama_bank added successfully<br>";
} else {
    echo "Error adding column nama_bank: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS atas_nama_rekening VARCHAR(100)";
if ($conn->query($query) === TRUE) {
    echo "Column atas_nama_rekening added successfully<br>";
} else {
    echo "Error adding column atas_nama_rekening: " . $conn->error . "<br>";
}

// Create hafidz_saran table
$query = "CREATE TABLE IF NOT EXISTS hafidz_saran (
    id INT(11) NOT NULL AUTO_INCREMENT,
    idhafidz INT(11) NOT NULL,
    saran TEXT NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY (idhafidz)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if ($conn->query($query) === TRUE) {
    echo "Table hafidz_saran created successfully<br>";
} else {
    echo "Error creating table hafidz_saran: " . $conn->error . "<br>";
}

echo "Database update completed.";
?>
