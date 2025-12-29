<?php
/**
 * Configuration File - E-LPTQ Application
 * 
 * Semua data aplikasi terpusat di sini.
 * Edit file ini untuk mengubah nama, warna, atau setting aplikasi
 * tanpa perlu mengubah file PHP lainnya.
 */

return [
    // ═══════════════════════════════════════════════════════════════
    // APP IDENTITY
    // ═══════════════════════════════════════════════════════════════
    'app' => [
        'name'           => 'E-LPTQ',
        'short_name'     => 'LPTQ',
        'full_name'      => 'Musabaqah - Sistem Manajemen Hafidz',
        'description'    => 'Sistem Manajemen Musabaqah & Hafidz',
        'meta_desc'      => 'E-LPTQ: Sistem Manajemen Musabaqah & Hafidz Jawa Timur',
        'logo_emoji'     => '🕌',
        'region'         => 'Jawa Timur',
        'lang'           => 'id-ID',
    ],

    // ═══════════════════════════════════════════════════════════════
    // COPYRIGHT & FOOTER
    // ═══════════════════════════════════════════════════════════════
    'copyright' => [
        'year'           => '2025',
        'holder'         => 'E-LPTQ',
        'footer_text'    => 'Musabaqah System · Dibuat dengan ❤️ untuk Jawa Timur',
    ],

    // ═══════════════════════════════════════════════════════════════
    // THEME COLORS
    // ═══════════════════════════════════════════════════════════════
    'theme' => [
        'primary'        => '#10b981',
        'primary_light'  => '#34d399',
        'primary_dark'   => '#059669',
        'background'     => '#0f172a',
        'surface'        => '#1e1e2e',
        'card'           => '#1e293b',
        'text_primary'   => '#ffffff',
        'text_secondary' => 'rgba(255, 255, 255, 0.6)',
        'error'          => '#ef4444',
    ],

    // ═══════════════════════════════════════════════════════════════
    // PWA CONFIGURATION
    // ═══════════════════════════════════════════════════════════════
    'pwa' => [
        'enabled'        => true,
        'start_url'      => '/lomba/musabaqah/',
        'scope'          => '/lomba/',
        'display'        => 'standalone',
        'orientation'    => 'portrait-primary',
        'categories'     => ['education', 'productivity'],
        'icons_path'     => '/lomba/assets/icons/',
    ],

    // ═══════════════════════════════════════════════════════════════
    // NAVIGATION LINKS
    // ═══════════════════════════════════════════════════════════════
    'links' => [
        'hafiz_login'    => 'musabaqah/hafidz/login.php',
        'emaqra'         => 'emaqra/',
        'main_dashboard' => 'musabaqah/?page=utama',
    ],

    // ═══════════════════════════════════════════════════════════════
    // FEATURE FLAGS
    // ═══════════════════════════════════════════════════════════════
    'features' => [
        'hafiz_portal'      => true,
        'emaqra_tools'      => true,
        'insentif_gubernur' => true,
        'pwa_offline'       => true,
    ],
];
