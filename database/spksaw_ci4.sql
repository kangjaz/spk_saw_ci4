-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2021 at 10:52 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spksaw_ci4`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_alternative`
--

CREATE TABLE `tbl_alternative` (
  `id_alternative` int(11) NOT NULL,
  `kode_alternative` varchar(5) NOT NULL,
  `nama_alternative` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_alternative`
--

INSERT INTO `tbl_alternative` (`id_alternative`, `kode_alternative`, `nama_alternative`) VALUES
(2, 'A1', 'Alternatif 1'),
(3, 'A2', 'Alternatif 2');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bobot`
--

CREATE TABLE `tbl_bobot` (
  `id_kriteria` int(11) NOT NULL,
  `id_sub_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_bobot`
--

INSERT INTO `tbl_bobot` (`id_kriteria`, `id_sub_kriteria`) VALUES
(1, 11),
(2, 12),
(3, 15);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_hasil`
--

CREATE TABLE `tbl_hasil` (
  `id_alternative` int(11) NOT NULL,
  `hasil` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_hasil`
--

INSERT INTO `tbl_hasil` (`id_alternative`, `hasil`) VALUES
(2, 4),
(3, 5.8);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kriteria`
--

CREATE TABLE `tbl_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `kode_kriteria` varchar(5) NOT NULL,
  `judul_kriteria` varchar(255) NOT NULL,
  `sifat` enum('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_kriteria`
--

INSERT INTO `tbl_kriteria` (`id_kriteria`, `kode_kriteria`, `judul_kriteria`, `sifat`) VALUES
(1, 'C1', 'Bahan', 'benefit'),
(2, 'C2', 'Harga', 'cost'),
(3, 'C3', 'Kualitas', 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penilaian`
--

CREATE TABLE `tbl_penilaian` (
  `id_alternative` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `id_sub_kriteria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_penilaian`
--

INSERT INTO `tbl_penilaian` (`id_alternative`, `id_kriteria`, `id_sub_kriteria`) VALUES
(3, 1, 11),
(3, 2, 13),
(2, 1, 10),
(2, 2, 12);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sub_kriteria`
--

CREATE TABLE `tbl_sub_kriteria` (
  `id_sub_kriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nilai` float NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_sub_kriteria`
--

INSERT INTO `tbl_sub_kriteria` (`id_sub_kriteria`, `id_kriteria`, `nilai`, `keterangan`) VALUES
(10, 1, 2, 'Buruk'),
(11, 1, 5, 'Bagus'),
(12, 2, 2, 'Biaya kurang dari 100.000'),
(13, 2, 5, 'Lebih dari 100.000'),
(14, 3, 2, 'Buruk'),
(15, 3, 5, 'Baik');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(35) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `fullname`, `password`, `role`) VALUES
(1, 'admin', 'Administrator', '$2y$08$FMeYhEo9DuFh/eUtmbNBfOqU9hGo/yhT1HJEMXmtbfsNR5nnbitEe', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_alternative`
--
ALTER TABLE `tbl_alternative`
  ADD PRIMARY KEY (`id_alternative`);

--
-- Indexes for table `tbl_bobot`
--
ALTER TABLE `tbl_bobot`
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `id_sub_kriteria` (`id_sub_kriteria`);

--
-- Indexes for table `tbl_hasil`
--
ALTER TABLE `tbl_hasil`
  ADD KEY `id_alternative` (`id_alternative`);

--
-- Indexes for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD KEY `id_alternatif` (`id_alternative`),
  ADD KEY `id_kriteria` (`id_kriteria`),
  ADD KEY `id_sub_kriteria` (`id_sub_kriteria`);

--
-- Indexes for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  ADD PRIMARY KEY (`id_sub_kriteria`),
  ADD KEY `id_kriteria` (`id_kriteria`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_alternative`
--
ALTER TABLE `tbl_alternative`
  MODIFY `id_alternative` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_kriteria`
--
ALTER TABLE `tbl_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  MODIFY `id_sub_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_bobot`
--
ALTER TABLE `tbl_bobot`
  ADD CONSTRAINT `tbl_bobot_ibfk_1` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `tbl_sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_bobot_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_hasil`
--
ALTER TABLE `tbl_hasil`
  ADD CONSTRAINT `tbl_hasil_ibfk_1` FOREIGN KEY (`id_alternative`) REFERENCES `tbl_alternative` (`id_alternative`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_penilaian`
--
ALTER TABLE `tbl_penilaian`
  ADD CONSTRAINT `tbl_penilaian_ibfk_2` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_penilaian_ibfk_3` FOREIGN KEY (`id_sub_kriteria`) REFERENCES `tbl_sub_kriteria` (`id_sub_kriteria`) ON DELETE CASCADE,
  ADD CONSTRAINT `tbl_penilaian_ibfk_4` FOREIGN KEY (`id_alternative`) REFERENCES `tbl_alternative` (`id_alternative`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_sub_kriteria`
--
ALTER TABLE `tbl_sub_kriteria`
  ADD CONSTRAINT `tbl_sub_kriteria_ibfk_1` FOREIGN KEY (`id_kriteria`) REFERENCES `tbl_kriteria` (`id_kriteria`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
