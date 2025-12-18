<?php
include "koneksi.php";

// Add email and email_verified to hafidz table
$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS email VARCHAR(255) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column email added to hafidz table successfully<br>";
} else {
    echo "Error adding column email: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS email_verified TINYINT(1) DEFAULT 0";
if ($conn->query($query) === TRUE) {
    echo "Column email_verified added successfully<br>";
} else {
    echo "Error adding column email_verified: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz ADD COLUMN IF NOT EXISTS verification_token VARCHAR(100) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column verification_token added successfully<br>";
} else {
    echo "Error adding column verification_token: " . $conn->error . "<br>";
}

// Add hari (day of week) and paraf to hafidz_reports
$query = "ALTER TABLE hafidz_reports ADD COLUMN IF NOT EXISTS hari VARCHAR(20) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column hari added to hafidz_reports successfully<br>";
} else {
    echo "Error adding column hari: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz_reports ADD COLUMN IF NOT EXISTS paraf VARCHAR(255) DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column paraf added to hafidz_reports successfully<br>";
} else {
    echo "Error adding column paraf: " . $conn->error . "<br>";
}

$query = "ALTER TABLE hafidz_reports ADD COLUMN IF NOT EXISTS materi TEXT DEFAULT NULL";
if ($conn->query($query) === TRUE) {
    echo "Column materi added to hafidz_reports successfully<br>";
} else {
    echo "Error adding column materi: " . $conn->error . "<br>";
}

echo "Database update v3 completed.";
?>
