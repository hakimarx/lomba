<?php

/**
 * Database Connection Configuration
 * 
 * Production credentials are loaded from koneksi_prod.php (not in Git)
 * For production setup, copy koneksi_prod.php.example to koneksi_prod.php
 * and fill in your credentials.
 */
$urlhost = $_SERVER['HTTP_HOST'];

if ($urlhost != "vx2f.cloud") {
    // Local development (XAMPP)
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "xmaqra";
} else {
    // Production - load from separate config file (not in Git)
    $prodConfigFile = __DIR__ . '/koneksi_prod.php';
    if (file_exists($prodConfigFile)) {
        include $prodConfigFile;
        $host = $prod_host ?? "localhost";
        $user = $prod_user ?? "";
        $pass = $prod_pass ?? "";
        $db = $prod_db ?? "";
    } else {
        die("Production config file not found. Please create koneksi_prod.php from koneksi_prod.php.example");
    }
}

try {
    $koneksi = mysqli_connect($host, $user, $pass, $db);
} catch (Throwable $e) {
    $koneksi = false;
    $err = $e->getMessage();
}

// If DB doesn't exist (Unknown database), try to create it automatically on local dev
if (!$koneksi) {
    if (!isset($err)) $err = mysqli_connect_error();
    if (strpos($err, 'Unknown database') !== false && $host === 'localhost' && $user === 'root' && $pass === '') {
        // connect without specifying DB
        $tmp = @mysqli_connect($host, $user, $pass);
        if (!$tmp) {
            die("MySQL server connection failed: " . mysqli_connect_error());
        }

        // create DB
        $safe_db = mysqli_real_escape_string($tmp, $db);
        $createSql = "CREATE DATABASE IF NOT EXISTS `$safe_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
        if (!mysqli_query($tmp, $createSql)) {
            die("Failed to create database '$db': " . mysqli_error($tmp));
        }

        // Try to import SQL dump if available
        $sqlFile = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'xmaqra.sql');
        if ($sqlFile && file_exists($sqlFile) && is_readable($sqlFile)) {
            $sqlContents = file_get_contents($sqlFile);
            if ($sqlContents !== false) {
                // Ensure we use the correct DB for the import
                $sqlContents = "USE `$safe_db`;\n" . $sqlContents;
                if (!mysqli_multi_query($tmp, $sqlContents)) {
                    // fall back to simple queries if multi_query fails
                    foreach (explode(";\n", $sqlContents) as $q) {
                        $q = trim($q);
                        if ($q) {
                            mysqli_query($tmp, $q);
                        }
                    }
                } else {
                    // flush any remainder
                    do {
                        if ($res = mysqli_store_result($tmp)) mysqli_free_result($res);
                    } while (mysqli_more_results($tmp) && mysqli_next_result($tmp));
                }
            }
        }

        mysqli_close($tmp);
        // reconnect to the newly created DB
        $koneksi = @mysqli_connect($host, $user, $pass, $db);
        if (!$koneksi) {
            die("Failed to connect after creating database '$db': " . mysqli_connect_error());
        }
    } else {
        die("Database connection failed: " . $err . ".\nIf you're running locally, import the 'xmaqra' DB from xmaqra.sql (root MySQL). Example (PowerShell/XAMPP):\nC:\\xampp2\\mysql\\bin\\mysql.exe -u root < C:\\xampp2\\htdocs\\lomba\\xmaqra.sql");
    }
}
