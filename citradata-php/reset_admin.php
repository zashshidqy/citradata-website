<?php
/**
 * Script sekali pakai untuk membuat / reset akun admin.
 * Akses via browser: yoursite.com/reset_admin.php
 * HAPUS FILE INI setelah selesai digunakan!
 */

require_once __DIR__ . '/config/database.php';

$email    = 'admin@citradata.com';
$password = 'Admin@1234';
$name     = 'Administrator';
$hash     = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);

try {
    $pdo = getDbConnection();

    // Cek apakah user sudah ada
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
    $stmt->execute([':email' => $email]);
    $existing = $stmt->fetch();

    if ($existing) {
        // Update password dan pastikan role = admin, is_active = 1
        $pdo->prepare('UPDATE users SET password_hash = :hash, role = "admin", is_active = 1 WHERE email = :email')
            ->execute([':hash' => $hash, ':email' => $email]);
        $msg = '✅ Password admin berhasil di-reset.';
    } else {
        // Insert baru
        $pdo->prepare('INSERT INTO users (name, email, password_hash, role, is_active) VALUES (:n, :e, :h, "admin", 1)')
            ->execute([':n' => $name, ':e' => $email, ':h' => $hash]);
        $msg = '✅ Akun admin berhasil dibuat.';
    }

    // Verifikasi hash
    $check = $pdo->prepare('SELECT password_hash FROM users WHERE email = :email LIMIT 1');
    $check->execute([':email' => $email]);
    $row = $check->fetch();
    $verified = password_verify($password, $row['password_hash']) ? '✅ Hash verified OK' : '❌ Hash verification FAILED';

} catch (Exception $e) {
    $msg      = '❌ Error: ' . $e->getMessage();
    $verified = '';
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Admin</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 80px auto; padding: 20px; background: #f8fafc; }
        .box { background: white; border-radius: 12px; padding: 30px; border: 1px solid #e2e8f0; }
        h2 { color: #0057a8; }
        p  { font-size: 15px; margin: 8px 0; }
        .warn { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px; margin-top: 20px; font-size: 13px; color: #92400e; }
        code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
<div class="box">
    <h2>Citradata – Admin Reset</h2>
    <p><?php echo $msg; ?></p>
    <p><?php echo $verified; ?></p>
    <hr style="margin:16px 0; border:none; border-top:1px solid #e2e8f0">
    <p><strong>Email:</strong> <code><?php echo $email; ?></code></p>
    <p><strong>Password:</strong> <code><?php echo $password; ?></code></p>
    <p><strong>Login URL:</strong> <a href="pages/login.php">pages/login.php</a></p>
    <div class="warn">
        ⚠️ <strong>Penting:</strong> Hapus file <code>reset_admin.php</code> dari server setelah selesai login!
    </div>
</div>
</body>
</html>
