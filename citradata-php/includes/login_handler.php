<?php
/**
 * Handler untuk form login member.
 */

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) session_start();

$loginUrl = url('pages/login.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect($loginUrl);
}

$email      = trim($_POST['email']    ?? '');
$password   = trim($_POST['password'] ?? '');
$redirectTo = trim($_POST['redirect'] ?? url('pages/dashboard.php'));

// Validasi ringan
if ($email === '' || $password === '') {
    $_SESSION['login_error'] = 'Email dan password wajib diisi.';
    redirect($loginUrl);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = 'Format email tidak valid.';
    redirect($loginUrl);
}

try {
    $pdo  = getDbConnection();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = :email AND is_active = 1 LIMIT 1');
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password_hash'])) {
        $_SESSION['login_error'] = 'Email atau password salah.';
        redirect($loginUrl);
    }

    // Update last_login_at
    $pdo->prepare('UPDATE users SET last_login_at = NOW() WHERE id = :id')
        ->execute([':id' => $user['id']]);

    // Set session
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email']= $user['email'];
    $_SESSION['user_role'] = $user['role'];

    // Redirect
    $safe = filter_var($redirectTo, FILTER_VALIDATE_URL) ? $redirectTo : url('pages/dashboard.php');
    redirect($safe);

} catch (Exception $e) {
    error_log('Login error: ' . $e->getMessage());
    $_SESSION['login_error'] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
    redirect($loginUrl);
}
