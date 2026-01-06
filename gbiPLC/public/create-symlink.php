<?php
/**
 * Script untuk membuat symbolic link storage
 * Sama seperti: php artisan storage:link
 * 
 * Cara pakai:
 * 1. Upload file ini ke folder public/
 * 2. Akses: https://yourdomain.com/create-symlink.php?key=gbi-plc-2024
 * 3. HAPUS file ini setelah selesai untuk keamanan!
 */

// Simple password protection
$secret_key = 'gbi-plc-2024';
$input_key = $_GET['key'] ?? '';

if ($input_key !== $secret_key) {
    die('<h1>Access Denied!</h1><p>Use: create-symlink.php?key=' . htmlspecialchars($secret_key) . '</p>');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Storage Symbolic Link</title>
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
        pre {
            background: #000;
            color: #0f0;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
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
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔗 Create Storage Symbolic Link</h1>
        
        <?php
        $basePath = dirname(__DIR__);
        $target = $basePath . '/storage/app/public';
        $link = __DIR__ . '/storage';
        
        echo "<div class='alert alert-info'>";
        echo "<h3>ℹ️ What is this?</h3>";
        echo "<p>This script creates a symbolic link from <code>public/storage</code> to <code>storage/app/public</code>.</p>";
        echo "<p>This allows uploaded files to be accessible via URL.</p>";
        echo "</div>";
        
        echo "<h3>📋 Paths</h3>";
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 10px;'><strong>Target (actual storage):</strong></td>";
        echo "<td style='padding: 10px;'><code>$target</code></td>";
        echo "</tr>";
        echo "<tr style='border-bottom: 1px solid #ddd;'>";
        echo "<td style='padding: 10px;'><strong>Link (public access):</strong></td>";
        echo "<td style='padding: 10px;'><code>$link</code></td>";
        echo "</tr>";
        echo "</table>";
        
        // Check if target exists
        if (!is_dir($target)) {
            echo "<div class='alert alert-danger'>";
            echo "<h3>✗ Target Directory Not Found!</h3>";
            echo "<p>The target directory <code>$target</code> does not exist.</p>";
            echo "<p><strong>Please create it first:</strong></p>";
            echo "<pre>mkdir -p " . escapeshellarg($target) . "\nchmod 775 " . escapeshellarg($target) . "</pre>";
            echo "<p>Or use: <a href='create-directories.php?key=$secret_key'>create-directories.php</a></p>";
            echo "</div>";
        } else {
            echo "<div class='alert alert-success'>";
            echo "<p>✓ Target directory exists</p>";
            echo "</div>";
            
            // Check if link already exists
            if (file_exists($link)) {
                if (is_link($link)) {
                    $currentTarget = readlink($link);
                    
                    if ($currentTarget === $target) {
                        echo "<div class='alert alert-success'>";
                        echo "<h3>✓ Symbolic Link Already Exists!</h3>";
                        echo "<p>The symbolic link is already correctly configured.</p>";
                        echo "<p><strong>Link:</strong> <code>$link</code></p>";
                        echo "<p><strong>Points to:</strong> <code>$currentTarget</code></p>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-warning'>";
                        echo "<h3>⚠️ Symbolic Link Exists but Points to Wrong Target</h3>";
                        echo "<p><strong>Current target:</strong> <code>$currentTarget</code></p>";
                        echo "<p><strong>Expected target:</strong> <code>$target</code></p>";
                        echo "<p><strong>To fix:</strong></p>";
                        echo "<pre>rm " . escapeshellarg($link) . "\nln -s " . escapeshellarg($target) . " " . escapeshellarg($link) . "</pre>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='alert alert-warning'>";
                    echo "<h3>⚠️ File/Directory Exists at Link Location</h3>";
                    echo "<p>A file or directory already exists at <code>$link</code> but it's not a symbolic link.</p>";
                    echo "<p><strong>To fix:</strong></p>";
                    echo "<pre>rm -rf " . escapeshellarg($link) . "\nln -s " . escapeshellarg($target) . " " . escapeshellarg($link) . "</pre>";
                    echo "</div>";
                }
            } else {
                // Try to create symbolic link
                if (@symlink($target, $link)) {
                    echo "<div class='alert alert-success'>";
                    echo "<h3>✓ Symbolic Link Created Successfully!</h3>";
                    echo "<p><strong>Link:</strong> <code>$link</code></p>";
                    echo "<p><strong>Points to:</strong> <code>$target</code></p>";
                    echo "<p><strong>Next steps:</strong></p>";
                    echo "<ol>";
                    echo "<li>Test uploading a file</li>";
                    echo "<li>Verify file is accessible via URL</li>";
                    echo "<li><strong>DELETE this file (create-symlink.php) for security!</strong></li>";
                    echo "</ol>";
                    echo "</div>";
                } else {
                    echo "<div class='alert alert-danger'>";
                    echo "<h3>✗ Failed to Create Symbolic Link</h3>";
                    echo "<p>Could not create symbolic link. This might be due to:</p>";
                    echo "<ul>";
                    echo "<li>Insufficient permissions</li>";
                    echo "<li>Server doesn't allow symlinks</li>";
                    echo "<li>PHP safe mode restrictions</li>";
                    echo "</ul>";
                    echo "<p><strong>Please create it manually via SSH:</strong></p>";
                    echo "<pre>cd " . escapeshellarg(__DIR__) . "\nln -s " . escapeshellarg($target) . " storage</pre>";
                    echo "<p><strong>Or run Laravel artisan command:</strong></p>";
                    echo "<pre>cd " . escapeshellarg($basePath) . "\nphp artisan storage:link</pre>";
                    echo "</div>";
                }
            }
        }
        ?>
        
        <hr style="margin: 30px 0;">
        
        <div class="alert alert-info">
            <h3>🔍 Verify Symbolic Link</h3>
            <p>After creating the symbolic link, verify it's working:</p>
            <ol>
                <li>Upload a test file via the application</li>
                <li>Check if file exists in: <code>storage/app/public/materi-kotbah/</code></li>
                <li>Try to access it via URL: <code>https://yourdomain.com/storage/materi-kotbah/filename.pdf</code></li>
            </ol>
        </div>
        
        <div class="alert alert-warning">
            <h3>⚠️ Security Warning</h3>
            <p><strong>IMPORTANT:</strong> Please delete this file after creating the symbolic link!</p>
            <pre>rm <?php echo __FILE__; ?></pre>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="test-upload-config.php" class="btn btn-primary">📊 Check Configuration</a>
            <a href="create-directories.php?key=<?php echo $secret_key; ?>" class="btn btn-primary">📁 Create Directories</a>
        </div>
    </div>
</body>
</html>
