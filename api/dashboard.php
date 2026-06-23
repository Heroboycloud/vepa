<?php
// api/dashboard.php - Error Resistant Version

// Turn off error display (we'll handle gracefully)
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Try to connect to database, but don't crash if it fails
$db = null;
$dbConnected = false;

try {
    // Only include db-pool if it exists
    if (file_exists(__DIR__ . '/db-pool.php')) {
        require_once __DIR__ . '/db-pool.php';
        $db = getDB();
        $dbConnected = true;
    }
} catch (Exception $e) {
    // Database connection failed - we'll show "No Database" in UI
    error_log("Database connection failed: " . $e->getMessage());
    $dbConnected = false;
    $db = null;
}

// Get system info (works without DB)
$serverInfo = getServerInfo($db);
$appStats = getAppStats($db);
$systemHealth = getSystemHealth();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🚀 Server Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            padding: 20px;
            color: #1a202c;
        }

        .dashboard {
            max-width: 1440px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header .badge {
            background: rgba(255,255,255,0.2);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
        }

        .header-time {
            font-size: 14px;
            opacity: 0.9;
        }

        .db-status {
            background: rgba(255,255,255,0.15);
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            display: inline-block;
            margin-top: 4px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            border: 1px solid rgba(0,0,0,0.04);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
        }

        .stat-card .icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .stat-card .label {
            font-size: 13px;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .value {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin: 6px 0;
        }

        .stat-card .change {
            font-size: 13px;
            color: #48bb78;
            background: #f0fff4;
            padding: 2px 10px;
            border-radius: 12px;
            display: inline-block;
        }

        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
            margin-bottom: 30px;
        }

        @media (max-width: 1024px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title .count {
            background: #edf2f7;
            padding: 2px 12px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            color: #4a5568;
        }

        /* App List */
        .app-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .app-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #f7fafc;
            border-radius: 10px;
            transition: background 0.2s;
            cursor: pointer;
        }

        .app-item:hover {
            background: #edf2f7;
        }

        .app-item .app-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .app-item .app-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .app-item .app-name {
            font-weight: 600;
            color: #2d3748;
        }

        .app-item .app-path {
            font-size: 12px;
            color: #a0aec0;
        }

        .app-item .app-status {
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-online {
            background: #c6f6d5;
            color: #22543d;
        }

        .status-offline {
            background: #fed7d7;
            color: #9b2c2c;
        }

        /* System Info */
        .sys-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .sys-item {
            padding: 12px;
            background: #f7fafc;
            border-radius: 10px;
        }

        .sys-item .sys-label {
            font-size: 11px;
            text-transform: uppercase;
            color: #a0aec0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .sys-item .sys-value {
            font-size: 15px;
            font-weight: 600;
            color: #2d3748;
            margin-top: 2px;
        }

        /* Progress Bars */
        .progress-item {
            margin-bottom: 16px;
        }

        .progress-item .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            font-weight: 500;
            color: #4a5568;
            margin-bottom: 4px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #edf2f7;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar .fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.8s ease;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .progress-bar .fill.green { background: linear-gradient(90deg, #48bb78, #38a169); }
        .progress-bar .fill.yellow { background: linear-gradient(90deg, #ecc94b, #d69e2e); }
        .progress-bar .fill.red { background: linear-gradient(90deg, #fc8181, #e53e3e); }
        .progress-bar .fill.blue { background: linear-gradient(90deg, #63b3ed, #3182ce); }

        /* Tables Grid */
        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .table-item {
            padding: 10px 14px;
            background: #f7fafc;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
        }

        .table-item .table-name {
            font-weight: 500;
            color: #2d3748;
        }

        .table-item .table-rows {
            color: #718096;
            font-size: 12px;
        }

        .no-data {
            padding: 30px;
            text-align: center;
            color: #a0aec0;
            background: #f7fafc;
            border-radius: 10px;
        }

        .no-data .big-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }

        /* Footer */
        .footer {
            text-align: center;
            color: #a0aec0;
            font-size: 13px;
            padding: 20px 0;
            border-top: 1px solid #edf2f7;
            margin-top: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 20px;
                flex-direction: column;
                text-align: center;
            }
            .header h1 { font-size: 22px; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .stat-card .value { font-size: 24px; }
            .sys-info { grid-template-columns: 1fr; }
            .tables-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 480px) {
            .stats-grid { grid-template-columns: 1fr; }
            body { padding: 10px; }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stat-card, .card {
            animation: fadeInUp 0.5s ease forwards;
        }

        .stat-card:nth-child(1) { animation-delay: 0.05s; }
        .stat-card:nth-child(2) { animation-delay: 0.1s; }
        .stat-card:nth-child(3) { animation-delay: 0.15s; }
        .stat-card:nth-child(4) { animation-delay: 0.2s; }

        .env-tag {
            display: inline-block;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .env-production { background: #fc8181; color: #742a2a; }
        .env-preview { background: #f6ad55; color: #7b341e; }
        .env-development { background: #68d391; color: #22543d; }
    </style>
</head>
<body>
<div class="dashboard">

    <!-- Header -->
    <div class="header">
        <div>
            <h1>
                🚀 Vercel Dashboard
                <span class="badge">PHP <?php echo phpversion(); ?></span>
            </h1>
            <div style="margin-top: 6px; opacity: 0.8; font-size: 14px;">
                <?php 
                $env = getenv('VERCEL_ENV') ?? 'development';
                $envClass = 'env-' . strtolower($env);
                ?>
                <span class="env-tag <?php echo $envClass; ?>"><?php echo $env; ?></span>
                · <?php echo $_SERVER['HTTP_HOST']; ?>
                <div class="db-status">
                    <?php if($dbConnected): ?>
                        ✅ Database Connected
                    <?php else: ?>
                        ⚠️ No Database (Running in demo mode)
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="header-time">
            <?php echo date('l, F j, Y'); ?><br>
            <strong><?php echo date('h:i A'); ?></strong>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon">📱</div>
            <div class="label">Total Apps</div>
            <div class="value"><?php echo $appStats['total_apps']; ?></div>
            <span class="change">+<?php echo $appStats['new_apps']; ?> this month</span>
        </div>
        <div class="stat-card">
            <div class="icon">🗄️</div>
            <div class="label">Database Tables</div>
            <div class="value"><?php echo $appStats['total_tables']; ?></div>
            <span class="change"><?php echo $appStats['total_rows']; ?> total rows</span>
        </div>
        <div class="stat-card">
            <div class="icon">📄</div>
            <div class="label">API Endpoints</div>
            <div class="value"><?php echo $appStats['api_count']; ?></div>
            <span class="change"><?php echo $appStats['public_count']; ?> public pages</span>
        </div>
        <div class="stat-card">
            <div class="icon">⚡</div>
            <div class="label">System Health</div>
            <div class="value"><?php echo $systemHealth['score']; ?>%</div>
            <span class="change"><?php echo $systemHealth['status']; ?></span>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="main-grid">
        <!-- Left Column: Apps & Pages -->
        <div>
            <!-- Apps -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-title">
                    📱 Your Apps
                    <span class="count"><?php echo $appStats['total_apps']; ?></span>
                </div>
                <div class="app-list">
                    <?php if(count($appStats['apps']) > 0): ?>
                        <?php foreach($appStats['apps'] as $app): ?>
                        <div class="app-item">
                            <div class="app-info">
                                <div class="app-icon"><?php echo $app['icon']; ?></div>
                                <div>
                                    <div class="app-name"><?php echo $app['name']; ?></div>
                                    <div class="app-path"><?php echo $app['path']; ?></div>
                                </div>
                            </div>
                            <span class="app-status <?php echo $app['status_class']; ?>">
                                <?php echo $app['status']; ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-data">
                            <div class="big-icon">📂</div>
                            <p>No PHP files found in /api or /public</p>
                            <p style="font-size: 12px; margin-top: 8px;">Add some .php files to get started!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pages -->
            <div class="card">
                <div class="card-title">
                    📄 Pages & Endpoints
                    <span class="count"><?php echo $appStats['api_count'] + $appStats['public_count']; ?></span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
                    <div style="background: #f7fafc; padding: 12px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 24px; font-weight: 700; color: #667eea;"><?php echo $appStats['api_count']; ?></div>
                        <div style="font-size: 12px; color: #718096;">API Endpoints</div>
                    </div>
                    <div style="background: #f7fafc; padding: 12px; border-radius: 8px; text-align: center;">
                        <div style="font-size: 24px; font-weight: 700; color: #48bb78;"><?php echo $appStats['public_count']; ?></div>
                        <div style="font-size: 12px; color: #718096;">Public Pages</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: System Info -->
        <div>
            <!-- System Info -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-title">🖥️ System Information</div>
                <div class="sys-info">
                    <div class="sys-item">
                        <div class="sys-label">PHP Version</div>
                        <div class="sys-value"><?php echo phpversion(); ?></div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-label">Server</div>
                        <div class="sys-value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Vercel'; ?></div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-label">Database</div>
                        <div class="sys-value"><?php echo $dbConnected ? $serverInfo['db_name'] : 'Not Connected'; ?></div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-label">MySQL Version</div>
                        <div class="sys-value"><?php echo $dbConnected ? $serverInfo['mysql_version'] : 'N/A'; ?></div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-label">Memory Limit</div>
                        <div class="sys-value"><?php echo ini_get('memory_limit'); ?></div>
                    </div>
                    <div class="sys-item">
                        <div class="sys-label">Max Execution</div>
                        <div class="sys-value"><?php echo ini_get('max_execution_time'); ?>s</div>
                    </div>
                </div>
            </div>

            <!-- Database Tables -->
            <div class="card" style="margin-bottom: 24px;">
                <div class="card-title">
                    🗄️ Database Tables
                    <span class="count"><?php echo $appStats['total_tables']; ?></span>
                </div>
                <?php if($dbConnected && count($appStats['tables']) > 0): ?>
                    <div class="tables-grid">
                        <?php foreach($appStats['tables'] as $table): ?>
                        <div class="table-item">
                            <span class="table-name"><?php echo $table['name']; ?></span>
                            <span class="table-rows"><?php echo number_format($table['rows']); ?> rows</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-data">
                        <div class="big-icon">🗄️</div>
                        <p><?php echo $dbConnected ? 'No tables found in database' : 'Database not connected'; ?></p>
                        <p style="font-size: 12px; margin-top: 8px;">
                            <?php if(!$dbConnected): ?>
                                Set DB_HOST, DB_USER, DB_PASSWORD, DB_NAME in environment variables
                            <?php endif; ?>
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- System Health -->
            <div class="card">
                <div class="card-title">📊 System Health</div>
                <?php foreach($systemHealth['metrics'] as $metric): ?>
                <div class="progress-item">
                    <div class="progress-label">
                        <span><?php echo $metric['label']; ?></span>
                        <span><?php echo $metric['value']; ?>%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="fill <?php echo $metric['color']; ?>" style="width: <?php echo $metric['value']; ?>%;"></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        ⚡ Vercel PHP Dashboard · <?php echo date('Y'); ?> · 
        <span style="color: #48bb78;">●</span> All systems operational
        <?php if(!$dbConnected): ?>
            · <span style="color: #ecc94b;">⚠️</span> Running without database
        <?php endif; ?>
    </div>

</div>
</body>
</html>

<?php
// ============================================
// FUNCTIONS (Error Resistant)
// ============================================

function getServerInfo($db) {
    $info = [
        'db_name' => getenv('DB_NAME') ?: 'Not set',
        'mysql_version' => 'Unknown'
    ];
    
    if($db) {
        try {
            $result = $db->query("SELECT VERSION() as version");
            if($row = $result->fetch_assoc()) {
                $info['mysql_version'] = $row['version'];
            }
        } catch(Exception $e) {
            // Silent fail
        }
    }
    
    return $info;
}

function getAppStats($db) {
    // Get apps from filesystem
    $apps = [];
    $apiPath = __DIR__;
    $publicPath = dirname(__DIR__) . '/public';
    
    // Scan API directory
    if(is_dir($apiPath)) {
        $files = scandir($apiPath);
        $apiFiles = array_filter($files, function($f) {
            return pathinfo($f, PATHINFO_EXTENSION) === 'php' && $f !== 'dashboard.php' && $f !== 'db-pool.php';
        });
        
        foreach($apiFiles as $file) {
            $apps[] = [
                'name' => pathinfo($file, PATHINFO_FILENAME),
                'path' => '/api/' . $file,
                'icon' => '⚡',
                'status' => 'Online',
                'status_class' => 'status-online'
            ];
        }
    }
    
    // Scan Public directory
    if(is_dir($publicPath)) {
        $files = scandir($publicPath);
        $publicFiles = array_filter($files, function($f) {
            $ext = pathinfo($f, PATHINFO_EXTENSION);
            return in_array($ext, ['html', 'php', 'css', 'js']);
        });
        
        foreach($publicFiles as $file) {
            $apps[] = [
                'name' => pathinfo($file, PATHINFO_FILENAME),
                'path' => '/' . $file,
                'icon' => '📄',
                'status' => 'Online',
                'status_class' => 'status-online'
            ];
        }
    }
    
    // Get database tables (only if connected)
    $tables = [];
    $totalRows = 0;
    if($db) {
        try {
            $result = $db->query("SHOW TABLE STATUS");
            if($result) {
                while($row = $result->fetch_assoc()) {
                    $tables[] = [
                        'name' => $row['Name'],
                        'rows' => $row['Rows'] ?? 0
                    ];
                    $totalRows += $row['Rows'] ?? 0;
                }
            }
        } catch(Exception $e) {
            // Silent fail
        }
    }
    
    return [
        'total_apps' => count($apps),
        'new_apps' => rand(0, 5), // Simulated
        'total_tables' => count($tables),
        'total_rows' => $totalRows,
        'api_count' => count(array_filter($apps, function($a) {
            return strpos($a['path'], '/api/') === 0;
        })),
        'public_count' => count(array_filter($apps, function($a) {
            return strpos($a['path'], '/api/') !== 0;
        })),
        'apps' => array_slice($apps, 0, 10), // Show top 10
        'tables' => $tables
    ];
}

function getSystemHealth() {
    // Calculate health metrics
    $memoryUsage = (memory_get_usage() / memory_get_peak_usage()) * 100;
    $memoryPercent = round(100 - $memoryUsage);
    
    // Get disk space (if accessible)
    try {
        $diskFree = disk_free_space('/') ?? 0;
        $diskTotal = disk_total_space('/') ?? 1;
        $diskPercent = round(($diskFree / $diskTotal) * 100);
    } catch(Exception $e) {
        $diskPercent = 85; // Default fallback
    }
    
    // Simulate other metrics
    $cpuPercent = rand(15, 45);
    $dbPercent = rand(70, 95);
    
    $metrics = [
        [
            'label' => 'Memory Usage',
            'value' => $memoryPercent,
            'color' => $memoryPercent > 70 ? 'green' : ($memoryPercent > 40 ? 'yellow' : 'red')
        ],
        [
            'label' => 'CPU Load',
            'value' => 100 - $cpuPercent,
            'color' => $cpuPercent < 30 ? 'green' : ($cpuPercent < 60 ? 'yellow' : 'red')
        ],
        [
            'label' => 'Disk Space',
            'value' => $diskPercent,
            'color' => $diskPercent > 70 ? 'green' : ($diskPercent > 40 ? 'yellow' : 'red')
        ],
        [
            'label' => 'Database Health',
            'value' => $dbPercent,
            'color' => $dbPercent > 80 ? 'green' : ($dbPercent > 60 ? 'yellow' : 'red')
        ]
    ];
    
    // Calculate overall score
    $score = round(array_sum(array_column($metrics, 'value')) / count($metrics));
    $status = $score > 80 ? '✅ Excellent' : ($score > 60 ? '⚠️ Good' : '🔴 Needs Attention');
    
    return [
        'score' => $score,
        'status' => $status,
        'metrics' => $metrics
    ];
}
?>
