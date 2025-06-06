## Important Notes

Jangan lupa tambahkan .env file di folder local

# SmartPricingAndPaymentSystem (Multi-Tenant Booking SaaS)

## Tujuan
Aplikasi booking multi-tenant (SaaS) berbasis CodeIgniter 4, Bootstrap 5, dan jQuery. Mendukung ratusan tenant (futsal, villa, salon, kursus, dsb.) dengan isolasi data tenant yang kuat.

## Penting untuk Developer
- **Baca instruksi pengembangan di `resources/INSTRUKSI_PENGEMBANGAN_APLIKASI_BOOKING_MULTI_TENANT.md` sebelum coding!**

### Keamanan & File Sensitif
1. **Environment Files**:
   - Wajib buat file `.env` di root project untuk konfigurasi lokal
   - Jangan pernah commit file `.env` atau `.env.*` ke repository
   - Gunakan `.env.example` sebagai template (sudah disediakan)

2. **Database & Migrations**:
   - File migration & seeder yang berisi struktur database & data master BOLEH dicommit
   - File SQL manual, backup database, dan seeder dengan data sensitif TIDAK BOLEH dicommit
   - Simpan SQL manual & backup di `/docs/sql/` (sudah di-ignore)

3. **Kredensial & Konfigurasi**:
   - Semua kredensial (API keys, passwords, dll) WAJIB di file `.env`
   - Konfigurasi default di `/app/Config/` boleh dicommit
   - Konfigurasi environment-specific taruh di `.env`

## Alur Kerja Pengembangan (Singkat)
1. Desain database & ERD: semua tabel utama wajib ada `tenant_id`.
2. Migration & Seeder: gunakan CI4 migration di `app/Database/Migrations/` dan seeder di `app/Database/Seeds/`.
3. Model: pastikan query utama selalu filter `tenant_id`.
4. Controller: CRUD, proteksi data per role & tenant.
5. View: Bootstrap 5, jQuery, form dinamis sesuai custom field per tenant.
6. Booking & Pembayaran: integrasi Midtrans, webhook, validasi slot.
7. Notifikasi: Email & WhatsApp.
8. Role & Permission: owner, admin, customer, proteksi route & data.
9. UGC: tenant bisa ajukan tipe layanan baru, admin moderasi.
10. Dokumentasi API: Swagger/OpenAPI.
11. Audit & Keamanan: semua perubahan penting tercatat, data antar tenant terisolasi.
12. Multi-language & Scalability: siap scaling dan multi-bahasa.

## CLI & Database Pipeline

### Initial Setup
```bash
# 1. Setup environment
cp .env.example .env
nano .env  # Edit sesuai environment local

# 2. Install dependencies
composer install
npm install

# 3. Initialize database
php spark migrate
php spark db:seed InitialSetupSeeder  # Data master wajib
php spark db:seed DevelopmentSeeder   # Data dummy untuk development
```

### Database Maintenance
```bash
# Update database structure
php spark migrate            # Jalankan migration baru
php spark migrate:rollback   # Rollback migration terakhir
php spark migrate:refresh    # Reset & rerun semua migration

# Seeding data
php spark db:seed MroleSeeder        # Role & permission
php spark db:seed MultiTenantSeeder  # Sample tenant data

# Tools
php spark db:maintenance     # Menu interaktif maintenance
```

## Struktur Project
- **Migration & Seeder**
  - `/app/Database/Migrations/` - File migration (commit ke repo)
  - `/app/Database/Seeds/` - File seeder data master (commit ke repo)
  - `/docs/sql/` - SQL manual & backup (jangan commit)

- **Konfigurasi**
  - `.env` - Environment variables & kredensial (jangan commit)
  - `/app/Config/` - Konfigurasi default (commit ke repo)
  
- **Dokumentasi**
  - `/resources/` - Dokumentasi teknis & panduan
  - `/docs/` - Dokumentasi tambahan & referensi

## Framework: CodeIgniter 4

CodeIgniter is a PHP full-stack web framework that is light, fast, flexible and secure.
More information can be found at the [official site](https://codeigniter.com).

This repository holds the distributable version of the framework.
It has been built from the
[development repository](https://github.com/codeigniter4/CodeIgniter4).

More information about the plans for version 4 can be found in [CodeIgniter 4](https://forum.codeigniter.com/forumdisplay.php?fid=28) on the forums.

You can read the [user guide](https://codeigniter.com/user_guide/)
corresponding to the latest version of the framework.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Contributing

We welcome contributions from the community.

Please read the [*Contributing to CodeIgniter*](https://github.com/codeigniter4/CodeIgniter4/blob/develop/CONTRIBUTING.md) section in the development repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
