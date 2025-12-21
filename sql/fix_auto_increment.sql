-- =====================================================
-- Fix script for missing AUTO_INCREMENT on xmaqra database
-- Run this in phpMyAdmin or MySQL client
-- =====================================================

-- Fix kafilah table
ALTER TABLE `kafilah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Fix hakim table  
ALTER TABLE `hakim`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Fix hakim_keahlian table
ALTER TABLE `hakim_keahlian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Add primary keys if missing
ALTER TABLE `kafilah` ADD PRIMARY KEY IF NOT EXISTS (`id`);
ALTER TABLE `hakim` ADD PRIMARY KEY IF NOT EXISTS (`id`);
ALTER TABLE `hakim_keahlian` ADD PRIMARY KEY IF NOT EXISTS (`id`);

-- Create hafidz table if not exists
CREATE TABLE IF NOT EXISTS `hafidz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` varchar(50) DEFAULT NULL,
  `periode` varchar(100) DEFAULT NULL,
  `asal` varchar(255) DEFAULT NULL,
  `nik` varchar(20) DEFAULT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `nama_lengkap` varchar(255) DEFAULT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `umur` int(11) DEFAULT NULL,
  `jk` varchar(10) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `rt` varchar(10) DEFAULT NULL,
  `rw` varchar(10) DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `desa_kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kab_kota` varchar(100) DEFAULT NULL,
  `jumlah_hafalan_juz` int(11) DEFAULT NULL,
  `lembaga_tahfidz` varchar(255) DEFAULT NULL,
  `nama_pembimbing` varchar(255) DEFAULT NULL,
  `tahun_khatam` int(11) DEFAULT NULL,
  `no_sertifikat_tahfidz` varchar(100) DEFAULT NULL,
  `status_mengajar` varchar(50) DEFAULT NULL,
  `nama_lembaga_mengajar` varchar(255) DEFAULT NULL,
  `tmt_mengajar` date DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `lulus` tinyint(1) DEFAULT 0,
  `is_insentif_kabko` tinyint(1) DEFAULT 0,
  `status_insentif_kabko` varchar(50) DEFAULT NULL,
  `catatan_insentif_kabko` text DEFAULT NULL,
  `tgl_insentif_kabko` datetime DEFAULT NULL,
  `verifikator_insentif_kabko` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `idkafilah` int(11) DEFAULT NULL,
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `atas_nama_rekening` varchar(255) DEFAULT NULL,
  `foto_ktp` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'aktif',
  `tanggal_meninggal` date DEFAULT NULL,
  `bukti_meninggal` varchar(255) DEFAULT NULL,
  `tujuan_pindah` varchar(100) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create mutasi table for hafidz transfers
CREATE TABLE IF NOT EXISTS `mutasi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idhafidz` int(11) NOT NULL,
  `asal_kabko` varchar(100) DEFAULT NULL,
  `tujuan_kabko` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Show confirmation
SELECT 'Database fixes applied successfully!' AS message;
