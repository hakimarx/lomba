<?php

/**
 * Emaqra - Tilawah & MHQ
 * Wrapper to embed emaqra tilawah in musabaqah frame
 */
?>

<style>
    .emaqra-container {
        background: var(--bg-card);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        padding: 20px;
        min-height: 500px;
    }

    .emaqra-container h2 {
        color: var(--primary-light);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary);
    }

    .emaqra-container iframe {
        width: 100%;
        height: 600px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }
</style>

<div class="emaqra-container">
    <h2>🎤 TILAWAH & MHQ (MUSABAQAH HIFDZIL QURAN)</h2>

    <?php
    // Include emaqra tilawah page directly
    include_once __DIR__ . "/../../emaqra/utama/tilawah2.php";
    ?>
</div>