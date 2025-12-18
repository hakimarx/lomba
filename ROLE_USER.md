# ROLE USER & KREDENSIAL LOGIN

## Database Terpisah
- **xmaqra**: Database untuk MTQ Management (musabaqah)
- **huffadz**: Database khusus untuk Huffadz (terpisah)

## 1. ADMIN PROVINSI (Admin Prov)
**Database**: xmaqra
**Role**: adminprov
**Login**: 
- URL: `http://localhost/lomba/musabaqah/login/login.php`
- Username: `adminprov`
- Password: `adminprov`

**Hak Akses**:
- Manage semua event MTQ
- Manage Admin Kab/Ko
- Approve transfer Hafidz antar region
- Lihat semua data Hafidz (semua region)
- Lihat semua laporan harian Hafidz
- Lihat semua saran dari Hafidz
- Lihat rekap rekening Hafidz
- Export data ke Excel

**Menu yang Terlihat**:
- Event, Kafilah, Cabang, Peserta
- Hafidz Management
- Hafidz Dashboard
- Hafidz Approval
- Hafidz Transfer (khusus Admin Prov)
- Hafidz Laporan
- Hafidz Saran
- Hafidz Rekening
- User Management
- Manage Admin Kab/Ko

---

## 2. ADMIN KAB/KO (Admin Kabupaten/Kota)
**Database**: xmaqra
**Role**: adminkabko
**Login**:
- URL: `http://localhost/lomba/musabaqah/login/login.php`
- Username: (dibuat oleh Admin Prov)
- Password: (dibuat oleh Admin Prov)
- **Kab/Kota**: Harus di-assign oleh Admin Prov

**Hak Akses**:
- Approve pendaftaran Hafidz dari region mereka
- Manage Hafidz di region mereka saja
- Lapor Hafidz meninggal (dengan upload bukti)
- Request transfer Hafidz ke region lain
- Lihat laporan harian Hafidz dari region mereka
- Lihat saran dari Hafidz region mereka
- Lihat rekening Hafidz region mereka

**Menu yang Terlihat**:
- Event, Kafilah, Cabang, Peserta (terbatas)
- Hafidz Management (filter by kab_kota)
- Hafidz Dashboard
- Hafidz Approval (hanya region mereka)
- Hafidz Laporan (hanya region mereka)
- Hafidz Saran
- Hafidz Rekening (filter by kab_kota)

**Cara Membuat Admin Kab/Ko**:
1. Login sebagai Admin Prov
2. Klik menu "manage admin kab/ko"
3. Tambah data baru
4. Isi username, nama, password, dan **Kab/Kota**
5. Simpan

---

## 3. USER HAFIDZ
**Database**: huffadz (TERPISAH)
**Role**: userhafidz
**Login**:
- URL: `http://localhost/lomba/musabaqah/hafidz/login.php`
- Username: `hafidz1`
- Password: `hafidz123`

**Atau Register Baru**:
- URL: `http://localhost/lomba/musabaqah/hafidz/register.php`
- Isi form: username, password, email, nama, telepon, kab/kota
- Verifikasi email (link dikirim ke email)
- Tunggu approval dari Admin Kab/Ko

**Hak Akses**:
- Update profil (termasuk data bank)
- Submit laporan harian (hari, tanggal, kegiatan, materi, foto, paraf)
- Lihat riwayat laporan harian
- Submit saran untuk Admin Prov
- Lihat riwayat saran

**Menu yang Terlihat**:
- Profile
- Laporan Harian
- Saran & Masukan

**Status Hafidz**:
- `pending`: Baru daftar, belum di-approve
- `aktif`: Sudah di-approve, bisa login dan submit laporan
- `pindah_request`: Request pindah ke region lain (menunggu approval Admin Prov)
- `meninggal`: Sudah dilaporkan meninggal oleh Admin Kab/Ko

---

## ALUR KERJA

### Pendaftaran Hafidz Baru:
1. Hafidz register di `hafidz/register.php`
2. Verifikasi email (klik link di email)
3. Status: `pending` - menunggu approval
4. Admin Kab/Ko login → menu "hafidz approval"
5. Admin Kab/Ko approve → status jadi `aktif`
6. Hafidz bisa login dan submit laporan

### Transfer Hafidz:
1. Admin Kab/Ko → menu "hafidz" → klik "Pindah" pada hafidz tertentu
2. Pilih tujuan Kab/Kota baru
3. Status jadi `pindah_request`
4. Admin Prov → menu "hafidz transfer"
5. Admin Prov approve → Hafidz pindah ke region baru

### Laporan Meninggal:
1. Admin Kab/Ko → menu "hafidz" → klik "Meninggal"
2. Isi tanggal meninggal dan upload bukti (JPG/PDF)
3. Status jadi `meninggal`
4. Hafidz tidak bisa login lagi

---

## TESTING

### Test sebagai Admin Prov:
```
URL: http://localhost/lomba/musabaqah/
Username: adminprov
Password: adminprov
```

### Test sebagai Hafidz:
```
URL: http://localhost/lomba/musabaqah/hafidz/login.php
Username: hafidz1
Password: hafidz123
```

### Buat Admin Kab/Ko baru:
1. Login sebagai adminprov
2. Menu "manage admin kab/ko"
3. Tambah: username=`adminkabko1`, password=`admin123`, kab_kota=`Surabaya`
4. Logout
5. Login dengan username=`adminkabko1`, password=`admin123`
