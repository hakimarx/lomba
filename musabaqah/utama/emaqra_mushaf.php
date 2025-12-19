<?php
/**
 * Emaqra - Mushaf
 * Wrapper to embed emaqra mushaf in musabaqah frame
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

.mushaf-buttons {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.mushaf-btn {
    padding: 15px 30px;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    font-weight: 600;
}

.mushaf-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.mushaf-btn:not(.active) {
    background: #f0f0f0;
    color: #333;
}

.mushaf-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>

<div class="emaqra-container">
    <h2>🕌 Mushaf Al-Quran</h2>
    
    <div class="mushaf-buttons">
        <button class="mushaf-btn <?php echo (!isset($_GET['jenis']) || $_GET['jenis']=='madinah') ? 'active' : ''; ?>" 
                onclick="window.location.href='?page=utama&page2=emaqra_mushaf&jenis=madinah'">
            🕋 Mushaf Madinah
        </button>
        <button class="mushaf-btn <?php echo (isset($_GET['jenis']) && $_GET['jenis']=='indonesia') ? 'active' : ''; ?>" 
                onclick="window.location.href='?page=utama&page2=emaqra_mushaf&jenis=indonesia'">
            🇮🇩 Mushaf Indonesia
        </button>
    </div>
    
    <?php
        // Include emaqra mushaf page directly
        include_once __DIR__ . "/../../emaqra/utama/mushaf.php";
    ?>
</div>
