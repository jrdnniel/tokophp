-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 17 Nov 2026
-- Versi server: 10.4.32-MariaDB / MySQL 8.0
-- Versi PHP: 8.2.12
--
-- =========================================================
-- DATABASE TOKO 33 (TOKO KELONTONG)
-- =========================================================
-- Akun Login Default:
--   1. Admin        : admin@toko33.com        | Password: admin123
--   2. Front Office : andi@toko33.com         | Password: fo123
--                     siti@toko33.com         | Password: fo123
--   3. Customer     : budi@gmail.com          | Password: cust123
--                     siti.aisyah@gmail.com   | Password: cust123
-- =========================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `db_toko33` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_toko33`;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `kritik_saran`, `detail_transaksi`, `transaksi`, `pengajuan_perubahan`, `produk`, `users`, `akun`;
SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------
-- Struktur dari tabel `akun` (Admin & Front Office)
-- --------------------------------------------------------

CREATE TABLE `akun` (
  `id_akun` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','fo') DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_telp` varchar(100) DEFAULT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `alamat` text,
  `foto_profile` varchar(255) DEFAULT NULL,
  `hari_kerja_mulai` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') DEFAULT NULL,
  `hari_kerja_selesai` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_akun`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `akun` (`id_akun`, `nama`, `email`, `password`, `role`, `tanggal_lahir`, `no_telp`, `gender`, `alamat`, `foto_profile`, `hari_kerja_mulai`, `hari_kerja_selesai`, `jam_mulai`, `jam_selesai`, `created_at`) VALUES
(1, 'Admin Toko 33', 'admin@toko33.com', '$2y$10$6YolCOBOxmPeQ1l9dzknj.3nZNyKRRC8Vzvz01THMyth3HiFi8HWC', 'admin', '2000-05-15', '081234567890', 'L', 'Jl. Medayu Selatan XV No.33, Surabaya', 'default-profile.png', 'senin', 'sabtu', '08:00:00', '17:00:00', '2026-10-01 08:00:00'),
(2, 'Andi Pratama', 'andi@toko33.com', '$2y$10$yO5.3HxRsFDhZhT2qB9.QOXR00FQv0CGc9ZUnTvyPTHkK7V2PR/xC', 'fo', '2003-04-12', '081298765432', 'L', 'Jl. Rungkut Madya No. 14, Surabaya', 'default-profile.png', 'senin', 'jumat', '08:00:00', '16:00:00', '2026-10-02 08:00:00'),
(3, 'Siti Rahma', 'siti@toko33.com', '$2y$10$yO5.3HxRsFDhZhT2qB9.QOXR00FQv0CGc9ZUnTvyPTHkK7V2PR/xC', 'fo', '2004-08-21', '081345678912', 'P', 'Jl. Gunung Anyar Mas No. 7, Surabaya', 'default-profile.png', 'senin', 'sabtu', '09:00:00', '17:00:00', '2026-10-03 08:00:00'),
(4, 'Budi Hartono', 'budi.fo@toko33.com', '$2y$10$yO5.3HxRsFDhZhT2qB9.QOXR00FQv0CGc9ZUnTvyPTHkK7V2PR/xC', 'fo', '2002-11-05', '085712345678', 'L', 'Jl. Medokan Ayu No. 45, Surabaya', 'default-profile.png', 'selasa', 'minggu', '13:00:00', '21:00:00', '2026-10-05 08:00:00');

-- --------------------------------------------------------
-- Struktur dari tabel `users` (Customer)
-- --------------------------------------------------------

CREATE TABLE `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `no_telp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `alamat` text,
  `tanggal_lahir` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `foto_profile` varchar(255) DEFAULT NULL,
  `gender` enum('L','P') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id_user`, `nama`, `no_telp`, `email`, `alamat`, `tanggal_lahir`, `password`, `foto_profile`, `gender`, `created_at`) VALUES
(1, 'Budi Santoso', '081234567890', 'budi@gmail.com', 'Jl. Medayu Selatan No. 12, Surabaya', '2003-03-10', '$2y$10$nK3jcET4xRAQhD7/EcOdDeOOy5LdSrfsEW/LO043XQooFAte.agfW', 'default-profile.png', 'L', '2026-10-10 09:15:00'),
(2, 'Siti Aisyah', '081398765432', 'siti.aisyah@gmail.com', 'Jl. Rungkut Asri No. 8, Surabaya', '2004-07-22', '$2y$10$nK3jcET4xRAQhD7/EcOdDeOOy5LdSrfsEW/LO043XQooFAte.agfW', 'default-profile.png', 'P', '2026-10-12 10:30:00'),
(3, 'Andi Wijaya', '082145678901', 'andi.wijaya@gmail.com', 'Jl. Gunung Anyar Jaya No. 21, Surabaya', '2002-12-01', '$2y$10$nK3jcET4xRAQhD7/EcOdDeOOy5LdSrfsEW/LO043XQooFAte.agfW', 'default-profile.png', 'L', '2026-10-15 14:20:00');

-- --------------------------------------------------------
-- Struktur dari tabel `produk`
-- --------------------------------------------------------

CREATE TABLE `produk` (
  `id_produk` int NOT NULL AUTO_INCREMENT,
  `kode_produk` varchar(50) DEFAULT NULL,
  `nama_produk` varchar(100) DEFAULT NULL,
  `kategori` enum('homecare','minuman') DEFAULT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  `stok` int DEFAULT 0,
  `stok_minimum` int DEFAULT 10,
  `tanggal_exp` date DEFAULT NULL,
  `lacak_exp` tinyint(1) DEFAULT 1,
  `deskripsi` text,
  `foto_produk` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id_produk`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `produk` (`id_produk`, `kode_produk`, `nama_produk`, `kategori`, `harga`, `stok`, `stok_minimum`, `tanggal_exp`, `lacak_exp`, `deskripsi`, `foto_produk`) VALUES
(1, 'HC-001', 'Wipol Karbol', 'homecare', 14000.00, 120, 15, '2026-12-10', 1, '750 ml - Karbol wangi cemara pembunuh kuman untuk lantai bersih kesat.', 'wipol.png'),
(2, 'HC-002', 'Sunlight', 'homecare', 15000.00, 85, 20, '2026-11-12', 1, '210 ml - Sabun cuci piring ekstrak jeruk nipis penghilang lemak membandel.', 'sunlight.png'),
(3, 'HC-003', 'So Klin', 'homecare', 31500.00, 40, 15, '2027-01-20', 1, '1,3 kg - Deterjen bubuk konsentrat pembersih noda pakaian.', 'soklin.png'),
(4, 'HC-004', 'Molto', 'homecare', 16500.00, 12, 15, '2027-01-23', 1, '765 ml - Pewangi dan pelembut pakaian tahan lama sepanjang hari.', 'molto.png'),
(5, 'HC-005', 'Harpic', 'homecare', 22000.00, 80, 10, '2027-01-09', 1, '450 ml - Pembersih kloset penghilang kerak dan kuman.', 'harpic.png'),
(6, 'MNM-001', 'Floridina', 'minuman', 4000.00, 200, 24, '2026-12-10', 1, '350 ml - Minuman jeruk dengan bulir jeruk asli yang menyegarkan.', 'floridina.png'),
(7, 'MNM-002', 'Teh Pucuk Harum', 'minuman', 4000.00, 200, 24, '2027-01-20', 1, '350 ml - Minuman teh melati segar dari pucuk daun teh pilihan.', 'tehpucuk.png'),
(8, 'MNM-003', 'Golda Coffee', 'minuman', 5000.00, 64, 20, '2026-12-05', 1, '200 ml - Kopi susu dolce latte dari biji kopi Brazil pilihan.', 'golda.png'),
(9, 'MNM-004', 'Pocari Sweat', 'minuman', 6000.00, 8, 24, '2027-03-15', 1, '350 ml - Minuman isotonik pengganti cairan dan ion tubuh.', 'pocarisweat.png'),
(10, 'MNM-005', 'Bear Brand', 'minuman', 10000.00, 18, 30, '2026-11-28', 1, '189 ml - Susu steril murni 100% berkualitas tinggi.', 'bearbrand.png');

-- --------------------------------------------------------
-- Struktur dari tabel `transaksi`
-- --------------------------------------------------------

CREATE TABLE `transaksi` (
  `id_transaksi` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `id_fo` int DEFAULT NULL,
  `tanggal_masuk` datetime DEFAULT NULL,
  `expired_at` datetime DEFAULT NULL,
  `tanggal_diproses` datetime DEFAULT NULL,
  `tanggal_selesai` datetime DEFAULT NULL,
  `tanggal_ditolak` datetime DEFAULT NULL,
  `status` enum('menunggu_pembayaran','menunggu_diproses','diproses','selesai','ditolak','expired') DEFAULT NULL,
  `total_pembayaran` decimal(12,2) DEFAULT NULL,
  `metode_pembayaran` enum('Cash','Transfer','QRIS') DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_transaksi`),
  KEY `transaksi_ibfk_1` (`id_user`),
  KEY `transaksi_ibfk_2` (`id_fo`),
  CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_fo`) REFERENCES `akun` (`id_akun`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `nama_customer`, `id_fo`, `tanggal_masuk`, `expired_at`, `tanggal_diproses`, `tanggal_selesai`, `tanggal_ditolak`, `status`, `total_pembayaran`, `metode_pembayaran`, `bukti_transfer`) VALUES
(1, 1, 'Budi Santoso', 2, '2026-11-15 09:30:00', NULL, '2026-11-15 09:35:00', '2026-11-15 09:50:00', NULL, 'selesai', 36000.00, 'Transfer', NULL),
(2, 2, 'Siti Aisyah', 2, '2026-11-16 14:10:00', NULL, '2026-11-16 14:15:00', '2026-11-16 14:30:00', NULL, 'selesai', 49500.00, 'Cash', NULL),
(3, 1, 'Budi Santoso', 2, '2026-11-17 08:15:00', NULL, '2026-11-17 08:20:00', NULL, NULL, 'diproses', 28000.00, 'Transfer', NULL),
(4, 3, 'Andi Wijaya', NULL, '2026-11-17 08:45:00', NULL, NULL, NULL, NULL, 'menunggu_diproses', 30000.00, 'Transfer', NULL);

-- --------------------------------------------------------
-- Struktur dari tabel `detail_transaksi`
-- --------------------------------------------------------

CREATE TABLE `detail_transaksi` (
  `id_detail` int NOT NULL AUTO_INCREMENT,
  `id_transaksi` int DEFAULT NULL,
  `id_produk` int DEFAULT NULL,
  `jumlah` int DEFAULT NULL,
  `harga_satuan` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  PRIMARY KEY (`id_detail`),
  KEY `detail_transaksi_ibfk_1` (`id_transaksi`),
  KEY `detail_transaksi_ibfk_2` (`id_produk`),
  CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`id_transaksi`) REFERENCES `transaksi` (`id_transaksi`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`id_produk`) REFERENCES `produk` (`id_produk`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `detail_transaksi` (`id_detail`, `id_transaksi`, `id_produk`, `jumlah`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 1, 2, 14000.00, 28000.00),
(2, 1, 7, 2, 4000.00, 8000.00),
(3, 2, 3, 1, 31500.00, 31500.00),
(4, 2, 9, 3, 6000.00, 18000.00),
(5, 3, 2, 1, 15000.00, 15000.00),
(6, 3, 8, 1, 5000.00, 5000.00),
(7, 3, 6, 2, 4000.00, 8000.00),
(8, 4, 10, 2, 10000.00, 20000.00),
(9, 4, 8, 2, 5000.00, 10000.00);

-- --------------------------------------------------------
-- Struktur dari tabel `pengajuan_perubahan`
-- --------------------------------------------------------

CREATE TABLE `pengajuan_perubahan` (
  `id_pengajuan` int NOT NULL AUTO_INCREMENT,
  `id_akun` int DEFAULT NULL,
  `nama_lama` varchar(100) DEFAULT NULL,
  `nama_baru` varchar(100) DEFAULT NULL,
  `no_telp_lama` varchar(20) DEFAULT NULL,
  `no_telp_baru` varchar(20) DEFAULT NULL,
  `alamat_lama` text,
  `alamat_baru` text,
  `gender_lama` enum('L','P') DEFAULT NULL,
  `gender_baru` enum('L','P') DEFAULT NULL,
  `jam_mulai_lama` time DEFAULT NULL,
  `jam_selesai_lama` time DEFAULT NULL,
  `jam_mulai_baru` time DEFAULT NULL,
  `jam_selesai_baru` time DEFAULT NULL,
  `hari_kerja_mulai` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') DEFAULT NULL,
  `hari_kerja_selesai` enum('senin','selasa','rabu','kamis','jumat','sabtu','minggu') DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengajuan`),
  KEY `pengajuan_perubahan_ibfk_1` (`id_akun`),
  CONSTRAINT `pengajuan_perubahan_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `pengajuan_perubahan` (`id_pengajuan`, `id_akun`, `nama_lama`, `nama_baru`, `no_telp_lama`, `no_telp_baru`, `alamat_lama`, `alamat_baru`, `gender_lama`, `gender_baru`, `jam_mulai_lama`, `jam_selesai_lama`, `jam_mulai_baru`, `jam_selesai_baru`, `hari_kerja_mulai`, `hari_kerja_selesai`, `status`, `created_at`) VALUES
(1, 2, 'Andi Pratama', 'Andi Pratama Putra', '081298765432', '081298769999', 'Jl. Rungkut Madya No. 14, Surabaya', 'Jl. Rungkut Madya No. 28, Surabaya', 'L', 'L', '08:00:00', '16:00:00', '09:00:00', '17:00:00', 'senin', 'sabtu', 'pending', '2026-11-16 10:00:00');

-- --------------------------------------------------------
-- Struktur dari tabel `kritik_saran`
-- --------------------------------------------------------

CREATE TABLE `kritik_saran` (
  `id_kritik` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `id_transaksi` varchar(50) DEFAULT NULL,
  `kategori` varchar(100) DEFAULT NULL,
  `rating` int DEFAULT 5,
  `jenis` enum('saran','keluhan','pujian') DEFAULT 'saran',
  `pesan` text,
  `balasan_admin` text,
  `status` enum('belum_dibalas','dibalas') DEFAULT 'belum_dibalas',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tanggal_balas` datetime DEFAULT NULL,
  PRIMARY KEY (`id_kritik`),
  KEY `kritik_saran_ibfk_1` (`id_user`),
  CONSTRAINT `kritik_saran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `kritik_saran` (`id_kritik`, `id_user`, `id_transaksi`, `kategori`, `rating`, `jenis`, `pesan`, `balasan_admin`, `status`, `created_at`, `tanggal_balas`) VALUES
(1, 1, 'TRX-0001', 'Pelayanan Tokoh / Kasir', 5, 'pujian', 'Pelayanan kasir sangat ramah, barang dikemas rapi dan produk minuman dingin semua. Terima kasih Toko 33!', 'Terima kasih banyak Kak Budi atas ulasan positifnya! Kami tunggu belanja berikutnya di Toko 33.', 'dibalas', '2026-11-15 11:00:00', '2026-11-15 13:20:00'),
(2, 2, 'TRX-0002', 'Kelengkapan Stok Produk', 4, 'saran', 'Mohon stok Pocari Sweat dan Bear Brand ditambah lagi karena sering cepat habis saat akhir pekan.', NULL, 'belum_dibalas', '2026-11-16 16:00:00', NULL);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
