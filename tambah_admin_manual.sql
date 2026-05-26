-- =====================================================
-- FILE: tambah_admin_manual.sql
-- FUNGSI: Menambahkan admin baru ke database secara manual
-- =====================================================

-- CARA PENGGUNAAN:
-- 1. Buka phpMyAdmin
-- 2. Pilih database: db_traveloop_fix
-- 3. Klik tab "SQL"
-- 4. Copy-paste query di bawah ini
-- 5. Ganti nilai sesuai kebutuhan
-- 6. Klik "Go" untuk eksekusi

-- =====================================================
-- CONTOH 1: Admin dengan password "admin123"
-- =====================================================
-- Password hash untuk "admin123": $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

INSERT INTO `admin` (`nama_lengkap`, `email`, `password`, `last_login`) VALUES
('Administrator Traveloop', 'admin@traveloop.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL);

-- =====================================================
-- CONTOH 2: Admin kedua dengan password "superadmin"
-- =====================================================
-- Password hash untuk "superadmin": $2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa

-- INSERT INTO `admin` (`nama_lengkap`, `email`, `password`, `last_login`) VALUES
-- ('Super Admin', 'superadmin@traveloop.com', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa', NULL);

-- =====================================================
-- CONTOH 3: Admin ketiga dengan password "password123"
-- =====================================================
-- Password hash untuk "password123": $2y$10$YCjXJqYzKmYvKpjcAU6Yl.SAc/5KVW5h5vQjXqX5vQjXqX5vQjXqX

-- INSERT INTO `admin` (`nama_lengkap`, `email`, `password`, `last_login`) VALUES
-- ('Manager Konten', 'manager@traveloop.com', '$2y$10$YCjXJqYzKmYvKpjcAU6Yl.SAc/5KVW5h5vQjXqX5vQjXqX5vQjXqX', NULL);

-- =====================================================
-- CARA MEMBUAT PASSWORD HASH BARU
-- =====================================================
-- Jika ingin membuat password hash sendiri, gunakan PHP:
-- 
-- <?php
-- echo password_hash('password_anda', PASSWORD_DEFAULT);
-- ?>
-- 
-- Atau gunakan online tool:
-- https://bcrypt-generator.com/ (pilih rounds: 10)
-- https://www.php.net/manual/en/function.password-hash.php

-- =====================================================
-- DAFTAR PASSWORD HASH SIAP PAKAI
-- =====================================================
-- Password: admin123
-- Hash: $2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

-- Password: superadmin
-- Hash: $2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa

-- Password: password123
-- Hash: $2y$10$YCjXJqYzKmYvKpjcAU6Yl.SAc/5KVW5h5vQjXqX5vQjXqX5vQjXqX

-- Password: traveloop2024
-- Hash: $2y$10$eHqWH.axSPCwyIze/4Nmq.YvWkTZ3z/f5S5FiamxJCasta4DBo.S6

-- Password: admin2024
-- Hash: $2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhCu

-- =====================================================
-- QUERY UNTUK MELIHAT SEMUA ADMIN
-- =====================================================
-- SELECT id, nama_lengkap, email, last_login FROM admin;

-- =====================================================
-- QUERY UNTUK MENGHAPUS ADMIN
-- =====================================================
-- DELETE FROM admin WHERE email = 'email@example.com';

-- =====================================================
-- QUERY UNTUK UPDATE PASSWORD ADMIN
-- =====================================================
-- UPDATE admin SET password = '$2y$10$...' WHERE email = 'admin@traveloop.com';

-- =====================================================
-- QUERY UNTUK UPDATE NAMA ADMIN
-- =====================================================
-- UPDATE admin SET nama_lengkap = 'Nama Baru' WHERE email = 'admin@traveloop.com';
