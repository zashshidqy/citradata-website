<?php
$source  = __DIR__;
$output  = __DIR__ . '/citradata-php-complete.zip';

if (file_exists($output)) unlink($output);

$zip = new ZipArchive();
if ($zip->open($output, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Cannot create zip\n");
}

$skip = [
    realpath($source . '/vendor'),
    realpath($output),
    realpath(__FILE__),
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    $realPath = realpath($file->getPathname());
    $shouldSkip = false;
    foreach ($skip as $s) {
        if ($s && str_starts_with($realPath, $s)) { $shouldSkip = true; break; }
    }
    if ($shouldSkip) continue;
    $relative = 'citradata-php/' . substr($realPath, strlen($source) + 1);
    $zip->addFile($realPath, $relative);
}
$zip->close();
echo "OK: $output (" . round(filesize($output)/1024) . " KB)\n";
