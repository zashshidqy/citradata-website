-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251115.402e5d71cc
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 11, 2026 at 04:49 PM
-- Server version: 8.4.3
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `citradata_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` enum('top','bottom','sidebar') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'top',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_logos`
--

CREATE TABLE `client_logos` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `website_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('client','collaboration') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_logos`
--

INSERT INTO `client_logos` (`id`, `name`, `logo_path`, `website_url`, `type`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Pacific Paint', 'assets/images/pacificpaint-logo.png', 'https://www.pacificpaints.com', 'client', 1, 1, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(2, 'China State', 'assets/images/chinastate-logo.png', 'https://www.csci.com.hk', 'client', 1, 2, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(3, 'Magiglass', 'assets/images/magiglass-logo.png', 'https://www.magiglass.com', 'client', 1, 3, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(4, 'Prolink', 'assets/images/prolink-logo.png', 'https://www.prolink.co.id', 'client', 1, 4, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(5, 'Grundfos', 'assets/images/grundfos-logo.png', 'https://www.grundfos.com', 'client', 1, 5, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(6, 'Quantum Indonesia', 'assets/images/quantum-logo.png', 'https://www.quantum.co.id', 'collaboration', 1, 1, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(7, 'Modulus', 'assets/images/modulus-logo.png', 'https://www.modulus.co.id', 'collaboration', 1, 2, '2026-06-23 09:04:16', '2026-06-23 09:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_sent` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hero_slides`
--

CREATE TABLE `hero_slides` (
  `id` int UNSIGNED NOT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt_text` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hero_slides`
--

INSERT INTO `hero_slides` (`id`, `image_path`, `alt_text`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'assets/images/1.png', 'Slide 1', 1, 1, '2026-06-25 02:18:39', '2026-06-25 02:18:39'),
(2, 'assets/images/2.png', 'Slide 2', 2, 1, '2026-06-25 02:18:39', '2026-06-25 02:18:39'),
(3, 'assets/images/3.png', 'Slide 3', 3, 1, '2026-06-25 02:18:39', '2026-06-25 02:18:39'),
(4, 'assets/images/4.png', 'Slide 4', 4, 1, '2026-06-25 02:18:39', '2026-06-25 02:18:39'),
(5, 'assets/images/5.png', 'Slide 5', 5, 1, '2026-06-25 02:18:39', '2026-06-25 02:18:39'),
(12, 'assets/images/6.png', 'Slide 6', 6, 1, '2026-07-11 15:31:34', '2026-07-11 15:31:34');

-- --------------------------------------------------------

--
-- Table structure for table `latest_news`
--

CREATE TABLE `latest_news` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `latest_news`
--

INSERT INTO `latest_news` (`id`, `title`, `summary`, `content`, `image_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Strategic Collaboration: Citradata, Quantum & Modulus', 'Citradata, Quantum Indonesia and Modulus announce strategic collaboration to strengthen data-driven insights across Indonesia\'s construction sector.', '<p>Citradata, Quantum Indonesia and Modulus are pleased to announce their strategic collaboration to strengthen the dissemination of data-driven insights and industry intelligence across Indonesia\'s construction sector.</p><p>This partnership marks a significant milestone in delivering comprehensive, accurate and timely construction project data to all stakeholders.</p>', 'assets/news/news_6a5260f24be83.jpg', 1, 1, '2026-06-23 09:04:16', '2026-07-11 15:27:46'),
(2, 'Citradata Launches New Digital Platform', 'Citradata\'s new online platform provides real-time access to the latest construction project data across Indonesia.', '<p>Citradata has launched its new digital platform, offering members unlimited real-time access to the latest construction project data and progress updates.</p>', NULL, 1, 2, '2026-06-23 09:04:16', '2026-06-23 09:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int UNSIGNED NOT NULL,
  `project_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sector` enum('Residential','Office','Retail','Industrial','Hospitality','Hospital','Mixed Use','Infrastructure','Other') COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Planning','Design','Tender','Construction','Completed','On Hold') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Planning',
  `location_city` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location_address` text COLLATE utf8mb4_unicode_ci,
  `project_value` bigint DEFAULT NULL COMMENT 'Value in IDR',
  `developer_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `developer_contact` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `developer_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `developer_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `contractor_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contractor_contact` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `contractor_phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `contractor_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'SENSITIVE',
  `consultant_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_name`, `sector`, `status`, `location_city`, `location_address`, `project_value`, `developer_name`, `developer_contact`, `developer_phone`, `developer_email`, `contractor_name`, `contractor_contact`, `contractor_phone`, `contractor_email`, `consultant_name`, `description`, `start_date`, `end_date`, `is_active`, `is_featured`, `created_at`, `updated_at`) VALUES
(1, 'Apartemen Grand Sudirman Tower', 'Residential', 'Construction', 'Jakarta Selatan', 'Jl. Jend. Sudirman Kav. 52-53, Jakarta Selatan 12190', 850000000000, 'PT Sinar Mulia Properti', 'Budi Santoso', '0811-2233-4455', 'budi.santoso@sinarmulia.co.id', 'PT Waskita Karya (Persero)', 'Doni Prasetyo', '0812-3344-5566', 'doni.p@waskita.co.id', 'PT Wiratman & Associates', 'Proyek apartemen 45 lantai dengan konsep mixed-use yang mengintegrasikan hunian, SOHO, dan retail. Berlokasi di koridor bisnis utama Jakarta, proyek ini menawarkan 800 unit dengan fasilitas lengkap termasuk rooftop garden dan podium parkir.', '2023-03-01', '2026-06-30', 1, 1, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(2, 'Mall Transpark Buaran', 'Retail', 'Completed', 'Jakarta Timur', 'Jl. Raya Bekasi Km. 22, Buaran, Jakarta Timur', 320000000000, 'PT Trans Property', 'Rina Wulandari', '0813-4455-6677', 'rina.w@transproperty.co.id', 'PT Pembangunan Perumahan', 'Hendra Kusuma', '0814-5566-7788', 'hendra.k@ptpp.co.id', 'PT Airmas Asri', 'Pusat perbelanjaan modern 5 lantai seluas 45.000 m² dengan konsep transit-oriented development terintegrasi dengan Stasiun Buaran. Fasilitas meliputi supermarket, bioskop, food court, dan area bermain anak.', '2020-07-01', '2023-04-30', 1, 0, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(3, 'Rumah Sakit Siloam Hospitals Surabaya', 'Hospital', 'Construction', 'Surabaya', 'Jl. Mayjend. Yono Soewoyo 2, Surabaya 60225', 475000000000, 'PT Lippo Karawaci Tbk', 'Antonius Wijaya', '0815-6677-8899', 'a.wijaya@siloam.co.id', 'PT Adhi Karya (Persero)', 'Siti Rahayu', '0816-7788-9900', 'siti.r@adhi.co.id', 'PT Arcadis Indonesia', 'Pembangunan rumah sakit bertaraf internasional dengan kapasitas 300 tempat tidur, 12 ruang operasi, dan pusat diagnostik canggih. Gedung setinggi 10 lantai ini dirancang dengan memenuhi standar green building GBCI.', '2022-11-01', '2025-10-31', 1, 1, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(4, 'Office Park TB Simatupang', 'Office', 'Tender', 'Jakarta Selatan', 'Jl. TB Simatupang No. 18, Cilandak, Jakarta Selatan', 560000000000, 'PT Intiland Development Tbk', 'Dewi Anggraini', '0817-8899-0011', 'd.anggraini@intiland.co.id', NULL, NULL, NULL, NULL, 'PT Arup Indonesia', 'Kawasan perkantoran Grade-A seluas 60.000 m² terdiri dari tiga tower 20 lantai dengan konsep smart office dan sustainable design. Dilengkapi basement parkir 4 lantai, central plaza, dan fasilitas pendukung kelas dunia.', '2024-01-15', NULL, 1, 0, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(5, 'Hotel Pullman Seminyak Bali', 'Hospitality', 'Design', 'Badung', 'Jl. Laksmana No. 88, Seminyak, Badung, Bali 80361', 380000000000, 'PT Surya Internusa Hotels', 'Made Suardhana', '0818-9900-1122', 'm.suardhana@surya-hotels.co.id', NULL, NULL, NULL, NULL, 'PT Aedas Indonesia', 'Resort hotel bintang 5 dengan 280 kamar dan villa, infinity pool, spa berstandar internasional, serta ballroom kapasitas 800 orang. Desain arsitektur mencerminkan budaya Bali kontemporer dengan material lokal premium.', '2024-06-01', '2027-05-31', 1, 1, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(6, 'Tol Semarang–Demak Seksi 2', 'Infrastructure', 'Construction', 'Semarang', 'Koridor Semarang–Demak, Jawa Tengah', 3200000000000, 'PT Pembangunan Jaya Infrastruktur', 'Bambang Hartono', '0819-0011-2233', 'b.hartono@pji.co.id', 'PT Hutama Karya (Persero)', 'Indah Permatasari', '0821-1122-3344', 'indah.p@hutamakarya.co.id', 'PT Virama Karya', 'Jalan tol dua jalur sepanjang 16,3 km melewati kawasan pesisir utara Semarang, mencakup jembatan layang di atas kawasan rawa dan reklamasi. Proyek strategis untuk menghubungkan kawasan industri Semarang ke Demak.', '2022-04-01', '2025-12-31', 1, 1, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(7, 'Superblok Podomoro Medan', 'Mixed Use', 'Planning', 'Medan', 'Jl. Gatot Subroto No. 95, Medan Sunggal, Medan 20122', 1100000000000, 'PT Agung Podomoro Land Tbk', 'Reza Gunawan', '0822-2233-4455', 'r.gunawan@agungpodomoro.com', NULL, NULL, NULL, NULL, 'PT Gistama Intisemesta', 'Pengembangan superblok seluas 8 hektar yang akan menghadirkan apartemen, hotel, mall, dan perkantoran dalam satu kawasan terintegrasi. Proyek ini menjadi ikon baru kota Medan dengan total luas lantai 350.000 m².', '2025-03-01', '2030-12-31', 1, 0, '2026-06-23 09:04:17', '2026-06-23 09:04:17'),
(8, 'Gudang Logistik Modern Cikarang', 'Industrial', 'Completed', 'Bekasi', 'Kawasan Industri MM2100, Cikarang Barat, Bekasi', 95000000000, 'PT Surya Cipta Swadaya', 'Teguh Wahyudi', '0823-3344-5566', 't.wahyudi@suryacipta.co.id', 'PT Totalindo Eka Persada', 'Nurul Hidayah', '0824-4455-6677', 'n.hidayah@totalindo.co.id', 'PT Duta Paramindo Sejahtera', 'Fasilitas pergudangan modern seluas 25.000 m² dengan sistem sortasi otomatis, cold storage berkapasitas 2.000 ton, dan akses kontainer langsung dari jalan arteri. Memenuhi standar green warehouse internasional.', '2021-08-01', '2022-11-30', 1, 0, '2026-06-23 09:04:17', '2026-06-23 09:04:17');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int UNSIGNED NOT NULL,
  `author_name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_role` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT '5',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `author_name`, `author_role`, `company`, `content`, `rating`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', 'Business Development Manager', 'PT Construction Corp', 'We are member for over a year and found it be of great use in finding construction projects in my area. It not only provided information about the projects but also provided supporting data on key contacts, phone numbers, emails.', 5, 1, 1, '2026-06-23 09:04:16', '2026-06-23 09:04:16'),
(2, 'Jane Smith', 'Sales Director', 'PT Material Supply Co', 'Citradata has been an invaluable resource for our sales team. The data accuracy and timeliness has significantly improved our ability to identify and pursue new project opportunities.', 5, 1, 2, '2026-06-23 09:04:16', '2026-06-23 09:04:16');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','member','trial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'member',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `role`, `is_active`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin@citradata.com', '$2y$12$KN0WcAUY0FfhqGxmvWkSLuE4yz/pL0nRS0X8QevQMEWg5qW9a1WcO', 'admin', 1, '2026-07-11 15:27:22', '2026-06-23 09:04:16', '2026-07-11 15:27:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_logos`
--
ALTER TABLE `client_logos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hero_slides`
--
ALTER TABLE `hero_slides`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `latest_news`
--
ALTER TABLE `latest_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sector` (`sector`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_city` (`location_city`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `client_logos`
--
ALTER TABLE `client_logos`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hero_slides`
--
ALTER TABLE `hero_slides`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `latest_news`
--
ALTER TABLE `latest_news`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
