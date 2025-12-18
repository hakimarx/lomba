<?php
// dbku khusus untuk hafidz dengan database huffadz
include __DIR__ . "/koneksi_hafidz.php";

function execute($query)
{
    $koneksi = $GLOBALS['koneksi'];
    try {
        $res = mysqli_query($koneksi, $query);
        if ($res === false) {
            $err = mysqli_error($koneksi);
            die("Database query failed: $err");
        }
        return $res;
    } catch (Throwable $e) {
        die("Database exception: " . $e->getMessage());
    }
}

function getdata($query)
{
    return execute($query);
}

function getonebaris($query)
{
    $data = execute($query);
    if (!$data || mysqli_num_rows($data) == 0) return null;
    return mysqli_fetch_array($data);
}

function getonedata($query)
{
    $data = execute($query);
    if (mysqli_num_rows($data) == 0) {
        return "";
    } else {
        $row = mysqli_fetch_array($data);
        return $row[0];
    }
}
