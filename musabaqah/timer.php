<?php
function setvar($rowgolongan)
{
    echo "
            let vwaktupersiapan=" . ($rowgolongan['waktupersiapan'] ?: 5) . ";
            let vwaktupenilaian=" . ($rowgolongan['waktupenilaian'] ?: 300) . ";
            let vwaktumenjelang=" . ($rowgolongan['waktumenjelang'] ?: 60) . ";
            let vwaktuhabis=" . ($rowgolongan['waktuhabis'] ?: 3) . ";
        ";
}

function getlink($idgolongan)
{
    $kode = getonedata("select kodemenu_link from golongan where id=$idgolongan");
    $link = getonedata("select link from menu_link where kode='$kode'");
    return $link;
}

$idgolongan = $_GET['idgolongan'];
$rowgolongan = getonebaris("select * from golongan where id=$idgolongan");
?>

<style>
    :root {
        --timer-bg: #0f172a;
        --timer-card: #1e293b;
        --timer-text: #f8fafc;
        --timer-primary: #10b981;
    }

    .timer-container {
        display: flex;
        height: calc(100vh - 40px);
        background: var(--timer-bg);
        color: var(--timer-text);
        font-family: 'Inter', sans-serif;
    }

    .timer-sidebar {
        width: 320px;
        padding: 20px;
        background: var(--timer-card);
        border-right: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .timer-main {
        flex: 1;
        background: #000;
    }

    .timer-main iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .timer-box {
        display: flex;
        align-items: center;
        gap: 15px;
        background: rgba(255, 255, 255, 0.05);
        padding: 15px;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.3s;
    }

    .timer-box.active-phase {
        background: rgba(16, 185, 129, 0.1);
        border-color: var(--timer-primary);
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }

    .timer-light {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    }

    .timer-light.yellow {
        background: #f1c40f;
        box-shadow: 0 0 15px #f1c40f;
    }

    .timer-light.green {
        background: #2ecc71;
        box-shadow: 0 0 15px #2ecc71;
    }

    .timer-light.orange {
        background: #e67e22;
        box-shadow: 0 0 15px #e67e22;
    }

    .timer-light.red {
        background: #e74c3c;
        box-shadow: 0 0 15px #e74c3c;
    }

    .timer-display {
        font-family: 'Courier New', Courier, monospace;
        font-size: 28px;
        font-weight: bold;
        color: var(--timer-text);
    }

    .timer-label {
        font-size: 11px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, 0.5);
        margin-top: -5px;
    }

    .timer-btn {
        padding: 15px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        margin-top: 10px;
    }

    .timer-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
    }

    .timer-btn.stop {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .info-box {
        margin-top: auto;
        padding: 15px;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 10px;
        font-size: 12px;
    }

    .info-box h4 {
        margin: 0 0 10px 0;
        color: var(--timer-primary);
    }

    .info-box p {
        margin: 5px 0;
        color: rgba(255, 255, 255, 0.7);
    }
</style>

<div class="timer-container">
    <div class="timer-sidebar">
        <h3 style="margin:0; font-size:18px; color:var(--timer-primary);">⏱️ Timer Penilaian</h3>
        <p style="margin:0 0 10px 0; font-size:13px; opacity:0.7;"><?php echo $rowgolongan['nama']; ?></p>

        <div class="timer-box" id="phase-0">
            <div class="timer-light yellow"></div>
            <div>
                <div class="timer-display" id="timer1">00:00</div>
                <div class="timer-label">Persiapan</div>
            </div>
        </div>

        <div class="timer-box" id="phase-1">
            <div class="timer-light green"></div>
            <div>
                <div class="timer-display" id="timer2">00:00</div>
                <div class="timer-label">Penilaian</div>
            </div>
        </div>

        <div class="timer-box" id="phase-2">
            <div class="timer-light orange"></div>
            <div>
                <div class="timer-display" id="timer3">00:00</div>
                <div class="timer-label">Tanda Selesai</div>
            </div>
        </div>

        <div class="timer-box" id="phase-3">
            <div class="timer-light red"></div>
            <div>
                <div class="timer-display" id="timer4">00:00</div>
                <div class="timer-label">Waktu Habis</div>
            </div>
        </div>

        <button class="timer-btn" id="ctrl-btn" onclick="toggleTimer()">▶ Mulai</button>

        <!-- Zoom Controls -->
        <div style="display: flex; gap: 10px; justify-content: center; margin: 10px 0;">
            <button onclick="zoomMushaf(10)" style="width: 50px; height: 50px; border-radius: 10px; border: none; background: #f39c12; color: white; font-size: 20px; cursor: pointer;">+</button>
            <button onclick="zoomMushaf(-10)" style="width: 50px; height: 50px; border-radius: 10px; border: none; background: #f39c12; color: white; font-size: 20px; cursor: pointer;">−</button>
            <button onclick="prevPage()" style="width: 50px; height: 50px; border-radius: 10px; border: none; background: #3498db; color: white; font-size: 20px; cursor: pointer;">◀</button>
            <button onclick="nextPage()" style="width: 50px; height: 50px; border-radius: 10px; border: none; background: #3498db; color: white; font-size: 20px; cursor: pointer;">▶</button>
        </div>

        <!-- Search Surat & Pilih Halaman/Juz -->
        <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-top: 10px;">
            <label style="display: block; color: rgba(255,255,255,0.7); font-size: 12px; margin-bottom: 6px;">🔍 Cari Surat</label>
            <input type="text" id="searchSurat" placeholder="Ketik nama surat..."
                style="width: 100%; padding: 10px; border: none; border-radius: 8px; background: rgba(255,255,255,0.1); color: white; font-size: 14px; box-sizing: border-box; margin-bottom: 8px;">

            <input type="number" id="inputAyat" placeholder="Ayat ke..." min="1"
                style="width: 100%; padding: 8px; border: none; border-radius: 8px; background: rgba(255,255,255,0.1); color: white; font-size: 14px; box-sizing: border-box; margin-bottom: 8px;">

            <button onclick="goToSuratAyat()" style="width: 100%; padding: 10px; border: none; border-radius: 8px; background: linear-gradient(135deg, #27ae60 0%, #1e8449 100%); color: white; font-weight: bold; cursor: pointer;">📖 Buka</button>

            <div style="display: flex; gap: 8px; margin-top: 10px;">
                <input type="number" id="inputHal" placeholder="hlm" min="1" max="604" style="flex: 1; padding: 8px; border: none; border-radius: 8px; background: rgba(255,255,255,0.1); color: white; font-size: 14px;">
                <button onclick="goToPage()" style="padding: 8px 15px; border: none; border-radius: 8px; background: #3498db; color: white; cursor: pointer;">pilih</button>
            </div>

            <div style="display: flex; gap: 8px; margin-top: 8px;">
                <input type="number" id="inputJuz" placeholder="juz" min="1" max="30" style="flex: 1; padding: 8px; border: none; border-radius: 8px; background: rgba(255,255,255,0.1); color: white; font-size: 14px;">
                <button onclick="goToJuz()" style="padding: 8px 15px; border: none; border-radius: 8px; background: #3498db; color: white; cursor: pointer;">pilih</button>
            </div>
        </div>

        <div class="info-box">
            <h4>📋 Pengaturan Waktu</h4>
            <p>Persiapan: <?php echo $rowgolongan['waktupersiapan']; ?>s</p>
            <p>Penilaian: <?php echo $rowgolongan['waktupenilaian']; ?>s</p>
            <p>Peringatan: <?php echo $rowgolongan['waktumenjelang']; ?>s</p>
            <p>Habis: <?php echo $rowgolongan['waktuhabis']; ?>s</p>
        </div>
    </div>

    <div class="timer-main">
        <iframe src="<?php echo getlink($idgolongan); ?>"></iframe>
    </div>
</div>

<div style="display:none">
    <audio id="eaudio1">
        <source src="../assets/sounds/ding-101492.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio2">
        <source src="../assets/sounds/metal-beaten-sfx-230501.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio3">
        <source src="../assets/sounds/metal-whoosh-hit-4-201906.mp3" type="audio/mpeg">
    </audio>
    <audio id="eaudio4">
        <source src="../assets/sounds/sword-drawing-1-35903.mp3" type="audio/mpeg">
    </audio>
</div>

<script>
    <?php setvar($rowgolongan); ?>

    let timerRunning = false;
    let timerInterval = null;
    let totalSeconds = 0;
    let currentPhase = -1;

    let eaudios = [];

    document.addEventListener("DOMContentLoaded", () => {
        eaudios = [
            document.getElementById("eaudio1"),
            document.getElementById("eaudio2"),
            document.getElementById("eaudio3"),
            document.getElementById("eaudio4")
        ];
        updateTimerDisplays();
    });

    function toggleTimer() {
        const btn = document.getElementById('ctrl-btn');
        if (timerRunning) {
            clearInterval(timerInterval);
            timerRunning = false;
            btn.innerText = "▶ Mulai";
            btn.classList.remove('stop');
        } else {
            totalSeconds = 0;
            currentPhase = -1;
            timerRunning = true;
            btn.innerText = "⏹ Stop";
            btn.classList.add('stop');

            // Start phase
            processTimerStep();
            timerInterval = setInterval(processTimerStep, 1000);
        }
    }

    function processTimerStep() {
        const phase1End = vwaktupersiapan;
        const phase2End = phase1End + vwaktupenilaian;
        const phase3End = phase2End + vwaktumenjelang;
        const phase4End = phase3End + vwaktuhabis;

        let prevPhase = currentPhase;

        if (totalSeconds < phase1End) {
            currentPhase = 0;
        } else if (totalSeconds < phase2End) {
            currentPhase = 1;
        } else if (totalSeconds < phase3End) {
            currentPhase = 2;
        } else if (totalSeconds < phase4End) {
            currentPhase = 3;
        } else {
            clearInterval(timerInterval);
            timerRunning = false;
            const btn = document.getElementById('ctrl-btn');
            btn.innerText = "▶ Mulai";
            btn.classList.remove('stop');
            eaudios[1].play(); // End sound
            return;
        }

        // Play sound on phase change
        if (currentPhase !== prevPhase) {
            highlightPhase(currentPhase);
            if (currentPhase === 0) eaudios[0].play();
            if (currentPhase === 1) eaudios[1].play();
            if (currentPhase === 2) eaudios[2].play();
            if (currentPhase === 3) eaudios[3].play();
        }

        updateTimerDisplays();
        totalSeconds++;
    }

    function updateTimerDisplays() {
        const phase1End = vwaktupersiapan;
        const phase2End = phase1End + vwaktupenilaian;
        const phase3End = phase2End + vwaktumenjelang;
        const phase4End = phase3End + vwaktuhabis;

        let times = [0, 0, 0, 0];

        if (totalSeconds <= phase1End) {
            times[0] = totalSeconds;
        } else if (totalSeconds <= phase2End) {
            times[0] = phase1End;
            times[1] = totalSeconds;
        } else if (totalSeconds <= phase3End) {
            times[0] = phase1End;
            times[1] = phase2End;
            times[2] = totalSeconds;
        } else {
            times[0] = phase1End;
            times[1] = phase2End;
            times[2] = phase3End;
            times[3] = Math.min(totalSeconds, phase4End);
        }

        for (let i = 0; i < 4; i++) {
            document.getElementById('timer' + (i + 1)).innerText = formatTime(times[i]);
        }
    }

    function highlightPhase(phase) {
        for (let i = 0; i < 4; i++) {
            const el = document.getElementById('phase-' + i);
            if (i === phase) el.classList.add('active-phase');
            else el.classList.remove('active-phase');
        }
    }

    function formatTime(vdetik) {
        let menit = Math.floor(vdetik / 60);
        let detik = vdetik % 60;
        return String(menit).padStart(2, '0') + ":" + String(detik).padStart(2, '0');
    }

    // ==========================================
    // MUSHAF CONTROL FUNCTIONS (via iframe)
    // ==========================================

    let mushafFrame = null;
    let currentPage = 1;
    let zoomLevel = 100;

    // Juz to page mapping
    const juzPages = [1, 22, 42, 62, 82, 102, 121, 142, 162, 182, 201, 222, 242, 262, 282, 302, 322, 342, 362, 382, 402, 422, 442, 462, 482, 502, 522, 542, 562, 582];

    // Surat search mapping (approximate pages)
    const suratData = [{
            no: 1,
            nama: "Al-Fatihah",
            page: 1
        }, {
            no: 2,
            nama: "Al-Baqarah",
            page: 2
        },
        {
            no: 3,
            nama: "Ali 'Imran",
            page: 50
        }, {
            no: 4,
            nama: "An-Nisa",
            page: 77
        },
        {
            no: 5,
            nama: "Al-Ma'idah",
            page: 106
        }, {
            no: 6,
            nama: "Al-An'am",
            page: 128
        },
        {
            no: 7,
            nama: "Al-A'raf",
            page: 151
        }, {
            no: 36,
            nama: "Ya-Sin",
            page: 440
        },
        {
            no: 55,
            nama: "Ar-Rahman",
            page: 531
        }, {
            no: 56,
            nama: "Al-Waqi'ah",
            page: 534
        },
        {
            no: 67,
            nama: "Al-Mulk",
            page: 562
        }, {
            no: 78,
            nama: "An-Naba",
            page: 582
        },
        {
            no: 112,
            nama: "Al-Ikhlas",
            page: 604
        }, {
            no: 114,
            nama: "An-Nas",
            page: 604
        }
    ];

    document.addEventListener("DOMContentLoaded", () => {
        mushafFrame = document.querySelector('.timer-main iframe');
    });

    function sendToMushaf(action, value) {
        // Try to communicate with the mushaf iframe
        if (mushafFrame && mushafFrame.contentWindow) {
            try {
                mushafFrame.contentWindow.postMessage({
                    action,
                    value
                }, '*');
            } catch (e) {
                console.log("Cannot access iframe:", e);
            }
        }
    }

    function zoomMushaf(delta) {
        zoomLevel += delta;
        if (zoomLevel < 50) zoomLevel = 50;
        if (zoomLevel > 200) zoomLevel = 200;
        sendToMushaf('zoom', zoomLevel);
        // Fallback: try direct access
        try {
            if (mushafFrame.contentDocument) {
                const img = mushafFrame.contentDocument.getElementById('mushafImg');
                if (img) img.style.width = zoomLevel + '%';
            }
        } catch (e) {}
    }

    function nextPage() {
        currentPage++;
        if (currentPage > 604) currentPage = 1;
        sendToMushaf('goToPage', currentPage);
        updateMushafDirectly();
    }

    function prevPage() {
        currentPage--;
        if (currentPage < 1) currentPage = 604;
        sendToMushaf('goToPage', currentPage);
        updateMushafDirectly();
    }

    function goToPage() {
        let page = parseInt(document.getElementById('inputHal').value);
        if (page >= 1 && page <= 604) {
            currentPage = page;
            sendToMushaf('goToPage', currentPage);
            updateMushafDirectly();
        }
    }

    function goToJuz() {
        let juz = parseInt(document.getElementById('inputJuz').value);
        if (juz >= 1 && juz <= 30) {
            currentPage = juzPages[juz - 1];
            sendToMushaf('goToPage', currentPage);
            updateMushafDirectly();
        }
    }

    function goToSuratAyat() {
        const suratName = document.getElementById('searchSurat').value.toLowerCase();
        const surat = suratData.find(s => s.nama.toLowerCase().includes(suratName));
        if (surat) {
            currentPage = surat.page;
            sendToMushaf('goToPage', currentPage);
            updateMushafDirectly();
        } else {
            alert('Surat tidak ditemukan');
        }
    }

    function updateMushafDirectly() {
        // Fallback: directly update iframe URL for mushaf pages
        try {
            if (mushafFrame.contentDocument) {
                const img = mushafFrame.contentDocument.getElementById('mushafImg');
                if (img) {
                    // Detect extension
                    const currentSrc = img.src;
                    const ext = currentSrc.includes('.png') ? 'png' : 'jpg';
                    const basePath = currentSrc.substring(0, currentSrc.lastIndexOf('/') + 1);
                    img.src = basePath + currentPage + '.' + ext;
                }
            }
        } catch (e) {
            console.log("Direct access failed, using postMessage");
        }
    }
</script>