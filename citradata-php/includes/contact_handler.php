<?php
/**
 * Handler form Contact Us.
 * Alur:
 * 1. Validasi input dasar.
 * 2. Simpan ke tabel `contact_messages` (MySQL via PDO).
 * 3. Kirim email notifikasi ke MAIL_TO_ADDRESS via PHPMailer (SMTP).
 * 4. Redirect kembali ke pages/contact.php dengan flash message sukses/error.
 */

session_start();

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/mail.php';
require_once __DIR__ . '/../vendor/autoload.php'; // PHPMailer via Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

$redirectTo = url('pages/contact.php');

// Hanya terima request POST.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $redirectTo);
    exit;
}

// Ambil & bersihkan input.
$name    = trim($_POST['name'] ?? '');
$company = trim($_POST['company'] ?? '');
$email   = trim($_POST['email'] ?? '');
$mobile  = trim($_POST['mobile'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

$_SESSION['contact_old_input'] = [
    'name' => $name, 'company' => $company, 'email' => $email,
    'mobile' => $mobile, 'subject' => $subject, 'message' => $message,
];

// Validasi sederhana.
$errors = [];
if ($name === '') {
    $errors[] = 'Nama wajib diisi.';
}
if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Email tidak valid.';
}

if (!empty($errors)) {
    $_SESSION['contact_error'] = implode(' ', $errors);
    header('Location: ' . $redirectTo);
    exit;
}

$ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
$emailSent = 0;

try {
    $pdo = getDbConnection();

    $stmt = $pdo->prepare(
        'INSERT INTO contact_messages (name, company, email, mobile, subject, message, ip_address, email_sent)
         VALUES (:name, :company, :email, :mobile, :subject, :message, :ip_address, :email_sent)'
    );

    $stmt->execute([
        ':name'       => $name,
        ':company'    => $company !== '' ? $company : null,
        ':email'      => $email,
        ':mobile'     => $mobile !== '' ? $mobile : null,
        ':subject'    => $subject !== '' ? $subject : null,
        ':message'    => $message !== '' ? $message : null,
        ':ip_address' => $ipAddress,
        ':email_sent' => 0,
    ]);

    $insertId = (int) $pdo->lastInsertId();

    // --- Kirim email notifikasi via PHPMailer (SMTP) ---
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_ENCRYPTION === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
        $mail->addAddress(MAIL_TO_ADDRESS, MAIL_TO_NAME);
        $mail->addReplyTo($email, $name);

        $mail->isHTML(true);
        $mail->Subject = 'Pesan Baru dari Website: ' . ($subject !== '' ? $subject : 'Tanpa Subjek');

        $safeName    = htmlspecialchars($name);
        $safeCompany = htmlspecialchars($company);
        $safeEmail   = htmlspecialchars($email);
        $safeMobile  = htmlspecialchars($mobile);
        $safeSubject = htmlspecialchars($subject);
        $safeMessage = nl2br(htmlspecialchars($message));

        $mail->Body = "
            <h2>Pesan Baru dari Form Contact Citradata</h2>
            <table cellpadding='6' cellspacing='0' border='0'>
                <tr><td><strong>Nama</strong></td><td>{$safeName}</td></tr>
                <tr><td><strong>Perusahaan</strong></td><td>{$safeCompany}</td></tr>
                <tr><td><strong>Email</strong></td><td>{$safeEmail}</td></tr>
                <tr><td><strong>Mobile</strong></td><td>{$safeMobile}</td></tr>
                <tr><td><strong>Subjek</strong></td><td>{$safeSubject}</td></tr>
                <tr><td><strong>Pesan</strong></td><td>{$safeMessage}</td></tr>
            </table>
        ";
        $mail->AltBody = "Nama: {$name}\nPerusahaan: {$company}\nEmail: {$email}\nMobile: {$mobile}\nSubjek: {$subject}\nPesan: {$message}";

        $mail->send();
        $emailSent = 1;

        // Update status email_sent di database.
        $updateStmt = $pdo->prepare('UPDATE contact_messages SET email_sent = 1 WHERE id = :id');
        $updateStmt->execute([':id' => $insertId]);

    } catch (PHPMailerException $mailException) {
        // Data tetap tersimpan di DB walau email gagal terkirim.
        error_log('PHPMailer error: ' . $mailException->getMessage());
    }

    unset($_SESSION['contact_old_input']);
    $_SESSION['contact_success'] = $emailSent
        ? 'Terima kasih! Pesan Anda berhasil terkirim. Tim kami akan segera menghubungi Anda.'
        : 'Pesan Anda berhasil disimpan, namun notifikasi email gagal terkirim. Tim kami tetap akan memprosesnya.';

} catch (PDOException $e) {
    error_log('Contact form DB error: ' . $e->getMessage());
    $_SESSION['contact_error'] = 'Terjadi kesalahan saat menyimpan pesan Anda. Silakan coba lagi.';
}

header('Location: ' . $redirectTo);
exit;
