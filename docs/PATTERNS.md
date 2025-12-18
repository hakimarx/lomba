# 🎨 Pola Desain & Coding Patterns

> Dokumentasi lengkap pola-pola desain dan konvensi coding yang digunakan dalam aplikasi Lomba/Musabaqah

---

## 📚 Daftar Isi

1. [Pola Arsitektur](#-pola-arsitektur)
2. [Pola Database](#-pola-database)
3. [Pola UI/Frontend](#-pola-uifrontend)
4. [Pola Keamanan](#-pola-keamanan)
5. [Konvensi Penamaan](#-konvensi-penamaan)
6. [Best Practices](#-best-practices)
7. [Anti-Patterns (Yang Harus Dihindari)](#-anti-patterns-yang-harus-dihindari)

---

## 🏛 Pola Arsitektur

### 1. Page Controller Pattern

Setiap halaman bertindak sebagai controller yang menangani request dan response.

```php
// Pattern: Page Controller
// File: musabaqah/utama/event.php

<?php
// 1. Authorization check
openfor(['adminprov', 'adminkabko']);

// 2. Define data structure
$vtabel = "event";
$querytabel = "SELECT * FROM $vtabel";
$kolomtrue = ["nama", "tahun", "aktif"];
$kolomfake = $kolomtrue;
$vth = ["Nama Event", "Tahun", "Status Aktif"];

// 3. Include CRUD handler
include "../global/class/crud.php";

// 4. Render UI
?>
<form method="post">
    <!-- form fields -->
</form>
<?php showtabel(); ?>
```

**Kapan menggunakan:**
- Untuk halaman CRUD standar
- Halaman yang memiliki satu responsibility utama

---

### 2. Front Controller Pattern

`index.php` bertindak sebagai single entry point yang me-routing semua request.

```php
// Pattern: Front Controller
// File: musabaqah/index.php

<?php
// 1. Bootstrap (load dependencies)
include_once __DIR__ . "/../global/class/fungsi.php";
include_once __DIR__ . "/../global/class/userrole.php";
include_once __DIR__ . "/dbku.php";
include_once __DIR__ . "/login/sesi.php";

// 2. Authentication check
if (!islogined()) {
    gotologin();
    return;
}

// 3. Route to page
$hal = $_GET["page"] ?? "utama";
include_once("$hal.php");
?>
```

**Keuntungan:**
- Satu titik kontrol untuk semua request
- Mudah menambahkan middleware (auth, logging, etc)

---

### 3. Template View Pattern

Layout (`utama.php`) sebagai template yang meng-include konten dinamis.

```php
// Pattern: Template View
// File: musabaqah/utama.php

<?php
// 1. Get page parameter
$page2 = $_GET["page2"] ?? "home";
?>

<!-- Layout structure -->
<div class="utama">
    <div class="header">
        <h2>musabaqah</h2>
    </div>
    
    <div class="menu">
        <?php getmenu(); ?>
    </div>
    
    <div class="row">
        <!-- Dynamic content -->
        <?php include_once("utama/$page2.php"); ?>
    </div>
    
    <div class="footer">
        copyright ©2025
    </div>
</div>
```

---

### 4. Registry Pattern (Global Variables)

Menggunakan `$GLOBALS` untuk sharing data antar fungsi.

```php
// Pattern: Registry via $GLOBALS
// File: global/class/crud.php

function hapus($id) {
    $vtabel = $GLOBALS['vtabel'];  // Access global registry
    dbdelete($vtabel, $id);
}

function settd() {
    $query = $GLOBALS['querytabel'];
    $kolomtrue = $GLOBALS['kolomtrue'];
    $kolomfake = $GLOBALS['kolomfake'];
    // ...
}
```

**Catatan:** Pattern ini sederhana tapi kurang ideal untuk aplikasi besar. Pertimbangkan dependency injection untuk refactoring.

---

## 🗄 Pola Database

### 1. Active Record (Simplified)

Helper functions yang wrap operasi database.

```php
// Pattern: Simplified Active Record
// File: musabaqah/dbku.php

// CREATE
function dbinsert($tabel, $arrkolom, $arrvalue) {
    $arrvalue = savevalue($arrvalue);  // Escape values
    $kolom = implode(',', $arrkolom);
    $value = implode("','", $arrvalue);
    $query = "INSERT INTO $tabel($kolom) VALUES('$value')";
    execute($query);
}

// READ
function getdata($query) {
    return execute($query);
}

function getonebaris($query) {
    $data = execute($query);
    return mysqli_fetch_array($data);
}

function getonedata($query) {
    $data = execute($query);
    if (mysqli_num_rows($data) == 0) {
        return "";
    }
    $row = mysqli_fetch_array($data);
    return $row[0];
}

// UPDATE
function dbupdate($tabel, $arrkolom, $arrvalue, $where) {
    $arrvalue = savevalue($arrvalue);
    $tmp = "";
    for ($i = 0; $i < count($arrkolom); $i++) {
        $tmp .= "$arrkolom[$i] = '$arrvalue[$i]',";
    }
    $tmp = substr($tmp, 0, strlen($tmp) - 1);
    $query = "UPDATE $tabel SET $tmp $where";
    execute($query);
}

// DELETE
function dbdelete($tabel, $id) {
    $query = "DELETE FROM $tabel WHERE id=$id";
    execute($query);
}
```

### 2. Query Object Pattern

Menyimpan query sebagai string di variabel `$querytabel`.

```php
// Pattern: Query Object (simple)
// File: musabaqah/utama/peserta.php

// Simple query
$querytabel = "SELECT * FROM peserta";

// Query with JOIN
$querytabel = "SELECT p.*, k.nama as kafilah_nama 
               FROM peserta p 
               LEFT JOIN kafilah k ON p.idkafilah = k.id";

// Query with WHERE
$querytabel = "SELECT * FROM peserta WHERE idgolongan = $idgolongan";
```

### 3. Database Connection Singleton

Koneksi database dibuat sekali dan di-share via global.

```php
// Pattern: Connection Singleton
// File: musabaqah/koneksi.php

<?php
// Connection created once
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'xmaqra';

$koneksi = mysqli_connect($host, $user, $pass, $dbname);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Usage in dbku.php via $GLOBALS
function execute($query) {
    $koneksi = $GLOBALS['koneksi'];
    return mysqli_query($koneksi, $query);
}
```

---

## 🎨 Pola UI/Frontend

### 1. CRUD Form Pattern

Template standar untuk form CRUD.

```html
<!-- Pattern: CRUD Form -->
<!-- File: Any CRUD page -->

<form method="post" id="frm">
    <!-- Hidden fields for CRUD operation -->
    <input type="hidden" name="crud" id="crud">
    <input type="hidden" name="crudid" id="crudid">
    
    <!-- Data fields -->
    <label>Nama:</label>
    <input type="text" name="nama" id="nama" required>
    
    <label>Tahun:</label>
    <input type="number" name="tahun" id="tahun" required>
    
    <!-- Action buttons -->
    <button type="button" onclick="tambah()">Tambah</button>
    <button type="button" onclick="simpan()">Simpan</button>
</form>
```

```javascript
// JavaScript handlers (crud.js)
function tambah() {
    gi('crud').value = 'tambah';
    gi('frm').submit();
}

function edit(id) {
    gi('crud').value = 'edit';
    gi('crudid').value = id;
    // Load data to form...
}

function hapus(id) {
    if (confirm('Yakin hapus?')) {
        gi('crud').value = 'hapus';
        gi('crudid').value = id;
        gi('frm').submit();
    }
}

function simpan() {
    if (gi('crudid').value == '') {
        tambah();
    } else {
        gi('crud').value = 'edit';
        gi('frm').submit();
    }
}
```

### 2. Dynamic Table Pattern

Render tabel dari data database.

```php
// Pattern: Dynamic Table
// File: global/class/crud.php

function showtabel() {
    echo "<table class=table>";
    setth();  // Render headers
    settd();  // Render data rows
    echo "</table>";
}

function setth() {
    $arrth = $GLOBALS['vth'];
    echo "<tr>";
    foreach ($arrth as $item) {
        echo "<th>$item</th>\n";
    }
    echo "<th class='thtombol'>edit</th>";
    echo "<th class='thtombol'>hapus</th>";
    echo "</tr>";
}

function settd() {
    $query = $GLOBALS['querytabel'];
    $data = getdata($query);
    
    while ($row = mysqli_fetch_array($data)) {
        $id = $row['id'];
        echo "<tr>";
        
        // Data columns
        for ($i = 0; $i < count($kolomtrue); $i++) {
            $tdid = "td" . $id . $kolomtrue[$i];
            $value = $row[$kolomfake[$i]];
            echo "<td id='$tdid'>$value</td>\n";
        }
        
        // Action buttons
        echo "<td><input type=button onclick='edit($id)' value=edit></td>";
        echo "<td><input type=button value=hapus onclick='hapus($id);'></td>";
        echo "</tr>\n";
    }
}
```

### 3. Dropdown/Select Pattern

Pattern untuk select dengan data dari database.

```php
<!-- Pattern: Database-driven Select -->
<?php
$dataEvent = getdata("SELECT * FROM event ORDER BY tahun DESC");
?>

<select name="idevent" id="idevent" onchange="loadCabang()">
    <option value="">-- Pilih Event --</option>
    <?php while($row = mysqli_fetch_array($dataEvent)): ?>
        <option value="<?= $row['id'] ?>" 
                <?= ($row['id'] == $selected) ? 'selected' : '' ?>>
            <?= $row['nama'] ?> (<?= $row['tahun'] ?>)
        </option>
    <?php endwhile; ?>
</select>
```

### 4. Cascading Dropdown Pattern

Dropdown yang bergantung pada pilihan sebelumnya.

```javascript
// Pattern: Cascading Dropdown
function loadCabang() {
    var idevent = gi('idevent').value;
    if (idevent == '') {
        gi('idcabang').innerHTML = '<option value="">-- Pilih Event dulu --</option>';
        return;
    }
    
    // AJAX call to get cabang options
    fetch(`json.php?action=getcabang&idevent=${idevent}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">-- Pilih Cabang --</option>';
            data.forEach(item => {
                options += `<option value="${item.id}">${item.nama}</option>`;
            });
            gi('idcabang').innerHTML = options;
        });
}
```

---

## 🔐 Pola Keamanan

### 1. Session-Based Authentication

```php
// Pattern: Session Auth
// File: musabaqah/login/sesi.php

function setlogin($iduser) {
    ensure_session();
    $_SESSION['islogin'] = ["iduser" => $iduser];
    session_write_close();
}

function setlogout() {
    ensure_session();
    unset($_SESSION['islogin']);
    session_write_close();
}

function islogined() {
    ensure_session();
    $istrue = isset($_SESSION['islogin']);
    session_write_close();
    return $istrue;
}

function getiduser() {
    ensure_session();
    if (isset($_SESSION['islogin']) && isset($_SESSION['islogin']["iduser"])) {
        $iduser = $_SESSION['islogin']["iduser"];
        session_write_close();
        return $iduser;
    }
    session_write_close();
    return null;
}
```

### 2. Role-Based Access Control (RBAC)

```php
// Pattern: RBAC
// File: global/class/userrole.php

function openfor($arrrole) {
    $iduser = getiduser();
    $rowuser = getonebaris("SELECT * FROM view_user WHERE iduser=$iduser");
    $roleuser = $rowuser["role"];
    
    $isada = false;
    foreach ($arrrole as $item) {
        if ($item == $roleuser) {
            $isada = true;
            break;
        }
    }
    
    if (!$isada) {
        die("akun anda tidak bisa mengakses halaman ini");
    }
}

// Usage in page:
openfor(['adminprov']);           // Only admin provinsi
openfor(['adminprov', 'adminkabko']); // Both admin types
```

### 3. Input Sanitization Pattern

```php
// Pattern: Input Sanitization
// File: musabaqah/dbku.php

function savevalue($arr) {
    for ($i = 0; $i < count($arr); $i++) {
        // Escape single quotes
        $arr[$i] = str_replace("'", "\\'", $arr[$i]);
    }
    return $arr;
}

// Usage:
$arrvalue = savevalue($_POST['values']);
```

**⚠️ Catatan Penting:** Pattern ini masih rentan terhadap SQL injection. Untuk keamanan yang lebih baik, gunakan prepared statements:

```php
// RECOMMENDED: Prepared Statements
$stmt = $koneksi->prepare("INSERT INTO user (nama, email) VALUES (?, ?)");
$stmt->bind_param("ss", $nama, $email);
$stmt->execute();
```

---

## 📛 Konvensi Penamaan

### File Naming

| Type | Convention | Example |
|------|------------|---------|
| Pages | lowercase, underscores | `user_level.php`, `hafidz_dashboard.php` |
| Classes | lowercase | `crud.php`, `userrole.php` |
| JavaScript | lowercase | `ku.js`, `crud.js` |
| CSS | lowercase | `ku.css`, `menu.css` |

### Variable Naming

```php
// Database tables & columns: lowercase, underscore
$vtabel = "user_level";
$kolomtrue = ["nama", "id_parent", "tanggal_lahir"];

// Arrays: prefix with 'arr' or 'data'
$arrkolom = ["nama", "email"];
$dataUser = getdata("SELECT * FROM user");

// Single row: prefix with 'row'
$rowUser = getonebaris("SELECT * FROM user WHERE id=1");

// Boolean: prefix with 'is' or 'ada'
$islogined = true;
$isada = false;

// ID references: prefix with 'id'
$iduser = 123;
$idevent = 456;
$idgolongan = 789;
```

### Function Naming

```php
// Database operations: prefix with 'db'
dbinsert($tabel, $kolom, $value);
dbupdate($tabel, $kolom, $value, $where);
dbdelete($tabel, $id);

// Get data: prefix with 'get'
getdata($query);
getonebaris($query);
getonedata($query);
getiduser();

// Set/Change: prefix with 'set'
setlogin($iduser);
setlogout();

// Check/Validate: prefix with 'is' or returns boolean
islogined();

// Display/Render: descriptive name
showtabel();
setth();  // set table header
settd();  // set table data
```

---

## ✅ Best Practices

### 1. Selalu Cek Login di Awal

```php
<?php
// Di awal setiap halaman yang memerlukan auth
include_once __DIR__ . "/login/sesi.php";
if (!islogined()) {
    header("Location: login/login.php");
    exit;
}
```

### 2. Gunakan Konstanta untuk Path

```php
// Define base path
define('BASE_PATH', __DIR__);
define('GLOBAL_PATH', BASE_PATH . '/../global');

// Usage
include GLOBAL_PATH . '/class/crud.php';
```

### 3. Handle Error dengan Baik

```php
// Tampilkan error saat development
if ($_SERVER['HTTP_HOST'] == 'localhost') {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
```

### 4. Validate Input Sebelum Query

```php
// Validate required fields
if (empty($_POST['nama'])) {
    die("Nama harus diisi");
}

// Validate numeric
if (!is_numeric($_GET['id'])) {
    die("ID tidak valid");
}

// Validate dalam range
$nilai = intval($_POST['nilai']);
if ($nilai < 0 || $nilai > 100) {
    die("Nilai harus antara 0-100");
}
```

### 5. Gunakan Prepared Statements (Recommended)

```php
// Instead of this (vulnerable):
$query = "SELECT * FROM user WHERE id=$id";

// Use this (secure):
$stmt = $koneksi->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
```

---

## ⚠️ Anti-Patterns (Yang Harus Dihindari)

### 1. ❌ SQL Query dengan Konkatenasi String

```php
// JANGAN LAKUKAN INI:
$query = "SELECT * FROM user WHERE username='$username' AND password='$password'";

// SQL Injection bisa terjadi dengan input:
// username: admin'--
// Hasil query: SELECT * FROM user WHERE username='admin'--' AND password='xxx'
```

### 2. ❌ Password Plain Text

```php
// JANGAN LAKUKAN INI:
$query = "SELECT * FROM user WHERE password='$password'";

// GUNAKAN password_hash dan password_verify:
$hash = password_hash($password, PASSWORD_DEFAULT);
// Simpan $hash ke database

// Untuk verifikasi:
$row = getonebaris("SELECT * FROM user WHERE username='$username'");
if (password_verify($password, $row['password'])) {
    // Login berhasil
}
```

### 3. ❌ Expose Error Detail ke User

```php
// JANGAN LAKUKAN INI:
die("Database error: " . mysqli_error($koneksi));

// GUNAKAN:
if ($result === false) {
    error_log("DB Error: " . mysqli_error($koneksi)); // Log to file
    die("Terjadi kesalahan sistem. Silakan coba lagi.");
}
```

### 4. ❌ Include File dari User Input

```php
// JANGAN LAKUKAN INI:
$page = $_GET['page'];
include($page . ".php"); // Vulnerable to LFI (Local File Inclusion)

// GUNAKAN whitelist:
$allowed_pages = ['home', 'event', 'user', 'hafidz'];
$page = $_GET['page'] ?? 'home';
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}
include($page . ".php");
```

### 5. ❌ Tidak Validate File Upload

```php
// JANGAN LAKUKAN INI:
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);

// GUNAKAN validation:
$allowed_types = ['image/jpeg', 'image/png', 'application/pdf'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($_FILES['file']['type'], $allowed_types)) {
    die("Tipe file tidak diizinkan");
}

if ($_FILES['file']['size'] > $max_size) {
    die("File terlalu besar");
}

$new_name = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9.]/', '', $_FILES['file']['name']);
move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $new_name);
```

---

## 📋 Checklist Pengembangan

### Sebelum Deploy

- [ ] Semua error handling sudah proper
- [ ] Tidak ada password/credentials hardcoded
- [ ] SQL injection vulnerability sudah dicek
- [ ] File permissions sudah benar
- [ ] Debug mode sudah dimatikan
- [ ] Session timeout sudah diset

### Menambah Halaman Baru

1. [ ] Buat file di `musabaqah/utama/newpage.php`
2. [ ] Tambahkan `openfor()` untuk access control
3. [ ] Define variabel CRUD (`$vtabel`, `$querytabel`, etc)
4. [ ] Include `crud.php` jika perlu
5. [ ] Buat form dengan hidden `crud` dan `crudid`
6. [ ] Tambahkan link di menu (`utama.php`)
7. [ ] Test semua operasi CRUD
8. [ ] Test dengan role berbeda

---

*Dokumentasi Pola Desain v1.0*
*Dibuat: 18 Desember 2025*
