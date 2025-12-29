<?php
/**
 * Config Helper Class
 * 
 * Akses konfigurasi dengan mudah menggunakan dot notation.
 * Contoh: Config::get('app.name') => 'E-LPTQ'
 */

class Config
{
    private static $config = null;
    private static $configPath = null;

    /**
     * Load configuration file
     */
    public static function load($path = null)
    {
        if (self::$config === null || $path !== null) {
            if ($path === null) {
                // Default path - search up from current location
                $searchPaths = [
                    __DIR__ . '/../../config.php',
                    __DIR__ . '/../../../config.php',
                    $_SERVER['DOCUMENT_ROOT'] . '/lomba/config.php',
                ];
                foreach ($searchPaths as $p) {
                    if (file_exists($p)) {
                        $path = $p;
                        break;
                    }
                }
            }

            if ($path && file_exists($path)) {
                self::$configPath = realpath($path);
                self::$config = include $path;
            } else {
                // Return empty array if no config found
                self::$config = [];
            }
        }
        return self::$config;
    }

    /**
     * Get config value using dot notation
     * 
     * @param string $key Dot-notated key (e.g., 'app.name')
     * @param mixed $default Default value if key not found
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        $config = self::load();

        if (empty($key)) {
            return $config;
        }

        $keys = explode('.', $key);
        $value = $config;

        foreach ($keys as $k) {
            if (!is_array($value) || !isset($value[$k])) {
                return $default;
            }
            $value = $value[$k];
        }

        return $value;
    }

    /**
     * Check if config key exists
     */
    public static function has($key)
    {
        return self::get($key, '__NOT_FOUND__') !== '__NOT_FOUND__';
    }

    /**
     * Get all config as array
     */
    public static function all()
    {
        return self::load();
    }

    /**
     * Get config path
     */
    public static function getPath()
    {
        self::load();
        return self::$configPath;
    }

    /**
     * Reset cached config (useful for testing)
     */
    public static function reset()
    {
        self::$config = null;
        self::$configPath = null;
    }
}
