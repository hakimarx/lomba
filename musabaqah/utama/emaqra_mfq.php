<?php

/**
 * Emaqra - MFQ/Hadits
 * Wrapper to embed emaqra MFQ in musabaqah frame
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

    /* MFQ specific fixes */
    .mfq .isi {
        color: var(--text-primary) !important;
        background: var(--bg-surface) !important;
    }

    .mfq .soal .isi,
    .mfq .jawaban .isi {
        color: #1e293b !important;
        background: #f8fafc !important;
    }

    .mfq h1 {
        color: var(--primary-light);
    }
</style>

<div class="emaqra-container">
    <h2>📖 MFQ / Hadits</h2>

    <?php
    // Include emaqra mfq page directly
    include_once __DIR__ . "/../../emaqra/utama/mfq.php";
    ?>
</div>