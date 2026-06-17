-- ============================================================
-- Traveloop — Setup Tabel Users & Wishlist
-- Jalankan SQL ini di phpMyAdmin pada database db_traveloop_fix
-- ============================================================

-- 1. Tabel Users (Akun Pengunjung / Wisatawan)
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Wishlist (Relasi User ↔ Destinasi)
CREATE TABLE IF NOT EXISTS `wishlist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `destinasi_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_destinasi` (`user_id`, `destinasi_id`),
  KEY `fk_wishlist_user` (`user_id`),
  KEY `fk_wishlist_destinasi` (`destinasi_id`),
  CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_wishlist_destinasi` FOREIGN KEY (`destinasi_id`) REFERENCES `destinasi` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
