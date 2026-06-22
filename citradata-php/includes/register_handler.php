<?php
/**
 * Handler untuk form registrasi member baru.
 * Alur:
 * 1. Validasi input.
 * 2. Cek duplikat email.
 * 3. Simpan ke tabel users (role = 'member', is_active = 1).
 * 4. Auto-login langsung setelah daftar.
 * 5. Redirect ke dashboard.
 */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$registerUrl = url('pages/register.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($registerUrl);
}

// Ambil & sanitize input
$name             = trim($_POST['name']             ?? '');
$email            = trim($_POST['email']            ?? '');
$company          = trim($_POST['company']          ?? '');
$password         = $_POST['password']              ?? '';
$password_confirm = $_POST['password_confirm']      ?? '';

// Simpan old input untuk repopulate form jika error
$_SESSION['register_old'] = [
    'name'    => $name,
    'email'   => $email,
    'company' => $company,
];

// --- Validasi ---
$errors = [];

if ($name === '') {
    $errors[] = 'Nama lengkap wajib diisi.';
}

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email tidak valid.';
}

if (strlen($password) < 8) {
    $errors[] = 'Password minimal 8 karakter.';
}

if ($password !== $password_confirm) {
    $errors[] = 'Konfirmasi password tidak cocok.';
}

if (!empty($errors)) {
    $_SESSION['register_error'] = implode(' ', $errors);
    redirect($registerUrl);
}

// --- Proses ke DB ---
try {
    $pdo = getDbConnection();

    // Cek apakah email sudah terdaftar
    $check = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $check->execute([':email' => $email]);
    if ($check->fetch()) {
        $_SESSION['register_error'] = 'Email sudah terdaftar. Silakan gunakan email lain atau <a href="' . url('pages/login.php') . '" class="underline font-semibold">Sign In</a>.';
        redirect($registerUrl);
    }

    // Hash password
    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

    // Insert user baru — role default: 'member', is_active: 1
    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password_hash, role, is_active) VALUES (:name, :email, :hash, :role, 1)'
    );
    $stmt->execute([
        ':name'  => $name,
        ':email' => $email,
        ':hash'  => $hash,
        ':role'  => 'member',
    ]);

    $newUserId = (int) $pdo->lastInsertId();

    // Update last_login_at sekarang juga
    $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
        ->execute([':id' => $newUserId]);

    // Auto-login: set session
    $_SESSION['user_id']    = $newUserId;
    $_SESSION['user_name']  = $name;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_role']  = 'member';

    // Hapus old input karena sukses
    unset($_SESSION['register_old']);

    // Redirect ke dashboard dengan pesan selamat datang
    $_SESSION['dashboard_welcome'] = 'Selamat datang, ' . $name . '! Akun Anda berhasil dibuat.';
    redirect(url('pages/dashboard.php'));

} catch (Exception $e) {
    error_log('Register error: ' . $e->getMessage());
    $_SESSION['register_error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    redirect($registerUrl);
}
