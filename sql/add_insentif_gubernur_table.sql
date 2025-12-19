-- ============================================
-- TABLE: huffadz_insentif_gubernur
-- Pendataan Huffadz Penerima Insentif Gubernur Jawa Timur
-- ============================================

CREATE TABLE IF NOT EXISTS `huffadz_insentif_gubernur` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tahun_anggaran` varchar(10) NOT NULL COMMENT 'Tahun Anggaran Insentif',
  `periode` varchar(50) DEFAULT NULL COMMENT 'Periode Penerimaan (Semester 1/2)',
  
  -- Data Pribadi
  `nik` varchar(20) NOT NULL COMMENT 'NIK Huffadz',
  `nama_lengkap` varchar(255) NOT NULL,
  `tempat_lahir` varchar(100) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `golongan_darah` varchar(5) DEFAULT NULL,
  `agama` varchar(20) DEFAULT 'Islam',
  
  -- Alamat
  `alamat` text DEFAULT NULL,
  `rt` varchar(5) DEFAULT NULL,
  `rw` varchar(5) DEFAULT NULL,
  `desa_kelurahan` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kab_kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(50) DEFAULT 'Jawa Timur',
  `kode_pos` varchar(10) DEFAULT NULL,
  
  -- Kontak
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  
  -- Data Hafalan
  `jumlah_hafalan_juz` int(11) DEFAULT 30 COMMENT 'Jumlah Juz yang dihafal',
  `lembaga_tahfidz` varchar(255) DEFAULT NULL COMMENT 'Nama Lembaga/Pesantren Tahfidz',
  `nama_pembimbing` varchar(255) DEFAULT NULL COMMENT 'Nama Ustadz/Pembimbing',
  `tahun_khatam` year DEFAULT NULL COMMENT 'Tahun Khatam 30 Juz',
  `no_sertifikat_tahfidz` varchar(100) DEFAULT NULL,
  `tanggal_sertifikat` date DEFAULT NULL,
  `lembaga_penerbit_sertifikat` varchar(255) DEFAULT NULL,
  
  -- Data Kegiatan Mengajar
  `status_mengajar` enum('Aktif','Tidak Aktif') DEFAULT 'Aktif',
  `nama_lembaga_mengajar` varchar(255) DEFAULT NULL,
  `alamat_lembaga_mengajar` text DEFAULT NULL,
  `jabatan` varchar(100) DEFAULT NULL COMMENT 'Ustadz/Pengasuh/dsb',
  `tmt_mengajar` date DEFAULT NULL COMMENT 'Tanggal Mulai Mengajar',
  `jumlah_santri_binaan` int(11) DEFAULT NULL,
  
  -- Data Bank untuk Transfer Insentif
  `nama_bank` varchar(100) DEFAULT NULL,
  `no_rekening` varchar(50) DEFAULT NULL,
  `atas_nama_rekening` varchar(255) DEFAULT NULL,
  `cabang_bank` varchar(100) DEFAULT NULL,
  
  -- Dokumen (path file)
  `foto_ktp` varchar(255) DEFAULT NULL,
  `foto_kk` varchar(255) DEFAULT NULL,
  `foto_sertifikat_tahfidz` varchar(255) DEFAULT NULL,
  `foto_buku_rekening` varchar(255) DEFAULT NULL,
  `foto_pas` varchar(255) DEFAULT NULL COMMENT 'Foto 3x4',
  `surat_keterangan_mengajar` varchar(255) DEFAULT NULL,
  `surat_rekomendasi` varchar(255) DEFAULT NULL,
  
  -- Status Verifikasi
  `status_verifikasi` enum('Pending','Diverifikasi Kabko','Diverifikasi Provinsi','Ditolak','Diterima') DEFAULT 'Pending',
  `catatan_verifikasi` text DEFAULT NULL,
  `tanggal_verifikasi_kabko` datetime DEFAULT NULL,
  `verifikator_kabko` int(11) DEFAULT NULL,
  `tanggal_verifikasi_provinsi` datetime DEFAULT NULL,
  `verifikator_provinsi` int(11) DEFAULT NULL,
  
  -- Status Pencairan
  `status_pencairan` enum('Belum','Proses','Sudah Cair') DEFAULT 'Belum',
  `tanggal_pencairan` date DEFAULT NULL,
  `nominal_insentif` decimal(15,2) DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL,
  
  -- Metadata
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_nik_tahun` (`nik`, `tahun_anggaran`),
  KEY `idx_kab_kota` (`kab_kota`),
  KEY `idx_status_verifikasi` (`status_verifikasi`),
  KEY `idx_tahun_anggaran` (`tahun_anggaran`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- View untuk laporan ringkasan per Kab/Kota
CREATE OR REPLACE VIEW view_insentif_summary AS
SELECT 
    tahun_anggaran,
    kab_kota,
    COUNT(*) as total_huffadz,
    SUM(CASE WHEN status_verifikasi = 'Diterima' THEN 1 ELSE 0 END) as diterima,
    SUM(CASE WHEN status_verifikasi = 'Pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status_verifikasi = 'Ditolak' THEN 1 ELSE 0 END) as ditolak,
    SUM(CASE WHEN status_pencairan = 'Sudah Cair' THEN 1 ELSE 0 END) as sudah_cair
FROM huffadz_insentif_gubernur
GROUP BY tahun_anggaran, kab_kota;

-- Sample data (optional - for testing)
INSERT INTO `huffadz_insentif_gubernur` 
(`tahun_anggaran`, `periode`, `nik`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `kab_kota`, `no_hp`, `jumlah_hafalan_juz`, `nama_bank`, `no_rekening`, `atas_nama_rekening`, `status_verifikasi`) 
VALUES
('2025', 'Semester 1', '3578012345670001', 'Ahmad Hafidz Contoh', 'Surabaya', '1990-05-15', 'L', 'Jl. Contoh No. 123', 'Surabaya', '081234567890', 30, 'Bank Jatim', '1234567890', 'Ahmad Hafidz Contoh', 'Pending'),
('2025', 'Semester 1', '3578012345670002', 'Fatimah Zahro', 'Malang', '1992-08-20', 'P', 'Jl. Melati No. 45', 'Malang', '081234567891', 30, 'Bank Jatim', '0987654321', 'Fatimah Zahro', 'Pending');
