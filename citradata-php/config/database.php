<?php
/**
 * Konfigurasi koneksi database MySQL.
 * Ubah nilai di bawah sesuai kredensial hosting/live server kamu.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'citradata_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

/**
 * Mengembalikan koneksi PDO ke database.
 * Menggunakan PDO supaya query aman dari SQL Injection (prepared statements).
 *
 * @return PDO
 */
function getDbConnection(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            // Lempar ulang exception supaya caller (misal contact_handler.php)
            // bisa menangkapnya dan menampilkan flash message yang rapi,
            // bukan mematikan request secara langsung.
            throw $e;
        }
    }

    return $pdo;
}
