-- CREATE TABLE for huffadz/hafidz
CREATE TABLE IF NOT EXISTS `hafidz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun` varchar(10) DEFAULT NULL,
  `asal` varchar(255) DEFAULT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jk` varchar(10) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `desa` varchar(255) DEFAULT NULL,
  `kecamatan` varchar(255) DEFAULT NULL,
  `kab_kota` varchar(255) DEFAULT NULL,
  `sertifikat_tahfidz` varchar(255) DEFAULT NULL,
  `mengajar` varchar(255) DEFAULT NULL,
  `tmt_mengajar` date DEFAULT NULL,
  `telepon` varchar(50) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `lulus` tinyint(1) DEFAULT 0,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `idkafilah` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Example: Add new roles for hafidz users (adminprov, admin_kab_ko, user_hafidz)
INSERT IGNORE INTO user_level (id, nama, role) VALUES
(100, 'admin prov', 'adminprov'),
(101, 'admin kab ko', 'adminkabko'),
(102, 'user hafidz', 'userhafidz');

-- sample admin users (only for dev/test)
INSERT IGNORE INTO `user` (`id`, `iduser_level`, `nama`, `username`, `password`) VALUES
(500, 100, 'admin prov', 'adminprov', 'adminprov');
INSERT IGNORE INTO `user` (`id`, `iduser_level`, `nama`, `username`, `password`) VALUES
(501, 101, 'admin kab ko contoh', 'adminkabko', 'adminkabko');

-- sample hafidz user (stored in hafidz table, not user)
INSERT IGNORE INTO `hafidz` (`id`, `username`, `password`, `nama`, `telepon`) VALUES
(501, 'hafidz1', 'hafidz1', 'Sample Hafidz 1', '08123456789');

-- Table for daily reports (rekap harian/ laporan)
CREATE TABLE IF NOT EXISTS `hafidz_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idhafidz` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `kegiatan` text NOT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY (`idhafidz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table for test scores (wawasan kebangsaan & hafalan quran)
CREATE TABLE IF NOT EXISTS `hafidz_nilai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idhafidz` int(11) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `nilai` decimal(5,2) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `idpenilai` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY (`idhafidz`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

