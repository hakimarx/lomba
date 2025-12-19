<?php
/**
 * Emaqra - Mushaf dengan Timer
 * Fullscreen mushaf viewer with timer panel
 */

// Get jenis parameter
$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'madinah';
?>

<style>
.mushaf-fullscreen {
    display: flex;
    min-height: 600px;
    gap: 0;
    background: #f0f0f0;
    border-radius: 10px;
    overflow: hidden;
}

/* Timer Panel - Left Side */
.timer-panel {
    width: 220px;
    background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.timer-box {
    display: flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.1);
    padding: 15px;
    border-radius: 8px;
}

.timer-light {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: 3px solid rgba(255,255,255,0.3);
}

.timer-light.yellow { background: #f1c40f; box-shadow: 0 0 10px #f1c40f; }
.timer-light.green { background: #2ecc71; box-shadow: 0 0 10px #2ecc71; }
.timer-light.red { background: #e74c3c; box-shadow: 0 0 10px #e74c3c; }
.timer-light.orange { background: #e67e22; box-shadow: 0 0 10px #e67e22; }

.timer-display {
    font-size: 24px;
    font-weight: bold;
    color: white;
    font-family: 'Courier New', monospace;
}

.timer-btn {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: bold;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
}

.timer-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(52, 152, 219, 0.4);
}

.zoom-controls {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: auto;
}

.zoom-btn {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    border: none;
    font-size: 24px;
    cursor: pointer;
    transition: all 0.2s;
    background: #f39c12;
    color: white;
}

.zoom-btn:hover {
    transform: scale(1.1);
}

/* Mushaf Panel - Right Side (Main) */
.mushaf-panel {
    flex: 1;
    display: flex;
    flex-direction: column;
    background: white;
}

.mushaf-header {
    background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
    padding: 12px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: white;
}

.mushaf-header h3 {
    margin: 0;
    font-size: 18px;
}

.hide-menu-btn {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
}

.mushaf-content {
    flex: 1;
    overflow: auto;
    text-align: center;
    padding: 10px;
    background: #fafafa;
}

.mushaf-content img {
    max-width: 100%;
    transition: width 0.3s;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.mushaf-footer {
    background: linear-gradient(135deg, #c0392b 0%, #a93226 100%);
    padding: 10px 15px;
    display: flex;
    gap: 10px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
}

.mushaf-footer input,
.mushaf-footer select {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

.mushaf-footer button {
    padding: 8px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background: white;
    color: #333;
}

/* Mushaf Type Selector */
.mushaf-type-selector {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    padding: 0 20px;
}

.type-btn {
    flex: 1;
    padding: 12px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s;
}

.type-btn.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.type-btn:not(.active) {
    background: #e0e0e0;
    color: #333;
}

@media (max-width: 768px) {
    .mushaf-fullscreen {
        flex-direction: column;
    }
    .timer-panel {
        width: 100%;
        flex-direction: row;
        flex-wrap: wrap;
    }
}
</style>

<div class="mushaf-type-selector">
    <a class="type-btn <?php echo $jenis=='madinah' ? 'active' : ''; ?>" 
       href="?page=utama&page2=emaqra_mushaf&jenis=madinah">
        🕋 Mushaf Madinah
    </a>
    <a class="type-btn <?php echo $jenis=='indonesia' ? 'active' : ''; ?>" 
       href="?page=utama&page2=emaqra_mushaf&jenis=indonesia">
        🇮🇩 Mushaf Indonesia
    </a>
</div>

<div class="mushaf-fullscreen">
    <!-- Timer Panel (Left) -->
    <div class="timer-panel">
        <div class="timer-box">
            <div class="timer-light yellow"></div>
            <span class="timer-display" id="timer1">00:00</span>
        </div>
        <div class="timer-box">
            <div class="timer-light green"></div>
            <span class="timer-display" id="timer2">00:00</span>
        </div>
        <div class="timer-box">
            <div class="timer-light red"></div>
            <span class="timer-display" id="timer3">00:00</span>
        </div>
        <div class="timer-box">
            <div class="timer-light orange"></div>
            <span class="timer-display" id="timer4">00:00</span>
        </div>
        
        <button class="timer-btn" onclick="startTimer()">▶ mulai</button>
        
        <div class="zoom-controls">
            <button class="zoom-btn" onclick="zoomMushaf(10)">+</button>
            <button class="zoom-btn" onclick="zoomMushaf(-10)">-</button>
            <button class="zoom-btn" onclick="prevPage()">◀</button>
            <button class="zoom-btn" onclick="nextPage()">▶</button>
        </div>
    </div>
    
    <!-- Mushaf Panel (Right) -->
    <div class="mushaf-panel">
        <div class="mushaf-header">
            <h3 id="pageInfo">halaman 1, juz 1</h3>
            <button class="hide-menu-btn" onclick="toggleFooter()">hide menu</button>
        </div>
        
        <div class="mushaf-content">
            <img id="mushafImg" src="../assets/mushaf/<?php echo $jenis; ?>/1.<?php echo $jenis=='madinah'?'jpg':'png'; ?>" alt="Mushaf">
        </div>
        
        <div class="mushaf-footer" id="mushafFooter">
            <input type="number" id="inputHal" placeholder="hlm" min="1" max="604" style="width:70px">
            <button onclick="goToPage()">pilih</button>
            
            <input type="number" id="inputJuz" placeholder="juz" min="1" max="30" style="width:70px">
            <button onclick="goToJuz()">pilih</button>
            
            <select id="selectSurat" onchange="loadAyat()">
                <option value="">-- pilih surat --</option>
            </select>
            
            <select id="selectAyat" onchange="goToAyat()">
                <option value="">-- pilih ayat --</option>
            </select>
        </div>
    </div>
</div>

<script>
// Mushaf variables
let currentPage = 1;
let currentJuz = 1;
let zoomLevel = 100;
const jenis = '<?php echo $jenis; ?>';
const ext = jenis === 'madinah' ? 'jpg' : 'png';

// Timer variables
let timerRunning = false;
let timerSeconds = [0, 0, 0, 0];
let timerInterval;

// Juz to page mapping (approximate)
const juzPages = [1, 22, 42, 62, 82, 102, 121, 142, 162, 182, 201, 222, 242, 262, 282, 302, 322, 342, 362, 382, 402, 422, 442, 462, 482, 502, 522, 542, 562, 582];

// Surat data - will be loaded
let suratData = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadSuratData();
    updatePageInfo();
});

function updatePageInfo() {
    document.getElementById('pageInfo').innerText = `halaman ${currentPage}, juz ${Math.ceil(currentPage/20)}`;
}

function updateMushafImage() {
    document.getElementById('mushafImg').src = `../assets/mushaf/${jenis}/${currentPage}.${ext}`;
    updatePageInfo();
}

function zoomMushaf(delta) {
    zoomLevel += delta;
    if(zoomLevel < 50) zoomLevel = 50;
    if(zoomLevel > 200) zoomLevel = 200;
    document.getElementById('mushafImg').style.width = zoomLevel + '%';
}

function nextPage() {
    currentPage++;
    if(currentPage > 604) currentPage = 1;
    updateMushafImage();
}

function prevPage() {
    currentPage--;
    if(currentPage < 1) currentPage = 604;
    updateMushafImage();
}

function goToPage() {
    let page = parseInt(document.getElementById('inputHal').value);
    if(page >= 1 && page <= 604) {
        currentPage = page;
        updateMushafImage();
    }
}

function goToJuz() {
    let juz = parseInt(document.getElementById('inputJuz').value);
    if(juz >= 1 && juz <= 30) {
        currentPage = juzPages[juz - 1];
        updateMushafImage();
    }
}

function toggleFooter() {
    let footer = document.getElementById('mushafFooter');
    footer.style.display = footer.style.display === 'none' ? 'flex' : 'none';
}

// Timer functions
function startTimer() {
    if(timerRunning) {
        clearInterval(timerInterval);
        timerRunning = false;
    } else {
        timerInterval = setInterval(updateTimers, 1000);
        timerRunning = true;
    }
}

function updateTimers() {
    for(let i = 0; i < 4; i++) {
        timerSeconds[i]++;
        let mins = Math.floor(timerSeconds[i] / 60);
        let secs = timerSeconds[i] % 60;
        document.getElementById('timer' + (i+1)).innerText = 
            String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    }
}

// Surat data loader
function loadSuratData() {
    // Standard Quran surat list
    const surats = [
        {no: 1, nama: "Al-Fatihah", ayat: 7},
        {no: 2, nama: "Al-Baqarah", ayat: 286},
        {no: 3, nama: "Ali 'Imran", ayat: 200},
        {no: 4, nama: "An-Nisa", ayat: 176},
        {no: 5, nama: "Al-Ma'idah", ayat: 120},
        {no: 6, nama: "Al-An'am", ayat: 165},
        {no: 7, nama: "Al-A'raf", ayat: 206},
        {no: 8, nama: "Al-Anfal", ayat: 75},
        {no: 9, nama: "At-Taubah", ayat: 129},
        {no: 10, nama: "Yunus", ayat: 109},
        // ... more surats would go here
    ];
    
    let select = document.getElementById('selectSurat');
    surats.forEach(s => {
        select.innerHTML += `<option value="${s.no}" data-ayat="${s.ayat}">${s.no}. ${s.nama}</option>`;
    });
    suratData = surats;
}

function loadAyat() {
    let select = document.getElementById('selectSurat');
    let ayatCount = select.options[select.selectedIndex].getAttribute('data-ayat');
    let ayatSelect = document.getElementById('selectAyat');
    ayatSelect.innerHTML = '<option value="">-- pilih ayat --</option>';
    for(let i = 1; i <= ayatCount; i++) {
        ayatSelect.innerHTML += `<option value="${i}">${i}</option>`;
    }
}

function goToAyat() {
    // Redirect to emaqra mushaf page with surat/ayat
    let surat = document.getElementById('selectSurat').value;
    let ayat = document.getElementById('selectAyat').value;
    if(surat && ayat) {
        window.open(`../emaqra/?page=mushaf&jenis=${jenis}&nosurat=${surat}&noayat=${ayat}`, '_blank');
    }
}
</script>
