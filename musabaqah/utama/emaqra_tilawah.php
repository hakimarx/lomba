<?php
/**
 * Emaqra - Tilawah & MHQ
 * Wrapper to embed emaqra tilawah in musabaqah frame
 */
?>

<style>
.emaqra-container {
    background: white;
    border-radius: 10px;
    padding: 20px;
    min-height: 500px;
}

.emaqra-container h2 {
    color: #1e3c72;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #667eea;
}

.emaqra-container iframe {
    width: 100%;
    height: 600px;
    border: 1px solid #ddd;
    border-radius: 8px;
}
</style>

<div class="emaqra-container">
    <h2>🎤 Tilawah & MHQ (Musabaqah Hifdzil Quran)</h2>
    
    <?php
        // Include emaqra tilawah page directly
        include_once __DIR__ . "/../../emaqra/utama/tilawah2.php";
    ?>
</div>
