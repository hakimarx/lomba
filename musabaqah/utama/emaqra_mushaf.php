<?php
/**
 * Emaqra - Mushaf
 * Wrapper to embed emaqra mushaf in musabaqah frame
 */

// Get jenis parameter
$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'madinah';
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
    text-decoration: none;
    display: inline-block;
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

.mushaf-frame {
    width: 100%;
    height: 700px;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.mushaf-form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    max-width: 400px;
    margin: 20px auto;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.mushaf-form select {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
}

.mushaf-form input[type="submit"] {
    padding: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
}

.mushaf-form input[type="submit"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
</style>

<div class="emaqra-container">
    <h2>🕌 Mushaf Al-Quran</h2>
    
    <div class="mushaf-buttons">
        <a class="mushaf-btn <?php echo $jenis=='madinah' ? 'active' : ''; ?>" 
           href="?page=utama&page2=emaqra_mushaf&jenis=madinah">
            🕋 Mushaf Madinah
        </a>
        <a class="mushaf-btn <?php echo $jenis=='indonesia' ? 'active' : ''; ?>" 
           href="?page=utama&page2=emaqra_mushaf&jenis=indonesia">
            🇮🇩 Mushaf Indonesia
        </a>
    </div>
    
    <?php
        // Include koneksi dan generate data surat
        include_once __DIR__ . "/../../emaqra/koneksi.php";
        
        $query = mysqli_query($koneksi, "SELECT * from daftarsurah;");
        $kalimat = "";
        $suratList = [];
        while($data = mysqli_fetch_array($query)){
            $vnosurat = $data['nosurat']; 
            $vnama = $data['nama'];
            $vjumlahayat = $data['akhir'];
            $kalimat .= "[$vnosurat,\"$vnama\", $vjumlahayat],\n";
            $suratList[] = $data;
        }
    ?>
    
    <form action="../emaqra/mushaf.php" method="get" target="_blank" class="mushaf-form">
        <input type="hidden" name="page" value="mushaf">
        <input type="hidden" name="jenis" value="<?php echo $jenis; ?>">
        
        <div>
            <label><strong>Pilih Surat:</strong></label>
            <select name="nosurat" id="vselect" onchange="ubahAyat(this)">
                <?php foreach($suratList as $surat): ?>
                    <option value="<?php echo $surat['nosurat']; ?>" data-ayat="<?php echo $surat['akhir']; ?>">
                        <?php echo $surat['nosurat']; ?>. <?php echo $surat['nama']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div>
            <label><strong>Pilih Ayat:</strong></label>
            <select name="noayat" id="vselect2"></select>
        </div>
        
        <input type="submit" value="📖 Lihat Mushaf <?php echo ucfirst($jenis); ?>">
    </form>
</div>

<script>
let vdata = [<?php echo $kalimat; ?>];

window.onload = function(){
    ubahAyat(document.getElementById('vselect'));
}

function ubahAyat(selectEl){
    let selectedOption = selectEl.options[selectEl.selectedIndex];
    let jumlahAyat = parseInt(selectedOption.getAttribute('data-ayat'));
    let vselect2 = document.getElementById('vselect2');
    
    vselect2.innerHTML = "";
    for(let i = 1; i <= jumlahAyat; i++){
        vselect2.innerHTML += "<option value='" + i + "'>" + i + "</option>";
    }
}
</script>
