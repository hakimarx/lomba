<?php
include "koneksi.php";

// Add kab_kota to user table
$query = "ALTER TABLE user ADD COLUMN IF NOT EXISTS kab_kota VARCHAR(255) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column kab_kota added to user table successfully<br>";
} else {
    echo "Error adding column kab_kota to user table: " . $conn->error . "<br>";
}

// Add status to hafidz table
$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS status ENUM('pending', 'aktif', 'pindah_request', 'meninggal') DEFAULT 'pending'";
if ($conn->query($query) === TRUE) {
    echo "Column status added to hafidz table successfully<br>";
    // Update existing records to 'aktif' if they were previously considered active (optional logic, assuming all existing are active for now)
    $conn->query("UPDATE hafidz SET status='aktif' WHERE status='pending'"); 
} else {
    echo "Error adding column status to hafidz table: " . $conn->error . "<br>";
}

// Add deceased reporting columns
$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS bukti_meninggal VARCHAR(255) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column bukti_meninggal added successfully<br>";
} else {
    echo "Error adding column bukti_meninggal: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS tanggal_meninggal DATE DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column tanggal_meninggal added successfully<br>";
} else {
    echo "Error adding column tanggal_meninggal: " . $conn->error . "<br>";
}

// Add transfer columns
$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS tujuan_pindah VARCHAR(255) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column tujuan_pindah added successfully<br>";
} else {
    echo "Error adding column tujuan_pindah: " . $conn->error . "<br>";
}

echo "Database update v2 completed.";
?>
