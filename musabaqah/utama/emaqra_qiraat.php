<?php
/**
 * Emaqra - Qiraat
 * Wrapper to view Qiraat PDFs
 */

$qiraatData = array(
    array("MUSHAF IMAM NAFI'", "qolun", "warsy", "01qolun.pdf", "02warsy.pdf"),
    array("MUSHAF IMAM IBNU KATSIR", "al-bazzi", "qunbul", "03albazzi.pdf", "04qunbul.pdf"),
    array("MUSHAF IMAM ABU 'AMR", "ad-duri", "as-susi", "05adduri.pdf", "06assusi.pdf"),
    array("MUSHAF IMAM IBNU 'AMIR", "hisyam", "ibnu dzakwan", "07hisyam.pdf", "08ibnudzakwan.pdf"),
    array("MUSHAF IMAM 'ASHIM", "syubah", "hafsh", "09syubah.pdf", "10hafsh.pdf"),
    array("MUSHAF IMAM HAMZAH", "kholaf", "khollad", "11kholaf.pdf", "12khollad.pdf"),
    array("MUSHAF IMAM ALI AL-KISAI", "abul harits", "hafshadduri", "13abulharits.pdf", "14hafshadduri.pdf"),
    array("MUSHAF IMAM ABU JA'FAR", "ibnu wardan", "ibnu jammaz", "15ibnuwardan.pdf", "16ibnujammaz.pdf"),
    array("MUSHAF IMAM YA'CUB", "ruwais", "rawh", "17ruwais.pdf", "18rawh.pdf"),
    array("MUSHAF IMAM KHOLAF", "idris", "ishaq", "19idris.pdf", "20ishaq.pdf"),
);

$id = isset($_GET['id']) ? intval($_GET['id']) : -1;
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

.qiraat-viewer {
    display: flex;
    flex-direction: column;
    height: 700px;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid #667eea;
}

.qiraat-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.qiraat-header h3 {
    margin: 0;
}

.qiraat-nav {
    display: flex;
    gap: 10px;
}

.qiraat-nav a {
    background: rgba(255,255,255,0.2);
    color: white;
    text-decoration: none;
    padding: 8px 15px;
    border-radius: 5px;
    transition: all 0.3s;
}

.qiraat-nav a:hover {
    background: rgba(255,255,255,0.4);
}

.qiraat-pdfs {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.pdf-section {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.pdf-label {
    background: #333;
    color: white;
    padding: 5px 15px;
    font-size: 12px;
}

.pdf-section embed {
    flex: 1;
    width: 100%;
    border: none;
}

.back-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-block;
    margin-bottom: 20px;
}
</style>

<div class="emaqra-container">
    <?php if($id < 0 || $id >= count($qiraatData)): ?>
        <!-- Show Qiraat Selection Grid -->
        <h2>📜 Qiraat - Mushaf 10 Imam</h2>
        
        <div class="qiraat-grid">
            <?php foreach($qiraatData as $index => $q): ?>
                <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $index; ?>" class="qiraat-card">
                    <?php echo $q[0]; ?>
                </a>
            <?php endforeach; ?>
        </div>
        
    <?php else: ?>
        <!-- Show PDF Viewer -->
        <?php
            $qiraat = $qiraatData[$id][0];
            $nama1 = $qiraatData[$id][1];
            $nama2 = $qiraatData[$id][2];
            $link1 = $qiraatData[$id][3];
            $link2 = $qiraatData[$id][4];
            $nextId = ($id + 1) % count($qiraatData);
            $prevId = ($id - 1 + count($qiraatData)) % count($qiraatData);
        ?>
        
        <a href="?page=utama&page2=emaqra_qiraat" class="back-btn">← Kembali ke Daftar Qiraat</a>
        
        <div class="qiraat-viewer">
            <div class="qiraat-header">
                <h3><?php echo $qiraat; ?></h3>
                <div class="qiraat-nav">
                    <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $prevId; ?>">← Sebelumnya</a>
                    <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $nextId; ?>">Selanjutnya →</a>
                </div>
            </div>
            
            <div class="qiraat-pdfs">
                <div class="pdf-section">
                    <div class="pdf-label"><?php echo strtoupper($nama1); ?></div>
                    <embed type="application/pdf" src="../assets/qiraat/<?php echo $link1; ?>">
                </div>
                <div class="pdf-section">
                    <div class="pdf-label"><?php echo strtoupper($nama2); ?></div>
                    <embed type="application/pdf" src="../assets/qiraat/<?php echo $link2; ?>">
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
