-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 25 Mar 2026 pada 22.30
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pmb_system`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `nama`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'Administrator');

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `soal` text DEFAULT NULL,
  `opsi_a` varchar(255) DEFAULT NULL,
  `opsi_b` varchar(255) DEFAULT NULL,
  `opsi_c` varchar(255) DEFAULT NULL,
  `opsi_d` varchar(255) DEFAULT NULL,
  `jawaban_benar` char(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `questions`
--

INSERT INTO `questions` (`id`, `soal`, `opsi_a`, `opsi_b`, `opsi_c`, `opsi_d`, `jawaban_benar`, `created_at`) VALUES
(1, '1 + 1 = ?', '1', '2', '3', '4', 'B', '2026-03-22 22:33:00'),
(2, 'Warna langit di siang hari?', 'Merah', 'Kuning', 'Biru', 'Hijau', 'C', '2026-03-22 22:33:00'),
(3, 'Hewan apa yang berkokok?', 'Kucing', 'Anjing', 'Ayam', 'Bebek', 'C', '2026-03-22 22:33:00'),
(4, 'Buah apa yang berwarna merah?', 'Pisang', 'Jeruk', 'Apel', 'Anggur', 'C', '2026-03-22 22:33:00'),
(5, 'Apa ibu kota Indonesia?', 'Bandung', 'Surabaya', 'Medan', 'Jakarta', 'D', '2026-03-22 22:33:00'),
(6, 'Berapa jumlah kaki manusia?', '1', '2', '3', '4', 'B', '2026-03-22 22:33:00'),
(7, 'Kenapa langit biru?', 'Karena catnya biru', 'Karena warna kesukaan Tuhan', 'Ya emang gitu', 'Karena biru itu indah', 'C', '2026-03-22 22:33:00'),
(8, 'Hewan apa yang bisa terbang?', 'Sapi', 'Kambing', 'Burung', 'Ikan', 'C', '2026-03-22 22:33:00'),
(9, 'Air laut rasanya?', 'Manis', 'Asin', 'Pahit', 'Asam', 'B', '2026-03-22 22:33:00'),
(10, '2 + 2 = ?', '3', '4', '5', '6', 'B', '2026-03-22 22:33:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nomor_tes` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT NULL,
  `kota_kab` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jurusan` varchar(50) DEFAULT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `status_pendaftaran` enum('belum_isi','menunggu_ujian','ujian_selesai','lulus','tidak_lulus','daftar_ulang','aktif') DEFAULT 'belum_isi',
  `status_ujian` enum('belum','sedang','selesai','lulus','tidak_lulus') DEFAULT 'belum',
  `nilai` decimal(5,2) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` enum('admin','user','siswa') DEFAULT 'siswa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `nomor_tes`, `username`, `password`, `nama_lengkap`, `nisn`, `jenis_kelamin`, `provinsi`, `kota_kab`, `tanggal_lahir`, `jurusan`, `asal_sekolah`, `alamat`, `foto`, `status_pendaftaran`, `status_ujian`, `nilai`, `nim`, `created_at`, `updated_at`, `role`) VALUES
(1, NULL, 'testuser', 'e10adc3949ba59abbe56e057f20f883e', 'Test User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'belum_isi', 'belum', NULL, NULL, '2026-03-22 22:28:20', '2026-03-22 22:28:20', 'siswa'),
(3, 'TEST202603230003', 'azmi', '6759a741187d18e153df3fe80bad07e0', 'azmi', '0078184434', 'P', 'DKI Jakarta', 'jakarta timur', '2007-07-26', 'Sistem Informasi', 'smkn 65', 'tebet', 'uploads/1774220472_3.png', 'lulus', 'lulus', 100.00, 'SGA20260003', '2026-03-22 22:43:26', '2026-03-25 20:25:45', 'siswa'),
(4, 'TEST202603230004', 'sehan', '1fb39d94d7a90a8c5070bc833e5b6718', 'sehan', '0078184434', 'L', 'DKI Jakarta', 'jakarta timur', '2007-10-11', 'Teknologi Informasi', 'smkn 65', 'tebet\r\n', NULL, 'tidak_lulus', 'tidak_lulus', 40.00, NULL, '2026-03-22 23:03:45', '2026-03-25 20:25:45', 'siswa'),
(5, 'TEST202603230005', 'paris', 'ccbee73cd81c7f42405e1920409247ec', 'paris', '0078184434', 'L', 'DKI Jakarta', 'jakarta timur', '2007-11-23', 'Sistem Informasi', 'smkn 65', 'tebet\r\n', 'uploads/1774472907_5.png', 'aktif', 'lulus', 90.00, 'SGA20260005', '2026-03-22 23:35:02', '2026-03-25 21:08:27', 'siswa'),
(10, 'TEST202603260010', 'alpi', '344bf6569e003504550076142a90e863', 'alpi', '07835629', 'L', 'DKI Jakarta', 'jakarta timur', '2009-04-03', 'Teknik Komputer', 'smkn 65', 'tebet\r\n', NULL, 'lulus', 'selesai', 90.00, NULL, '2026-03-25 20:31:58', '2026-03-25 20:35:36', 'siswa'),
(12, 'TEST202603260012', 'pahri', '82a812108d997b6b55bd8561dd5eb9e2', 'pahri', '087263820', 'L', 'DKI Jakarta', 'jakarta timur', '2008-05-12', 'Manajemen Informatika', 'smkn 65', 'tebet', NULL, 'tidak_lulus', 'selesai', 30.00, NULL, '2026-03-25 21:04:56', '2026-03-25 21:06:07', 'siswa');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `jawaban` char(1) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user_answers`
--

INSERT INTO `user_answers` (`id`, `user_id`, `question_id`, `jawaban`, `is_correct`, `created_at`) VALUES
(11, 3, 1, 'B', 1, '2026-03-22 22:48:17'),
(12, 3, 2, 'C', 1, '2026-03-22 22:48:17'),
(13, 3, 3, 'C', 1, '2026-03-22 22:48:17'),
(14, 3, 4, 'C', 1, '2026-03-22 22:48:17'),
(15, 3, 5, 'D', 1, '2026-03-22 22:48:17'),
(16, 3, 6, 'B', 1, '2026-03-22 22:48:17'),
(17, 3, 7, 'C', 1, '2026-03-22 22:48:17'),
(18, 3, 8, 'C', 1, '2026-03-22 22:48:17'),
(19, 3, 9, 'B', 1, '2026-03-22 22:48:17'),
(20, 3, 10, 'B', 1, '2026-03-22 22:48:17'),
(21, 4, 1, 'B', 1, '2026-03-22 23:13:09'),
(22, 4, 2, 'C', 1, '2026-03-22 23:13:09'),
(23, 4, 3, 'B', 0, '2026-03-22 23:13:09'),
(24, 4, 4, 'C', 1, '2026-03-22 23:13:09'),
(25, 4, 5, 'D', 1, '2026-03-22 23:13:09'),
(26, 4, 6, 'A', 0, '2026-03-22 23:13:09'),
(27, 4, 7, 'D', 0, '2026-03-22 23:13:09'),
(28, 4, 8, 'B', 0, '2026-03-22 23:13:09'),
(29, 4, 9, 'C', 0, '2026-03-22 23:13:09'),
(30, 4, 10, 'C', 0, '2026-03-22 23:13:09'),
(31, 5, 1, 'B', 1, '2026-03-22 23:36:29'),
(32, 5, 2, 'C', 1, '2026-03-22 23:36:29'),
(33, 5, 3, 'C', 1, '2026-03-22 23:36:29'),
(34, 5, 4, 'C', 1, '2026-03-22 23:36:29'),
(35, 5, 5, 'D', 1, '2026-03-22 23:36:29'),
(36, 5, 6, 'B', 1, '2026-03-22 23:36:29'),
(37, 5, 7, 'B', 0, '2026-03-22 23:36:29'),
(38, 5, 8, 'C', 1, '2026-03-22 23:36:29'),
(39, 5, 9, 'B', 1, '2026-03-22 23:36:29'),
(40, 5, 10, 'B', 1, '2026-03-22 23:36:29'),
(41, 10, 1, 'B', 1, '2026-03-25 20:35:36'),
(42, 10, 2, 'C', 1, '2026-03-25 20:35:36'),
(43, 10, 3, 'C', 1, '2026-03-25 20:35:36'),
(44, 10, 4, 'A', 0, '2026-03-25 20:35:36'),
(45, 10, 5, 'D', 1, '2026-03-25 20:35:36'),
(46, 10, 6, 'B', 1, '2026-03-25 20:35:36'),
(47, 10, 7, 'C', 1, '2026-03-25 20:35:36'),
(48, 10, 8, 'C', 1, '2026-03-25 20:35:36'),
(49, 10, 9, 'B', 1, '2026-03-25 20:35:36'),
(50, 10, 10, 'B', 1, '2026-03-25 20:35:36'),
(61, 12, 1, 'A', 0, '2026-03-25 21:06:07'),
(62, 12, 2, 'A', 0, '2026-03-25 21:06:07'),
(63, 12, 3, 'B', 0, '2026-03-25 21:06:07'),
(64, 12, 4, 'B', 0, '2026-03-25 21:06:07'),
(65, 12, 5, 'A', 0, '2026-03-25 21:06:07'),
(66, 12, 6, 'B', 1, '2026-03-25 21:06:07'),
(67, 12, 7, 'C', 1, '2026-03-25 21:06:07'),
(68, 12, 8, 'C', 1, '2026-03-25 21:06:07'),
(69, 12, 9, 'C', 0, '2026-03-25 21:06:07'),
(70, 12, 10, 'D', 0, '2026-03-25 21:06:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_tes` (`nomor_tes`);

--
-- Indeks untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `question_id` (`question_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
