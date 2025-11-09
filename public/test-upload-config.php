<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Upload Test - cPanel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .info-group {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #007bff;
        }
        .info-group h3 {
            margin-top: 0;
            color: #007bff;
        }
        .value {
            font-weight: bold;
            color: #28a745;
        }
        .warning {
            color: #dc3545;
            font-weight: bold;
        }
        .success {
            color: #28a745;
            font-weight: bold;
        }
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        table td:first-child {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php
        // Handle auto-create directories
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_dirs') {
            $basePath = dirname(__DIR__);
            $dirsToCreate = [
                $basePath . '/storage/app/chunks',
                $basePath . '/storage/app/public/materi-kotbah',
                $basePath . '/storage/logs',
                $basePath . '/bootstrap/cache',
            ];
            
            $created = [];
            $failed = [];
            
            foreach ($dirsToCreate as $dir) {
                if (!is_dir($dir)) {
                    if (@mkdir($dir, 0775, true)) {
                        @chmod($dir, 0775);
                        $created[] = $dir;
                    } else {
                        $failed[] = $dir;
                    }
                }
            }
            
            if (!empty($created)) {
                echo "<div class='alert alert-success'>";
                echo "<strong>✓ Success!</strong> Created " . count($created) . " directories:<br>";
                foreach ($created as $dir) {
                    echo "- " . str_replace($basePath . '/', '', $dir) . "<br>";
                }
                echo "</div>";
            }
            
            if (!empty($failed)) {
                echo "<div class='alert alert-danger'>";
                echo "<strong>✗ Failed!</strong> Could not create " . count($failed) . " directories:<br>";
                foreach ($failed as $dir) {
                    echo "- " . str_replace($basePath . '/', '', $dir) . "<br>";
                }
                echo "<p><strong>Please create these directories manually via cPanel File Manager or SSH.</strong></p>";
                echo "</div>";
            }
        }
        ?>
        
        <h1>🔧 PHP Configuration Test - Upload Settings</h1>
        <p>Halaman ini menampilkan konfigurasi PHP untuk upload file di cPanel.</p>
        
        <div class="info-group">
            <h3>📊 Upload Settings</h3>
            <table>
                <tr>
                    <td>upload_max_filesize</td>
                    <td class="value"><?php echo ini_get('upload_max_filesize'); ?></td>
                </tr>
                <tr>
                    <td>post_max_size</td>
                    <td class="value"><?php echo ini_get('post_max_size'); ?></td>
                </tr>
                <tr>
                    <td>max_file_uploads</td>
                    <td class="value"><?php echo ini_get('max_file_uploads'); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="info-group">
            <h3>⏱️ Execution Time</h3>
            <table>
                <tr>
                    <td>max_execution_time</td>
                    <td class="value"><?php echo ini_get('max_execution_time'); ?> seconds</td>
                </tr>
                <tr>
                    <td>max_input_time</td>
                    <td class="value"><?php echo ini_get('max_input_time'); ?> seconds</td>
                </tr>
            </table>
        </div>
        
        <div class="info-group">
            <h3>💾 Memory</h3>
            <table>
                <tr>
                    <td>memory_limit</td>
                    <td class="value"><?php echo ini_get('memory_limit'); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="info-group">
            <h3>🔍 Recommendations</h3>
            <?php
            $upload_max = ini_get('upload_max_filesize');
            $post_max = ini_get('post_max_size');
            $memory = ini_get('memory_limit');
            
            // Convert to bytes for comparison
            function convertToBytes($val) {
                $val = trim($val);
                $last = strtolower($val[strlen($val)-1]);
                $val = (int)$val;
                switch($last) {
                    case 'g': $val *= 1024;
                    case 'm': $val *= 1024;
                    case 'k': $val *= 1024;
                }
                return $val;
            }
            
            $upload_bytes = convertToBytes($upload_max);
            $post_bytes = convertToBytes($post_max);
            $memory_bytes = convertToBytes($memory);
            
            $required_upload = 2048 * 1024 * 1024; // 2GB in bytes
            $required_memory = 512 * 1024 * 1024; // 512MB in bytes
            
            echo "<ul>";
            
            if ($upload_bytes >= $required_upload) {
                echo "<li class='success'>✓ upload_max_filesize sudah cukup untuk file besar</li>";
            } else {
                echo "<li class='warning'>✗ upload_max_filesize terlalu kecil. Recommended: 2048M atau lebih</li>";
            }
            
            if ($post_bytes >= $required_upload) {
                echo "<li class='success'>✓ post_max_size sudah cukup untuk file besar</li>";
            } else {
                echo "<li class='warning'>✗ post_max_size terlalu kecil. Recommended: 2048M atau lebih</li>";
            }
            
            if ($memory_bytes >= $required_memory) {
                echo "<li class='success'>✓ memory_limit sudah cukup</li>";
            } else {
                echo "<li class='warning'>✗ memory_limit terlalu kecil. Recommended: 512M atau lebih</li>";
            }
            
            $max_exec = (int)ini_get('max_execution_time');
            if ($max_exec >= 300 || $max_exec == 0) {
                echo "<li class='success'>✓ max_execution_time sudah cukup</li>";
            } else {
                echo "<li class='warning'>✗ max_execution_time terlalu kecil. Recommended: 600 seconds atau lebih</li>";
            }
            
            echo "</ul>";
            ?>
        </div>
        
        <div class="info-group">
            <h3>📁 Directory Permissions</h3>
            <?php
            // Detect base path
            $basePath = dirname(__DIR__); // Parent of public folder
            
            $directories = [
                'storage/app/chunks' => $basePath . '/storage/app/chunks',
                'storage/app/public/materi-kotbah' => $basePath . '/storage/app/public/materi-kotbah',
                'storage/logs' => $basePath . '/storage/logs',
                'bootstrap/cache' => $basePath . '/bootstrap/cache',
            ];
            
            echo "<table>";
            echo "<tr><td colspan='2'><strong>Base Path:</strong> " . $basePath . "</td></tr>";
            
            $missingDirs = [];
            
            foreach ($directories as $name => $path) {
                $exists = is_dir($path);
                $writable = $exists ? is_writable($path) : false;
                
                echo "<tr>";
                echo "<td>$name</td>";
                echo "<td>";
                
                if ($exists) {
                    if ($writable) {
                        echo "<span class='success'>✓ Exists & Writable</span>";
                        // Show actual permissions
                        $perms = substr(sprintf('%o', fileperms($path)), -4);
                        echo " <small>(Permission: $perms)</small>";
                    } else {
                        echo "<span class='warning'>✗ Exists but NOT Writable</span>";
                        $perms = substr(sprintf('%o', fileperms($path)), -4);
                        echo " <small>(Permission: $perms - Need 755 or 775)</small>";
                    }
                } else {
                    echo "<span class='warning'>✗ Directory NOT Found</span>";
                    echo " <small>(Path: $path)</small>";
                    $missingDirs[] = $path;
                }
                
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
            
            // Show fix instructions if directories are missing
            if (!empty($missingDirs)) {
                echo "<div style='margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107;'>";
                echo "<h4 style='margin-top: 0; color: #856404;'>⚠️ Action Required: Create Missing Directories</h4>";
                echo "<p>Run these commands via SSH or create folders via cPanel File Manager:</p>";
                echo "<pre style='background: #000; color: #0f0; padding: 10px; border-radius: 5px;'>";
                foreach ($missingDirs as $dir) {
                    echo "mkdir -p " . escapeshellarg($dir) . "\n";
                    echo "chmod 775 " . escapeshellarg($dir) . "\n";
                }
                echo "</pre>";
                
                echo "<p><strong>Via cPanel File Manager:</strong></p>";
                echo "<ol>";
                foreach ($missingDirs as $dir) {
                    $relativePath = str_replace($basePath . '/', '', $dir);
                    echo "<li>Navigate to <code>$basePath</code></li>";
                    echo "<li>Create folder: <code>$relativePath</code></li>";
                    echo "<li>Right-click → Change Permissions → Set to 755 or 775</li>";
                }
                echo "</ol>";
                
                echo "<p><strong>Or use Auto-Fix button below:</strong></p>";
                echo "<form method='post' style='margin: 10px 0;'>";
                echo "<input type='hidden' name='action' value='create_dirs'>";
                echo "<button type='submit' style='background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;'>🔧 Auto-Create Missing Directories</button>";
                echo "</form>";
                echo "</div>";
            }
            ?>
        </div>
        
        <div class="info-group">
            <h3>ℹ️ Server Information</h3>
            <table>
                <tr>
                    <td>PHP Version</td>
                    <td class="value"><?php echo phpversion(); ?></td>
                </tr>
                <tr>
                    <td>Server Software</td>
                    <td class="value"><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></td>
                </tr>
                <tr>
                    <td>Document Root</td>
                    <td class="value"><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown'; ?></td>
                </tr>
            </table>
        </div>
        
        <p style="margin-top: 30px; color: #666; font-size: 14px;">
            <strong>Note:</strong> Setelah selesai testing, hapus file ini untuk keamanan.
        </p>
    </div>
</body>
</html>
