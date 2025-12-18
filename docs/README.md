# 📖 Dokumentasi Aplikasi Lomba (Musabaqah)

Selamat datang di dokumentasi teknis aplikasi **Lomba/Musabaqah** - Sistem Manajemen Perlombaan dan Data Hafidz.

---

## 📂 Daftar Dokumen

| Dokumen | Deskripsi |
|---------|-----------|
| [**ARCHITECTURE.md**](./ARCHITECTURE.md) | Arsitektur sistem, struktur direktori, diagram komponen, dan overview teknologi |
| [**FLOWCHARTS.md**](./FLOWCHARTS.md) | Flowchart visual untuk semua alur kerja utama (login, CRUD, scoring, dll) |
| [**PATTERNS.md**](./PATTERNS.md) | Pola desain, coding patterns, konvensi penamaan, best practices |

---

## 🚀 Quick Start

### Prasyarat
- XAMPP/MAMP (Apache + MySQL)
- PHP 7.4+
- Browser modern

### Instalasi
1. Clone repository ke folder webroot XAMPP
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/[repo]/lomba.git
   ```

2. Import database
   ```bash
   C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\lomba\xmaqra.sql
   ```

3. Akses di browser
   ```
   http://localhost/lomba/musabaqah/
   ```

4. Login dengan kredensial default:
   - **Admin Provinsi:** `adminprov` / `adminprov`
   - **Operator:** `operator` / `operator`

---

## 🔑 User Roles

| Role | Deskripsi | Akses |
|------|-----------|-------|
| `adminprov` | Admin Provinsi | Full access, manage admin kab/ko, transfer hafidz |
| `adminkabko` | Admin Kabupaten/Kota | Manage local data, hafidz daerah |
| `operator` | Operator | Basic CRUD operations |
| `hakim` | Hakim/Juri | Input nilai, view peserta |

---

## 🗂 Struktur Aplikasi

```
lomba/
├── musabaqah/          # Admin panel (backend)
│   ├── index.php       # Entry point & router
│   ├── utama.php       # Dashboard layout
│   └── utama/          # Page modules
├── emaqra/             # User portal (frontend)
├── global/             # Shared components
│   ├── class/          # PHP helpers
│   ├── css/            # Stylesheets
│   └── js/             # JavaScript
├── assets/             # Static files & vendor
├── sql/                # SQL migrations
└── docs/               # Documentation (you are here)
```

---

## 📚 Untuk Pengembang

### Menambah Halaman CRUD Baru

1. Buat file di `musabaqah/utama/`:
   ```php
   <?php
   // 1. Access control
   openfor(['adminprov']);
   
   // 2. Define CRUD variables
   $vtabel = "nama_tabel";
   $querytabel = "SELECT * FROM $vtabel";
   $kolomtrue = ["kolom1", "kolom2"];
   $kolomfake = $kolomtrue;
   $vth = ["Header1", "Header2"];
   
   // 3. Include CRUD handler
   include "../global/class/crud.php";
   ?>
   
   <!-- 4. Form -->
   <form method="post">
       <input type="hidden" name="crud" id="crud">
       <input type="hidden" name="crudid" id="crudid">
       <input name="kolom1">
       <input name="kolom2">
   </form>
   
   <!-- 5. Display table -->
   <?php showtabel(); ?>
   ```

2. Tambahkan link di menu `utama.php`

### Database Helpers

```php
// Insert
dbinsert("tabel", ["kolom1","kolom2"], ["value1","value2"]);

// Update
dbupdate("tabel", ["kolom1"], ["value1"], "WHERE id=1");

// Delete
dbdelete("tabel", $id);

// Select multiple rows
$data = getdata("SELECT * FROM tabel");
while($row = mysqli_fetch_array($data)) {
    echo $row['kolom'];
}

// Select single row
$row = getonebaris("SELECT * FROM tabel WHERE id=1");

// Select single value
$nilai = getonedata("SELECT nama FROM tabel WHERE id=1");
```

---

## 🔗 Link Terkait

- [GitHub Repository](https://github.com/hakimarx/lomba)
- [Copilot Instructions](../.github/copilot-instructions.md)
- [Role User Documentation](../ROLE_USER.md)
- [Setup Hafidz Guide](../SETUP_HAFIDZ.md)

---

*Dokumentasi ini dibuat pada 18 Desember 2025*
