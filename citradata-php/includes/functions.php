<?php
/**
 * Helper functions umum yang dipakai di seluruh halaman.
 */

/**
 * Mengembalikan base URL absolut project (tanpa trailing slash).
 * Dihitung dari lokasi file functions.php itu sendiri (selalu ada di /includes/),
 * sehingga hasilnya konsisten dari halaman mana pun (pages/, admin/, root).
 */
function baseUrl(): string
{
    static $base = null;

    if ($base === null) {
        // __FILE__ = .../citradata-php/includes/functions.php
        // dirname(__FILE__) = .../citradata-php/includes
        // dirname(dirname(__FILE__)) = .../citradata-php  ← project root (filesystem)
        $projectRoot = str_replace('\\', '/', dirname(__DIR__));
        $docRoot     = str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/\\'));

        // Ambil path relatif dari docroot ke project root
        $relative = '';
        if (str_starts_with($projectRoot, $docRoot)) {
            $relative = substr($projectRoot, strlen($docRoot));
        }

        $base = rtrim($relative, '/');
    }

    return $base;
}

function asset(string $path): string
{
    return baseUrl() . '/' . ltrim($path, '/');
}

function url(string $path): string
{
    return baseUrl() . '/' . ltrim($path, '/');
}

function isActivePage(string $page): bool
{
    $current = basename($_SERVER['SCRIPT_NAME']);
    return $current === $page;
}

/**
 * Redirect helper
 */
function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

/**
 * Cek apakah user sudah login. Redirect ke login jika belum.
 */
function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_id'])) {
        redirect(url('pages/login.php') . '?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
}

/**
 * Cek apakah user adalah admin. Redirect ke home jika bukan.
 */
function requireAdmin(): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (empty($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
        redirect(url('index.php'));
    }
}

/**
 * Escape HTML output
 */
function e(string $str): string
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Ambil data news aktif dari DB
 */
function getActiveNews(int $limit = 3): array
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM latest_news WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT :lim'
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Ambil data testimonial aktif dari DB
 */
function getActiveTestimonials(int $limit = 4): array
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM testimonials WHERE is_active = 1 ORDER BY sort_order ASC, created_at DESC LIMIT :lim'
        );
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Ambil logo client atau collaboration dari DB
 */
function getLogos(string $type = 'client'): array
{
    try {
        $pdo = getDbConnection();
        $stmt = $pdo->prepare(
            'SELECT * FROM client_logos WHERE type = :type AND is_active = 1 ORDER BY sort_order ASC'
        );
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}
