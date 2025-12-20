<?php

/**
 * Emaqra - Qiraat
 * Wrapper to view Qiraat PDFs with synchronized scrolling
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
        background: var(--bg-card, #1e293b);
        border-radius: 12px;
        padding: 20px;
        min-height: 500px;
    }

    .emaqra-container h2 {
        color: var(--primary-light, #34d399);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--primary, #10b981);
    }

    .qiraat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
    }

    .qiraat-card {
        text-decoration: none;
        border: 2px solid var(--primary, #10b981);
        padding: 20px 25px;
        font-size: 14px;
        color: var(--text-primary, #fff);
        background: var(--bg-surface, rgba(15, 23, 42, 0.6));
        border-radius: 12px;
        transition: all 0.3s;
        text-align: center;
    }

    .qiraat-card:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
    }

    .qiraat-viewer {
        display: flex;
        flex-direction: column;
        height: 800px;
        border-radius: 12px;
        overflow: hidden;
        border: 2px solid var(--primary, #10b981);
    }

    .qiraat-header {
        background: linear-gradient(135deg, #065f46 0%, #047857 100%);
        color: white;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .qiraat-header h3 {
        margin: 0;
    }

    .qiraat-nav {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .qiraat-nav a,
    .qiraat-nav button {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        text-decoration: none;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-size: 14px;
    }

    .qiraat-nav a:hover,
    .qiraat-nav button:hover {
        background: rgba(255, 255, 255, 0.4);
    }

    .page-controls {
        display: flex;
        align-items: center;
        gap: 10px;
        color: white;
    }

    .page-controls input {
        width: 60px;
        padding: 6px 10px;
        border: none;
        border-radius: 5px;
        text-align: center;
    }

    .qiraat-pdfs {
        flex: 1;
        display: flex;
        flex-direction: row;
        gap: 0;
        overflow: hidden;
    }

    .pdf-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        border-right: 1px solid rgba(255, 255, 255, 0.1);
    }

    .pdf-section:last-child {
        border-right: none;
    }

    .pdf-label {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
        color: white;
        padding: 10px 15px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
    }

    .pdf-canvas-wrapper {
        flex: 1;
        overflow: auto;
        background: #fafafa;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 10px;
    }

    /* Hide PDF footer using overlay - CSS solution for islamweb.net */
    .pdf-canvas-wrapper::after {
        content: '';
        position: sticky;
        bottom: 0;
        left: 0;
        right: 0;
        height: 40px;
        background: linear-gradient(to bottom, transparent, #fafafa);
        pointer-events: none;
    }

    .pdf-page {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin-bottom: 10px;
        background: white;
    }

    .sync-indicator {
        background: #f39c12;
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 11px;
        margin-left: 10px;
    }

    .back-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 8px;
        display: inline-block;
        margin-bottom: 20px;
    }

    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }
</style>

<div class="emaqra-container">
    <?php if ($id < 0 || $id >= count($qiraatData)): ?>
        <!-- Show Qiraat Selection Grid -->
        <h2>📜 QIRAAT - MUSHAF 10 IMAM</h2>

        <div class="qiraat-grid">
            <?php foreach ($qiraatData as $index => $q): ?>
                <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $index; ?>" class="qiraat-card">
                    <?php echo $q[0]; ?>
                    <div style="margin-top:8px;font-size:12px;opacity:0.7">
                        <?php echo strtoupper($q[1]); ?> | <?php echo strtoupper($q[2]); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <!-- Show PDF Viewer with Sync Scroll -->
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
                <div>
                    <h3><?php echo $qiraat; ?></h3>
                    <span class="sync-indicator">🔗 Sync Scroll Aktif</span>
                </div>
                <div class="page-controls">
                    <button onclick="goToPage()">Go</button>
                    <input type="number" id="pageInput" value="1" min="1">
                    <span id="pageInfo">/ 604</span>
                </div>
                <div class="qiraat-nav">
                    <button onclick="prevPage()">◀ Prev</button>
                    <button onclick="nextPage()">Next ▶</button>
                    <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $prevId; ?>">← Qiraat Sebelumnya</a>
                    <a href="?page=utama&page2=emaqra_qiraat&id=<?php echo $nextId; ?>">Qiraat Selanjutnya →</a>
                </div>
            </div>

            <div class="qiraat-pdfs">
                <div class="pdf-section">
                    <div class="pdf-label"><?php echo strtoupper($nama1); ?></div>
                    <div class="pdf-canvas-wrapper" id="pdfWrapper1">
                        <canvas id="pdfCanvas1" class="pdf-page"></canvas>
                    </div>
                </div>
                <div class="pdf-section">
                    <div class="pdf-label"><?php echo strtoupper($nama2); ?></div>
                    <div class="pdf-canvas-wrapper" id="pdfWrapper2">
                        <canvas id="pdfCanvas2" class="pdf-page"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- PDF.js Library -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
        <script>
            // PDF.js configuration
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

            // PDF documents
            let pdf1 = null;
            let pdf2 = null;
            let currentPage = 1;
            let totalPages = 604;
            let scale = 1.5;
            let isRendering = false;

            // PDF URLs
            const pdfUrl1 = '../assets/qiraat/<?php echo $link1; ?>';
            const pdfUrl2 = '../assets/qiraat/<?php echo $link2; ?>';

            // Initialize PDFs
            async function initPDFs() {
                try {
                    [pdf1, pdf2] = await Promise.all([
                        pdfjsLib.getDocument(pdfUrl1).promise,
                        pdfjsLib.getDocument(pdfUrl2).promise
                    ]);

                    totalPages = Math.min(pdf1.numPages, pdf2.numPages);
                    document.getElementById('pageInfo').textContent = `/ ${totalPages}`;

                    await renderPage(currentPage);
                    setupScrollSync();
                } catch (error) {
                    console.error('Error loading PDFs:', error);
                    // Fallback to embed
                    fallbackToEmbed();
                }
            }

            // Render specific page on both canvases
            async function renderPage(pageNum) {
                if (isRendering) return;
                isRendering = true;

                try {
                    const [page1, page2] = await Promise.all([
                        pdf1.getPage(pageNum),
                        pdf2.getPage(pageNum)
                    ]);

                    await Promise.all([
                        renderPageToCanvas(page1, 'pdfCanvas1'),
                        renderPageToCanvas(page2, 'pdfCanvas2')
                    ]);

                    document.getElementById('pageInput').value = pageNum;
                } catch (error) {
                    console.error('Error rendering page:', error);
                }

                isRendering = false;
            }

            async function renderPageToCanvas(page, canvasId) {
                const canvas = document.getElementById(canvasId);
                const ctx = canvas.getContext('2d');

                const viewport = page.getViewport({
                    scale: scale
                });

                // Crop bottom 50px to hide www.islamweb.net footer
                const cropHeight = 50 * scale;
                canvas.height = viewport.height - cropHeight;
                canvas.width = viewport.width;

                await page.render({
                    canvasContext: ctx,
                    viewport: viewport
                }).promise;
            }

            // Setup synchronized scrolling
            function setupScrollSync() {
                const wrapper1 = document.getElementById('pdfWrapper1');
                const wrapper2 = document.getElementById('pdfWrapper2');

                let syncing = false;

                wrapper1.addEventListener('scroll', function() {
                    if (syncing) return;
                    syncing = true;

                    // Sync scroll position
                    wrapper2.scrollTop = wrapper1.scrollTop;
                    wrapper2.scrollLeft = wrapper1.scrollLeft;

                    setTimeout(() => syncing = false, 10);
                });

                wrapper2.addEventListener('scroll', function() {
                    if (syncing) return;
                    syncing = true;

                    wrapper1.scrollTop = wrapper2.scrollTop;
                    wrapper1.scrollLeft = wrapper2.scrollLeft;

                    setTimeout(() => syncing = false, 10);
                });

                // Mouse wheel sync for page navigation
                wrapper1.addEventListener('wheel', handleWheelPageChange);
                wrapper2.addEventListener('wheel', handleWheelPageChange);
            }

            // Handle mouse wheel for page changes
            let wheelThrottle = false;

            function handleWheelPageChange(e) {
                const wrapper = e.currentTarget;
                const atBottom = wrapper.scrollHeight - wrapper.scrollTop <= wrapper.clientHeight + 50;
                const atTop = wrapper.scrollTop <= 50;

                if (wheelThrottle) return;

                // If at bottom and scrolling down, go to next page
                if (atBottom && e.deltaY > 0 && currentPage < totalPages) {
                    wheelThrottle = true;
                    nextPage();
                    setTimeout(() => wheelThrottle = false, 500);
                    e.preventDefault();
                }
                // If at top and scrolling up, go to previous page
                else if (atTop && e.deltaY < 0 && currentPage > 1) {
                    wheelThrottle = true;
                    prevPage();
                    setTimeout(() => wheelThrottle = false, 500);
                    e.preventDefault();
                }
            }

            // Navigation functions
            function nextPage() {
                if (currentPage < totalPages) {
                    currentPage++;
                    renderPage(currentPage);
                    // Reset scroll position
                    document.getElementById('pdfWrapper1').scrollTop = 0;
                    document.getElementById('pdfWrapper2').scrollTop = 0;
                }
            }

            function prevPage() {
                if (currentPage > 1) {
                    currentPage--;
                    renderPage(currentPage);
                    // Reset scroll position
                    document.getElementById('pdfWrapper1').scrollTop = 0;
                    document.getElementById('pdfWrapper2').scrollTop = 0;
                }
            }

            function goToPage() {
                let page = parseInt(document.getElementById('pageInput').value);
                if (page >= 1 && page <= totalPages) {
                    currentPage = page;
                    renderPage(currentPage);
                    document.getElementById('pdfWrapper1').scrollTop = 0;
                    document.getElementById('pdfWrapper2').scrollTop = 0;
                }
            }

            // Fallback to embed if PDF.js fails
            function fallbackToEmbed() {
                const wrapper1 = document.getElementById('pdfWrapper1');
                const wrapper2 = document.getElementById('pdfWrapper2');

                wrapper1.innerHTML = `<embed type="application/pdf" src="${pdfUrl1}" style="width:100%;height:100%">`;
                wrapper2.innerHTML = `<embed type="application/pdf" src="${pdfUrl2}" style="width:100%;height:100%">`;
            }

            // Initialize on load
            document.addEventListener('DOMContentLoaded', initPDFs);
        </script>
    <?php endif; ?>
</div>