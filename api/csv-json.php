<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSV to JSON · elegant converter</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background: linear-gradient(145deg, #f6f9fc 0%, #e9f0f5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.5rem;
        }

        .card {
            max-width: 1100px;
            width: 100%;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 48px;
            padding: 2.5rem 2.8rem;
            box-shadow: 0 30px 60px -20px rgba(0, 20, 40, 0.25), 0 8px 20px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.5);
            transition: all 0.2s ease;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 600;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #0b1c2f, #1f4b6e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.25rem;
        }

        .subhead {
            font-size: 1rem;
            color: #3a5a7a;
            font-weight: 400;
            margin-bottom: 2.2rem;
            border-left: 4px solid #3b82f6;
            padding-left: 1rem;
            background: rgba(59, 130, 246, 0.03);
            border-radius: 0 12px 12px 0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 720px) {
            .card {
                padding: 1.8rem 1.2rem;
                border-radius: 32px;
            }
            .grid-2 {
                grid-template-columns: 1fr;
                gap: 1.8rem;
            }
            h1 {
                font-size: 1.8rem;
            }
        }

        .panel {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            border-radius: 28px;
            padding: 1.6rem 1.6rem 1.8rem;
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.8), 0 6px 14px rgba(0, 20, 30, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.7);
            transition: box-shadow 0.15s;
        }

        .panel:hover {
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.9), 0 10px 20px rgba(0, 20, 30, 0.06);
        }

        .panel-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #2b4b6a;
            margin-bottom: 1.2rem;
        }

        .panel-label i {
            font-size: 1.2rem;
            opacity: 0.7;
        }

        textarea, .json-output {
            width: 100%;
            min-height: 240px;
            padding: 1rem 1.2rem;
            font-size: 0.9rem;
            line-height: 1.6;
            border-radius: 20px;
            background: white;
            border: 1px solid #dce7f0;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.01);
            resize: vertical;
            font-family: 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
            color: #0a1e2e;
            transition: 0.2s;
        }

        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .json-output {
            background: #f8fafd;
            overflow-y: auto;
            white-space: pre-wrap;
            word-break: break-word;
            color: #0b263b;
            border-color: #d0dfeb;
            min-height: 240px;
            max-height: 400px;
        }

        .json-output .empty-hint {
            color: #9bb3c9;
            font-style: italic;
            font-family: 'Inter', sans-serif;
            font-weight: 350;
        }

        .action-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            margin: 1.8rem 0 0.8rem;
        }

        .btn-group {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.7rem 1.8rem;
            border-radius: 60px;
            font-weight: 500;
            font-size: 0.95rem;
            border: none;
            background: white;
            color: #1f3d57;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.01);
            border: 1px solid #d0dfeb;
            cursor: pointer;
            transition: 0.2s;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(4px);
        }

        .btn-primary {
            background: #0b263b;
            border: 1px solid #0b263b;
            color: white;
            box-shadow: 0 8px 18px -8px rgba(11, 38, 59, 0.25);
        }

        .btn-primary:hover {
            background: #153e5a;
            border-color: #153e5a;
            transform: scale(1.01);
            box-shadow: 0 12px 24px -10px rgba(11, 38, 59, 0.35);
        }

        .btn-primary:active {
            transform: scale(0.96);
        }

        .btn-outline {
            background: transparent;
            border: 1px solid #cbdae6;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.5);
            border-color: #8aa9c7;
        }

        .btn-success {
            background: #0e4930;
            border: 1px solid #0e4930;
            color: white;
        }

        .btn-success:hover {
            background: #14603f;
            border-color: #14603f;
            box-shadow: 0 8px 18px -8px rgba(14, 73, 48, 0.3);
        }

        .file-input-wrapper {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .file-input-wrapper input[type="file"] {
            display: none;
        }

        .file-label {
            background: rgba(255, 255, 255, 0.6);
            border: 1px dashed #9bb9d4;
            border-radius: 40px;
            padding: 0.6rem 1.6rem;
            font-size: 0.9rem;
            color: #1f4b6e;
            cursor: pointer;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            backdrop-filter: blur(2px);
        }

        .file-label:hover {
            background: white;
            border-color: #3b82f6;
        }

        .badge {
            font-size: 0.7rem;
            background: rgba(59, 130, 246, 0.08);
            padding: 0.15rem 0.8rem;
            border-radius: 40px;
            color: #1f5a8a;
            border: 1px solid rgba(59, 130, 246, 0.1);
        }

        .stats {
            display: flex;
            gap: 1rem;
            font-size: 0.8rem;
            color: #2d577b;
            padding: 0.4rem 0.2rem;
            align-items: center;
            flex-wrap: wrap;
        }

        .stats span {
            background: rgba(255, 255, 255, 0.5);
            padding: 0.15rem 0.8rem;
            border-radius: 30px;
        }

        .footer-note {
            margin-top: 2rem;
            font-size: 0.8rem;
            color: #5982a3;
            text-align: center;
            opacity: 0.8;
            border-top: 1px solid rgba(117, 155, 190, 0.15);
            padding-top: 1.5rem;
            letter-spacing: 0.01em;
        }

        .icon-svg {
            width: 20px;
            height: 20px;
            display: inline-block;
            vertical-align: middle;
            background: currentColor;
            mask-size: cover;
            -webkit-mask-size: cover;
        }

        .flex-center {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        hr {
            border: none;
            border-top: 1px solid rgba(117, 155, 190, 0.15);
            margin: 1rem 0;
        }

        /* custom scroll */
        .json-output::-webkit-scrollbar {
            width: 6px;
        }
        .json-output::-webkit-scrollbar-track {
            background: #e6edf4;
            border-radius: 10px;
        }
        .json-output::-webkit-scrollbar-thumb {
            background: #b7cfdf;
            border-radius: 10px;
        }
    </style>
</head>
<body>

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap;">
        <div>
            <h1>
                <span style="background: #0b263b; color: white; padding: 0.1rem 0.8rem; border-radius: 60px; font-size: 1.6rem; line-height: 1.4;">⇄</span>
                CSV → JSON
            </h1>
            <div class="subhead">
                clean, structured, ready for APIs &amp; pipelines
            </div>
        </div>
        <div class="badge" style="margin-top: 0.3rem;">⚡ neat output</div>
    </div>

    <?php
    // ------------------ core logic ------------------
    $convertedJson = null;
    $rawCsv = '';
    $errorMsg = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['convert'])) {
        $csvInput = trim($_POST['csv_data'] ?? '');
        
        // if file uploaded
        if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
            $fileTmp = $_FILES['csv_file']['tmp_name'];
            $fileContent = file_get_contents($fileTmp);
            if ($fileContent !== false) {
                $csvInput = $fileContent;
            }
        }

        if (!empty($csvInput)) {
            $lines = preg_split('/\r\n|\r|\n/', $csvInput);
            $lines = array_filter($lines, function($line) {
                return trim($line) !== '';
            });

            if (count($lines) < 2) {
                $errorMsg = 'CSV must contain at least a header row and one data row.';
            } else {
                // parse headers
                $header = str_getcsv(trim($lines[0]));
                // clean headers: trim, replace spaces with underscore, lowercase
                $header = array_map(function($col) {
                    $col = trim($col);
                    $col = preg_replace('/[^a-zA-Z0-9_]/', '_', $col);
                    $col = preg_replace('/_+/', '_', $col);
                    return strtolower($col) ?: 'column';
                }, $header);

                $data = [];
                for ($i = 1; $i < count($lines); $i++) {
                    $row = str_getcsv(trim($lines[$i]));
                    // if row has fewer columns, pad with empty; if more, truncate
                    $row = array_pad($row, count($header), '');
                    $row = array_slice($row, 0, count($header));
                    $assoc = [];
                    foreach ($header as $idx => $key) {
                        $assoc[$key] = $row[$idx] ?? '';
                    }
                    $data[] = $assoc;
                }

                // encode with JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
                $convertedJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                if ($convertedJson === false) {
                    $errorMsg = 'Failed to encode JSON. Please check your CSV content.';
                    $convertedJson = null;
                } else {
                    // store raw csv for display
                    $rawCsv = $csvInput;
                }
            }
        } else {
            $errorMsg = 'Please paste CSV data or upload a .csv file.';
        }
    }

    // pre-fill textarea with previous input if any
    $displayCsv = isset($_POST['csv_data']) ? $_POST['csv_data'] : '';
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK && empty($_POST['csv_data'])) {
        // if file uploaded and no manual paste, show file content in textarea
        if (isset($rawCsv) && !empty($rawCsv)) {
            $displayCsv = $rawCsv;
        }
    }
    ?>

    <form method="post" enctype="multipart/form-data" id="csvForm">
        <div class="grid-2">
            <!-- left panel: CSV input -->
            <div class="panel">
                <div class="panel-label">
                    <span>📄</span> CSV source
                    <span class="badge" style="margin-left: auto;">paste or upload</span>
                </div>
                <textarea id="csvInput" name="csv_data" rows="8" placeholder="Paste CSV here...&#10;header1,header2,header3&#10;value1,value2,value3"><?php echo htmlspecialchars($displayCsv); ?></textarea>
                
                <div class="file-input-wrapper" style="margin-top: 1rem;">
                    <label for="fileUpload" class="file-label">
                        <span>📎</span> Choose .csv file
                    </label>
                    <input type="file" id="fileUpload" name="csv_file" accept=".csv,.tsv,.txt">
                    <span style="font-size: 0.75rem; color: #5982a3;" id="fileName">no file selected</span>
                </div>
                <?php if ($errorMsg): ?>
                    <div style="background: #fee9e7; color: #a1322a; padding: 0.6rem 1.2rem; border-radius: 40px; margin-top: 1rem; font-size: 0.85rem; border-left: 4px solid #c73d32;">
                        ⚠️ <?php echo htmlspecialchars($errorMsg); ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- right panel: JSON output -->
            <div class="panel">
                <div class="panel-label">
                    <span>✨</span> Neat JSON
                    <span class="badge" style="margin-left: auto;">pretty · ready</span>
                </div>
                <div class="json-output" id="jsonOutput"><?php 
                    if ($convertedJson !== null) {
                        echo htmlspecialchars($convertedJson);
                    } else {
                        echo '<span class="empty-hint">⏎ your JSON will appear here, beautifully formatted</span>';
                    }
                ?></div>
                <div class="stats" id="statsBar">
                    <?php if ($convertedJson !== null): 
                        $decoded = json_decode($convertedJson, true);
                        $rows = is_array($decoded) ? count($decoded) : 0;
                        $cols = ($rows > 0 && is_array($decoded[0])) ? count($decoded[0]) : 0;
                    ?>
                        <span>📊 <?php echo $rows; ?> rows</span>
                        <span>📋 <?php echo $cols; ?> columns</span>
                        <span>📦 <?php echo number_format(strlen($convertedJson)); ?> bytes</span>
                    <?php else: ?>
                        <span>⏳ waiting for conversion</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="btn-group">
                <button type="submit" name="convert" class="btn btn-primary">
                    <span>⟳</span> Convert to JSON
                </button>
                <button type="reset" class="btn btn-outline" onclick="resetForm()">↺ Clear</button>
                <?php if ($convertedJson !== null): ?>
                <button type="button" class="btn btn-success" id="copyBtn">
                    <span>⎘</span> Copy JSON
                </button>
                <button type="button" class="btn btn-outline" id="downloadBtn">
                    <span>⬇</span> Download .json
                </button>
                <?php endif; ?>
            </div>
            <span style="font-size: 0.8rem; color: #3f6d8f;">
                <?php if ($convertedJson !== null) echo '✓ converted ' . date('H:i:s'); ?>
            </span>
        </div>
    </form>

    <div class="footer-note">
        ⚡ clean JSON · auto‑detect headers · empty values preserved · built for developers
    </div>
</div>

<script>
    (function() {
        // file input display
        const fileInput = document.getElementById('fileUpload');
        const fileNameSpan = document.getElementById('fileName');
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = this.files[0];
                if (file) {
                    fileNameSpan.textContent = file.name + ' (' + (file.size/1024).toFixed(1) + ' KB)';
                    // optional: read file and fill textarea
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        const textarea = document.getElementById('csvInput');
                        if (textarea) {
                            textarea.value = ev.target.result;
                        }
                    };
                    reader.readAsText(file);
                } else {
                    fileNameSpan.textContent = 'no file selected';
                }
            });
        }

        // copy JSON
        const copyBtn = document.getElementById('copyBtn');
        if (copyBtn) {
            copyBtn.addEventListener('click', function() {
                const output = document.getElementById('jsonOutput');
                if (!output) return;
                const text = output.textContent || output.innerText;
                if (text && !text.includes('your JSON will appear')) {
                    navigator.clipboard?.writeText(text).then(() => {
                        const orig = copyBtn.innerHTML;
                        copyBtn.innerHTML = '✓ copied!';
                        setTimeout(() => { copyBtn.innerHTML = orig; }, 2000);
                    }).catch(() => {
                        // fallback
                        const range = document.createRange();
                        range.selectNode(output);
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(range);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                        const orig = copyBtn.innerHTML;
                        copyBtn.innerHTML = '✓ copied!';
                        setTimeout(() => { copyBtn.innerHTML = orig; }, 2000);
                    });
                } else {
                    alert('No JSON to copy. Convert CSV first.');
                }
            });
        }

        // download JSON
        const downloadBtn = document.getElementById('downloadBtn');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                const output = document.getElementById('jsonOutput');
                if (!output) return;
                let content = output.textContent || output.innerText;
                if (!content || content.includes('your JSON will appear')) {
                    alert('No JSON to download. Convert CSV first.');
                    return;
                }
                // try to validate
                try {
                    JSON.parse(content);
                } catch(e) {
                    alert('The JSON seems invalid. Please convert again.');
                    return;
                }
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

        // reset form helper (clear textarea & output & stats)
        window.resetForm = function() {
            document.getElementById('csvInput').value = '';
            const output = document.getElementById('jsonOutput');
            if (output) {
                output.innerHTML = '<span class="empty-hint">⏎ your JSON will appear here, beautifully formatted</span>';
            }
            const stats = document.getElementById('statsBar');
            if (stats) {
                stats.innerHTML = '<span>⏳ waiting for conversion</span>';
            }
            const fileInput = document.getElementById('fileUpload');
            if (fileInput) fileInput.value = '';
            const fileNameSpan = document.getElementById('fileName');
            if (fileNameSpan) fileNameSpan.textContent = 'no file selected';
            // remove error messages
            const errDiv = document.querySelector('.panel .error-msg');
            if (errDiv) errDiv.remove();
        };
    })();
</script>

</body>
</html>
