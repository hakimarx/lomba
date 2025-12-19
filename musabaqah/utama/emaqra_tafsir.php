<?php
/**
 * Emaqra - Tafsir
 * Wrapper to embed emaqra tafsir in musabaqah frame
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
</style>

<div class="emaqra-container">
    <h2>📚 Tafsir Al-Quran</h2>
    
    <?php
        // Include emaqra tafsir page directly
        include_once __DIR__ . "/../../emaqra/utama/tafsir.php";
    ?>
</div>
