Hafidz Module Setup (dev/XAMPP)

This document explains how to add the new Huffadz (hafidz) module, roles, and supporting tables to your local MySQL (XAMPP) database.

1. Import the main database dump (if not already imported):
   - From PowerShell:
```
C:\xampp2\mysql\bin\mysql.exe -u root < C:\xampp2\htdocs\lomba\xmaqra.sql
```

2. Import the hafidz module additions (table, roles, reports, tests):
```
C:\xampp2\mysql\bin\mysql.exe -u root < C:\xampp2\htdocs\lomba\sql\add_hafidz_table.sql
```

3. Login credentials (for dev testing):
   - admin prov: username `adminprov`, password `adminprov` (created by the migration script)
   - Use `register` on the Hafidz side to create user-hafidz accounts, or add via admin page.

4. Pages and links
   - Admin prov: Can manage admin kab/ko, manage hafidz, and view hafidz dashboard
     - Menu: Manage Admin Kab/Ko (under Musabaqah -> Main -> user)
   - Admin kab/ko: Can manage hafidz and input test scores
     - Menu: Hafidz, Hafidz dashboard, Penilaian Hafidz
   - User hafidz: Can register, login, update profile, add daily reports and upload proofs

5. For any issues, verify:
   - `musabaqah/koneksi.php` uses `xmaqra` DB and is connected
   - If xmaqra DB does not exist, the local koneksi.php has a fallback to create/import the SQL dump for dev.

If you'd like, I can also add a small CLI helper to run the SQL import and create the database automatically.
