<?php
// api/db-viewer.php
require_once 'db-pool.php';

$db = getDB();
$action = $_GET['action'] ?? 'tables';
$table = $_GET['table'] ?? '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Viewer</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        @media (max-width: 768px) {
    table { font-size: 12px; }
    table td, table th { padding: 5px; }
    .container { padding: 10px; }
    input[type="text"], textarea { width: 100%; }
}
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #4CAF50; color: white; }
        tr:hover { background: #f5f5f5; }
        .nav { margin: 20px 0; }
        .nav a { 
            background: #4CAF50; 
            color: white; 
            padding: 10px 15px; 
            text-decoration: none; 
            border-radius: 4px;
            margin: 5px;
            display: inline-block;
        }
        .nav a:hover { background: #45a049; }
        .badge { 
            background: #2196F3; 
            color: white; 
            padding: 3px 8px; 
            border-radius: 12px;
            font-size: 12px;
        }
        .query-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            margin: 10px 0;
            border-left: 4px solid #4CAF50;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🗄️ Database Viewer</h1>
    <p><strong>Database:</strong> <?php echo getenv('DB_NAME'); ?></p>
    
    <div class="nav">
        <a href="?action=tables">📋 All Tables</a>
        <a href="?action=query">✏️ Run Query</a>
        <a href="?action=info">ℹ️ Server Info</a>
    </div>

    <?php
    switch($action) {
        case 'tables':
            showTables($db);
            break;
        case 'query':
            showQueryForm($db);
            break;
        case 'info':
            showServerInfo($db);
            break;
        case 'view_table':
            viewTable($db, $table);
            break;
        default:
            showTables($db);
    }
    ?>

</div>
</body>
</html>

<?php
function showTables($db) {
    $result = $db->query("SHOW TABLE STATUS");
    ?>
    <h2>📋 Tables (<?php echo $result->num_rows; ?>)</h2>
    <table>
        <thead>
            <tr>
                <th>Table</th>
                <th>Engine</th>
                <th>Rows</th>
                <th>Data Size</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo $row['Name']; ?></strong></td>
                <td><?php echo $row['Engine']; ?></td>
                <td><?php echo number_format($row['Rows']); ?></td>
                <td><?php echo formatSize($row['Data_length']); ?></td>
                <td><?php echo $row['Create_time']; ?></td>
                <td>
                    <a href="?action=view_table&table=<?php echo $row['Name']; ?>">👁️ View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
}

function viewTable($db, $table) {
    // Get column info
    $cols = $db->query("SHOW COLUMNS FROM `$table`");
    ?>
    <h2>📊 Table: <?php echo $table; ?></h2>
    <p><a href="?action=tables">← Back to tables</a></p>
    
    <?php
    // Get data (limit to 100 rows)
    $result = $db->query("SELECT * FROM `$table` LIMIT 100");
    ?>
    
    <div class="query-box">
        <strong>Query:</strong> SELECT * FROM `<?php echo $table; ?>` LIMIT 100
    </div>
    
    <?php if($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <?php while($col = $cols->fetch_assoc()): ?>
                <th><?php echo $col['Field']; ?></th>
                <?php endwhile; ?>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <?php foreach($row as $value): ?>
                <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                <?php endforeach; ?>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No data found in this table.</p>
    <?php endif; ?>
    
    <?php
}

function showQueryForm($db) {
    if(isset($_POST['query'])) {
        $query = $_POST['query'];
        ?>
        <div class="query-box">
            <strong>Executing:</strong><br>
            <code><?php echo htmlspecialchars($query); ?></code>
        </div>
        <?php
        
        if(stripos($query, 'SELECT') === 0) {
            // SELECT query - show results
            $result = $db->query($query);
            if($result && $result->num_rows > 0) {
                ?>
                <h3>✅ Results (<?php echo $result->num_rows; ?> rows)</h3>
                <table>
                    <thead>
                        <tr>
                            <?php while($field = $result->fetch_field()): ?>
                            <th><?php echo $field->name; ?></th>
                            <?php endwhile; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <?php foreach($row as $value): ?>
                            <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php
            } else {
                echo "<p>No results or error: " . $db->error . "</p>";
            }
        } else {
            // Non-SELECT query
            if($db->query($query)) {
                echo "<p>✅ Query executed successfully. Affected rows: " . $db->affected_rows . "</p>";
            } else {
                echo "<p>❌ Error: " . $db->error . "</p>";
            }
        }
    }
    ?>
    
    <h2>✏️ Run Custom Query</h2>
    <form method="POST">
        <textarea name="query" rows="5" style="width:100%; padding:10px; font-family: monospace;">SELECT * FROM users LIMIT 10</textarea>
        <br><br>
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
            ▶️ Execute Query
        </button>
        <button type="button" onclick="this.form.query.value='SHOW TABLES'" style="padding: 10px 15px;">Show Tables</button>
        <button type="button" onclick="this.form.query.value='SELECT * FROM users LIMIT 10'" style="padding: 10px 15px;">Users Sample</button>
    </form>
    <?php
}

function showServerInfo($db) {
    ?>
    <h2>ℹ️ Server Information</h2>
    <table>
        <tr><th>MySQL Version</th><td><?php echo $db->server_info; ?></td></tr>
        <tr><th>Host</th><td><?php echo getenv('DB_HOST'); ?></td></tr>
        <tr><th>Database</th><td><?php echo getenv('DB_NAME'); ?></td></tr>
        <tr><th>User</th><td><?php echo getenv('DB_USER'); ?></td></tr>
        <tr><th>Connection Status</th><td><?php echo $db->ping() ? '✅ Connected' : '❌ Disconnected'; ?></td></tr>
        <tr><th>Character Set</th><td><?php echo $db->character_set_name(); ?></td></tr>
    </table>
    
    <h3>📈 Server Variables</h3>
    <table>
        <thead><tr><th>Variable</th><th>Value</th></tr></thead>
        <tbody>
            <?php 
            $vars = ['max_connections', 'query_cache_size', 'innodb_buffer_pool_size', 'version_comment'];
            foreach($vars as $var):
                $result = $db->query("SHOW VARIABLES LIKE '$var'");
                if($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?php echo $row['Variable_name']; ?></td>
                <td><?php echo $row['Value']; ?></td>
            </tr>
            <?php endif; endforeach; ?>
        </tbody>
    </table>
    <?php
}

function formatSize($bytes) {
    if($bytes == 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = floor(log($bytes, 1024));
    return round($bytes / pow(1024, $i), 2) . ' ' . $units[$i];
}
?>
<?php
// Add to api/db-viewer.php
function editTable($db, $table) {
    $action = $_GET['subaction'] ?? 'browse';
    $id = $_GET['id'] ?? null;
    $primaryKey = getPrimaryKey($db, $table);
    
    switch($action) {
        case 'browse':
            browseTable($db, $table);
            break;
        case 'edit':
            editRow($db, $table, $primaryKey, $id);
            break;
        case 'delete':
            deleteRow($db, $table, $primaryKey, $id);
            break;
        case 'insert':
            insertRow($db, $table);
            break;
    }
}

function browseTable($db, $table) {
    $result = $db->query("SELECT * FROM `$table` LIMIT 100");
    ?>
    <h2><?php echo $table; ?></h2>
    <a href="?action=view_table&table=<?php echo $table; ?>&subaction=insert">➕ Add New Row</a>
    <table>
        <thead>
            <tr>
                <?php 
                $cols = $db->query("SHOW COLUMNS FROM `$table`");
                while($col = $cols->fetch_assoc()): 
                ?>
                <th><?php echo $col['Field']; ?></th>
                <?php endwhile; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <?php foreach($row as $key => $value): ?>
                <td><?php echo htmlspecialchars($value ?? 'NULL'); ?></td>
                <?php endforeach; ?>
                <td>
                    <a href="?action=view_table&table=<?php echo $table; ?>&subaction=edit&id=<?php echo $row[getPrimaryKey($db, $table)]; ?>">✏️ Edit</a>
                    <a href="?action=view_table&table=<?php echo $table; ?>&subaction=delete&id=<?php echo $row[getPrimaryKey($db, $table)]; ?>" onclick="return confirm('Delete this row?')">🗑️ Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php
}

function getPrimaryKey($db, $table) {
    $result = $db->query("SHOW KEYS FROM `$table` WHERE Key_name = 'PRIMARY'");
    if($row = $result->fetch_assoc()) {
        return $row['Column_name'];
    }
    return 'id'; // fallback
}

function insertRow($db, $table) {
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cols = array_keys($_POST);
        $values = array_map(function($v) use ($db) {
            return "'" . $db->real_escape_string($v) . "'";
        }, array_values($_POST));
        
        $query = "INSERT INTO `$table` (`" . implode('`, `', $cols) . "`) VALUES (" . implode(', ', $values) . ")";
        if($db->query($query)) {
            echo "<p>✅ Row inserted successfully!</p>";
            echo "<a href='?action=view_table&table=$table'>← Back to table</a>";
            return;
        } else {
            echo "<p>❌ Error: " . $db->error . "</p>";
        }
    }
    ?>
    <h2>➕ Insert New Row into <?php echo $table; ?></h2>
    <form method="POST">
        <?php 
        $cols = $db->query("SHOW COLUMNS FROM `$table`");
        while($col = $cols->fetch_assoc()):
            // Skip auto-increment columns
            if(strpos($col['Extra'], 'auto_increment') !== false) continue;
        ?>
        <div style="margin: 10px 0;">
            <label><strong><?php echo $col['Field']; ?></strong> (<?php echo $col['Type']; ?>)</label><br>
            <input type="text" name="<?php echo $col['Field']; ?>" style="width: 100%; padding: 8px;">
        </div>
        <?php endwhile; ?>
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px;">Insert Row</button>
        <a href="?action=view_table&table=<?php echo $table; ?>">Cancel</a>
    </form>
    <?php
}
?>
