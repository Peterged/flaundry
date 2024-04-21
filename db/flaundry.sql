-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 21 Apr 2024 pada 14.36
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
-- Database: `laundry`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_detail_transaksi`
--

CREATE TABLE `tb_detail_transaksi` (
  `id` int(11) NOT NULL,
  `id_transaksi` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `qty` double NOT NULL,
  `keterangan` text NOT NULL,
  `total_harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_detail_transaksi`
--

INSERT INTO `tb_detail_transaksi` (`id`, `id_transaksi`, `id_paket`, `qty`, `keterangan`, `total_harga`) VALUES
(32, 106, 93, 6, '', 150000),
(48, 108, 112, 15, '', 52500),
(49, 108, 104, 5, '', 12500),
(51, 110, 116, 100, '', 9000000),
(55, 113, 104, 4, '', 10000),
(56, 113, 104, 5, '', 12500),
(57, 114, 98, 15, '', 75000),
(58, 115, 98, 5, '', 25000),
(59, 115, 93, 5, '', 125000),
(60, 116, 100, 15, '', 270000),
(61, 117, 109, 12, '', 360000),
(63, 119, 115, 3, '', 150000),
(64, 120, 104, 25, '', 62500),
(77, 130, 99, 15, '', 150000),
(78, 131, 104, 5, '', 12500),
(79, 132, 119, 1, 'Akun Koyta', 11000000),
(80, 134, 93, 5, '', 125000),
(81, 137, 101, 5, '', 125000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_member`
--

CREATE TABLE `tb_member` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tlp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_member`
--

INSERT INTO `tb_member` (`id`, `nama`, `alamat`, `jenis_kelamin`, `tlp`) VALUES
(1, 'I Kadek Robert Dananjaya', 'Jln. Robertos', 'L', '724389579'),
(2, 'Ida Bagus Yoga Dharma Putra ', 'Jl. David Gadgetin', 'L', '3214231542312'),
(8, 'Anak Agung Gede Bagus Abi Wiguna', 'Jl. Anak Agung', 'L', '524353245');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_outlet`
--

CREATE TABLE `tb_outlet` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `alamat` text NOT NULL,
  `tlp` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_outlet`
--

INSERT INTO `tb_outlet` (`id`, `nama`, `alamat`, `tlp`) VALUES
(1, 'PT. FLaundry Washington DC', 'Jl. Raya Sesetan, Gg. Batu Emas', '08132144321'),
(2, 'PT. FLaundry Surabaya', 'Jl. Stasiun Wonokromo', '043215652325'),
(3, 'PT. FLaundry Denpasar', 'Jl. Raya Sesetan, No. 224', '0836123123'),
(74, 'PT. FLaundry Surabaya', 'dsafasfsd', '1233123123');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_paket`
--

CREATE TABLE `tb_paket` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `jenis` enum('kiloan','selimut','bed_cover','kaos','lain') NOT NULL,
  `nama_paket` varchar(100) NOT NULL,
  `harga` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_paket`
--

INSERT INTO `tb_paket` (`id`, `id_outlet`, `jenis`, `nama_paket`, `harga`) VALUES
(93, 1, 'bed_cover', 'Bed Cover Tipis', 25000),
(94, 3, 'kaos', 'Batang Hari Jas', 75000),
(98, 1, 'kiloan', 'Kiloan 0.5KG', 5000),
(99, 1, 'kiloan', 'Kiloan 1KG', 10000),
(100, 1, 'kiloan', 'Kiloan 2KG', 18000),
(101, 1, 'kiloan', 'Kiloan 3KG', 25000),
(103, 1, 'kiloan', 'Kiloan 5KG', 38000),
(104, 1, 'kaos', 'Kaos Polos', 2500),
(105, 1, 'lain', 'Satuan - Kemeja', 5000),
(106, 1, 'lain', 'Satuan - Celana Panjang', 5000),
(107, 1, 'lain', 'Satuan - Gaun', 10000),
(108, 1, 'lain', 'Satuan - Jas', 7000),
(109, 1, 'bed_cover', 'Satuan - Bedcover', 30000),
(110, 1, 'selimut', 'Satuan - Selimut', 25000),
(111, 1, 'lain', 'Satuan - Sprei', 20000),
(112, 1, 'lain', 'Satuan - Celana Pendek', 3500),
(113, 1, 'lain', 'Paket - Cuci dan Setrika Sepatu (1 pasang)', 25000),
(114, 1, 'lain', 'Paket - Cuci dan Setrika Jas + Celana', 12000),
(115, 1, 'lain', 'Paket - Cuci dan Setrika Bedcover + Sprei + 2 Sarung Bantal', 50000),
(116, 1, 'lain', 'Paket - Cuci dan Setrika 10 Kg Pakaian', 90000),
(117, 1, 'selimut', 'Selimut Tebal', 17500),
(118, 2, 'kiloan', 'Testing', 50000),
(119, 1, 'lain', 'Doxxing + Phishing', 11000000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `kode_invoice` varchar(100) NOT NULL,
  `id_member` int(11) NOT NULL,
  `tgl` datetime NOT NULL,
  `batas_waktu` datetime NOT NULL,
  `tgl_bayar` datetime DEFAULT NULL,
  `biaya_tambahan` double NOT NULL,
  `diskon` double NOT NULL,
  `pajak` double NOT NULL,
  `status` enum('baru','proses','selesai','diambil') NOT NULL,
  `dibayar` enum('dibayar','belum_dibayar') NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_transaksi`
--

INSERT INTO `tb_transaksi` (`id`, `id_outlet`, `kode_invoice`, `id_member`, `tgl`, `batas_waktu`, `tgl_bayar`, `biaya_tambahan`, `diskon`, `pajak`, `status`, `dibayar`, `id_user`) VALUES
(106, 1, 'INV/2024/03/12/4', 1, '2024-03-13 00:20:40', '2024-03-16 00:20:40', '2024-03-13 00:21:11', 15000, 0, 0.0075, 'selesai', 'dibayar', 171),
(108, 1, 'INV/2024/03/16/6', 1, '2024-03-18 14:53:02', '2024-03-19 14:53:02', '2024-03-16 14:54:19', 0, 0, 0.0075, 'selesai', 'dibayar', 171),
(110, 1, 'INV/2024/03/16/8', 1, '2024-03-17 15:04:23', '2024-03-20 15:04:23', '2024-03-17 15:04:32', 0, 0.1, 0.0075, 'baru', 'dibayar', 171),
(113, 1, 'INV/2024/03/19/10', 1, '2024-03-19 14:01:48', '2024-03-22 14:01:48', '2024-03-19 14:02:01', 1000, 0, 0.0075, 'baru', 'dibayar', 171),
(114, 1, 'INV/2024/03/23/11', 1, '2024-03-24 14:11:16', '2024-03-26 14:21:16', '2024-03-23 14:21:22', 0, 0.1, 0.0075, 'baru', 'dibayar', 171),
(115, 1, 'INV/2024/03/24/12', 1, '2024-03-30 14:05:23', '2024-03-27 14:05:23', '2024-03-24 14:05:40', 0, 0, 0.0075, 'proses', 'dibayar', 171),
(116, 1, 'INV/2024/03/24/13', 1, '2024-03-29 14:09:33', '2024-03-27 14:09:33', '2024-03-24 14:09:38', 0, 0, 0.0075, 'selesai', 'dibayar', 171),
(117, 1, 'INV/2024/03/24/14', 2, '2024-03-28 14:09:42', '2024-03-27 14:09:42', '2024-03-24 14:09:47', 0, 0, 0.0075, 'proses', 'dibayar', 171),
(119, 1, 'INV/2024/03/24/16', 1, '2024-03-21 14:09:58', '2024-03-27 14:09:58', '2024-03-24 14:10:03', 0, 0.1, 0.0075, 'baru', 'dibayar', 171),
(120, 1, 'INV/2024/03/24/17', 1, '2024-03-22 14:10:05', '2024-03-27 14:10:05', '2024-03-24 14:10:11', 0, 0, 0.0075, 'baru', 'dibayar', 171),
(130, 1, 'INV/2024/03/27/18', 1, '2024-03-27 20:47:29', '2024-03-30 20:47:29', '2024-03-27 20:47:35', 0, 0, 0.0075, 'baru', 'dibayar', 171),
(131, 1, 'INV/2024/03/28/19', 1, '2024-03-28 18:59:14', '2024-03-31 18:59:14', NULL, 0, 0.1, 0.0075, 'baru', 'belum_dibayar', 171),
(132, 1, 'INV/2024/03/29/20', 2, '2024-03-29 12:28:00', '2024-04-01 12:28:00', '2024-03-29 12:28:26', 500000, 0, 0.0075, 'proses', 'dibayar', 171),
(134, 1, 'INV/2024/03/29/22', 1, '2024-03-29 17:36:42', '2024-04-01 17:36:42', '2024-03-29 17:37:34', 5000, 0, 0.0075, 'selesai', 'dibayar', 171),
(137, 1, 'INV/2024/04/20/3', 1, '2024-04-20 14:37:42', '2024-04-23 14:37:42', '2024-04-20 15:37:24', 0, 0, 0.0075, 'proses', 'dibayar', 171);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id` int(11) NOT NULL,
  `id_outlet` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` text NOT NULL,
  `role` enum('admin','kasir','owner') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id`, `id_outlet`, `nama`, `username`, `password`, `role`) VALUES
(171, 1, 'admin', 'admin', '$2y$10$0LYmdQOLty9yKrlgDXxBG.V3HlhIHEfGDTedgEhPbdlQI/7xKUA9m', 'admin'),
(174, 1, 'Robert Dananjaya', 'robert', '$2y$10$hybnNMb6bOkcQXDUbkodHeQgeey/5P3uvB2hJbrCAMPR5nOuIc5Jq', 'admin'),
(181, 3, 'I Putu Adik Kreshna Dhana', 'kreshna', '$2y$10$7KDu2vq9Dvekyas7UZ6nbOc1LSu/.FS.JuZYQC1iekn8qhzntU/aS', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_DetailTransaksi_IdTransaksi_Transaksi_Id` (`id_transaksi`),
  ADD KEY `FK_DetailTransaksi_IdPaket_Paket_Id` (`id_paket`);

--
-- Indeks untuk tabel `tb_member`
--
ALTER TABLE `tb_member`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_outlet`
--
ALTER TABLE `tb_outlet`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Paket_IdOutlet_Outlet_Id` (`id_outlet`);

--
-- Indeks untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_Transaksi_IdMember_Member_Id` (`id_member`),
  ADD KEY `FK_Transaksi_IdOutlet_Outlet_Id` (`id_outlet`),
  ADD KEY `FK_Transaksi_IdUser_User_Id` (`id_user`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `FK_User_IdOutlet_Outlet_Id` (`id_outlet`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT untuk tabel `tb_member`
--
ALTER TABLE `tb_member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `tb_outlet`
--
ALTER TABLE `tb_outlet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=183;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `tb_detail_transaksi`
--
ALTER TABLE `tb_detail_transaksi`
  ADD CONSTRAINT `FK_DetailTransaksi_IdPaket_Paket_Id` FOREIGN KEY (`id_paket`) REFERENCES `tb_paket` (`id`),
  ADD CONSTRAINT `FK_DetailTransaksi_IdTransaksi_Transaksi_Id` FOREIGN KEY (`id_transaksi`) REFERENCES `tb_transaksi` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `tb_paket`
--
ALTER TABLE `tb_paket`
  ADD CONSTRAINT `FK_Paket_IdOutlet_Outlet_Id` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD CONSTRAINT `FK_Transaksi_IdMember_Member_Id` FOREIGN KEY (`id_member`) REFERENCES `tb_member` (`id`),
  ADD CONSTRAINT `FK_Transaksi_IdOutlet_Outlet_Id` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`),
  ADD CONSTRAINT `FK_Transaksi_IdUser_User_Id` FOREIGN KEY (`id_user`) REFERENCES `tb_user` (`id`);

--
-- Ketidakleluasaan untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD CONSTRAINT `FK_User_IdOutlet_Outlet_Id` FOREIGN KEY (`id_outlet`) REFERENCES `tb_outlet` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
