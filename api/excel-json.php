<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">
    <title>CSV · Excel to JSON · smart converter</title>
    <!-- Font & Icons (Inter + simple icons) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f4f8fe;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem 1rem;
        }

        /* ----- main container ----- */
        .app-wrapper {
            max-width: 1200px;
            width: 100%;
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            border-radius: 56px;
            padding: 1.8rem 2rem 1rem;
            box-shadow: 0 20px 48px -12px rgba(0,20,40,0.20), 0 8px 24px rgba(0,0,0,0.02);
            border: 1px solid rgba(255,255,255,0.6);
            transition: padding 0.2s;
            display: flex;
            flex-direction: column;
        }

        /* ----- cool header ----- */
        .brand-header {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.8rem;
            border-bottom: 1px solid rgba(60, 100, 140, 0.12);
            padding-bottom: 1.2rem;
        }

        .brand-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-icon {
            background: #0b263b;
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            width: 52px;
            height: 52px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px -8px rgba(11,38,59,0.25);
        }

        .brand-text {
            font-size: 1.9rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            background: linear-gradient(145deg, #0b1e2f, #1f4b6e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            line-height: 1.2;
        }

        .brand-text small {
            font-weight: 400;
            font-size: 0.9rem;
            color: #3f6d8f;
            background: transparent;
            -webkit-background-clip: unset;
            background-clip: unset;
            display: inline-block;
            margin-left: 0.3rem;
        }

        .header-badge {
            background: rgba(11, 38, 59, 0.06);
            padding: 0.5rem 1.2rem;
            border-radius: 60px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #1f4b6e;
            border: 1px solid rgba(59, 130, 246, 0.12);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .header-badge i { font-style: normal; }

        /* responsive header */
        @media (max-width: 640px) {
            .brand-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .header-badge { align-self: flex-start; margin-top: 0.2rem; }
            .brand-text { font-size: 1.6rem; }
            .brand-icon { width: 44px; height: 44px; font-size: 1.5rem; }
        }

        /* ----- grid: 2 columns ----- */
        .converter-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.8rem;
            margin: 0.5rem 0 0.8rem;
        }

        @media (max-width: 720px) {
            .app-wrapper { padding: 1.2rem 1rem; border-radius: 32px; }
            .converter-grid { grid-template-columns: 1fr; gap: 1.5rem; }
        }

        /* panels */
        .panel {
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 28px;
            padding: 1.4rem 1.4rem 1.6rem;
            border: 1px solid rgba(255,255,255,0.8);
            box-shadow: inset 0 1px 2px rgba(255,255,255,0.8), 0 6px 14px rgba(0,20,30,0.02);
            transition: 0.15s;
        }

        .panel-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #1f4b6e;
            margin-bottom: 1rem;
        }

        .panel-label span:last-child {
            margin-left: auto;
            background: rgba(59,130,246,0.06);
            padding: 0.1rem 0.7rem;
            border-radius: 30px;
            font-size: 0.7rem;
            font-weight: 400;
            color: #2b5d82;
        }

        textarea, .json-output {
            width: 100%;
            min-height: 200px;
            padding: 0.9rem 1.2rem;
            font-size: 0.88rem;
            line-height: 1.6;
            border-radius: 20px;
            background: white;
            border: 1px solid #dce7f0;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
            color: #0a1e2e;
            transition: 0.2s;
            resize: vertical;
        }

        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.10);
        }

        .json-output {
            background: #fafcff;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-word;
            min-height: 200px;
            max-height: 360px;
            border-color: #d0dfeb;
        }

        .json-output .empty-hint {
            color: #9bb3c9;
            font-style: italic;
            font-family: 'Inter', sans-serif;
            font-weight: 350;
        }

        /* file input */
        .file-upload-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .file-label {
            background: white;
            border: 1px dashed #b0cbd9;
            border-radius: 60px;
            padding: 0.5rem 1.6rem;
            font-size: 0.85rem;
            color: #1f4b6e;
            cursor: pointer;
            transition: 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(2px);
            flex: 0 1 auto;
        }

        .file-label:hover {
            border-color: #3b82f6;
            background: rgba(255,255,255,0.9);
        }

        .file-label input[type="file"] { display: none; }

        .file-name {
            font-size: 0.75rem;
            color: #3f6d8f;
            background: rgba(255,255,255,0.4);
            padding: 0.2rem 0.9rem;
            border-radius: 30px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        /* indicators */
        .status-indicator {
            margin-top: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.8rem;
            color: #2b577b;
            background: rgba(255,255,255,0.3);
            padding: 0.4rem 1rem;
            border-radius: 40px;
            border: 1px solid rgba(117,155,190,0.12);
            flex-wrap: wrap;
        }

        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 10px;
            background: #b7cfdf;
            transition: 0.2s;
        }
        .status-dot.idle { background: #b7cfdf; }
        .status-dot.processing { background: #f5b342; animation: pulse 1s infinite; }
        .status-dot.done { background: #2a9d8f; }
        .status-dot.error { background: #d94f4a; }

        @keyframes pulse { 0% { opacity: 0.5; transform: scale(0.95); } 50% { opacity: 1; transform: scale(1.1); } 100% { opacity: 0.5; transform: scale(0.95); } }

        .stats-bar {
            display: flex;
            gap: 1rem;
            font-size: 0.75rem;
            color: #2d577b;
            padding: 0.3rem 0.1rem;
            flex-wrap: wrap;
            align-items: center;
        }
        .stats-bar span {
            background: rgba(255,255,255,0.5);
            padding: 0.1rem 0.7rem;
            border-radius: 30px;
        }

        .action-bar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            margin: 1.8rem 0 0.5rem;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.7rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.6rem;
            border-radius: 60px;
            font-weight: 500;
            font-size: 0.9rem;
            border: 1px solid #d0dfeb;
            background: rgba(255,255,255,0.6);
            backdrop-filter: blur(4px);
            color: #1f3d57;
            cursor: pointer;
            transition: 0.15s;
            box-shadow: 0 2px 6px rgba(0,0,0,0.01);
        }

        .btn-primary {
            background: #0b263b;
            border-color: #0b263b;
            color: white;
            box-shadow: 0 8px 18px -8px rgba(11,38,59,0.25);
        }
        .btn-primary:hover { background: #153e5a; border-color: #153e5a; transform: scale(1.01); }
        .btn-success { background: #0e4930; border-color: #0e4930; color: white; }
        .btn-success:hover { background: #14603f; }
        .btn-outline { background: transparent; }
        .btn-outline:hover { background: rgba(255,255,255,0.5); border-color: #8aa9c7; }
        .btn:active { transform: scale(0.96); }

        .footer-note {
            margin-top: 2rem;
            font-size: 0.78rem;
            color: #5982a3;
            text-align: center;
            border-top: 1px solid rgba(117,155,190,0.15);
            padding-top: 1.2rem;
            letter-spacing: 0.01em;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.8rem 1.5rem;
        }
        .footer-note span { opacity: 0.7; }

        .error-msg {
            background: #fee9e7;
            color: #a1322a;
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            margin-top: 0.8rem;
            font-size: 0.8rem;
            border-left: 4px solid #c73d32;
        }

        /* responsive tweaks */
        @media (max-width: 480px) {
            .btn { padding: 0.5rem 1.2rem; font-size: 0.8rem; }
            .brand-text { font-size: 1.4rem; }
            .brand-icon { width: 40px; height: 40px; font-size: 1.3rem; }
            .file-label { padding: 0.4rem 1.2rem; }
        }
    </style>
</head>
<body>
<div class="app-wrapper">
    <!-- cool header -->
    <header class="brand-header">
        <div class="brand-left">
            <div class="brand-icon">⇄</div>
            <div class="brand-text">
                CSV · Excel <small>→ JSON</small>
            </div>
        </div>
        <div class="header-badge">
            <i>⚡</i> smart · structured · ready
        </div>
    </header>

    <?php
    // ------------------------------------------------------------
    // CORE LOGIC : CSV / Excel -> JSON with indicators
    // ------------------------------------------------------------
    $convertedJson = null;
    $rawInput = '';
    $errorMsg = null;
    $status = 'idle'; // idle | processing | done | error
    $rowCount = 0;
    $colCount = 0;
    $byteSize = 0;

    // helper to parse CSV from string
    function parseCsvToArray($csvContent) {
        $lines = preg_split('/\r\n|\r|\n/', $csvContent);
        $lines = array_filter($lines, function($line) { return trim($line) !== ''; });
        if (count($lines) < 2) return null;
        $header = str_getcsv(trim($lines[0]));
        $header = array_map(function($col) {
            $col = trim($col);
            $col = preg_replace('/[^a-zA-Z0-9_]/', '_', $col);
            $col = preg_replace('/_+/', '_', $col);
            return strtolower($col) ?: 'column';
        }, $header);
        $data = [];
        for ($i = 1; $i < count($lines); $i++) {
            $row = str_getcsv(trim($lines[$i]));
            $row = array_pad($row, count($header), '');
            $row = array_slice($row, 0, count($header));
            $assoc = [];
            foreach ($header as $idx => $key) {
                $assoc[$key] = $row[$idx] ?? '';
            }
            $data[] = $assoc;
        }
        return $data;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert'])) {
        $status = 'processing';
        $csvInput = trim($_POST['csv_data'] ?? '');
        $fileContent = null;

        // handle file upload (CSV or Excel)
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $tmp = $_FILES['csv_file']['tmp_name'];
            $name = $_FILES['csv_file']['name'];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $fileData = file_get_contents($tmp);
            if ($fileData !== false) {
                // if excel, try to read via simple trick: if extension xlsx/xls, we tell user to save as CSV (since no external lib)
                // but we support .csv and .tsv directly. For .xlsx we show a note, but still try to read as text (fallback)
                if ($ext === 'xlsx' || $ext === 'xls') {
                    // we cannot parse binary excel without lib, but we give a friendly message
                    $errorMsg = 'Excel files (.xlsx/.xls) are not supported directly. Please save as CSV and upload again.';
                    $status = 'error';
                } else {
                    $fileContent = $fileData;
                }
            }
        }

        // determine input source: file content > textarea
        if ($fileContent !== null && empty($errorMsg)) {
            $csvInput = $fileContent;
        }

        if (empty($csvInput) && empty($errorMsg)) {
            $errorMsg = 'Please paste CSV data or upload a .csv file.';
            $status = 'error';
        }

        if (empty($errorMsg) && !empty($csvInput)) {
            $dataArray = parseCsvToArray($csvInput);
            if ($dataArray === null) {
                $errorMsg = 'CSV must contain a header row and at least one data row.';
                $status = 'error';
            } else {
                $json = json_encode($dataArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                if ($json === false) {
                    $errorMsg = 'Failed to encode JSON. Check your CSV content.';
                    $status = 'error';
                } else {
                    $convertedJson = $json;
                    $rawInput = $csvInput;
                    $status = 'done';
                    $rowCount = count($dataArray);
                    $colCount = ($rowCount > 0 && is_array($dataArray[0])) ? count($dataArray[0]) : 0;
                    $byteSize = strlen($json);
                }
            }
        }
        // if errorMsg set from excel fallback
        if (!empty($errorMsg)) $status = 'error';
    }

    // prefill textarea with previous input (if any)
    $displayCsv = isset($_POST['csv_data']) ? $_POST['csv_data'] : '';
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK && empty($_POST['csv_data']) && isset($rawInput) && !empty($rawInput)) {
        $displayCsv = $rawInput;
    }
    ?>

    <form method="post" enctype="multipart/form-data" id="csvForm">
        <div class="converter-grid">
            <!-- left: input -->
            <div class="panel">
                <div class="panel-label">
                    <span>📄</span> Source
                    <span>paste or upload</span>
                </div>
                <textarea id="csvInput" name="csv_data" rows="8" placeholder="Paste CSV here...&#10;header1,header2,header3&#10;value1,value2,value3"><?php echo htmlspecialchars($displayCsv); ?></textarea>
                <div class="file-upload-row">
                    <label for="fileUpload" class="file-label">
                        <span>📎</span> Choose .csv
                        <input type="file" id="fileUpload" name="csv_file" accept=".csv,.tsv,.txt">
                    </label>
                    <span class="file-name" id="fileName">no file</span>
                </div>
                <!-- status indicator -->
                <div class="status-indicator">
                    <span class="status-dot <?php echo $status; ?>"></span>
                    <span id="statusText">
                        <?php
                        if ($status === 'idle') echo '⏳ waiting for input';
                        elseif ($status === 'processing') echo '⟳ processing ...';
                        elseif ($status === 'done') echo '✓ conversion completed';
                        elseif ($status === 'error') echo '⚠️ error';
                        ?>
                    </span>
                    <?php if ($status === 'done'): ?>
                        <span style="margin-left:auto; background: #e6f0f8; padding:0.1rem 0.8rem; border-radius:30px;">✔ ready</span>
                    <?php endif; ?>
                </div>
                <?php if ($errorMsg): ?>
                    <div class="error-msg">⚠️ <?php echo htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>
            </div>

            <!-- right: JSON output -->
            <div class="panel">
                <div class="panel-label">
                    <span>✨</span> Neat JSON
                    <span>pretty · ready</span>
                </div>
                <div class="json-output" id="jsonOutput">
                    <?php if ($convertedJson !== null): ?>
                        <?php echo htmlspecialchars($convertedJson); ?>
                    <?php else: ?>
                        <span class="empty-hint">⏎ your JSON will appear here, beautifully formatted</span>
                    <?php endif; ?>
                </div>
                <div class="stats-bar" id="statsBar">
                    <?php if ($convertedJson !== null): ?>
                        <span>📊 <?php echo $rowCount; ?> rows</span>
                        <span>📋 <?php echo $colCount; ?> columns</span>
                        <span>📦 <?php echo number_format($byteSize); ?> bytes</span>
                    <?php else: ?>
                        <span>⏳ waiting for conversion</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="btn-group">
                <button type="submit" name="convert" class="btn btn-primary" id="convertBtn">⟳ Convert to JSON</button>
                <button type="reset" class="btn btn-outline" onclick="resetForm()">↺ Clear</button>
                <?php if ($convertedJson !== null): ?>
                    <button type="button" class="btn btn-success" id="copyBtn">⎘ Copy</button>
                    <button type="button" class="btn btn-outline" id="downloadBtn">⬇ Download .json</button>
                <?php endif; ?>
            </div>
            <span style="font-size:0.75rem; color:#3f6d8f;">
                <?php if ($convertedJson !== null) echo '✓ ' . date('H:i:s'); ?>
            </span>
        </div>
    </form>

    <!-- cool footer -->
    <footer class="footer-note">
        <span>⚡ auto‑detect headers · empty values preserved</span>
        <span>📁 supports .csv, .tsv</span>
        <span>🔄 built for devs &amp; data pipelines</span>
    </footer>
</div>

<script>
    (function() {
        // file input display + fill textarea
        const fileInput = document.getElementById('fileUpload');
        const fileNameSpan = document.getElementById('fileName');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    fileNameSpan.textContent = file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const textarea = document.getElementById('csvInput');
                        if (textarea) textarea.value = ev.target.result;
                    };
                    reader.readAsText(file);
                } else {
                    fileNameSpan.textContent = 'no file';
                }
            });
        }

        // copy
        const copyBtn = document.getElementById('copyBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const output = document.getElementById('jsonOutput');
                if (!output) return;
                let text = output.textContent || output.innerText;
                if (text && !text.includes('your JSON will appear')) {
                    navigator.clipboard?.writeText(text).then(() => {
                        const orig = copyBtn.innerHTML;
                        copyBtn.innerHTML = '✓ copied!';
                        setTimeout(() => { copyBtn.innerHTML = orig; }, 1800);
                    }).catch(() => {
                        const range = document.createRange();
                        range.selectNode(output);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        const orig = copyBtn.innerHTML;
                        copyBtn.innerHTML = '✓ copied!';
                        setTimeout(() => { copyBtn.innerHTML = orig; }, 1800);
                    });
                } else {
                    alert('No JSON to copy. Convert first.');
                }
            });
        }

        // download
        const downloadBtn = document.getElementById('downloadBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const output = document.getElementById('jsonOutput');
                if (!output) return;
                let content = output.textContent || output.innerText;
                if (!content || content.includes('your JSON will appear')) {
                    alert('No JSON to download. Convert first.');
                    return;
                }
                try { JSON.parse(content); } catch(e) { alert('Invalid JSON. Convert again.'); return; }
                const blob = new Blob([content], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'data-' + new Date().toISOString().slice(0,10) + '.json';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            });
        }

        // reset
        window.resetForm = function() {
            document.getElementById('csvInput').value = '';
            const output = document.getElementById('jsonOutput');
            if (output) output.innerHTML = '<span class="empty-hint">⏎ your JSON will appear here, beautifully formatted</span>';
            const stats = document.getElementById('statsBar');
            if (stats) stats.innerHTML = '<span>⏳ waiting for conversion</span>';
            const fileInput = document.getElementById('fileUpload');
            if (fileInput) fileInput.value = '';
            document.getElementById('fileName').textContent = 'no file';
            // reset status dot
            const dot = document.querySelector('.status-dot');
            if (dot) { dot.className = 'status-dot idle'; }
            const statusText = document.getElementById('statusText');
            if (statusText) statusText.textContent = '⏳ waiting for input';
            // remove error messages
            const errDiv = document.querySelector('.error-msg');
            if (errDiv) errDiv.remove();
        };
    })();
</script>
</body>
</html>