<?php

/**
 * System Configuration Helper Functions
 * 
 * Provides functions to get/set system-wide configuration values
 * stored in the system_config database table.
 */

/**
 * Get a configuration value by key
 * 
 * @param string $key Configuration key
 * @param mixed $default Default value if key not found
 * @return mixed Configuration value or default
 */
function getConfig($key, $default = null)
{
    $key = addslashes($key);
    $value = getonedata("SELECT config_value FROM system_config WHERE config_key='$key'");
    return $value !== null && $value !== '' ? $value : $default;
}

/**
 * Set a configuration value
 * 
 * @param string $key Configuration key
 * @param mixed $value Configuration value
 * @return bool Success status
 */
function setConfig($key, $value)
{
    global $koneksi;
    $key = addslashes($key);
    $value = addslashes($value);

    $query = "INSERT INTO system_config (config_key, config_value) 
              VALUES ('$key', '$value') 
              ON DUPLICATE KEY UPDATE config_value = '$value'";

    return mysqli_query($koneksi, $query);
}

/**
 * Get all configuration values
 * 
 * @return array Associative array of all config key-value pairs
 */
function getAllConfig()
{
    $result = [];
    $data = getdata("SELECT config_key, config_value FROM system_config");
    while ($row = mysqli_fetch_array($data)) {
        $result[$row['config_key']] = $row['config_value'];
    }
    return $result;
}

/**
 * Get mushaf image URL based on current settings
 * 
 * @param int $halaman Page number (1-604)
 * @param string $jenis Type: 'indonesia' or 'madinah'
 * @return string Image URL (local path or API URL)
 */
function getMushafImageUrl($halaman, $jenis = 'madinah')
{
    $source = getConfig('mushaf_source', 'offline');

    if ($source === 'online') {
        // Use API
        $apiUrl = getConfig('mushaf_api_' . $jenis, 'https://everyayah.com/data/images_png/');
        return $apiUrl . $halaman . '.png';
    } else {
        // Use local files (offline)
        $ext = ($jenis === 'indonesia') ? 'png' : 'jpg';
        return "../assets/mushaf/$jenis/$halaman.$ext";
    }
}
