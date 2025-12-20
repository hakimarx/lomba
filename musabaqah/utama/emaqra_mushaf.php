<?php

/**
 * Emaqra - Mushaf dengan Timer
 * Fullscreen mushaf viewer with timer panel
 */

// Get jenis parameter
$jenis = isset($_GET['jenis']) ? $_GET['jenis'] : 'madinah';

// Full Quran Surat Data
$suratData = [
    ["no" => 1, "nama" => "Al-Fatihah", "ayat" => 7],
    ["no" => 2, "nama" => "Al-Baqarah", "ayat" => 286],
    ["no" => 3, "nama" => "Ali 'Imran", "ayat" => 200],
    ["no" => 4, "nama" => "An-Nisa", "ayat" => 176],
    ["no" => 5, "nama" => "Al-Ma'idah", "ayat" => 120],
    ["no" => 6, "nama" => "Al-An'am", "ayat" => 165],
    ["no" => 7, "nama" => "Al-A'raf", "ayat" => 206],
    ["no" => 8, "nama" => "Al-Anfal", "ayat" => 75],
    ["no" => 9, "nama" => "At-Taubah", "ayat" => 129],
    ["no" => 10, "nama" => "Yunus", "ayat" => 109],
    ["no" => 11, "nama" => "Hud", "ayat" => 123],
    ["no" => 12, "nama" => "Yusuf", "ayat" => 111],
    ["no" => 13, "nama" => "Ar-Ra'd", "ayat" => 43],
    ["no" => 14, "nama" => "Ibrahim", "ayat" => 52],
    ["no" => 15, "nama" => "Al-Hijr", "ayat" => 99],
    ["no" => 16, "nama" => "An-Nahl", "ayat" => 128],
    ["no" => 17, "nama" => "Al-Isra", "ayat" => 111],
    ["no" => 18, "nama" => "Al-Kahf", "ayat" => 110],
    ["no" => 19, "nama" => "Maryam", "ayat" => 98],
    ["no" => 20, "nama" => "Taha", "ayat" => 135],
    ["no" => 21, "nama" => "Al-Anbiya", "ayat" => 112],
    ["no" => 22, "nama" => "Al-Hajj", "ayat" => 78],
    ["no" => 23, "nama" => "Al-Mu'minun", "ayat" => 118],
    ["no" => 24, "nama" => "An-Nur", "ayat" => 64],
    ["no" => 25, "nama" => "Al-Furqan", "ayat" => 77],
    ["no" => 26, "nama" => "Ash-Shu'ara", "ayat" => 227],
    ["no" => 27, "nama" => "An-Naml", "ayat" => 93],
    ["no" => 28, "nama" => "Al-Qasas", "ayat" => 88],
    ["no" => 29, "nama" => "Al-'Ankabut", "ayat" => 69],
    ["no" => 30, "nama" => "Ar-Rum", "ayat" => 60],
    ["no" => 31, "nama" => "Luqman", "ayat" => 34],
    ["no" => 32, "nama" => "As-Sajdah", "ayat" => 30],
    ["no" => 33, "nama" => "Al-Ahzab", "ayat" => 73],
    ["no" => 34, "nama" => "Saba", "ayat" => 54],
    ["no" => 35, "nama" => "Fatir", "ayat" => 45],
    ["no" => 36, "nama" => "Ya-Sin", "ayat" => 83],
    ["no" => 37, "nama" => "As-Saffat", "ayat" => 182],
    ["no" => 38, "nama" => "Sad", "ayat" => 88],
    ["no" => 39, "nama" => "Az-Zumar", "ayat" => 75],
    ["no" => 40, "nama" => "Ghafir", "ayat" => 85],
    ["no" => 41, "nama" => "Fussilat", "ayat" => 54],
    ["no" => 42, "nama" => "Ash-Shura", "ayat" => 53],
    ["no" => 43, "nama" => "Az-Zukhruf", "ayat" => 89],
    ["no" => 44, "nama" => "Ad-Dukhan", "ayat" => 59],
    ["no" => 45, "nama" => "Al-Jathiyah", "ayat" => 37],
    ["no" => 46, "nama" => "Al-Ahqaf", "ayat" => 35],
    ["no" => 47, "nama" => "Muhammad", "ayat" => 38],
    ["no" => 48, "nama" => "Al-Fath", "ayat" => 29],
    ["no" => 49, "nama" => "Al-Hujurat", "ayat" => 18],
    ["no" => 50, "nama" => "Qaf", "ayat" => 45],
    ["no" => 51, "nama" => "Adh-Dhariyat", "ayat" => 60],
    ["no" => 52, "nama" => "At-Tur", "ayat" => 49],
    ["no" => 53, "nama" => "An-Najm", "ayat" => 62],
    ["no" => 54, "nama" => "Al-Qamar", "ayat" => 55],
    ["no" => 55, "nama" => "Ar-Rahman", "ayat" => 78],
    ["no" => 56, "nama" => "Al-Waqi'ah", "ayat" => 96],
    ["no" => 57, "nama" => "Al-Hadid", "ayat" => 29],
    ["no" => 58, "nama" => "Al-Mujadila", "ayat" => 22],
    ["no" => 59, "nama" => "Al-Hashr", "ayat" => 24],
    ["no" => 60, "nama" => "Al-Mumtahanah", "ayat" => 13],
    ["no" => 61, "nama" => "As-Saff", "ayat" => 14],
    ["no" => 62, "nama" => "Al-Jumu'ah", "ayat" => 11],
    ["no" => 63, "nama" => "Al-Munafiqun", "ayat" => 11],
    ["no" => 64, "nama" => "At-Taghabun", "ayat" => 18],
    ["no" => 65, "nama" => "At-Talaq", "ayat" => 12],
    ["no" => 66, "nama" => "At-Tahrim", "ayat" => 12],
    ["no" => 67, "nama" => "Al-Mulk", "ayat" => 30],
    ["no" => 68, "nama" => "Al-Qalam", "ayat" => 52],
    ["no" => 69, "nama" => "Al-Haqqah", "ayat" => 52],
    ["no" => 70, "nama" => "Al-Ma'arij", "ayat" => 44],
    ["no" => 71, "nama" => "Nuh", "ayat" => 28],
    ["no" => 72, "nama" => "Al-Jinn", "ayat" => 28],
    ["no" => 73, "nama" => "Al-Muzzammil", "ayat" => 20],
    ["no" => 74, "nama" => "Al-Muddaththir", "ayat" => 56],
    ["no" => 75, "nama" => "Al-Qiyamah", "ayat" => 40],
    ["no" => 76, "nama" => "Al-Insan", "ayat" => 31],
    ["no" => 77, "nama" => "Al-Mursalat", "ayat" => 50],
    ["no" => 78, "nama" => "An-Naba", "ayat" => 40],
    ["no" => 79, "nama" => "An-Nazi'at", "ayat" => 46],
    ["no" => 80, "nama" => "'Abasa", "ayat" => 42],
    ["no" => 81, "nama" => "At-Takwir", "ayat" => 29],
    ["no" => 82, "nama" => "Al-Infitar", "ayat" => 19],
    ["no" => 83, "nama" => "Al-Mutaffifin", "ayat" => 36],
    ["no" => 84, "nama" => "Al-Inshiqaq", "ayat" => 25],
    ["no" => 85, "nama" => "Al-Buruj", "ayat" => 22],
    ["no" => 86, "nama" => "At-Tariq", "ayat" => 17],
    ["no" => 87, "nama" => "Al-A'la", "ayat" => 19],
    ["no" => 88, "nama" => "Al-Ghashiyah", "ayat" => 26],
    ["no" => 89, "nama" => "Al-Fajr", "ayat" => 30],
    ["no" => 90, "nama" => "Al-Balad", "ayat" => 20],
    ["no" => 91, "nama" => "Ash-Shams", "ayat" => 15],
    ["no" => 92, "nama" => "Al-Layl", "ayat" => 21],
    ["no" => 93, "nama" => "Ad-Duha", "ayat" => 11],
    ["no" => 94, "nama" => "Ash-Sharh", "ayat" => 8],
    ["no" => 95, "nama" => "At-Tin", "ayat" => 8],
    ["no" => 96, "nama" => "Al-'Alaq", "ayat" => 19],
    ["no" => 97, "nama" => "Al-Qadr", "ayat" => 5],
    ["no" => 98, "nama" => "Al-Bayyinah", "ayat" => 8],
    ["no" => 99, "nama" => "Az-Zalzalah", "ayat" => 8],
    ["no" => 100, "nama" => "Al-'Adiyat", "ayat" => 11],
    ["no" => 101, "nama" => "Al-Qari'ah", "ayat" => 11],
    ["no" => 102, "nama" => "At-Takathur", "ayat" => 8],
    ["no" => 103, "nama" => "Al-'Asr", "ayat" => 3],
    ["no" => 104, "nama" => "Al-Humazah", "ayat" => 9],
    ["no" => 105, "nama" => "Al-Fil", "ayat" => 5],
    ["no" => 106, "nama" => "Quraysh", "ayat" => 4],
    ["no" => 107, "nama" => "Al-Ma'un", "ayat" => 7],
    ["no" => 108, "nama" => "Al-Kawthar", "ayat" => 3],
    ["no" => 109, "nama" => "Al-Kafirun", "ayat" => 6],
    ["no" => 110, "nama" => "An-Nasr", "ayat" => 3],
    ["no" => 111, "nama" => "Al-Masad", "ayat" => 5],
    ["no" => 112, "nama" => "Al-Ikhlas", "ayat" => 4],
    ["no" => 113, "nama" => "Al-Falaq", "ayat" => 5],
    ["no" => 114, "nama" => "An-Nas", "ayat" => 6]
];
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
        width: 260px;
        background: linear-gradient(180deg, #2c3e50 0%, #1a252f 100%);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .timer-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .timer-header h4 {
        color: white;
        margin: 0;
        font-size: 14px;
    }

    .timer-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        padding: 12px;
        border-radius: 8px;
    }

    .timer-light {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .timer-light.yellow {
        background: #f1c40f;
        box-shadow: 0 0 10px #f1c40f;
    }

    .timer-light.green {
        background: #2ecc71;
        box-shadow: 0 0 10px #2ecc71;
    }

    .timer-light.red {
        background: #e74c3c;
        box-shadow: 0 0 10px #e74c3c;
    }

    .timer-light.orange {
        background: #e67e22;
        box-shadow: 0 0 10px #e67e22;
    }

    /* Active phase highlight */
    .timer-box.active-phase {
        background: rgba(255, 255, 255, 0.25);
        border: 2px solid rgba(255, 255, 255, 0.5);
        animation: pulse-glow 1s ease-in-out infinite;
    }

    @keyframes pulse-glow {

        0%,
        100% {
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }

        50% {
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.6);
        }
    }

    .timer-display {
        font-size: 20px;
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

    /* Zoom controls MOVED near timer */
    .zoom-controls {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 10px;
        margin-top: 10px;
    }

    .zoom-btn {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        border: none;
        font-size: 20px;
        cursor: pointer;
        transition: all 0.2s;
        background: #f39c12;
        color: white;
    }

    .zoom-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(243, 156, 18, 0.4);
    }

    /* Search Surat Section */
    .search-section {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .search-section label {
        display: block;
        color: rgba(255, 255, 255, 0.7);
        font-size: 12px;
        margin-bottom: 6px;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 10px 12px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 14px;
        box-sizing: border-box;
    }

    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .search-input:focus {
        outline: none;
        background: rgba(255, 255, 255, 0.15);
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.3);
    }

    .search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #1e293b;
        border-radius: 8px;
        max-height: 200px;
        overflow-y: auto;
        z-index: 100;
        display: none;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }

    .search-results.active {
        display: block;
    }

    .search-result-item {
        padding: 10px 12px;
        cursor: pointer;
        color: white;
        font-size: 13px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        transition: background 0.2s;
    }

    .search-result-item:hover {
        background: rgba(52, 152, 219, 0.3);
    }

    .search-result-item .surat-no {
        color: #f39c12;
        font-weight: bold;
    }

    .ayat-input {
        width: 100%;
        margin-top: 8px;
        padding: 8px 12px;
        border: none;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: white;
        font-size: 14px;
        box-sizing: border-box;
    }

    .go-btn {
        width: 100%;
        margin-top: 10px;
        padding: 10px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%);
        color: white;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }

    .go-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(39, 174, 96, 0.4);
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
        background: rgba(255, 255, 255, 0.2);
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
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
    }

    /* Footer simplified - page navigation only */
    .mushaf-footer {
        background: linear-gradient(135deg, #2c3e50 0%, #1a252f 100%);
        padding: 10px 15px;
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        flex-wrap: wrap;
    }

    .mushaf-footer input {
        padding: 8px 12px;
        border-radius: 5px;
        border: none;
        background: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .mushaf-footer input::placeholder {
        color: rgba(255, 255, 255, 0.5);
    }

    .mushaf-footer button {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background: #3498db;
        color: white;
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
        text-decoration: none;
        text-align: center;
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

        .search-section {
            width: 100%;
        }
    }
</style>

<div class="mushaf-type-selector">
    <a class="type-btn <?php echo $jenis == 'madinah' ? 'active' : ''; ?>"
        href="?page=utama&page2=emaqra_mushaf&jenis=madinah">
        🕋 MUSHAF MADINAH
    </a>
    <a class="type-btn <?php echo $jenis == 'indonesia' ? 'active' : ''; ?>"
        href="?page=utama&page2=emaqra_mushaf&jenis=indonesia">
        🇮🇩 MUSHAF INDONESIA
    </a>
</div>

<div class="mushaf-fullscreen">
    <!-- Timer Panel (Left) with Zoom Controls and Search -->
    <div class="timer-panel">
        <div class="timer-header">
            <h4>⏱️ Timer</h4>
        </div>

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

        <!-- Zoom Controls - MOVED HERE near timer -->
        <div class="zoom-controls">
            <button class="zoom-btn" onclick="zoomMushaf(10)" title="Zoom In">+</button>
            <button class="zoom-btn" onclick="zoomMushaf(-10)" title="Zoom Out">−</button>
            <button class="zoom-btn" onclick="prevPage()" title="Halaman Sebelumnya">◀</button>
            <button class="zoom-btn" onclick="nextPage()" title="Halaman Berikutnya">▶</button>
        </div>

        <!-- Search Surat/Ayat - NEW searchable input -->
        <div class="search-section">
            <label>🔍 Cari Surat</label>
            <div class="search-input-wrapper">
                <input type="text" class="search-input" id="searchSurat"
                    placeholder="Ketik nama surat..."
                    autocomplete="off"
                    onfocus="showSearchResults()"
                    oninput="filterSurats()">
                <div class="search-results" id="searchResults">
                    <?php foreach ($suratData as $s): ?>
                        <div class="search-result-item"
                            data-no="<?php echo $s['no']; ?>"
                            data-ayat="<?php echo $s['ayat']; ?>"
                            onclick="selectSurat(<?php echo $s['no']; ?>, '<?php echo $s['nama']; ?>', <?php echo $s['ayat']; ?>)">
                            <span class="surat-no"><?php echo $s['no']; ?>.</span> <?php echo $s['nama']; ?>
                            <small style="color:rgba(255,255,255,0.5)"> (<?php echo $s['ayat']; ?> ayat)</small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <input type="number" class="ayat-input" id="inputAyat"
                placeholder="Ayat ke..." min="1" max="286">

            <button class="go-btn" onclick="goToSuratAyat()">📖 Buka</button>
        </div>
    </div>

    <!-- Mushaf Panel (Right) -->
    <div class="mushaf-panel">
        <div class="mushaf-header">
            <h3 id="pageInfo">halaman 1, juz 1</h3>
            <button class="hide-menu-btn" onclick="toggleFooter()">hide menu</button>
        </div>

        <div class="mushaf-content">
            <img id="mushafImg" src="../assets/mushaf/<?php echo $jenis; ?>/1.<?php echo $jenis == 'madinah' ? 'jpg' : 'png'; ?>" alt="Mushaf">
        </div>

        <div class="mushaf-footer" id="mushafFooter">
            <input type="number" id="inputHal" placeholder="hlm" min="1" max="604" style="width:70px">
            <button onclick="goToPage()">pilih</button>

            <input type="number" id="inputJuz" placeholder="juz" min="1" max="30" style="width:70px">
            <button onclick="goToJuz()">pilih</button>
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

    // Selected surat data
    let selectedSurat = null;
    let selectedAyatCount = 286;

    // Juz to page mapping (approximate)
    const juzPages = [1, 22, 42, 62, 82, 102, 121, 142, 162, 182, 201, 222, 242, 262, 282, 302, 322, 342, 362, 382, 402, 422, 442, 462, 482, 502, 522, 542, 562, 582];

    // Surat to page mapping (approximate start pages)
    const suratPages = [
        1, 1, 2, 50, 77, 106, 128, 151, 177, 187, 208, 221, 235, 249, 255, 262, 267, 282, 293, 305, 312, 322, 332, 341, 350, 359, 367, 377, 385, 392,
        400, 404, 411, 415, 418, 422, 428, 434, 440, 453, 467, 477, 489, 495, 499, 504, 507, 511, 515, 518, 520, 523, 526, 528, 531, 534, 537, 542,
        545, 549, 551, 553, 554, 556, 558, 560, 562, 564, 566, 568, 570, 572, 574, 575, 577, 578, 580, 582, 583, 585, 586, 587, 587, 589, 590, 591,
        591, 592, 593, 594, 594, 595, 596, 596, 597, 597, 598, 598, 599, 599, 600, 600, 600, 601, 601, 601, 602, 602, 602, 603, 603, 604, 604, 604
    ];

    // Timer variables - Sequential timer with phases
    let timerRunning = false;
    let timerInterval = null;
    let totalSeconds = 0;
    let currentPhase = 0; // 0=persiapan, 1=penilaian, 2=menjelang, 3=habis

    // Timer settings (in seconds) - default values, can be customized
    let timerSettings = {
        persiapan: 5, // Yellow first - preparation time
        penilaian: 300, // Green - assessment time (5 min default)
        menjelang: 4, // Orange - warning time
        habis: 3 // Red - time up
    };

    // Audio context for beep sounds
    let audioContext = null;

    function initAudio() {
        if (!audioContext) {
            audioContext = new(window.AudioContext || window.webkitAudioContext)();
        }
    }

    function playBeep(count = 1, frequency = 800) {
        initAudio();
        for (let i = 0; i < count; i++) {
            setTimeout(() => {
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                oscillator.frequency.value = frequency;
                oscillator.type = 'sine';
                gainNode.gain.setValueAtTime(0.3, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.3);
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.3);
            }, i * 400);
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updatePageInfo();
        updateTimerDisplays();

        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-input-wrapper')) {
                document.getElementById('searchResults').classList.remove('active');
            }
        });
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
        if (zoomLevel < 50) zoomLevel = 50;
        if (zoomLevel > 200) zoomLevel = 200;
        document.getElementById('mushafImg').style.width = zoomLevel + '%';
    }

    function nextPage() {
        currentPage++;
        if (currentPage > 604) currentPage = 1;
        updateMushafImage();
    }

    function prevPage() {
        currentPage--;
        if (currentPage < 1) currentPage = 604;
        updateMushafImage();
    }

    function goToPage() {
        let page = parseInt(document.getElementById('inputHal').value);
        if (page >= 1 && page <= 604) {
            currentPage = page;
            updateMushafImage();
        }
    }

    function goToJuz() {
        let juz = parseInt(document.getElementById('inputJuz').value);
        if (juz >= 1 && juz <= 30) {
            currentPage = juzPages[juz - 1];
            updateMushafImage();
        }
    }

    function toggleFooter() {
        let footer = document.getElementById('mushafFooter');
        footer.style.display = footer.style.display === 'none' ? 'flex' : 'none';
    }

    // ==========================================
    // SEQUENTIAL TIMER FUNCTIONS
    // ==========================================

    function startTimer() {
        if (timerRunning) {
            // Stop timer
            clearInterval(timerInterval);
            timerRunning = false;
            document.querySelector('.timer-btn').textContent = '▶ mulai';
        } else {
            // Start timer
            initAudio();
            currentPhase = 0;
            totalSeconds = 0;
            timerRunning = true;
            document.querySelector('.timer-btn').textContent = '⏹ stop';

            // Play start beep (1 beep for persiapan)
            playBeep(1);

            // Highlight current phase
            updatePhaseHighlight();

            timerInterval = setInterval(updateSequentialTimer, 1000);
        }
    }

    function updateSequentialTimer() {
        totalSeconds++;

        // Calculate phase boundaries
        const phase1End = timerSettings.persiapan;
        const phase2End = phase1End + timerSettings.penilaian;
        const phase3End = phase2End + timerSettings.menjelang;
        const phase4End = phase3End + timerSettings.habis;

        // Determine current phase and trigger sounds
        let prevPhase = currentPhase;

        if (totalSeconds <= phase1End) {
            currentPhase = 0; // Persiapan (Yellow)
        } else if (totalSeconds <= phase2End) {
            currentPhase = 1; // Penilaian (Green)
        } else if (totalSeconds <= phase3End) {
            currentPhase = 2; // Menjelang Habis (Orange)
        } else if (totalSeconds <= phase4End) {
            currentPhase = 3; // Waktu Habis (Red)
        } else {
            // Timer completed
            clearInterval(timerInterval);
            timerRunning = false;
            document.querySelector('.timer-btn').textContent = '▶ mulai';
            return;
        }

        // Play sound on phase transition
        if (currentPhase !== prevPhase) {
            updatePhaseHighlight();
            switch (currentPhase) {
                case 1:
                    playBeep(2, 880);
                    break; // Green: 2 beeps
                case 2:
                    playBeep(1, 660);
                    break; // Orange: 1 beep
                case 3:
                    playBeep(3, 440);
                    break; // Red: 3 beeps
            }
        }

        // Update timer displays
        updateTimerDisplays();
    }

    function updateTimerDisplays() {
        const phase1End = timerSettings.persiapan;
        const phase2End = phase1End + timerSettings.penilaian;
        const phase3End = phase2End + timerSettings.menjelang;
        const phase4End = phase3End + timerSettings.habis;

        // Calculate time for each phase
        let times = [0, 0, 0, 0];

        if (totalSeconds <= phase1End) {
            times[0] = totalSeconds;
        } else if (totalSeconds <= phase2End) {
            times[0] = timerSettings.persiapan;
            times[1] = totalSeconds - phase1End;
        } else if (totalSeconds <= phase3End) {
            times[0] = timerSettings.persiapan;
            times[1] = timerSettings.penilaian;
            times[2] = totalSeconds - phase2End;
        } else {
            times[0] = timerSettings.persiapan;
            times[1] = timerSettings.penilaian;
            times[2] = timerSettings.menjelang;
            times[3] = Math.min(totalSeconds - phase3End, timerSettings.habis);
        }

        // Update display
        for (let i = 0; i < 4; i++) {
            const mins = Math.floor(times[i] / 60);
            const secs = times[i] % 60;
            document.getElementById('timer' + (i + 1)).innerText =
                String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }
    }

    function updatePhaseHighlight() {
        // Remove active class from all timer boxes
        document.querySelectorAll('.timer-box').forEach((box, index) => {
            if (index === currentPhase) {
                box.classList.add('active-phase');
            } else {
                box.classList.remove('active-phase');
            }
        });
    }

    // ==========================================
    // SEARCH SURAT FUNCTIONS
    // ==========================================

    function showSearchResults() {
        document.getElementById('searchResults').classList.add('active');
    }

    function filterSurats() {
        const query = document.getElementById('searchSurat').value.toLowerCase();
        const items = document.querySelectorAll('.search-result-item');

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('searchResults').classList.add('active');
    }

    function selectSurat(no, nama, ayat) {
        selectedSurat = no;
        selectedAyatCount = ayat;
        document.getElementById('searchSurat').value = no + '. ' + nama;
        document.getElementById('inputAyat').max = ayat;
        document.getElementById('inputAyat').placeholder = `Ayat 1-${ayat}`;
        document.getElementById('searchResults').classList.remove('active');
    }

    function goToSuratAyat() {
        if (selectedSurat) {
            // Get the page for this surat
            const page = suratPages[selectedSurat - 1] || 1;
            currentPage = page;
            updateMushafImage();

            // Scroll to content
            document.querySelector('.mushaf-content').scrollIntoView({
                behavior: 'smooth'
            });
        } else {
            alert('Pilih surat terlebih dahulu');
        }
    }
</script>