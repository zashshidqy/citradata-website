<?php
/**
 * Autoloader manual sederhana (pengganti vendor/autoload.php bawaan Composer).
 *
 * Project ini menyertakan PHPMailer secara langsung (tanpa proses `composer install`)
 * supaya bisa langsung di-upload dan dijalankan di shared hosting yang tidak
 * menyediakan akses CLI Composer.
 *
 * Jika di kemudian hari kamu ingin mengelola dependency lewat Composer:
 * 1. Hapus folder vendor/ ini.
 * 2. Jalankan: composer require phpmailer/phpmailer
 * 3. File vendor/autoload.php bawaan Composer akan otomatis menggantikan file ini.
 */

spl_autoload_register(function (string $class) {
    // Mapping namespace PHPMailer\PHPMailer\* -> vendor/phpmailer/phpmailer/src/*
    $prefix = 'PHPMailer\\PHPMailer\\';
    $baseDir = __DIR__ . '/phpmailer/phpmailer/src/';

    if (strncmp($prefix, $class, strlen($prefix)) === 0) {
        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

        if (file_exists($file)) {
            require $file;
        }
    }
});
