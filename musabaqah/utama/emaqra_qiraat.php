<?php
/**
 * Emaqra - Qiraat
 * Wrapper to embed emaqra qiraat in musabaqah frame
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

.qiraat-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    justify-content: center;
}

.qiraat-card {
    text-decoration: none;
    border: 2px solid #667eea;
    padding: 20px 30px;
    font-size: 14px;
    color: #333;
    background: white;
    border-radius: 10px;
    transition: all 0.3s;
    text-align: center;
    min-width: 200px;
}

.qiraat-card:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
}
</style>

<div class="emaqra-container">
    <h2>📜 Qiraat - Mushaf 10 Imam</h2>
    
    <div class="qiraat-grid">
        <a href="../emaqra/qiraat.php?id=0" target="_blank" class="qiraat-card">🕋 Mushaf Imam Nafi'</a>
        <a href="../emaqra/qiraat.php?id=1" target="_blank" class="qiraat-card">📖 Mushaf Imam Ibnu Katsir</a>
        <a href="../emaqra/qiraat.php?id=2" target="_blank" class="qiraat-card">📜 Mushaf Imam Abu 'Amr</a>
        <a href="../emaqra/qiraat.php?id=3" target="_blank" class="qiraat-card">📚 Mushaf Imam Ibnu 'Amir</a>
        <a href="../emaqra/qiraat.php?id=4" target="_blank" class="qiraat-card">🎯 Mushaf Imam 'Ashim</a>
        <a href="../emaqra/qiraat.php?id=5" target="_blank" class="qiraat-card">⭐ Mushaf Imam Hamzah</a>
        <a href="../emaqra/qiraat.php?id=6" target="_blank" class="qiraat-card">🌙 Mushaf Imam Ali Al-Kisai</a>
        <a href="../emaqra/qiraat.php?id=7" target="_blank" class="qiraat-card">🕌 Mushaf Imam Abu Ja'far</a>
        <a href="../emaqra/qiraat.php?id=8" target="_blank" class="qiraat-card">📿 Mushaf Imam Ya'cub</a>
        <a href="../emaqra/qiraat.php?id=9" target="_blank" class="qiraat-card">✨ Mushaf Imam Kholaf</a>
        <a href="../emaqra/qiraat.php?id=10" target="_blank" class="qiraat-card">📕 Kaidah Qiraat Tujuh</a>
    </div>
</div>
