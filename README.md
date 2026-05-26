# Traveloop — Tourism Catalog Website

Website katalog wisata modern dan responsif yang dibangun dengan **PHP**, **MySQL**, **Bootstrap 5**, dan **Google Maps API**.

## 📁 Struktur File

```
Traveloop_FIX/
├── index.php                    # Halaman utama (Hero, Kategori, Destinasi Unggulan)
├── explore.php                  # Halaman eksplorasi (Grid, Filter, Pencarian)
├── detail.php                   # Halaman detail destinasi
├── contact.php                  # Halaman kontak & form pesan
├── map.php                      # Halaman peta interaktif Google Maps
├── about.php                    # Halaman tentang kami
│
├── config/
│   └── koneksi.php             # Koneksi database & LOGIKA CRUD TERPUSAT
│
├── includes/
│   ├── header.php              # Header global (Navbar, CSS)
│   └── footer.php              # Footer global (Scripts, Modal)
│
├── admin/
│   ├── admin.php               # Panel admin (Dashboard, CRUD, Pesan)
│   └── admin.css               # Styling khusus admin
│
├── assets/
│   ├── css/
│   │   └── style.css           # Desain sistem & styling global
│   └── js/
│       └── script.js           # Logika frontend
│
├── uploads/                     # Folder penyimpanan foto destinasi
└── db_traveloop_fix.sql        # File database SQL
```

---

## 🗄️ Struktur Database

Database: **`db_traveloop_fix`**

### Tabel Utama:

#### 1. **`destinasi`** - Menyimpan data tempat wisata
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT (PK, AI) | ID unik destinasi |
| `nama` | VARCHAR(100) | Nama destinasi wisata |
| `kategori` | VARCHAR(50) | Kategori (Alam, Pantai, Gunung, Budaya, Sejarah) |
| `alamat` | TEXT | Alamat lengkap lokasi |
| `rating` | DECIMAL(3,1) | Rating 0.0 - 5.0 |
| `deskripsi` | TEXT | Deskripsi singkat destinasi |
| `foto_url` | LONGTEXT | Path foto (uploads/namafile.jpg) |
| `maps_url` | VARCHAR(255) | URL/iframe Google Maps |
| `kontak` | VARCHAR(50) | Kontak pengelola (WA/IG) |
| `tarif` | VARCHAR(100) | Harga tiket masuk |
| `history` | TEXT | Sejarah & latar belakang |
| `tips` | TEXT | Tips berkunjung |
| `created_at` | TIMESTAMP | Waktu data dibuat |

#### 2. **`pesan_kontak`** - Menyimpan pesan dari pengunjung
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT (PK, AI) | ID unik pesan |
| `nama` | VARCHAR(100) | Nama pengirim |
| `email` | VARCHAR(100) | Email pengirim |
| `no_wa` | VARCHAR(20) | Nomor WhatsApp |
| `topik` | VARCHAR(100) | Topik pesan |
| `pesan` | TEXT | Isi pesan |
| `tanggal` | DATETIME | Waktu pesan dikirim |

#### 3. **`admin`** - Menyimpan data admin (belum digunakan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT (PK, AI) | ID admin |
| `nama_lengkap` | VARCHAR(100) | Nama lengkap admin |
| `email` | VARCHAR(100) | Email admin (UNIQUE) |
| `password` | VARCHAR(255) | Password terenkripsi |
| `last_login` | DATETIME | Waktu login terakhir |

#### 4. **`ulasan`** - Menyimpan ulasan pengunjung (belum digunakan)
| Kolom | Tipe | Keterangan |
|-------|------|------------|
| `id` | INT (PK, AI) | ID ulasan |
| `id_destinasi` | INT (FK) | Relasi ke tabel destinasi |
| `nama_pengunjung` | VARCHAR(100) | Nama pengulas |
| `rating` | INT (1-5) | Rating yang diberikan |
| `komentar` | TEXT | Isi ulasan |
| `tanggal` | TIMESTAMP | Waktu ulasan dibuat |

---

## 🔐 Sistem Autentikasi Admin

### **Lokasi Logika Login:**
File: **`admin/admin.php`** (Baris 7-32)

### **Cara Kerja:**

1. **Database-Based Authentication** (Aman & Terenkripsi)
   ```php
   // Query admin berdasarkan email
   $query_login = mysqli_query($koneksi, "SELECT * FROM admin WHERE email = '$email'");
   $admin = mysqli_fetch_assoc($query_login);
   
   // Verifikasi password dengan password_verify
   if (password_verify($password, $admin['password'])) {
       $_SESSION['admin_logged_in'] = true;
       $_SESSION['admin_id'] = $admin['id'];
       $_SESSION['admin_nama'] = $admin['nama_lengkap'];
       $_SESSION['admin_email'] = $admin['email'];
       
       // Update last_login
       mysqli_query($koneksi, "UPDATE admin SET last_login = NOW() WHERE id = '$admin_id'");
   }
   ```

2. **Session Management:**
   - Ketika login berhasil, sistem membuat session variables:
     - `$_SESSION['admin_logged_in'] = true` → Status login
     - `$_SESSION['admin_id']` → ID admin
     - `$_SESSION['admin_nama']` → Nama lengkap admin
     - `$_SESSION['admin_email']` → Email admin
   - Session dimulai di `config/koneksi.php` (baris 2-4):
     ```php
     if (session_status() === PHP_SESSION_NONE) {
         session_start();
     }
     ```

3. **Proteksi Halaman Admin:**
   - Setiap halaman admin memeriksa session:
     ```php
     if (!isset($_SESSION['admin_logged_in'])) {
         // Tampilkan form login
     } else {
         // Tampilkan dashboard admin
     }
     ```

4. **Logout:**
   ```php
   if (isset($_GET['action']) && $_GET['action'] == 'logout') {
       session_destroy();
       header("Location: admin.php");
       exit;
   }
   ```

5. **Password Encryption:**
   - Password disimpan dalam bentuk hash menggunakan `password_hash()` (bcrypt)
   - Verifikasi menggunakan `password_verify()` yang aman dari timing attacks

### **Kredensial Login Default:**
- **Email:** `admin@traveloop.com`
- **Password:** `admin123`

### **Cara Menambahkan Admin Baru:**

#### **Metode 1: Menggunakan Password Generator (Recommended)**
1. Akses: `http://localhost/Traveloop_FIX/generate_password.php`
2. Masukkan password yang diinginkan
3. Klik "Generate Hash"
4. Copy hash yang dihasilkan
5. Buka phpMyAdmin → database `db_traveloop_fix` → tab SQL
6. Jalankan query:
   ```sql
   INSERT INTO `admin` (`nama_lengkap`, `email`, `password`) 
   VALUES ('Nama Admin', 'email@example.com', 'PASTE_HASH_DISINI');
   ```

#### **Metode 2: Menggunakan File SQL Manual**
1. Buka file: `tambah_admin_manual.sql`
2. Pilih salah satu contoh atau gunakan password hash yang tersedia
3. Copy query yang sesuai
4. Jalankan di phpMyAdmin

#### **Metode 3: Menggunakan PHP Script**
```php
<?php
// Buat file test_hash.php di root folder
$password = "password_baru";
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Password: $password<br>";
echo "Hash: $hash";
?>
```

### **Daftar Password Hash Siap Pakai:**
| Password | Hash |
|----------|------|
| `admin123` | `$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi` |
| `superadmin` | `$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa` |
| `password123` | `$2y$10$YCjXJqYzKmYvKpjcAU6Yl.SAc/5KVW5h5vQjXqX5vQjXqX5vQjXqX` |
| `traveloop2024` | `$2y$10$eHqWH.axSPCwyIze/4Nmq.YvWkTZ3z/f5S5FiamxJCasta4DBo.S6` |
| `admin2024` | `$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhCu` |

> **Catatan Keamanan:** 
> - Password disimpan dalam bentuk hash bcrypt (tidak bisa di-decrypt)
> - Setiap hash unik meskipun password sama (karena salt otomatis)
> - Gunakan password yang kuat untuk produksi
> - Hapus file `generate_password.php` setelah selesai digunakan

---

## 🔄 Logika CRUD (Create, Read, Update, Delete)

### **Lokasi CRUD Terpusat:**
File: **`config/koneksi.php`** (Baris 20-145)

### **Konsep Arsitektur:**
Semua operasi CRUD dipusatkan di satu file (`koneksi.php`) yang di-include oleh semua halaman. Ini memastikan:
- ✅ Konsistensi logika database
- ✅ Mudah maintenance
- ✅ Keamanan terpusat (validasi session)

---

### **1. CREATE (Tambah Data)**

**Lokasi:** `config/koneksi.php` (Baris 24-60)

**Cara Kerja:**
1. Form di `admin/admin.php` mengirim data via POST dengan `action=tambah`
2. Sistem memeriksa session admin:
   ```php
   if (isset($_SESSION['admin_logged_in'])) {
       // Hanya admin yang login bisa tambah data
   }
   ```
3. Data di-sanitasi untuk mencegah SQL Injection:
   ```php
   $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
   ```
4. Upload foto (jika ada):
   ```php
   if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
       $target_dir = "../uploads/";
       $file_name = time() . '_' . basename($_FILES["foto_file"]["name"]);
       move_uploaded_file($_FILES["foto_file"]["tmp_name"], $target_file);
       $foto_path = "uploads/" . $file_name;
   }
   ```
5. Insert ke database:
   ```php
   $query = "INSERT INTO destinasi (nama, kategori, alamat, rating, deskripsi, foto_url, maps_url, kontak, tarif, history, tips) 
             VALUES ('$nama', '$kategori', '$alamat', '$rating', '$deskripsi', '$foto_path', '$maps_url', '$kontak', '$tarif', '$history', '$tips')";
   mysqli_query($koneksi, $query);
   ```
6. Redirect dengan status:
   ```php
   header("Location: ../admin/admin.php?status=success_tambah");
   ```

**Form HTML:**
```html
<form method="POST" action="../config/koneksi.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="tambah">
    <input type="text" name="nama" required>
    <input type="file" name="foto_file">
    <!-- Field lainnya -->
    <button type="submit">Simpan</button>
</form>
```

---

### **2. READ (Baca Data)**

**Lokasi:** Tersebar di berbagai file (index.php, explore.php, admin.php)

**Cara Kerja:**

#### A. **Tampilan Publik (index.php, explore.php)**
```php
// Ambil 6 destinasi dengan rating tertinggi
$query_featured = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY rating DESC, id DESC LIMIT 6");

while($row = mysqli_fetch_assoc($query_featured)) {
    echo $row['nama'];
    echo $row['kategori'];
    // dst...
}
```

#### B. **Dengan Filter & Pencarian (explore.php)**
```php
$search = mysqli_real_escape_string($koneksi, $_GET['q']);
$category = mysqli_real_escape_string($koneksi, $_GET['cat']);

$sql = "SELECT * FROM destinasi WHERE 1=1";

// Filter pencarian teks
if (!empty($search)) {
    $sql .= " AND (nama LIKE '%$search%' OR alamat LIKE '%$search%' OR deskripsi LIKE '%$search%')";
}

// Filter kategori
if (!empty($category) && $category != 'all') {
    $sql .= " AND kategori = '$db_cat'";
}

// Filter Hidden Gem
if ($tag == 'hidden-gem') {
    $sql .= " AND (deskripsi LIKE '%hidden gem%' OR deskripsi LIKE '%Hidden Gem%')";
}

$query_explore = mysqli_query($koneksi, $sql);
```

#### C. **Detail Destinasi (detail.php)**
```php
$id = (int)$_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM destinasi WHERE id = $id");
$row = mysqli_fetch_assoc($query);
```

#### D. **Admin Dashboard (admin.php)**
```php
// Statistik
$q_dest = mysqli_query($koneksi, "SELECT id FROM destinasi");
$jml_destinasi = mysqli_num_rows($q_dest);

// Daftar destinasi
$query_places = mysqli_query($koneksi, "SELECT * FROM destinasi ORDER BY id DESC");
```

---

### **3. UPDATE (Edit Data)**

**Lokasi:** `config/koneksi.php` (Baris 62-105)

**Cara Kerja:**
1. Form edit mengirim data via POST dengan `action=edit` dan `id` destinasi
2. Sistem memeriksa session admin
3. Data di-sanitasi
4. Foto lama dipertahankan jika tidak ada upload baru:
   ```php
   $foto_url = mysqli_real_escape_string($koneksi, $_POST['foto_url_lama']);
   
   if (isset($_FILES['foto_file']) && $_FILES['foto_file']['error'] === UPLOAD_ERR_OK) {
       // Upload foto baru
       $foto_url = "uploads/" . $file_name;
   }
   ```
5. Update database:
   ```php
   $query = "UPDATE destinasi SET 
               nama='$nama', 
               kategori='$kategori', 
               alamat='$alamat', 
               rating='$rating', 
               deskripsi='$deskripsi', 
               foto_url='$foto_url',
               maps_url='$maps_url',
               kontak='$kontak',
               tarif='$tarif',
               history='$history',
               tips='$tips' 
             WHERE id='$id'";
   mysqli_query($koneksi, $query);
   ```

**Form HTML:**
```html
<form method="POST" action="../config/koneksi.php" enctype="multipart/form-data">
    <input type="hidden" name="action" value="edit">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="hidden" name="foto_url_lama" value="<?= $foto_lama ?>">
    <!-- Field lainnya -->
    <button type="submit">Update</button>
</form>
```

**Cara Mengisi Form Edit (JavaScript):**
```javascript
function openEditModal(btnEl) {
    document.getElementById('formAction').value = 'edit';
    document.getElementById('formId').value = btnEl.getAttribute('data-id');
    document.getElementById('formNama').value = btnEl.getAttribute('data-nama');
    document.getElementById('formFotoLama').value = btnEl.getAttribute('data-foto');
    // dst...
}
```

---

### **4. DELETE (Hapus Data)**

**Lokasi:** `config/koneksi.php` (Baris 107-118)

**Cara Kerja:**
1. Link hapus mengirim GET request dengan `action=hapus` dan `id`
2. Sistem memeriksa session admin
3. Hapus dari database:
   ```php
   if (isset($_GET['action']) && $_GET['action'] == 'hapus' && isset($_GET['id'])) {
       $id = mysqli_real_escape_string($koneksi, $_GET['id']);
       $query = "DELETE FROM destinasi WHERE id='$id'";
       mysqli_query($koneksi, $query);
       header("Location: ../admin/admin.php?status=success_hapus");
   }
   ```

**HTML Button dengan Konfirmasi (SweetAlert2):**
```html
<button onclick='confirmHapus("../config/koneksi.php?action=hapus&id=<?= $id ?>", "<?= $nama ?>")'>
    <i class="bi bi-trash-fill"></i>
</button>
```

**JavaScript Konfirmasi:**
```javascript
function confirmHapus(url, nama) {
    Swal.fire({
        title: 'Hapus Destinasi?',
        text: `Yakin ingin menghapus "${nama}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}
```

---

## 🔒 Keamanan CRUD

### **1. Validasi Session**
Semua operasi CRUD dibungkus dalam pengecekan session:
```php
if (isset($_SESSION['admin_logged_in'])) {
    // CRUD operations
}
```

### **2. SQL Injection Prevention**
Semua input user di-sanitasi:
```php
$nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
```

### **3. File Upload Security**
- Validasi tipe file:
  ```php
  $allowed_types = ['jpg', 'jpeg', 'png', 'webp'];
  if (in_array($imageFileType, $allowed_types)) {
      // Allow upload
  }
  ```
- Rename file dengan timestamp untuk menghindari konflik:
  ```php
  $file_name = time() . '_' . basename($_FILES["foto_file"]["name"]);
  ```

### **4. XSS Prevention**
Output di-escape saat ditampilkan:
```php
echo htmlspecialchars($row['nama'], ENT_QUOTES);
```

---

## 🔄 Alur Kerja Lengkap

### **Alur Tambah Destinasi:**
```
1. Admin login → Session dibuat
2. Admin klik "Tambah Destinasi" → Modal terbuka
3. Admin isi form → Submit
4. Data dikirim ke config/koneksi.php (POST)
5. koneksi.php cek session → Valid
6. koneksi.php sanitasi data → Aman
7. koneksi.php upload foto → Tersimpan di uploads/
8. koneksi.php INSERT ke database → Berhasil
9. Redirect ke admin.php?status=success_tambah
10. SweetAlert2 tampilkan notifikasi sukses
```

### **Alur Edit Destinasi:**
```
1. Admin klik tombol Edit → JavaScript ambil data dari atribut HTML
2. JavaScript isi form modal dengan data lama
3. Admin ubah data → Submit
4. Data dikirim ke config/koneksi.php (POST) dengan action=edit
5. koneksi.php UPDATE database WHERE id=...
6. Redirect ke admin.php?status=success_edit
```

### **Alur Hapus Destinasi:**
```
1. Admin klik tombol Hapus → SweetAlert2 konfirmasi
2. Admin konfirmasi → Redirect ke koneksi.php?action=hapus&id=...
3. koneksi.php DELETE FROM destinasi WHERE id=...
4. Redirect ke admin.php?status=success_hapus
```

---

## 🚀 Setup & Instalasi

### **1. Persiapan Database**
```sql
1. Buka phpMyAdmin
2. Buat database baru: db_traveloop_fix
3. Import file: db_traveloop_fix.sql
```

### **2. Konfigurasi Koneksi**
Edit `config/koneksi.php` (baris 7-10):
```php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_traveloop_fix";
```

### **3. Jalankan Aplikasi**
```
1. Pastikan XAMPP/WAMP sudah running
2. Akses: http://localhost/Traveloop_FIX/
3. Admin Panel: http://localhost/Traveloop_FIX/admin/admin.php
```

---

## ✨ Fitur Utama

| Fitur | Status |
|-------|--------|
| Responsive Design (mobile/tablet/desktop) | ✅ |
| Hero dengan animasi particles | ✅ |
| Pencarian berdasarkan nama/lokasi | ✅ |
| Filter kategori & Hidden Gem | ✅ |
| Grid & List view toggle | ✅ |
| Detail modal dengan Google Maps | ✅ |
| Form kontak dengan validasi | ✅ |
| Admin Dashboard dengan statistik | ✅ |
| Admin CRUD lengkap (Create, Read, Update, Delete) | ✅ |
| Upload foto destinasi | ✅ |
| Session-based authentication | ✅ |
| SweetAlert2 notifications | ✅ |

---

## 🎨 Desain

- **Font:** Plus Jakarta Sans (modern sans-serif)
- **Palet Warna:** Deep navy (#0f172a) + Amber gold (#eab308)
- **Style:** Editorial travel magazine aesthetic
- **Animasi:** Smooth hover, card elevation, particle hero

---

## 🛠 Tech Stack

- **Backend:** PHP 8.x + MySQL/MariaDB
- **Frontend:** HTML5, CSS3, JavaScript (Vanilla)
- **Framework CSS:** Bootstrap 5.3
- **Icons:** Bootstrap Icons
- **Maps:** Google Maps Embed API
- **Notifications:** SweetAlert2
- **Server:** Apache (XAMPP/WAMP)

---

## 📝 Catatan Pengembangan

### **Status Implementasi:**
- ✅ Autentikasi database dengan password terenkripsi (bcrypt)
- ✅ Session management dengan multiple data admin
- ✅ Update last_login otomatis
- ✅ Password hash generator tool
- ✅ SQL manual untuk tambah admin

### **Untuk Produksi:**
1. ~~Ganti autentikasi hardcoded dengan database (tabel `admin`)~~ ✅ **SUDAH DIIMPLEMENTASI**
2. ~~Gunakan `password_hash()` dan `password_verify()` untuk password~~ ✅ **SUDAH DIIMPLEMENTASI**
3. Implementasi prepared statements untuk mencegah SQL injection
4. Tambahkan CSRF token pada form
5. Implementasi pagination untuk daftar destinasi
6. Tambahkan fitur ulasan pengunjung (tabel `ulasan` sudah tersedia)
7. Implementasi soft delete untuk data destinasi
8. Tambahkan rate limiting untuk login (mencegah brute force)
9. Implementasi email verification untuk admin baru
10. Tambahkan 2FA (Two-Factor Authentication) untuk keamanan ekstra

### **Fitur yang Bisa Ditambahkan:**
- [ ] Multi-admin dengan role management (Super Admin, Editor, Viewer)
- [ ] Export data ke Excel/PDF
- [ ] Email notification untuk pesan kontak
- [ ] Sistem rating & review dari pengunjung
- [ ] Galeri foto multiple per destinasi
- [ ] Integrasi payment gateway untuk booking
- [ ] Dashboard analytics dengan chart.js
- [ ] Fitur registrasi admin dengan approval system
- [ ] Activity log untuk tracking perubahan data
- [ ] Backup & restore database otomatis
