<?php
include 'musabaqah/dbku.php';

$levels = [
    [100, 'Admin Provinsi', 'adminprov'],
    [101, 'Admin Kab/Ko', 'adminkabko'],
    [103, 'Panitera', 'panitera']
];

foreach ($levels as $level) {
    $id = $level[0];
    $nama = $level[1];
    $role = $level[2];

    $check = mysqli_query($koneksi, "SELECT id FROM user_level WHERE id=$id");
    if (mysqli_num_rows($check) == 0) {
        echo "Inserting level $id ($nama)\n";
        execute("INSERT INTO user_level (id, nama, role) VALUES ($id, '$nama', '$role')");
    } else {
        echo "Level $id already exists\n";
    }
}

// Optional: should we update existing users? 
// adminprov currently has level 2. Let's update it to 100.
echo "Updating adminprov user 1 to level 100\n";
execute("UPDATE user SET iduser_level=100 WHERE iduser_level=2 AND username='adminprov'");

echo "Done.\n";
