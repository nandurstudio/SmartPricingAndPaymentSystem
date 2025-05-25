# Instruksi Pengembangan Aplikasi Booking Multi-Tenant

## Tujuan
Membangun aplikasi booking multi-tenant (SaaS) yang fleksibel, scalable, dan dapat mengakomodasi ratusan tenant dengan berbagai jenis bisnis (futsal, villa, salon, kursus, dsb.), menggunakan CodeIgniter 4 (CI4) untuk backend, Bootstrap 5 dan jQuery untuk frontend. Semua data tenant harus terisolasi dengan baik.

---

## Alur Kerja Pengembangan (Langkah-Langkah)

### 1. **Desain Database & ERD**
- Buat Entity Relationship Diagram (ERD) yang mencakup:
  - users, tenants, services, bookings, schedules/slots, service_types, service_type_attributes (untuk custom field), dan tabel audit.
  - Pastikan setiap data utama mengandung `tenant_id` sebagai isolasi multi-tenant.
- Rancang relasi antar tabel (foreign key).

### 2. **Pembuatan Database (SQL/Migration)**
- Tulis migration CodeIgniter 4 untuk semua tabel utama sesuai ERD.
- Sertakan:
  - Tabel master jenis layanan (`service_types`) dan atribut dinamis per tipe (`service_type_attributes`).
  - Tabel untuk nilai custom attribute per service/booking (`service_custom_values`), atau gunakan field JSON jika DB mendukung.
  - Field audit (`created_date`, `created_by`, `updated_date`, `updated_by`, `is_active`).

### 3. **Pembuatan Model**
- Buat model CI4 untuk setiap tabel utama.
  - Implementasi fillable, validasi, relasi (jika pakai CI4 Entity/Relationship).
  - Pastikan setiap query utama selalu filter berdasarkan `tenant_id`.

### 4. **Pembuatan Controller**
- Buat controller untuk:
  - Registrasi user dan pembuatan tenant sekaligus.
  - CRUD layanan (service), jadwal/slot, booking.
  - Pengelolaan field custom attribute per tenant/layanan.
- Selalu proteksi data berdasarkan role dan tenant_id.

### 5. **Pembuatan View (Frontend)**
- Buat tampilan dengan Bootstrap 5 & jQuery:
  - Form CRUD layanan, booking, dan jadwal.
  - Form booking harus dinamis mengikuti custom field per tenant.
  - Dashboard owner tenant, customer, dan admin.
  - Sidebar/menu dinamis sesuai role.

### 6. **Implementasi Booking & Pembayaran**
- Form booking harus:
  - Menyesuaikan field dinamis per tenant.
  - Cek ketersediaan slot (tidak double booking).
- Integrasi pembayaran Midtrans (API key per tenant).
- Handler webhook pembayaran untuk update status booking otomatis.

### 7. **Notifikasi**
- Implementasi notifikasi booking dan pembayaran:
  - Email (SMTP/API)
  - WhatsApp (API)

### 8. **Role & Permission**
- Implementasi sistem role: owner, admin, customer.
- Proteksi route dan query data berbasis role & tenant_id.

### 9. **Dukungan UGC (User Generated Content)**
- Tenant dapat mengajukan/membuat tipe tenant/layanan baru.
- Admin dapat moderasi, approve, edit, atau merge tipe tenant/layanan baru dari UGC.

### 10. **Dokumentasi & API**
- Buat dokumentasi API (Swagger/OpenAPI) untuk endpoint utama.
- Sertakan quickstart guide untuk integrasi API eksternal.

### 11. **Audit & Keamanan**
- Setiap perubahan data penting harus tercatat di field audit.
- Pastikan data antar tenant terisolasi dan tidak dapat diakses tenant lain.

### 12. **Multi-language & Scalability**
- Rancang UI/UX dan struktur data agar mudah mendukung multi-language.
- Terapkan best practice deployment dan scaling untuk aplikasi multi-tenant besar.

---

## Catatan Teknis
- Gunakan migration CI4 agar perubahan database bisa diatur versioning-nya.
- Gunakan dynamic form builder (jika perlu) untuk menambahkan field custom pada layanan/booking.
- Dokumentasikan setiap modul, endpoint, dan flow penting.
- Selalu review keamanan dan validasi data pada semua endpoint dan form.

---

## Referensi File
- Simpan file instruksi ini sebagai referensi utama pengembangan aplikasi.
- Update dokumen ini jika ada perubahan besar pada arsitektur atau flow bisnis.

---

**Dokumen ini WAJIB dibaca oleh seluruh tim pengembang sebelum memulai project.**