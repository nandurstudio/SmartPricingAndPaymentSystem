# SmartPricingAndPaymentSystem

**Kriteria Penilaian:**

| No | Kriteria Penilaian                  | Status      |
|----|-------------------------------------|-------------|
| 1  | Login with Google                   | ✅ Selesai  |
| 2  | Tombol aksi di kiri                 | ✅ Selesai  |
| 3  | Field audit (CreatedBy, etc)        | ✅ Selesai  |
| 4  | Hosting/deploy domain               | ✅ Aktif    |
| 5  | Payment Gateway (Midtrans Sandbox)  | ✅ Selesai  |
| 6  | Reporting                           | ✅ Ditampilkan |
| 7  | Chart Dashboard                     | ✅ Ditampilkan |

**Team Members:**
1. 312310233  NANDANG DURYAT (Leader)
2. 312310555  IRA YUSAN
3. 312310158  RADHIKA BASSAM
4. 312310453  BRIAN L JUNIOR
5. 312310209  MUHAMMAD GHALY BINTANG
6. 312310385  SYAB AKHMAD ZAKI <sup>🚫 Inactive</sup>
7. 312310728  RAFIZA ADLIN NABIHA <sup>🚫 Inactive</sup>

**Website:** [https://smartpaymentplus.com](https://smartpaymentplus.com)

**GitHub Repository:** [https://github.com/your-org/SmartPricingAndPaymentSystem](https://github.com/your-org/SmartPricingAndPaymentSystem)

---

🎓 **Academic Identity & Project Context**

Assalamualaikum warahmatullahi wabarakatuh, dan salam sejahtera bagi kita semua.

Sebelum memulai, berikut identitas akademik kami sebagai bentuk pelaporan dan tanggung jawab dalam mengerjakan tugas ini:

- **Mata Kuliah:** Pemrograman Web 2 (Semester 4)
- **Kelas:** TI.23.B.1
- **Jurusan:** Teknik Informatika, Universitas Pelita Bangsa
- **Dosen Pengampu:** Bapak Sanudin, S.Kom., M.Kom.
- **Tahun Akademik:** 2024/2025

Dokumen ini merupakan naskah presentasi dan dokumentasi pengembangan aplikasi **SmartPricingAndPaymentSystem** sebagai tugas akhir dari mata kuliah Pemrograman Web 2. Aplikasi ini dikembangkan oleh tim mahasiswa TI.23.B.1 sebagai bentuk penerapan praktik pemrograman web lanjutan berbasis framework modern.

---

![CI4 Version](https://img.shields.io/badge/CodeIgniter-4.4.3-orange.svg)
![PHP Version](https://img.shields.io/badge/PHP-%3E=8.1-blue.svg)
![License](https://img.shields.io/badge/License-MIT-green.svg)

> Multi-tenant Booking SaaS Platform built with CodeIgniter 4

[English](#english) | [Bahasa Indonesia](#bahasa-indonesia)

---

## Tambahan Penting (ID)

- **File SQL Lengkap:**
  Jika ingin setup database secara manual, gunakan file `production/migrations/complete_migration_v2.sql` untuk membuat seluruh struktur dan seed data awal.

- **Folder `production/`:**
  Berisi konfigurasi, script, dan file pendukung untuk deployment production (termasuk .env, migrasi, dan dokumentasi troubleshooting).

- **Midtrans Payment:**
  Sudah mendukung mode sandbox & production. Atur kunci MIDTRANS di file `.env` sesuai kebutuhan. Pastikan environment variable sudah benar agar pembayaran berjalan lancar.

- **Keamanan:**
  Pastikan file `.env` dan file sensitif lain tidak dapat diakses publik (sudah ada proteksi di `.htaccess`).

---

## English

### Overview

SmartPricingAndPaymentSystem is a sophisticated multi-tenant SaaS booking platform designed to serve hundreds of business types (sports venues, accommodations, salons, courses, etc.) with robust tenant data isolation and modern payment integration.

### Key Features

- 🏢 **Multi-tenant Architecture**
  - Strong data isolation per tenant
  - Customizable booking flows
  - Tenant-specific configurations
  
- 💳 **Payment Integration**
  - Midtrans payment gateway integration 
  - Multiple payment methods
  - Automatic reconciliation
  
- 📱 **Modern UI/UX**
  - Responsive Bootstrap 5 design
  - Dynamic form generation
  - Real-time availability updates
  
- 🔐 **Advanced Security**
  - Role-based access control
  - Tenant data isolation
  - Activity audit logging

### Prerequisites

- PHP 8.1 or higher
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Node.js & npm
- Git

Required PHP Extensions:
- intl
- json
- mbstring
- mysqlnd
- xml
- curl

### Quick Start

1. **Clone & Install Dependencies**
   ```powershell
   # Clone repository
   git clone https://your-repository-url.git
   cd SmartPricingAndPaymentSystem

   # Install dependencies
   composer install
   npm install
   ```

2. **Environment Setup**
   ```powershell
   # Create environment file
   Copy-Item .env.example .env
   
   # Generate encryption key
   php spark key:generate
   ```

3. **Configure Environment**
   Edit `.env` file with your database and Midtrans credentials:
   ```ini
   database.default.hostname = localhost
   database.default.database = your_database
   database.default.username = your_username
   database.default.password = your_password

   MIDTRANS_SERVER_KEY = your_server_key
   MIDTRANS_CLIENT_KEY = your_client_key
   ```

4. **Initialize Database**
   Pilihan:
   - **Opsi A:**
     ```powershell
     # Run database migrations and seed initial data
     php spark migrate
     php spark db:seed InitialSetupSeeder
     ```
   - **Opsi B (Manual SQL):**
     Import file `production/migrations/complete_migration_v2.sql` ke database MySQL Anda.

### Development Workflow

1. **Database Changes**
   ```powershell
   # Create new migration
   php spark make:migration AddNewFeature
   
   # Run migrations
   php spark migrate
   
   # Rollback if needed
   php spark migrate:rollback
   ```

2. **Adding Features**
   - Follow MVC pattern
   - Use CI4 Models for data access
   - Implement tenant filtering
   - Write unit tests

3. **Testing**
   ```powershell
   # Run all tests
   vendor/bin/phpunit
   
   # Run specific test suite
   vendor/bin/phpunit --testsuite unit
   ```

### Project Structure

```
📦 SmartPricingAndPaymentSystem
├── 📂 app                    # Application source code
│   ├── Config               # Configuration files
│   ├── Controllers          # MVC Controllers
│   ├── Models              # Database Models
│   ├── Entities            # Entity classes
│   └── Views               # Template files
├── 📂 public               # Web root directory
│   ├── assets             # Compiled assets
│   └── uploads            # User uploads
├── 📂 resources           # Development resources
│   └── documentation      # Technical documentation
└── 📂 writable            # Writable directory for logs, cache
```

### Deployment

1. **Production Setup**
   - Set `CI_ENVIRONMENT = production` in `.env`
   - Configure secure database credentials
   - Enable HTTPS
   - Set proper file permissions

2. **Web Server Configuration**
   - Point DocumentRoot to `/public` directory
   - Enable URL rewriting
   - Configure PHP-FPM (recommended)

### Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

---

## Bahasa Indonesia

### Gambaran Umum

SmartPricingAndPaymentSystem adalah platform SaaS booking multi-tenant yang dirancang untuk melayani ratusan jenis bisnis (lapangan olahraga, akomodasi, salon, kursus, dll.) dengan isolasi data tenant yang kuat dan integrasi pembayaran modern.

### Fitur Utama

- 🏢 **Arsitektur Multi-tenant**
  - Isolasi data yang kuat per tenant
  - Alur booking yang dapat disesuaikan
  - Konfigurasi spesifik per tenant
  
- 💳 **Integrasi Pembayaran**
  - Integrasi payment gateway Midtrans
  - Multiple metode pembayaran
  - Rekonsiliasi otomatis
  
- 📱 **UI/UX Modern**
  - Desain responsif dengan Bootstrap 5
  - Generasi form dinamis
  - Update ketersediaan real-time
  
- 🔐 **Keamanan Tingkat Lanjut**
  - Kontrol akses berbasis peran
  - Isolasi data tenant
  - Pencatatan audit aktivitas

### Prasyarat Sistem

- PHP 8.1 atau lebih tinggi
- MySQL 5.7+ / MariaDB 10.3+
- Composer
- Node.js & npm
- Git

Ekstensi PHP yang diperlukan:
- intl
- json
- mbstring
- mysqlnd
- xml
- curl

### Panduan Cepat Memulai

1. **Clone & Install Dependensi**
   ```powershell
   # Clone repository
   git clone https://your-repository-url.git
   cd SmartPricingAndPaymentSystem

   # Install dependensi
   composer install
   npm install
   ```

2. **Setup Environment**
   ```powershell
   # Buat file environment
   Copy-Item .env.example .env
   
   # Generate kunci enkripsi
   php spark key:generate
   ```

3. **Konfigurasi Environment**
   Edit file `.env` dengan kredensial database dan Midtrans Anda:
   ```ini
   database.default.hostname = localhost
   database.default.database = your_database
   database.default.username = your_username
   database.default.password = your_password

   MIDTRANS_SERVER_KEY = your_server_key
   MIDTRANS_CLIENT_KEY = your_client_key
   ```

4. **Inisialisasi Database**
   Pilihan:
   - **Opsi A:**
     ```powershell
     # Jalankan migrasi database dan seed data awal
     php spark migrate
     php spark db:seed InitialSetupSeeder
     ```
   - **Opsi B (Manual SQL):**
     Import file `production/migrations/complete_migration_v2.sql` ke database MySQL Anda.

### Alur Kerja Pengembangan

1. **Perubahan Database**
   ```powershell
   # Buat migrasi baru
   php spark make:migration AddNewFeature
   
   # Jalankan migrasi
   php spark migrate
   
   # Rollback jika diperlukan
   php spark migrate:rollback
   ```

2. **Menambah Fitur**
   - Ikuti pola MVC
   - Gunakan CI4 Models untuk akses data
   - Implementasikan filter tenant
   - Tulis unit test

3. **Testing**
   ```powershell
   # Jalankan semua test
   vendor/bin/phpunit
   
   # Jalankan suite test tertentu
   vendor/bin/phpunit --testsuite unit
   ```

### Struktur Proyek

```
📦 SmartPricingAndPaymentSystem
├── 📂 app                    # Kode sumber aplikasi
│   ├── Config               # File konfigurasi
│   ├── Controllers          # MVC Controllers
│   ├── Models              # Model database
│   ├── Entities            # Kelas entitas
│   └── Views               # File template
├── 📂 public               # Direktori web root
│   ├── assets             # Asset terkompilasi
│   └── uploads            # Upload pengguna
├── 📂 resources           # Resource pengembangan
│   └── documentation      # Dokumentasi teknis
└── 📂 writable            # Direktori writable untuk log, cache
```

### Deployment

1. **Setup Produksi**
   - Set `CI_ENVIRONMENT = production` di `.env`
   - Konfigurasi kredensial database yang aman
   - Aktifkan HTTPS
   - Atur permission file dengan benar

2. **Konfigurasi Web Server**
   - Arahkan DocumentRoot ke direktori `/public`
   - Aktifkan URL rewriting
   - Konfigurasi PHP-FPM (direkomendasikan)

### Kontribusi

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/FiturKeren`)
3. Commit perubahan (`git commit -m 'Tambah FiturKeren'`)
4. Push branch (`git push origin feature/FiturKeren`)
5. Buat Pull Request

---

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [CodeIgniter](https://codeigniter.com) - The web framework used
- [Midtrans](https://midtrans.com) - Payment gateway integration
- [Bootstrap](https://getbootstrap.com) - Frontend framework
