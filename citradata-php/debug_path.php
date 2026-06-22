<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/config/database.php';

echo '<pre>';
echo 'DOCUMENT_ROOT : ' . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo 'SCRIPT_NAME   : ' . $_SERVER['SCRIPT_NAME'] . "\n";
echo '__DIR__       : ' . __DIR__ . "\n";
echo 'dirname(__DIR__): ' . dirname(__DIR__) . "\n";
echo 'baseUrl()     : ' . baseUrl() . "\n";
echo 'asset(assets/logos/test.png): ' . asset('assets/logos/test.png') . "\n";
echo "\n";

// Cek isi folder assets/logos
$dir = __DIR__ . '/assets/logos/';
echo "Files in assets/logos/:\n";
if (is_dir($dir)) {
    foreach (scandir($dir) as $f) {
        if ($f === '.' || $f === '..') continue;
        echo "  - $f (" . filesize($dir.$f) . " bytes)\n";
    }
} else {
    echo "  [folder tidak ada]\n";
}

// Cek DB
echo "\nLogos di database:\n";
try {
    $pdo = getDbConnection();
    $rows = $pdo->query('SELECT id, name, logo_path FROM client_logos')->fetchAll();
    foreach ($rows as $r) {
        $full = __DIR__ . '/' . $r['logo_path'];
        $exists = file_exists($full) ? 'FILE ADA' : 'FILE TIDAK ADA';
        $url = asset($r['logo_path']);
        echo "  [{$r['id']}] {$r['name']}\n";
        echo "       logo_path : {$r['logo_path']}\n";
        echo "       full path : $full\n";
        echo "       file      : $exists\n";
        echo "       URL       : $url\n\n";
    }
} catch (Exception $e) {
    echo "DB error: " . $e->getMessage();
}
echo '</pre>';
