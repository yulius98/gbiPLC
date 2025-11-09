<?php
/**
 * Script untuk membuat direktori yang dibutuhkan untuk upload file
 * 
 * Cara pakai:
 * 1. Upload file ini ke folder public/
 * 2. Akses: https://yourdomain.com/create-directories.php
 * 3. HAPUS file ini setelah selesai untuk keamanan!
 */

// Simple password protection
$secret_key = 'gbi-plc-2024'; // Ganti dengan string random Anda
$input_key = $_GET['key'] ?? '';

if ($input_key !== $secret_key) {
    die('<h1>Access Denied!</h1><p>Use: create-directories.php?key=' . htmlspecialchars($secret_key) . '</p>');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Required Directories</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            border-left: 5px solid;
        }
        .alert-success {
            background: #d4edda;
            border-color: #28a745;
            color: #155724;
        }
        .alert-warning {
            background: #fff3cd;
            border-color: #ffc107;
            color: #856404;
        }
        .alert-danger {
            background: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
        }
        .alert-info {
            background: #d1ecf1;
            border-color: #17a2b8;
            color: #0c5460;
        }
        .btn {
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 5px;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn:hover {
            opacity: 0.9;
        }
        pre {
            background: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background: #007bff;
            color: white;
        }
        .status-ok {
            color: #28a745;
            font-weight: bold;
        }
        .status-error {
            color: #dc3545;
            font-weight: bold;
        }
        .status-warning {
            color: #ffc107;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📁 Create Required Directories for Upload</h1>
        
        <?php
        $basePath = dirname(__DIR__); // Parent of public folder
        
        // Directories yang dibutuhkan
        $requiredDirectories = [
            'storage/app/chunks' => [
                'path' => $basePath . '/storage/app/chunks',
                'permission' => 0775,
                'description' => 'Temporary storage untuk chunk upload'
            ],
            'storage/app/public/materi-kotbah' => [
                'path' => $basePath . '/storage/app/public/materi-kotbah',
                'permission' => 0775,
                'description' => 'Storage untuk file materi kotbah'
            ],
            'storage/app/public' => [
                'path' => $basePath . '/storage/app/public',
                'permission' => 0775,
                'description' => 'Public storage root'
            ],
            'storage/logs' => [
                'path' => $basePath . '/storage/logs',
                'permission' => 0775,
                'description' => 'Laravel log files'
            ],
            'bootstrap/cache' => [
                'path' => $basePath . '/bootstrap/cache',
                'permission' => 0775,
                'description' => 'Bootstrap cache files'
            ],
            'storage/framework/cache' => [
                'path' => $basePath . '/storage/framework/cache',
                'permission' => 0775,
                'description' => 'Framework cache'
            ],
            'storage/framework/sessions' => [
                'path' => $basePath . '/storage/framework/sessions',
                'permission' => 0775,
                'description' => 'Session files'
            ],
            'storage/framework/views' => [
                'path' => $basePath . '/storage/framework/views',
                'permission' => 0775,
                'description' => 'Compiled views'
            ],
        ];
        
        // Handle auto-create
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_all') {
            echo "<div class='alert alert-info'>";
            echo "<h3>🔄 Creating Directories...</h3>";
            echo "</div>";
            
            $results = [];
            
            foreach ($requiredDirectories as $name => $config) {
                $path = $config['path'];
                $perm = $config['permission'];
                
                $result = [
                    'name' => $name,
                    'path' => $path,
                    'status' => 'skipped',
                    'message' => ''
                ];
                
                if (is_dir($path)) {
                    $result['status'] = 'exists';
                    $result['message'] = 'Already exists';
                    
                    // Try to fix permission
                    if (@chmod($path, $perm)) {
                        $result['message'] .= ' (permission updated)';
                    }
                } else {
                    if (@mkdir($path, $perm, true)) {
                        @chmod($path, $perm);
                        $result['status'] = 'created';
                        $result['message'] = 'Successfully created';
                    } else {
                        $result['status'] = 'failed';
                        $result['message'] = 'Failed to create - check parent directory permissions';
                    }
                }
                
                $results[] = $result;
            }
            
            // Display results
            echo "<table>";
            echo "<thead><tr><th>Directory</th><th>Status</th><th>Message</th></tr></thead>";
            echo "<tbody>";
            
            $createdCount = 0;
            $failedCount = 0;
            
            foreach ($results as $result) {
                echo "<tr>";
                echo "<td><code>" . htmlspecialchars($result['name']) . "</code></td>";
                
                $statusClass = '';
                $statusIcon = '';
                
                switch ($result['status']) {
                    case 'created':
                        $statusClass = 'status-ok';
                        $statusIcon = '✓';
                        $createdCount++;
                        break;
                    case 'exists':
                        $statusClass = 'status-warning';
                        $statusIcon = '⚠';
                        break;
                    case 'failed':
                        $statusClass = 'status-error';
                        $statusIcon = '✗';
                        $failedCount++;
                        break;
                }
                
                echo "<td class='$statusClass'>$statusIcon " . strtoupper($result['status']) . "</td>";
                echo "<td>" . htmlspecialchars($result['message']) . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody></table>";
            
            // Summary
            if ($failedCount > 0) {
                echo "<div class='alert alert-danger'>";
                echo "<h3>⚠️ Some Directories Failed to Create</h3>";
                echo "<p>Please create the failed directories manually via cPanel File Manager or SSH.</p>";
                echo "<p><strong>Manual creation via SSH:</strong></p>";
                echo "<pre>";
                foreach ($results as $result) {
                    if ($result['status'] === 'failed') {
                        echo "mkdir -p " . escapeshellarg($result['path']) . "\n";
                        echo "chmod 775 " . escapeshellarg($result['path']) . "\n";
                    }
                }
                echo "</pre>";
                echo "</div>";
            } elseif ($createdCount > 0) {
                echo "<div class='alert alert-success'>";
                echo "<h3>✓ Success!</h3>";
                echo "<p>Successfully created <strong>$createdCount</strong> directories.</p>";
                echo "<p><strong>Next steps:</strong></p>";
                echo "<ol>";
                echo "<li>Verify symbolic link: <code>php artisan storage:link</code></li>";
                echo "<li>Test upload functionality</li>";
                echo "<li><strong>DELETE this file (create-directories.php) for security!</strong></li>";
                echo "</ol>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-info'>";
                echo "<h3>ℹ️ All Directories Already Exist</h3>";
                echo "<p>No action needed. All required directories are already in place.</p>";
                echo "</div>";
            }
            
        } else {
            // Show current status
            echo "<div class='alert alert-info'>";
            echo "<p><strong>Base Path:</strong> <code>$basePath</code></p>";
            echo "<p>This script will create all required directories for file upload functionality.</p>";
            echo "</div>";
            
            echo "<h3>📋 Current Directory Status</h3>";
            echo "<table>";
            echo "<thead><tr><th>Directory</th><th>Status</th><th>Description</th></tr></thead>";
            echo "<tbody>";
            
            $allExist = true;
            
            foreach ($requiredDirectories as $name => $config) {
                $path = $config['path'];
                $exists = is_dir($path);
                $writable = $exists ? is_writable($path) : false;
                
                echo "<tr>";
                echo "<td><code>" . htmlspecialchars($name) . "</code></td>";
                
                if ($exists) {
                    if ($writable) {
                        echo "<td class='status-ok'>✓ Exists & Writable</td>";
                    } else {
                        echo "<td class='status-warning'>⚠ Exists but NOT Writable</td>";
                        $allExist = false;
                    }
                } else {
                    echo "<td class='status-error'>✗ NOT Found</td>";
                    $allExist = false;
                }
                
                echo "<td>" . htmlspecialchars($config['description']) . "</td>";
                echo "</tr>";
            }
            
            echo "</tbody></table>";
            
            if (!$allExist) {
                echo "<div class='alert alert-warning'>";
                echo "<h3>⚠️ Action Required</h3>";
                echo "<p>Some directories are missing or not writable. Click the button below to auto-create them.</p>";
                echo "</div>";
                
                echo "<form method='post'>";
                echo "<input type='hidden' name='action' value='create_all'>";
                echo "<button type='submit' class='btn btn-success'>🔧 Create All Missing Directories</button>";
                echo "</form>";
                
                echo "<div class='alert alert-info' style='margin-top: 20px;'>";
                echo "<h4>Manual Creation (Alternative)</h4>";
                echo "<p>If auto-creation fails, create directories manually via SSH:</p>";
                echo "<pre>";
                foreach ($requiredDirectories as $name => $config) {
                    if (!is_dir($config['path'])) {
                        echo "mkdir -p " . escapeshellarg($config['path']) . "\n";
                        echo "chmod 775 " . escapeshellarg($config['path']) . "\n";
                    }
                }
                echo "</pre>";
                echo "</div>";
            } else {
                echo "<div class='alert alert-success'>";
                echo "<h3>✓ All Directories Exist!</h3>";
                echo "<p>All required directories are in place and writable.</p>";
                echo "<p><strong>Next steps:</strong></p>";
                echo "<ol>";
                echo "<li>Ensure symbolic link exists: <code>php artisan storage:link</code></li>";
                echo "<li>Test the upload functionality</li>";
                echo "<li><strong>DELETE this file (create-directories.php) for security!</strong></li>";
                echo "</ol>";
                echo "</div>";
            }
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <div class="alert alert-warning">
            <h3>⚠️ Security Warning</h3>
            <p><strong>IMPORTANT:</strong> After creating the directories, please delete this file for security reasons!</p>
            <p>Run this command via SSH or delete via cPanel File Manager:</p>
            <pre>rm <?php echo __FILE__; ?></pre>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="test-upload-config.php" class="btn btn-primary">📊 Check PHP Configuration</a>
            <a href="clear-cache.php?key=<?php echo $secret_key; ?>" class="btn btn-primary">🗑️ Clear Laravel Cache</a>
        </div>
    </div>
</body>
</html>
