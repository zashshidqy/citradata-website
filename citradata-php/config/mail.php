<?php
/**
 * Konfigurasi pengiriman email (SMTP) untuk notifikasi form contact.
 * Ubah nilai di bawah sesuai akun email yang akan digunakan untuk mengirim.
 *
 * Catatan untuk Gmail: gunakan "App Password", bukan password akun biasa.
 * Aktifkan dulu 2-Step Verification di akun Google, lalu buat App Password
 * di https://myaccount.google.com/apppasswords
 */

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'youremail@gmail.com');
define('SMTP_PASSWORD', 'your-app-password');
define('SMTP_ENCRYPTION', 'tls'); // 'tls' atau 'ssl'

// Email tujuan yang akan menerima notifikasi setiap ada pesan baru dari form contact
define('MAIL_TO_ADDRESS', 'citradatajakarta@gmail.com');
define('MAIL_TO_NAME', 'PT Citradata Indonusa');

// Nama pengirim yang akan muncul di email (biasanya sama dengan SMTP_USERNAME)
define('MAIL_FROM_ADDRESS', 'youremail@gmail.com');
define('MAIL_FROM_NAME', 'Citradata Website');
