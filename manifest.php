<?php
/**
 * Dynamic Manifest Generator
 * 
 * Generates PWA manifest.json from config.php
 * Access via: /manifest.php or configure web server to serve as manifest.json
 */

header('Content-Type: application/json');
header('Cache-Control: public, max-age=86400');

include_once __DIR__ . '/global/class/Config.php';

$iconsPath = Config::get('pwa.icons_path', '/lomba/assets/icons/');
$iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

$icons = [];
foreach ($iconSizes as $size) {
    $icons[] = [
        'src' => $iconsPath . "icon-{$size}x{$size}.png",
        'sizes' => "{$size}x{$size}",
        'type' => 'image/png',
        'purpose' => 'any maskable',
    ];
}

$manifest = [
    'name' => Config::get('app.full_name', Config::get('app.name')),
    'short_name' => Config::get('app.short_name', Config::get('app.name')),
    'description' => Config::get('app.meta_desc', Config::get('app.description')),
    'start_url' => Config::get('pwa.start_url', '/'),
    'scope' => Config::get('pwa.scope', '/'),
    'display' => Config::get('pwa.display', 'standalone'),
    'background_color' => Config::get('theme.background', '#0f172a'),
    'theme_color' => Config::get('theme.primary', '#10b981'),
    'orientation' => Config::get('pwa.orientation', 'portrait-primary'),
    'icons' => $icons,
    'categories' => Config::get('pwa.categories', ['education', 'productivity']),
    'lang' => Config::get('app.lang', 'id-ID'),
    'dir' => 'ltr',
];

echo json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
