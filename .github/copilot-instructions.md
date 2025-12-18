<!-- .github/copilot-instructions.md for lomba repo -->

# AI Coding Agent Guide — lomba (PHP, XAMPP)

This guide summarizes essential architecture, workflows, and actionable patterns for AI agents contributing to the lomba codebase. Follow these instructions for safe, effective, and consistent changes.

## Big Picture Architecture
- **Main apps:**
  - `emaqra/` (participant/user-facing)
  - `musabaqah/` (admin, CRUD, event management)
- **Shared logic/UI:** `emaqra/index.php` includes `musabaqah/index.php`.
- **Assets:** Shared CSS/JS/vendor libraries in `global/` and `assets/`.
- **Routing:** `musabaqah/index.php` is the admin router; includes pages via `?page=xxx`.

## Developer Workflow
- **Local dev:** Use XAMPP/MAMP (Apache + MySQL). Place under webroot, start Apache/MySQL.
- **DB setup:** Import `xmaqra.sql` into MySQL (`xmaqra` DB). Example:
  ```powershell
  C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\lomba\xmaqra.sql
  ```
- **DB credentials:** See `emaqra/koneksi.php` (switches by `$_SERVER['HTTP_HOST']`).
- **Composer:** Vendor packages in `assets/vendor/`. To update, run `composer install` in the folder with `composer.json` (e.g. `musabaqah/`).

## Key Conventions & Patterns
- **CRUD pages:**
  - Define `$vtabel`, `$querytabel`, `$kolomtrue`, `$kolomfake`, `$vth` (columns/labels).
  - Include `global/class/crud.php` for table rendering and `$_POST['crud']` actions (`hapus`, `tambah`, `edit`).
  - Example: `musabaqah/utama/user_level.php`.
- **DB helpers:** In `musabaqah/dbku.php` (`execute`, `getdata`, `getonebaris`, `getonedata`, `dbinsert`, `dbupdate`, `dbdelete`). Use global `$koneksi`.
- **Auth:** `musabaqah/login/sesi.php` (`setlogin`, `setlogout`, `islogined`, `getiduser`). Login checks in `musabaqah/index.php`.
- **Authorization:** `global/class/userrole.php` (`openfor([...])`).
- **JS helpers:** `global/js/ku.js` (utility), `global/js/crud.js` (CRUD UI). Use `gi()`, `qs()`.

## Common File Patterns
- **Add CRUD page:**
  1. Create file in `musabaqah/utama/`
  2. Define `$vtabel`, `$querytabel`, `$kolomtrue`, `$kolomfake`, `$vth`
  3. Include `../global/class/crud.php`
  4. Use `<input type=hidden name=crud id=crud>` and `crudid` in forms
  Example:
  ```php
  $vtabel = "user_level";
  $querytabel = "select * from $vtabel";
  $kolomtrue = ["nama","iduser_role"];
  $kolomfake = $kolomtrue;
  $vth = $kolomfake;
  include "../global/class/crud.php";
  ```

## Integration Points
- `assets/vendor/autoload.php` (used by `global/class/excelku.php` for PhpSpreadsheet)
- `musabaqah/dbku.php` (DB access for admin pages)
- `global/class/crud.php`, `global/js/crud.js`, `global/js/ku.js` (CRUD UI layer)

## Security & Risk Patterns
- **SQL:** Manual escaping via `savevalue()`; avoid new code with string concatenation for user input. Use prepared statements if fixing.
- **Auth:** Passwords compared as plain text in `musabaqah/login/login.php`.
- **Sessions:** Always use `session_start()` for session logic.

## Debugging & Testing
- No unit tests; test manually in browser.
- Enable verbose errors for dev:
  ```php
  ini_set('display_errors', 1);
  error_reporting(E_ALL);
  ```

## Change Rules
- Use relative includes as in neighboring files.
- For new pages, follow `kolomtrue/kolomfake/vth` and reuse `crud.php`/`crud.js`.
- Avoid broad SQL refactors unless tested and planned.

## Example Quick Tasks for AI
- Add CRUD page in `musabaqah/utama/` (see `user_level.php`)
- Huffadz module: see `musabaqah/utama/hafidz.php`, `musabaqah/hafidz/register.php`, etc.
- Fix UI bugs in `global/js/ku.js` or `global/css/*`
- Add helper to `global/class/` and reuse

---
If any section is unclear or missing, ask for feedback to iterate or expand with more examples.
