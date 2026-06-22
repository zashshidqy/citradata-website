-- ============================================================
-- Schema database untuk Citradata Website (PHP version)
-- ============================================================
-- Import file ini ke MySQL/MariaDB sebelum menjalankan website.
-- mysql -u root -p citradata_db < sql/citradata_db.sql

CREATE DATABASE IF NOT EXISTS `citradata_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `citradata_db`;

-- ----------------------------------------------------------
-- Tabel contact_messages
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_messages` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`       VARCHAR(150) NOT NULL,
    `company`    VARCHAR(150) DEFAULT NULL,
    `email`      VARCHAR(150) NOT NULL,
    `mobile`     VARCHAR(30)  DEFAULT NULL,
    `subject`    VARCHAR(255) DEFAULT NULL,
    `message`    TEXT         DEFAULT NULL,
    `ip_address` VARCHAR(45)  DEFAULT NULL,
    `email_sent` TINYINT(1)   NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Tabel latest_news
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `latest_news` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title`      VARCHAR(255) NOT NULL,
    `summary`    TEXT         DEFAULT NULL,
    `content`    LONGTEXT     DEFAULT NULL,
    `image_url`  VARCHAR(500) DEFAULT NULL,
    `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
    `sort_order` INT          NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Tabel testimonials
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `testimonials` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `author_name` VARCHAR(150) NOT NULL,
    `author_role` VARCHAR(150) DEFAULT NULL,
    `company`     VARCHAR(150) DEFAULT NULL,
    `content`     TEXT         NOT NULL,
    `rating`      TINYINT(1)   NOT NULL DEFAULT 5,
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `sort_order`  INT          NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Tabel client_logos  (Valuable Clients)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `client_logos` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(150) NOT NULL,
    `logo_path`   VARCHAR(500) NOT NULL,
    `website_url` VARCHAR(500) DEFAULT NULL,
    `type`        ENUM('client','collaboration') NOT NULL DEFAULT 'client',
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `sort_order`  INT          NOT NULL DEFAULT 0,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Tabel users  (member login)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
    `id`              INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`            VARCHAR(150) NOT NULL,
    `email`           VARCHAR(150) NOT NULL,
    `password_hash`   VARCHAR(255) NOT NULL,
    `role`            ENUM('admin','member','trial') NOT NULL DEFAULT 'member',
    `is_active`       TINYINT(1)   NOT NULL DEFAULT 1,
    `last_login_at`   TIMESTAMP    NULL DEFAULT NULL,
    `created_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Seed: default admin user  (password: Admin@1234)
-- ----------------------------------------------------------
INSERT INTO `users` (`name`, `email`, `password_hash`, `role`, `is_active`) VALUES
('Administrator', 'admin@citradata.com', '$2y$12$eImiTXuWVxfM37uY4JANjQe5s/VhUYuGTNEHFGFWmJYT/FU0MOtKq', 'admin', 1);
-- Note: hash di atas adalah bcrypt dari "Admin@1234"
-- Ganti password setelah login pertama kali!

-- ----------------------------------------------------------
-- Seed: default client logos
-- ----------------------------------------------------------
INSERT INTO `client_logos` (`name`, `logo_path`, `website_url`, `type`, `sort_order`) VALUES
('Pacific Paint', 'assets/images/pacificpaint-logo.png', 'https://www.pacificpaints.com', 'client', 1),
('China State', 'assets/images/chinastate-logo.png', 'https://www.csci.com.hk', 'client', 2),
('Magiglass', 'assets/images/magiglass-logo.png', 'https://www.magiglass.com', 'client', 3),
('Prolink', 'assets/images/prolink-logo.png', 'https://www.prolink.co.id', 'client', 4),
('Grundfos', 'assets/images/grundfos-logo.png', 'https://www.grundfos.com', 'client', 5),
('Quantum Indonesia', 'assets/images/quantum-logo.png', 'https://www.quantum.co.id', 'collaboration', 1),
('Modulus', 'assets/images/modulus-logo.png', 'https://www.modulus.co.id', 'collaboration', 2);

-- ----------------------------------------------------------
-- Seed: sample latest_news
-- ----------------------------------------------------------
INSERT INTO `latest_news` (`title`, `summary`, `content`, `is_active`, `sort_order`) VALUES
(
  'Strategic Collaboration: Citradata, Quantum & Modulus',
  'Citradata, Quantum Indonesia and Modulus announce strategic collaboration to strengthen data-driven insights across Indonesia''s construction sector.',
  '<p>Citradata, Quantum Indonesia and Modulus are pleased to announce their strategic collaboration to strengthen the dissemination of data-driven insights and industry intelligence across Indonesia''s construction sector.</p><p>This partnership marks a significant milestone in delivering comprehensive, accurate and timely construction project data to all stakeholders.</p>',
  1, 1
),
(
  'Citradata Launches New Digital Platform',
  'Citradata''s new online platform provides real-time access to the latest construction project data across Indonesia.',
  '<p>Citradata has launched its new digital platform, offering members unlimited real-time access to the latest construction project data and progress updates.</p>',
  1, 2
);

-- ----------------------------------------------------------
-- Seed: sample testimonials
-- ----------------------------------------------------------
INSERT INTO `testimonials` (`author_name`, `author_role`, `company`, `content`, `rating`, `is_active`, `sort_order`) VALUES
(
  'John Doe',
  'Business Development Manager',
  'PT Construction Corp',
  'We are member for over a year and found it be of great use in finding construction projects in my area. It not only provided information about the projects but also provided supporting data on key contacts, phone numbers, emails.',
  5, 1, 1
),
(
  'Jane Smith',
  'Sales Director',
  'PT Material Supply Co',
  'Citradata has been an invaluable resource for our sales team. The data accuracy and timeliness has significantly improved our ability to identify and pursue new project opportunities.',
  5, 1, 2
);

-- ----------------------------------------------------------
-- Tabel projects  (Find Projects feature)
-- ----------------------------------------------------------
CREATE TABLE IF NOT EXISTS `projects` (
    `id`                  INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `project_name`        VARCHAR(255)   NOT NULL,
    `sector`              ENUM('Residential','Office','Retail','Industrial','Hospitality','Hospital','Mixed Use','Infrastructure','Other') NOT NULL,
    `status`              ENUM('Planning','Design','Tender','Construction','Completed','On Hold') NOT NULL DEFAULT 'Planning',
    `location_city`       VARCHAR(150)   DEFAULT NULL,
    `location_address`    TEXT           DEFAULT NULL,
    `project_value`       BIGINT         DEFAULT NULL COMMENT 'Value in IDR',
    `developer_name`      VARCHAR(255)   DEFAULT NULL,
    `developer_contact`   VARCHAR(150)   DEFAULT NULL COMMENT 'SENSITIVE',
    `developer_phone`     VARCHAR(50)    DEFAULT NULL COMMENT 'SENSITIVE',
    `developer_email`     VARCHAR(150)   DEFAULT NULL COMMENT 'SENSITIVE',
    `contractor_name`     VARCHAR(255)   DEFAULT NULL,
    `contractor_contact`  VARCHAR(150)   DEFAULT NULL COMMENT 'SENSITIVE',
    `contractor_phone`    VARCHAR(50)    DEFAULT NULL COMMENT 'SENSITIVE',
    `contractor_email`    VARCHAR(150)   DEFAULT NULL COMMENT 'SENSITIVE',
    `consultant_name`     VARCHAR(255)   DEFAULT NULL,
    `description`         TEXT           DEFAULT NULL,
    `start_date`          DATE           DEFAULT NULL,
    `end_date`            DATE           DEFAULT NULL,
    `is_active`           TINYINT(1)     NOT NULL DEFAULT 1,
    `is_featured`         TINYINT(1)     NOT NULL DEFAULT 0,
    `created_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_sector`  (`sector`),
    KEY `idx_status`  (`status`),
    KEY `idx_city`    (`location_city`),
    KEY `idx_active`  (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------
-- Seed: sample Indonesian construction projects
-- ----------------------------------------------------------
INSERT INTO `projects`
    (`project_name`,`sector`,`status`,`location_city`,`location_address`,`project_value`,
     `developer_name`,`developer_contact`,`developer_phone`,`developer_email`,
     `contractor_name`,`contractor_contact`,`contractor_phone`,`contractor_email`,
     `consultant_name`,`description`,`start_date`,`end_date`,`is_active`,`is_featured`)
VALUES
(
  'Apartemen Grand Sudirman Tower',
  'Residential','Construction','Jakarta Selatan',
  'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan 12190',
  850000000000,
  'PT Sinar Mulia Properti',   'Budi Santoso',   '0811-2233-4455',   'budi.santoso@sinarmulia.co.id',
  'PT Waskita Karya (Persero)', 'Doni Prasetyo',  '0812-3344-5566',   'doni.p@waskita.co.id',
  'PT Wiratman & Associates',
  'Proyek apartemen 45 lantai dengan konsep mixed-use yang mengintegrasikan hunian, SOHO, dan retail. Berlokasi di koridor bisnis utama Jakarta, proyek ini menawarkan 800 unit dengan fasilitas lengkap termasuk rooftop garden dan podium parkir.',
  '2023-03-01','2026-06-30',1,1
),
(
  'Mall Transpark Buaran',
  'Retail','Completed','Jakarta Timur',
  'Jl. Raya Bekasi Km. 22, Buaran, Jakarta Timur',
  320000000000,
  'PT Trans Property',        'Rina Wulandari',  '0813-4455-6677',   'rina.w@transproperty.co.id',
  'PT Pembangunan Perumahan',  'Hendra Kusuma',   '0814-5566-7788',   'hendra.k@ptpp.co.id',
  'PT Airmas Asri',
  'Pusat perbelanjaan modern 5 lantai seluas 45.000 m² dengan konsep transit-oriented development terintegrasi dengan Stasiun Buaran. Fasilitas meliputi supermarket, bioskop, food court, dan area bermain anak.',
  '2020-07-01','2023-04-30',1,0
),
(
  'Rumah Sakit Siloam Hospitals Surabaya',
  'Hospital','Construction','Surabaya',
  'Jl. Mayjend. Yono Soewoyo 2, Surabaya 60225',
  475000000000,
  'PT Lippo Karawaci Tbk',     'Antonius Wijaya', '0815-6677-8899',   'a.wijaya@siloam.co.id',
  'PT Adhi Karya (Persero)',   'Siti Rahayu',     '0816-7788-9900',   'siti.r@adhi.co.id',
  'PT Arcadis Indonesia',
  'Pembangunan rumah sakit bertaraf internasional dengan kapasitas 300 tempat tidur, 12 ruang operasi, dan pusat diagnostik canggih. Gedung setinggi 10 lantai ini dirancang dengan memenuhi standar green building GBCI.',
  '2022-11-01','2025-10-31',1,1
),
(
  'Office Park TB Simatupang',
  'Office','Tender','Jakarta Selatan',
  'Jl. TB Simatupang No. 18, Cilandak, Jakarta Selatan',
  560000000000,
  'PT Intiland Development Tbk','Dewi Anggraini',  '0817-8899-0011',   'd.anggraini@intiland.co.id',
  NULL, NULL, NULL, NULL,
  'PT Arup Indonesia',
  'Kawasan perkantoran Grade-A seluas 60.000 m² terdiri dari tiga tower 20 lantai dengan konsep smart office dan sustainable design. Dilengkapi basement parkir 4 lantai, central plaza, dan fasilitas pendukung kelas dunia.',
  '2024-01-15',NULL,1,0
),
(
  'Hotel Pullman Seminyak Bali',
  'Hospitality','Design','Badung',
  'Jl. Laksmana No. 88, Seminyak, Badung, Bali 80361',
  380000000000,
  'PT Surya Internusa Hotels', 'Made Suardhana',  '0818-9900-1122',   'm.suardhana@surya-hotels.co.id',
  NULL, NULL, NULL, NULL,
  'PT Aedas Indonesia',
  'Resort hotel bintang 5 dengan 280 kamar dan villa, infinity pool, spa berstandar internasional, serta ballroom kapasitas 800 orang. Desain arsitektur mencerminkan budaya Bali kontemporer dengan material lokal premium.',
  '2024-06-01','2027-05-31',1,1
),
(
  'Tol Semarang–Demak Seksi 2',
  'Infrastructure','Construction','Semarang',
  'Koridor Semarang–Demak, Jawa Tengah',
  3200000000000,
  'PT Pembangunan Jaya Infrastruktur','Bambang Hartono','0819-0011-2233','b.hartono@pji.co.id',
  'PT Hutama Karya (Persero)', 'Indah Permatasari','0821-1122-3344','indah.p@hutamakarya.co.id',
  'PT Virama Karya',
  'Jalan tol dua jalur sepanjang 16,3 km melewati kawasan pesisir utara Semarang, mencakup jembatan layang di atas kawasan rawa dan reklamasi. Proyek strategis untuk menghubungkan kawasan industri Semarang ke Demak.',
  '2022-04-01','2025-12-31',1,1
),
(
  'Superblok Podomoro Medan',
  'Mixed Use','Planning','Medan',
  'Jl. Gatot Subroto No. 95, Medan Sunggal, Medan 20122',
  1100000000000,
  'PT Agung Podomoro Land Tbk','Reza Gunawan',    '0822-2233-4455',   'r.gunawan@agungpodomoro.com',
  NULL, NULL, NULL, NULL,
  'PT Gistama Intisemesta',
  'Pengembangan superblok seluas 8 hektar yang akan menghadirkan apartemen, hotel, mall, dan perkantoran dalam satu kawasan terintegrasi. Proyek ini menjadi ikon baru kota Medan dengan total luas lantai 350.000 m².',
  '2025-03-01','2030-12-31',1,0
),
(
  'Gudang Logistik Modern Cikarang',
  'Industrial','Completed','Bekasi',
  'Kawasan Industri MM2100, Cikarang Barat, Bekasi',
  95000000000,
  'PT Surya Cipta Swadaya',    'Teguh Wahyudi',   '0823-3344-5566',   't.wahyudi@suryacipta.co.id',
  'PT Totalindo Eka Persada',  'Nurul Hidayah',   '0824-4455-6677',   'n.hidayah@totalindo.co.id',
  'PT Duta Paramindo Sejahtera',
  'Fasilitas pergudangan modern seluas 25.000 m² dengan sistem sortasi otomatis, cold storage berkapasitas 2.000 ton, dan akses kontainer langsung dari jalan arteri. Memenuhi standar green warehouse internasional.',
  '2021-08-01','2022-11-30',1,0
);
