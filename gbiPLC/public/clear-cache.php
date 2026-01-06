<?php
/**
 * Script untuk clear cache Laravel via browser
 * Gunakan ini jika tidak punya akses SSH ke cPanel
 * 
 * Cara pakai:
 * 1. Upload file ini ke folder public/
 * 2. Akses: https://yourdomain.com/clear-cache.php
 * 3. HAPUS file ini setelah selesai untuk keamanan!
 */

// Prevent direct access from browser (optional security)
$secret_key = 'gbi-plc-2024'; // Ganti dengan string random Anda
$input_key = $_GET['key'] ?? '';

if ($input_key !== $secret_key) {
    die('Access Denied! Use: clear-cache.php?key=' . $secret_key);
}

echo "<h1>Laravel Cache Cleaner</h1>";
echo "<pre>";

try {
    // Load Laravel
    require __DIR__.'/../vendor/autoload.php';
    $app = require_once __DIR__.'/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // Clear config cache
    echo "Clearing config cache...\n";
    $kernel->call('config:clear');
    echo "✓ Config cache cleared\n\n";

    // Clear application cache
    echo "Clearing application cache...\n";
    $kernel->call('cache:clear');
    echo "✓ Application cache cleared\n\n";

    // Clear route cache
    echo "Clearing route cache...\n";
    $kernel->call('route:clear');
    echo "✓ Route cache cleared\n\n";

    // Clear view cache
    echo "Clearing view cache...\n";
    $kernel->call('view:clear');
    echo "✓ View cache cleared\n\n";

    // Clear compiled files
    echo "Clearing compiled files...\n";
    $kernel->call('clear-compiled');
    echo "✓ Compiled files cleared\n\n";

    echo "=================================\n";
    echo "✓ ALL CACHES CLEARED SUCCESSFULLY!\n";
    echo "=================================\n\n";
    
    echo "Next steps:\n";
    echo "1. Test your application\n";
    echo "2. DELETE this file (clear-cache.php) for security!\n";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString();
}

echo "</pre>";

echo "<style>
    body {
        font-family: 'Courier New', monospace;
        background: #1e1e1e;
        color: #00ff00;
        padding: 20px;
    }
    h1 {
        color: #00ff00;
        text-shadow: 0 0 10px #00ff00;
    }
    pre {
        background: #000;
        padding: 20px;
        border: 2px solid #00ff00;
        border-radius: 5px;
        line-height: 1.6;
    }
</style>";
?>
