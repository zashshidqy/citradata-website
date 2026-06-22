# Citradata Website (PHP Version)

Hasil migrasi dari website statis HTML ke PHP, untuk **PT Citradata Indonusa**. Struktur, desain, dan konten visual dipertahankan 100% sama dengan versi HTML asli — perbedaannya hanya pada arsitektur kode (PHP includes/partials) dan fitur baru: **form Contact Us yang benar-benar berfungsi** (simpan ke database MySQL + kirim notifikasi email).

## Apa yang Berubah dari Versi HTML

| Versi HTML | Versi PHP |
|---|---|
| 4 file `.html` berdiri sendiri, header/nav/footer diduplikasi di tiap file | Header, nav, hero, footer, dan script dipisah jadi *partials* di `includes/`, di-`include` ke setiap halaman `.php` |
| Form contact `action="#"` (tidak memproses apa pun) | Form mengirim POST ke `includes/contact_handler.php`, yang menyimpan ke MySQL dan mengirim email notifikasi |
| Path asset hardcode `/assets/images/...` | Path asset dibentuk otomatis lewat helper `asset()` & `url()`, otomatis menyesuaikan jika project diletakkan di subfolder |

Tidak ada perubahan pada tampilan, teks, gambar, warna, atau struktur Tailwind — semua class dan markup dipertahankan sama persis.

## Struktur Folder

```
citradata-php/
├── index.php                      # Halaman Home
├── pages/
│   ├── about.php
│   ├── product.php
│   └── contact.php                # Form contact (action -> includes/contact_handler.php)
├── includes/
│   ├── head.php                   # <head> bersama (Tailwind config, fonts, dst)
│   ├── nav.php                    # Navbar + mobile menu (active state otomatis)
│   ├── hero.php                   # Hero header (dipakai di semua halaman)
│   ├── footer.php                 # Footer (varian 'dark' & 'light')
│   ├── scripts.php                # Script Swiper init + mobile menu toggle
│   ├── functions.php              # Helper: asset(), url(), isActivePage(), baseUrl()
│   └── contact_handler.php        # Logic submit form: validasi -> simpan DB -> kirim email
├── config/
│   ├── database.php                # Kredensial & koneksi PDO MySQL — WAJIB DIISI
│   └── mail.php                    # Kredensial SMTP — WAJIB DIISI
├── sql/
│   └── citradata_db.sql            # Schema database, import sebelum dipakai
├── vendor/
│   └── phpmailer/...                # PHPMailer (sudah disertakan, tidak perlu composer install)
│   └── autoload.php                 # Autoloader manual pengganti Composer
├── assets/images/                   # Semua gambar (logo, hero, slider, dst) — tidak diubah
├── css/style.css
└── js/main.js
```

## Cara Pasang di Live Server (cPanel / shared hosting)

### 1. Upload File
Upload seluruh isi folder ini ke `public_html` (atau subfolder jika website ditaruh di subfolder). Bisa lewat File Manager cPanel atau FTP.

### 2. Buat Database MySQL
Di cPanel, buka **MySQL Databases**:
1. Buat database baru, contoh: `namauser_citradata`
2. Buat MySQL user baru + password, lalu assign ke database tersebut dengan **All Privileges**
3. Buka **phpMyAdmin**, pilih database tersebut, masuk tab **Import**, upload file `sql/citradata_db.sql`
   - Jika hosting tidak izinkan `CREATE DATABASE` dari file SQL, buka file tersebut dan hapus 2 baris paling atas (`CREATE DATABASE` dan `USE`), lalu import ulang.

### 3. Atur `config/database.php`
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'namauser_citradata');   // sesuaikan
define('DB_USER', 'namauser_dbuser');      // sesuaikan
define('DB_PASS', 'password_db_anda');     // sesuaikan
```

### 4. Atur `config/mail.php`
Form contact mengirim notifikasi email lewat SMTP. Paling mudah pakai akun Gmail dengan **App Password** (bukan password biasa):

1. Aktifkan **2-Step Verification** di akun Google pengirim
2. Buka https://myaccount.google.com/apppasswords, buat App Password baru
3. Isi ke `config/mail.php`:
```php
define('SMTP_USERNAME', 'emailpengirim@gmail.com');
define('SMTP_PASSWORD', 'app-password-16-digit');
define('MAIL_TO_ADDRESS', 'citra_jkt@citradataconstruction.com'); // email tujuan notifikasi
```

Atau, jika hosting punya email sendiri (misal `noreply@citradataconstruction.com`), gunakan SMTP hosting tersebut — biasanya `mail.namadomain.com`, port `587` atau `465`. Tanyakan ke provider hosting untuk detail SMTP-nya.

### 5. Pastikan Ekstensi PHP Aktif
Pastikan hosting mendukung PHP 7.4+ dengan ekstensi `pdo_mysql` aktif (hampir semua shared hosting modern sudah otomatis aktif).

### 6. Selesai
Akses domain Anda — website langsung jalan. Tidak perlu menjalankan `composer install`, karena PHPMailer sudah disertakan langsung di folder `vendor/`.

## Menjalankan di Localhost (XAMPP / Laragon)

1. Salin folder `citradata-php` ke `htdocs` (XAMPP) atau `www` (Laragon)
2. Buat database lewat phpMyAdmin, import `sql/citradata_db.sql`
3. Sesuaikan `config/database.php` (biasanya `DB_USER = 'root'`, `DB_PASS = ''`)
4. Akses lewat browser: `http://localhost/citradata-php/`

## Catatan Teknis

- **Tidak perlu Composer**: PHPMailer disertakan langsung di `vendor/phpmailer/` dengan autoloader manual (`vendor/autoload.php`) yang meniru cara kerja Composer secara sederhana, supaya bisa langsung jalan di hosting yang tidak punya akses CLI.
- **Path otomatis**: Fungsi `asset()` dan `url()` di `includes/functions.php` mendeteksi base path secara otomatis lewat `$_SERVER['SCRIPT_NAME']`. Jadi project ini tetap berfungsi baik diletakkan di root domain (`https://domain.com/`) maupun di subfolder (`https://domain.com/citradata-php/`).
- **Keamanan form**: Semua input di-escape dengan `htmlspecialchars()` sebelum ditampilkan ulang (anti XSS), dan query database menggunakan **prepared statements** PDO (anti SQL Injection).
- **Jika email gagal terkirim**: Data tetap tersimpan ke database (kolom `email_sent` akan bernilai `0`), sehingga pesan dari calon klien tidak hilang meskipun ada masalah konfigurasi SMTP. Cek tabel `contact_messages` secara berkala lewat phpMyAdmin sebagai cadangan.
