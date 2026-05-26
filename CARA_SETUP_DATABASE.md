# 📚 Cara Setup Database Traveloop

## 🎯 Langkah-Langkah Setup

### **1. Persiapan**
- Pastikan XAMPP sudah terinstall dan berjalan
- Start Apache dan MySQL di XAMPP Control Panel

### **2. Import Database**

#### **Opsi A: Import Lengkap (Recommended)**
1. Buka browser, akses: `http://localhost/phpmyadmin`
2. Klik tab **"New"** atau **"Databases"** di sidebar kiri
3. Buat database baru dengan nama: `db_traveloop_fix`
4. Klik database yang baru dibuat
5. Klik tab **"Import"**
6. Klik **"Choose File"** dan pilih file: `db_traveloop_fix.sql`
7. Scroll ke bawah, klik **"Go"**
8. Tunggu hingga muncul pesan sukses

#### **Opsi B: Manual via SQL**
1. Buka phpMyAdmin
2. Buat database: `db_traveloop_fix`
3. Klik database tersebut
4. Klik tab **"SQL"**
5. Copy seluruh isi file `db_traveloop_fix.sql`
6. Paste ke text area
7. Klik **"Go"**

### **3. Verifikasi Database**

Setelah import berhasil, pastikan tabel-tabel berikut sudah ada:

| Tabel | Jumlah Data |
|-------|-------------|
| `admin` | 1 row (admin@traveloop.com) |
| `destinasi` | 16 rows (destinasi wisata) |
| `pesan_kontak` | 3 rows (contoh pesan) |
| `ulasan` | 0 rows (kosong, siap digunakan) |

**Cara Cek:**
```sql
-- Cek tabel admin
SELECT * FROM admin;

-- Cek jumlah destinasi
SELECT COUNT(*) FROM destinasi;

-- Cek jumlah pesan
SELECT COUNT(*) FROM pesan_kontak;
```

### **4. Test Login Admin**

1. Akses: `http://localhost/Traveloop_FIX/admin/admin.php`
2. Login dengan kredensial:
   - **Email:** `admin@traveloop.com`
   - **Password:** `admin123`
3. Jika berhasil, Anda akan masuk ke dashboard admin

---

## 🔐 Menambahkan Admin Baru

### **Metode 1: Menggunakan Password Generator (Paling Mudah)**

1. Akses: `http://localhost/Traveloop_FIX/generate_password.php`
2. Masukkan password yang diinginkan (contoh: `mypassword123`)
3. Klik **"Generate Hash"**
4. Copy hash yang dihasilkan
5. Buka phpMyAdmin → database `db_traveloop_fix` → tab **"SQL"**
6. Jalankan query berikut (ganti nilai sesuai kebutuhan):

```sql
INSERT INTO `admin` (`nama_lengkap`, `email`, `password`) 
VALUES 
('Nama Admin Baru', 'emailbaru@traveloop.com', 'PASTE_HASH_DISINI');
```

**Contoh Lengkap:**
```sql
INSERT INTO `admin` (`nama_lengkap`, `email`, `password`) 
VALUES 
('John Doe', 'john@traveloop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
```

### **Metode 2: Menggunakan File SQL Manual**

1. Buka file: `tambah_admin_manual.sql`
2. Pilih salah satu contoh query yang sudah disediakan
3. Sesuaikan nama, email, dan password hash
4. Copy query tersebut
5. Buka phpMyAdmin → tab **"SQL"**
6. Paste dan jalankan query

### **Metode 3: Menggunakan Password Hash Siap Pakai**

Gunakan salah satu password hash berikut:

| Password | Hash |
|----------|------|
| `admin123` | `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` |
| `superadmin` | `$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa` |
| `password123` | `$2y$10$YCjXJqYzKmYvKpjcAU6Yl.SAc/5KVW5h5vQjXqX5vQjXqX5vQjXqX` |
| `traveloop2024` | `$2y$10$eHqWH.axSPCwyIze/4Nmq.YvWkTZ3z/f5S5FiamxJCasta4DBo.S6` |
| `admin2024` | `$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhCu` |

**Contoh Query:**
```sql
-- Admin dengan password "superadmin"
INSERT INTO `admin` (`nama_lengkap`, `email`, `password`) 
VALUES 
('Super Admin', 'superadmin@traveloop.com', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa');
```

---

## 🔧 Troubleshooting

### **Error: "Table 'db_traveloop_fix.admin' doesn't exist"**
**Solusi:**
- Database belum di-import dengan benar
- Ulangi langkah import database dari awal

### **Error: "Access denied for user 'root'@'localhost'"**
**Solusi:**
- Cek konfigurasi di `config/koneksi.php`
- Pastikan username dan password MySQL sesuai:
  ```php
  $host = "localhost";
  $user = "root";
  $pass = "";  // Kosongkan jika tidak ada password
  $db = "db_traveloop_fix";
  ```

### **Login Gagal Terus Menerus**
**Solusi:**
1. Cek apakah data admin sudah ada di database:
   ```sql
   SELECT * FROM admin WHERE email = 'admin@traveloop.com';
   ```
2. Jika tidak ada, jalankan query:
   ```sql
   INSERT INTO `admin` (`nama_lengkap`, `email`, `password`) 
   VALUES 
   ('Administrator Traveloop', 'admin@traveloop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
   ```
3. Coba login lagi dengan:
   - Email: `admin@traveloop.com`
   - Password: `admin123`

### **Password Hash Tidak Cocok**
**Solusi:**
- Gunakan tool generator: `generate_password.php`
- Atau gunakan hash yang sudah disediakan di tabel di atas
- Pastikan tidak ada spasi di awal/akhir hash saat copy-paste

---

## 📊 Query Berguna

### **Melihat Semua Admin**
```sql
SELECT id, nama_lengkap, email, last_login FROM admin;
```

### **Menghapus Admin**
```sql
DELETE FROM admin WHERE email = 'email@example.com';
```

### **Update Password Admin**
```sql
UPDATE admin 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE email = 'admin@traveloop.com';
```

### **Update Nama Admin**
```sql
UPDATE admin 
SET nama_lengkap = 'Nama Baru' 
WHERE email = 'admin@traveloop.com';
```

### **Reset Last Login**
```sql
UPDATE admin SET last_login = NULL WHERE id = 1;
```

### **Cek Statistik Database**
```sql
-- Total destinasi
SELECT COUNT(*) as total_destinasi FROM destinasi;

-- Total pesan
SELECT COUNT(*) as total_pesan FROM pesan_kontak;

-- Total admin
SELECT COUNT(*) as total_admin FROM admin;

-- Destinasi per kategori
SELECT kategori, COUNT(*) as jumlah 
FROM destinasi 
GROUP BY kategori 
ORDER BY jumlah DESC;
```

---

## ⚠️ Catatan Keamanan

1. **Jangan gunakan password default untuk produksi**
   - Ganti `admin123` dengan password yang lebih kuat
   - Minimal 12 karakter, kombinasi huruf, angka, dan simbol

2. **Hapus file generator setelah selesai**
   - File `generate_password.php` sebaiknya dihapus setelah setup
   - Atau pindahkan ke folder yang tidak bisa diakses publik

3. **Backup database secara berkala**
   - Export database via phpMyAdmin
   - Simpan di lokasi yang aman

4. **Jangan share password hash**
   - Meskipun sudah di-hash, tetap jaga kerahasiaan
   - Setiap admin harus punya password unik

---

## ✅ Checklist Setup

- [ ] XAMPP sudah running (Apache + MySQL)
- [ ] Database `db_traveloop_fix` sudah dibuat
- [ ] File `db_traveloop_fix.sql` sudah di-import
- [ ] Tabel `admin`, `destinasi`, `pesan_kontak`, `ulasan` sudah ada
- [ ] Data admin default sudah ada (admin@traveloop.com)
- [ ] Berhasil login ke admin panel
- [ ] Konfigurasi `config/koneksi.php` sudah sesuai
- [ ] Website bisa diakses di `http://localhost/Traveloop_FIX/`

---

## 🆘 Butuh Bantuan?

Jika masih mengalami masalah:
1. Cek error log di XAMPP Control Panel
2. Cek error di browser console (F12)
3. Pastikan semua file ada di folder yang benar
4. Restart Apache dan MySQL di XAMPP

**Kontak Support:**
- Email: withtraveloop@gmail.com
- WhatsApp: +62 857-1322-8321
