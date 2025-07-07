# Panduan Pengecekan Fitur Aplikasi

Dokumen ini membantu Anda melakukan pengecekan fitur-fitur utama yang ada di aplikasi **SmartPricingAndPaymentSystem** berdasarkan struktur dan kode sumber aplikasi.

---

## 1. Fitur Utama (Berdasarkan README)

- **Arsitektur Multi-tenant**: Isolasi data tenant, alur booking fleksibel, konfigurasi tenant.
- **Integrasi Pembayaran**: Payment gateway Midtrans, multi-metode pembayaran, rekonsiliasi otomatis.
- **UI/UX Modern**: Bootstrap 5, form dinamis, update ketersediaan real-time.
- **Keamanan**: Kontrol akses berbasis peran, audit aktivitas, isolasi data tenant.
- **Manajemen Layanan**: CRUD layanan, jadwal/slot, booking, laporan basic.
- **Notifikasi**: Email, WhatsApp (cek implementasi di kode/notifikasi).

---

## 2. Daftar Modul & Controller Penting

- **Auth**: Login, register, Google OAuth, reset password, session, role.
- **Register**: Registrasi user & tenant, validasi email/username.
- **Dashboard**: Statistik, menu dinamis sesuai role.
- **ProductController**: CRUD produk.
- **BookingController**: Booking, refund, kalender booking.
- **ScheduleController**: CRUD jadwal layanan, slot, validasi ketersediaan.
- **SpecialController**: Jadwal khusus layanan.
- **RoleMenuAccessController**: Manajemen akses menu per role.
- **OnboardingController**: Setup tenant, branding, paket langganan.

---

## 3. Panduan Pengecekan Fitur

### a. **Autentikasi & Registrasi**
- Cek login (form & AJAX), register, reset password, Google OAuth.
- Validasi email/username unik (lihat `Register.php` & JS register).

### b. **Manajemen Tenant & Layanan**
- Setup tenant baru (Onboarding), CRUD layanan (ProductController, ServiceModel).
- Cek isolasi data tenant di setiap query/model.

### c. **Booking & Jadwal**
- Booking layanan, cek slot tersedia, refund booking.
- CRUD jadwal/slot (ScheduleController), validasi double booking.
- Kalender booking (BookingController::calendar).

### d. **Pembayaran**
- Integrasi Midtrans (cek konfigurasi & webhook di OnboardingController).
- Simulasi pembayaran & update status booking otomatis.

### e. **Role & Permission**
- Cek proteksi akses menu/fitur sesuai role (RoleMenuAccessController, AuthCheck filter).
- Cek menu dinamis di dashboard.

### f. **Laporan & Dashboard**
- Cek tampilan dashboard, statistik user/tenant, laporan basic.

### g. **Notifikasi**
- Cek pengiriman email/WhatsApp pada booking/pembayaran (lihat kode notifikasi).

### h. **User Generated Content (UGC)**
- Tenant dapat mengajukan/membuat tipe layanan baru (cek modul terkait UGC).

### i. **Audit & Keamanan**
- Pastikan setiap perubahan data penting tercatat (field audit di DB).
- Cek isolasi data antar tenant.

---

## 4. Referensi File & Struktur

- `app/Controllers/` : Semua controller utama.
- `app/Models/`      : Model database (cek filter tenant_id).
- `app/Views/`       : Tampilan frontend (cek form, dashboard, booking, dsb).
- `public/assets/js/`: Script frontend (login, register, booking, dsb).
- `app/Config/Routes.php`: Daftar endpoint/route aplikasi.
- `resources/INSTRUKSI_PENGEMBANGAN_APLIKASI_BOOKING_MULTI_TENANT.md`: Instruksi pengembangan & checklist teknis.

---

## 5. Tips Pengecekan

- Ikuti alur user: register → setup tenant → tambah layanan → booking → pembayaran → laporan.
- Cek setiap fitur dari sisi UI, API endpoint, dan database.
- Gunakan data dummy multi-tenant untuk uji isolasi data.
- Cek log/audit untuk setiap aksi penting.
- Pastikan proteksi akses berjalan sesuai role.

---

**Catatan:**
- Untuk detail pengujian/deployment, cek juga `README.md` dan `cli-guide.md`.
- Update dokumen ini jika ada penambahan fitur besar.
