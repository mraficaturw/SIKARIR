-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Okt 2025 pada 18.32
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sikarir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `internjobs`
--

CREATE TABLE `internjobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'Internship',
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `description` text NOT NULL,
  `responsibility` text DEFAULT NULL,
  `qualifications` text DEFAULT NULL,
  `deadline` date DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `apply_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `internjobs`
--

INSERT INTO `internjobs` (`id`, `title`, `company`, `location`, `type`, `salary_min`, `salary_max`, `description`, `responsibility`, `qualifications`, `deadline`, `logo`, `category`, `created_at`, `updated_at`, `apply_url`) VALUES
(1, 'Software Engineer Intern', 'Tech Corp', 'Jakarta', 'Internship', 3000000.00, 5000000.00, 'Develop software applications.', 'Code, test, and debug software.', 'Basic programming knowledge.', '2025-11-25', NULL, 'Fakultas Ilmu Komputer', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(2, 'Marketing Intern', 'Business Inc', 'Surabaya', 'Internship', 2500000.00, 4000000.00, 'Assist in marketing campaigns.', 'Create content and analyze data.', 'Communication skills.', '2025-11-20', NULL, 'Fakultas Ekonomi dan Bisnis', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(3, 'Mechanical Engineer Intern', 'Engineering Ltd', 'Bandung', 'Internship', 3500000.00, 5500000.00, 'Design mechanical systems.', 'Assist in design and prototyping.', 'Engineering background.', '2025-11-15', NULL, 'Fakultas Teknik', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(4, 'Legal Intern', 'Law Firm', 'Yogyakarta', 'Internship', 2000000.00, 3500000.00, 'Assist in legal research.', 'Research and draft documents.', 'Law student.', '2025-11-10', NULL, 'Fakultas Hukum', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(5, 'Nursing Intern', 'Hospital', 'Semarang', 'Internship', 2800000.00, 4500000.00, 'Assist in patient care.', 'Monitor patients and assist nurses.', 'Nursing student.', '2025-11-05', NULL, 'Fakultas Kesehatan', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(6, 'Agricultural Intern', 'Farm Co', 'Medan', 'Internship', 2200000.00, 3800000.00, 'Research crop improvement.', 'Conduct field research.', 'Agriculture background.', '2025-11-30', NULL, 'Fakultas Pertanian', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(7, 'Political Science Intern', 'NGO', 'Makassar', 'Internship', 2400000.00, 4000000.00, 'Analyze political data.', 'Research and report.', 'Social science degree.', '2025-12-05', NULL, 'Fakultas Ilmu Sosial dan Politik', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(8, 'Education Intern', 'School', 'Palembang', 'Internship', 2600000.00, 4200000.00, 'Assist in teaching.', 'Prepare lessons and tutor.', 'Education student.', '2025-12-10', NULL, 'Fakultas Keguruan dan Ilmu Pendidikan', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(9, 'Islamic Studies Intern', 'Religious Center', 'Pekanbaru', 'Internship', 2300000.00, 3900000.00, 'Research Islamic topics.', 'Write articles and assist events.', 'Islamic studies background.', '2025-12-15', NULL, 'Fakultas Agama Islam', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(10, 'Data Analyst Intern', 'Data Tech', 'Bali', 'Internship', 3200000.00, 5200000.00, 'Analyze data sets.', 'Clean data and create reports.', 'Statistics or computer science.', '2025-12-20', NULL, 'Fakultas Ilmu Komputer', '2025-10-26 13:09:15', '2025-10-26 13:09:15', NULL),
(12, 'Backend Developer', 'Himtika Unsika', 'Karawang, Indonesia', 'Internship', 1000000.00, 3000000.00, 'Jadi Ketua Himpunan', 'Tanggung Jawab Besar', 'Bertakwa', '2025-10-24', 'logos/01K8M2YDMRBNFPSCPDS3XS5N6F.png', 'Fakultas Ilmu Komputer', '2025-10-27 17:19:34', '2025-10-27 17:19:34', 'https://himtika.cs.unsika.ac.id/hikode/');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"c2fdc00a-5ee2-4593-bd4e-167bde3e8afe\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\UserAccount\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"42eb0256-347a-4e26-8468-1a3d86ef7d88\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761531975,\"delay\":null}', 0, NULL, 1761531975, 1761531975),
(2, 'default', '{\"uuid\":\"276625a2-09ca-4a54-9d8f-03822e693f95\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\UserAccount\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"67cbfa72-ae2a-4a54-8f5c-ad83c6b241c3\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761534957,\"delay\":null}', 0, NULL, 1761534957, 1761534957),
(3, 'default', '{\"uuid\":\"76dd167b-1c61-4138-a881-fd21e2014fd2\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\UserAccount\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"3050286b-56fd-40cc-86b6-969e83bdb429\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761534983,\"delay\":null}', 0, NULL, 1761534983, 1761534983),
(4, 'default', '{\"uuid\":\"7531f654-348f-4e55-a4ed-377ef6cb09a9\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\UserAccount\\\";s:2:\\\"id\\\";a:1:{i:0;i:4;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"229a13b4-b89b-4902-a3b4-115a0a16fe9c\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761535063,\"delay\":null}', 0, NULL, 1761535063, 1761535063),
(5, 'default', '{\"uuid\":\"7ae4900e-a21c-4137-af01-3d0ca3415465\",\"displayName\":\"App\\\\Notifications\\\\VerifyEmailNotification\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\",\"command\":\"O:48:\\\"Illuminate\\\\Notifications\\\\SendQueuedNotifications\\\":3:{s:11:\\\"notifiables\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:22:\\\"App\\\\Models\\\\UserAccount\\\";s:2:\\\"id\\\";a:1:{i:0;i:7;}s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:12:\\\"notification\\\";O:41:\\\"App\\\\Notifications\\\\VerifyEmailNotification\\\":1:{s:2:\\\"id\\\";s:36:\\\"58de2cbe-08cf-451f-becc-b46b47d09b7e\\\";}s:8:\\\"channels\\\";a:1:{i:0;s:4:\\\"mail\\\";}}\"},\"createdAt\":1761595806,\"delay\":null}', 0, NULL, 1761595806, 1761595806);

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_02_173406_create_internjobs_table', 1),
(5, '2025_10_02_193523_drop_type_from_internjobs_table', 1),
(6, '2025_10_08_151604_add_apply_url_to_internjobs_table', 1),
(7, '2025_10_09_000000_create_user_accounts_table', 1),
(8, '2025_10_09_000001_create_user_favorites_table', 1),
(9, '2025_10_26_135215_create_user_applied_table', 1),
(10, '2025_10_26_135311_add_email_verified_at_to_user_accounts_table', 1),
(11, '2025_10_27_170354_create_pending_user_accounts_table', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pending_user_accounts`
--

CREATE TABLE `pending_user_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `verification_token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pending_user_accounts`
--

INSERT INTO `pending_user_accounts` (`id`, `name`, `email`, `password`, `verification_token`, `expires_at`, `created_at`, `updated_at`) VALUES
(4, 'Test User', 'test@student.unsika.ac.id', '$2y$12$S8GaTAgct1HzDs41d3gn1eNbHoDPaVZtaFkMKMvv/8fUMywZQYL1O', 'test-token-123', '2025-10-28 11:29:11', '2025-10-27 11:29:11', '2025-10-27 11:29:11');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('LICx0fAZbGW4Keq3iEcfLrBegMK76m9DJN3jJ84U', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWmlrcWh6VkZpU0Z0SGZERGxBa0EzM0R6R3BUUGJaOHU2c05wWDRMbiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjA6ImxvZ2luX3VzZXJfYWNjb3VudHNfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMzt9', 1761626717),
('ydIOFAJXtdCv4En3aeX1xmzbPYf5c4W10JseOi1o', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTkRpU1hHZXc3NzBObW1KTVVwcjFMRTJGbUVWSlNUMDVBbEM4YzdYRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NjA6ImxvZ2luX3VzZXJfYWNjb3VudHNfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMzt9', 1761758219);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(3, 'Admin', 'admin@staff.unsika.ac.id', NULL, '$2y$12$khB/jQ3dQeX7VAJzKPpnbuJSfQws4H6qoWeUDL/vxnDwV1D4gLFki', NULL, '2025-10-27 20:10:58', '2025-10-27 20:10:58');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_accounts`
--

CREATE TABLE `user_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_accounts`
--

INSERT INTO `user_accounts` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(13, 'Rafi Catur', '2210631170086@student.unsika.ac.id', '2025-10-27 20:18:45', '$2y$12$4hxIgcCnc/yufIITH4ps/Ozw1fQyFMYuexvasdJfCAnV9Nd4kokWO', NULL, '2025-10-27 20:17:54', '2025-10-27 20:18:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_account_applied`
--

CREATE TABLE `user_account_applied` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_account_id` bigint(20) UNSIGNED NOT NULL,
  `internjob_id` bigint(20) UNSIGNED NOT NULL,
  `applied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_account_applied`
--

INSERT INTO `user_account_applied` (`id`, `user_account_id`, `internjob_id`, `applied_at`, `created_at`, `updated_at`) VALUES
(2, 13, 12, '2025-10-27 20:19:37', '2025-10-27 20:19:37', '2025-10-27 20:19:37');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_account_favorites`
--

CREATE TABLE `user_account_favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_account_id` bigint(20) UNSIGNED NOT NULL,
  `internjob_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `user_account_favorites`
--

INSERT INTO `user_account_favorites` (`id`, `user_account_id`, `internjob_id`, `created_at`, `updated_at`) VALUES
(4, 13, 12, '2025-10-27 20:18:57', '2025-10-27 20:18:57'),
(5, 13, 1, '2025-10-27 20:19:07', '2025-10-27 20:19:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `internjobs`
--
ALTER TABLE `internjobs`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pending_user_accounts`
--
ALTER TABLE `pending_user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pending_user_accounts_email_unique` (`email`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `user_accounts`
--
ALTER TABLE `user_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_accounts_email_unique` (`email`);

--
-- Indeks untuk tabel `user_account_applied`
--
ALTER TABLE `user_account_applied`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_account_applied_user_account_id_internjob_id_unique` (`user_account_id`,`internjob_id`),
  ADD KEY `user_account_applied_internjob_id_foreign` (`internjob_id`);

--
-- Indeks untuk tabel `user_account_favorites`
--
ALTER TABLE `user_account_favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_account_favorites_user_account_id_internjob_id_unique` (`user_account_id`,`internjob_id`),
  ADD KEY `user_account_favorites_internjob_id_foreign` (`internjob_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `internjobs`
--
ALTER TABLE `internjobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pending_user_accounts`
--
ALTER TABLE `pending_user_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `user_accounts`
--
ALTER TABLE `user_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `user_account_applied`
--
ALTER TABLE `user_account_applied`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user_account_favorites`
--
ALTER TABLE `user_account_favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `user_account_applied`
--
ALTER TABLE `user_account_applied`
  ADD CONSTRAINT `user_account_applied_internjob_id_foreign` FOREIGN KEY (`internjob_id`) REFERENCES `internjobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_account_applied_user_account_id_foreign` FOREIGN KEY (`user_account_id`) REFERENCES `user_accounts` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user_account_favorites`
--
ALTER TABLE `user_account_favorites`
  ADD CONSTRAINT `user_account_favorites_internjob_id_foreign` FOREIGN KEY (`internjob_id`) REFERENCES `internjobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_account_favorites_user_account_id_foreign` FOREIGN KEY (`user_account_id`) REFERENCES `user_accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
