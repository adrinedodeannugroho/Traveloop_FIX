-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2026 at 02:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_traveloop_fix`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `destinasi`
--

CREATE TABLE `destinasi` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `alamat` text NOT NULL,
  `rating` decimal(3,1) DEFAULT 0.0,
  `deskripsi` text NOT NULL,
  `foto_url` varchar(255) DEFAULT NULL,
  `maps_url` varchar(255) DEFAULT NULL,
  `kontak` varchar(50) DEFAULT NULL,
  `tarif` varchar(100) DEFAULT NULL,
  `history` text DEFAULT NULL,
  `tips` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `destinasi`
--

INSERT INTO `destinasi` (`id`, `nama`, `kategori`, `alamat`, `rating`, `deskripsi`, `foto_url`, `maps_url`, `kontak`, `tarif`, `history`, `tips`, `created_at`) VALUES
(2, 'Hutan Pinus Limpakuwus', 'Alam', 'Limpakuwus, Sumbang, Kab. Banyumas, Jawa Tengah', 4.6, 'Destinasi alam yang sedang populer dengan suasana sejuk dan deretan pohon pinus menjulang tinggi. Cocok untuk liburan keluarga, tersedia wahana outbound, playground, dan camping ground.', '', '', '', 'Rp 17.500', 'Kawasan hutan pinus ini pada awalnya murni berfungsi sebagai hutan lindung di lereng selatan Gunung Slamet. Seiring berjalannya waktu, Perhutani bersama masyarakat desa setempat mengalihfungsikan sebagian area menjadi wana wisata tanpa merusak ekosistem pinus yang sudah berumur puluhan tahun.', 'Jam buka mulai 07.30 - 16.30 WIB. Waktu terbaik berkunjung adalah pagi hari saat kabut masih turun. Jangan lupa bawa jaket karena udara cukup dingin, serta siapkan uang tunai ekstra jika ingin mencoba wahana seperti ATV atau jembatan gantung.', '2026-05-22 12:33:00'),
(3, 'Telaga Sunyi', 'Alam', 'Limpakuwus, Sumbang, Kab. Banyumas, Jawa Tengah', 4.5, 'Spot hidden gem di Banyumas. Menawarkan keindahan alam yang menenangkan dengan air jernih kehijauan yang sangat ideal untuk bersantai dan merendam kaki.', '', '', '', 'Rp 15.000', 'Sesuai dengan namanya, Telaga Sunyi berada di pedalaman hutan yang jauh dari hiruk-pikuk kota. Mata air ini mengalir langsung dari mata air murni Gunung Slamet dan dijaga ketat kebersihannya oleh masyarakat lokal sejak puluhan tahun lalu.', 'Buka pukul 07.00 - 17.00 WIB. Disarankan datang saat musim kemarau agar warna air telaga memancarkan gradasi pirus (biru kehijauan) secara maksimal. Berhati-hatilah jika ingin berenang karena airnya sangat dingin dan beberapa titik cukup dalam.', '2026-05-22 12:33:00'),
(4, 'Pancuran Pitu Baturraden', 'Alam', 'Ketenger, Baturraden, Kab. Banyumas, Jawa Tengah', 4.4, 'Memiliki tujuh pancuran air panas (70°C - 90°C) alami dari perut Gunung Slamet. Tebingnya memiliki gradasi warna unik merah, kuning, dan hijau akibat belerang.', '', '', '', 'Rp 15.000', 'Tempat ini telah ditemukan sejak zaman kolonial sebagai sumber panas bumi alami. Bebatuan tebing berwarna terbentuk dari endapan belerang (sulfur) yang mengalir perlahan selama ratusan tahun, membentuk lanskap estetis sekaligus alami.', 'Kamu harus berjalan kaki menyusuri anak tangga yang cukup panjang dari area parkir. Di sana juga banyak jasa pijat belerang dari warga lokal yang dipercaya dapat menyembuhkan berbagai penyakit kulit dan pegal linu.', '2026-05-22 12:33:00'),
(5, 'Curug Song', 'Alam', 'Desa Kalisalak, Kec. Kebasen, Kab. Banyumas, Jawa Tengah', 4.3, 'Wisata air terjun hidden gem yang dikelilingi hutan pinus dan jati seluas 18 hektar. Memiliki wahana wisata lengkap untuk melepas penat.', '', '', '', 'Rp 15.000', 'Berawal dari potensi air terjun tersembunyi di pedesaan, Curug Song kini dikelola secara profesional oleh BUMDes setempat dan Perhutani. Ini menjadikannya salah satu ikon wisata alam baru di selatan Purwokerto.', 'Jarak dari Purwokerto sekitar 1 jam perjalanan. Akses jalan menuju lokasi sudah diaspal namun agak sempit di beberapa titik, pastikan kendaraan dalam keadaan prima. Sangat ramah di kantong!', '2026-05-22 12:33:00'),
(6, 'Goa Lawa Purbalingga (Golaga)', 'Sejarah', 'Desa Karangreja, Kec. Karangreja, Kab. Purbalingga, Jawa Tengah', 4.6, 'Goa purba alami yang dikemas dengan pencahayaan modern nan estetis. Dilengkapi taman bawah tanah dan wahana kekinian seperti Skyline.', '', '', '', 'Rp 20.000', 'Goa Lawa (Goa Kelelawar) merupakan goa vulkanik yang terbentuk dari proses pendinginan aliran lava Gunung Slamet di masa lampau. Dahulu goa ini sangat gelap, namun kini telah direvitalisasi dengan tata lampu LED modern yang memukau.', 'Buka setiap hari mulai pukul 08.00 WIB. Tiket akhir pekan biasanya naik menjadi Rp 25.000. Sangat direkomendasikan untuk mencoba wahana Boomerang Rainbow Slide dan Pine Slide Coaster di area luarnya.', '2026-05-22 12:33:00'),
(7, 'Pantai Menganti', 'Pantai', 'Desa Karangduwur, Kec. Ayah, Kab. Kebumen, Jawa Tengah', 4.8, 'Dikenal sebagai Selandia Baru-nya Indonesia. Pantai berpasir putih ini dikelilingi oleh perbukitan karst hijau dengan pemandangan tebing samudera Hindia.', '', '', '', 'Rp 20.000', 'Dulunya hanya pelabuhan kecil bagi para nelayan setempat. Karena keindahan perbukitan karst dan tebing pantainya yang tiada duanya di pesisir selatan Jawa, kawasan ini diresmikan sebagai objek wisata andalan Kabupaten Kebumen.', 'Biaya masuk umumnya sudah termasuk tiket parkir. Akses jalan menuju pantai ini sangat berkelok dan menanjak tajam, pastikan rem kendaraan (terutama motor matic) berfungsi sangat baik. Waktu terbaik adalah menjelang sunset.', '2026-05-22 12:33:00'),
(8, 'Benteng Van der Wijck', 'Sejarah', 'Sidayu, Kec. Gombong, Kab. Kebumen, Jawa Tengah', 4.4, 'Benteng peninggalan kolonial Belanda yang sangat ikonik dengan bentuk segi delapan (oktagonal) berwarna bata merah. Sering menjadi lokasi syuting film nasional.', '', '', '', 'Rp 25.000', 'Dibangun pada awal abad ke-19, benteng ini awalnya digunakan sebagai kantor VOC dan kemudian dialihfungsikan sebagai benteng pertahanan militer Belanda untuk meredam perlawanan Pangeran Diponegoro.', 'Di atas atap benteng terdapat wahana kereta mini yang bisa dinaiki untuk mengelilingi kompleks benteng dari ketinggian. Tempat ini sangat estetik untuk fotografi bertema vintage.', '2026-05-22 12:33:00'),
(9, 'Wisata Alam Waduk Jembangan', 'Alam', 'Kec. Poncowarno, Kab. Kebumen, Jawa Tengah', 4.5, 'Kawasan waduk asri yang dikelilingi hutan hijau. Terdapat restoran apung dan jembatan ikonik yang pas untuk liburan santai.', '', '', '', 'Rp 5.000', 'Waduk ini awalnya berfungsi murni sebagai saluran irigasi untuk mengairi lahan pertanian di Kebumen timur. Kini fungsinya diperluas sebagai kawasan konservasi air sekaligus wisata alam unggulan (Jembangan Wisata Alam).', 'Tiket masuknya sangat murah. Kamu bisa mencoba menaiki perahu naga atau bebek air untuk berkeliling waduk. Sangat direkomendasikan makan siang di restoran terapung dengan menu ikan air tawar.', '2026-05-22 12:33:00'),
(10, 'Kawah Sikidang Dieng', 'Alam', 'Desa Dieng Kulon, Kec. Batur, Kab. Banjarnegara, Jawa Tengah', 4.5, 'Fenomena kawah vulkanik aktif yang unik karena berpindah-pindah. Pengunjung dapat menikmati pemandangan asap belerang dari jembatan kayu estetik (boardwalk) yang membentang luas di atas kawasan kawah.', '', '', '', 'Rp 20.000', 'Nama Sikidang diambil dari karakteristik kawah utama yang kolam lumpur panasnya sering melompat-lompat atau berpindah tempat seperti seekor kidang (kijang). Kawasan ini terbentuk akibat aktivitas vulkanik dataran tinggi Dieng purba ribuan tahun silam.', 'Jam operasional pukul 07.00 - 17.00 WIB. Sangat disarankan membawa masker karena bau belerang di sekitar kawah cukup menyengat. Gunakan pakaian hangat karena suhu udara di Dieng bisa sangat dingin, terutama di pagi hari.', '2026-05-22 12:35:36'),
(11, 'Candi Arjuna Dieng', 'Sejarah', 'Desa Dieng Kulon, Kec. Batur, Kab. Banjarnegara, Jawa Tengah', 4.7, 'Kompleks candi Hindu tertua di Pulau Jawa yang terletak di dataran tinggi. Menawarkan pemandangan arsitektur kuno berselimut kabut tipis dengan latar belakang perbukitan hijau yang memukau.', '', '', '', 'Rp 20.000', 'Diperkirakan dibangun pada awal abad ke-9 masehi oleh Dinasti Sanjaya dari Kerajaan Mataram Kuno. Kompleks ini awalnya digunakan sebagai tempat pemujaan kepada Dewa Syiwa dan baru ditemukan kembali oleh tentara Inggris pada tahun 1814 dalam kondisi tergenang air.', 'Buka setiap hari dari pukul 06.00 hingga 17.00 WIB. Tiket masuk biasanya menjadi satu paket dengan Kawah Sikidang. Datanglah sekitar pukul 06.00 - 07.30 WIB untuk berburu momen golden hour dan pemandangan embun upas (embun es) jika beruntung di musim kemarau.', '2026-05-22 12:35:36'),
(12, 'Situ Tirta Marta', 'Alam', 'Desa Karangcegak, Kec. Kutasari, Kab. Purbalingga, Jawa Tengah', 4.4, 'Destinasi mata air alami hidden gem yang sangat jernih di pedesaan. Terkenal sebagai spot foto underwater (bawah air) kekinian dengan berbagai properti unik seperti motor dan sepeda di dalam air.', '', '', '', 'Rp 5.000', 'Mata air murni ini awalnya dimanfaatkan oleh warga lokal untuk kebutuhan irigasi sawah dan air minum sehari-hari. Potensi airnya yang sangat bening dan konstan sepanjang tahun akhirnya dikembangkan oleh pemuda desa menjadi destinasi wisata kreatif.', 'Buka pukul 07.00 - 17.00 WIB. Bawa kamera tahan air atau sewa casing waterproof di lokasi untuk berfoto di dalam air. Disarankan datang pada hari kerja (weekday) jika ingin menikmati suasana tenang dan air yang belum keruh oleh banyak perenang.', '2026-05-22 12:35:36'),
(13, 'Owabong Waterpark', 'Alam', 'Jl. Raya Bojongsari No.2, Kec. Bojongsari, Kab. Purbalingga, Jawa Tengah', 4.6, 'Taman rekreasi air terbesar di Jawa Tengah dengan sumber air alami tanpa kaporit. Menyediakan berbagai kolam tematik mulai dari kolam ombak, kolam arus, hingga wahana ekstrem kapsul luncur.', '', '', '', 'Rp 25.000', 'Owabong merupakan singkatan dari Obyek Wisata Bojongsari. Tempat ini awalnya merupakan situs pemandian kuno yang dibangun sejak zaman kolonial Belanda pada tahun 1946, kemudian diambil alih dan diperluas oleh Pemerintah Daerah menjadi waterpark modern pada tahun 2004.', 'Jam pelayanan mulai pukul 07.00 - 17.00 WIB. Harga tiket untuk akhir pekan atau hari libur nasional biasanya naik menjadi Rp 35.000. Datanglah sejak pagi hari agar bisa puas mencoba seluruh wahana air sebelum cuaca terlalu terik.', '2026-05-22 12:35:36'),
(14, 'Benteng Pendem Cilacap', 'Sejarah', 'Jl. Sentolokawat, Teluk Penyu, Kec. Cilacap Selatan, Kab. Cilacap, Jawa Tengah', 4.3, 'Benteng pertahanan bawah tanah peninggalan Belanda yang masih kokoh. Memiliki arsitektur lorong parit, ruang penjara, barak militer, dan dikelilingi pagar pohon rindang yang dihuni oleh banyak rusa jinak.', '', '', '', 'Rp 7.500', 'Memiliki nama asli Kusbatterij op de Landtong te Tjilatjap, benteng ini dibangun secara bertahap oleh pemerintah Hindia Belanda dari tahun 1861 hingga 1879. Benteng ini sempat tertimbun pasir pantai sebelum akhirnya digali kembali dan dibuka untuk umum pada tahun 1987.', 'Buka setiap hari dari pukul 08.00 hingga 18.00 WIB. Anda bisa membeli wortel di gerbang masuk jika ingin memberi makan rusa secara langsung di dalam kompleks benteng. Gunakan alas kaki yang nyaman karena area benteng cukup luas untuk dijelajahi.', '2026-05-22 12:35:36'),
(15, 'Pantai Teluk Penyu', 'Pantai', 'Kec. Cilacap Selatan, Kab. Cilacap, Jawa Tengah', 4.2, 'Pantai ikonik di Cilacap dengan panorama langsung menghadap ke Pulau Nusakambangan. Pengunjung bisa menikmati ombak landai, kuliner seafood segar, atau menyewa perahu nelayan untuk menyeberang.', '', '', '', 'Rp 5.000', 'Dinamakan Teluk Penyu karena dahulu pantai ini menjadi tempat bersarang dan bertelurnya ribuan penyu hijau samudera. Meskipun populasi penyu bermigrasi seiring padatnya jalur pelayaran industri, nama ini tetap melekat sebagai identitas wisata Cilacap.', 'Akses terbuka selama 24 jam, namun jam pelayanan loket utama dan perahu penyeberangan beroperasi pukul 06.00 - 18.00 WIB. Jika ingin menyeberang ke Pulau Nusakambangan (Pantai Pasir Putih), siapkan biaya sewa perahu sekitar Rp 30.000 - Rp 40.000 per orang pulang-pergi.', '2026-05-22 12:35:36'),
(16, 'Goa Jatijajar', 'Alam', 'Jl. Jatijajar, Kec. Ayah, Kab. Kebumen, Jawa Tengah', 4.5, 'Goa kapur alam yang megah dengan stalaktit dan stalagmit yang terbentuk selama ratusan tahun. Di dalam goa terdapat diorama legenda Raden Kamandaka serta empat mata air (sendang) murni.', '', '', '', 'Rp 12.500', 'Goa ini ditemukan pertama kali pada tahun 1802 oleh seorang petani setempat. Di dalam goa sengaja dibangun patung diorama kisah Lutung Kasarung (Raden Kamandaka) untuk melestarikan cerita rakyat yang dipercaya memiliki keterkaitan historis dengan tempat ini.', 'Jam operasional resmi pukul 07.30 - 16.00 WIB. Jalur di dalam goa sudah dilengkapi tangga beton dan lampu penerangan yang baik, namun lantai bisa menjadi cukup licin akibat tetesan air kapur dari langit-langit goa, jadi harap berhati-hati.', '2026-05-22 12:35:36'),
(17, 'Curug Cipendok', 'Alam', 'Desa Karangtengah, Kec. Cilongok, Kab. Banyumas, Jawa Tengah', 4.5, 'Salah satu air terjun tertinggi di Barlingmascakeb dengan ketinggian mencapai 92 meter. Terletak tersembunyi di lereng Gunung Slamet, menawarkan suasana hutan hujan tropis yang murni dan asri.', 'uploads/1779453461_cc14366c-939e-45d7-bdd4-ce9576661c62.jpg', '', '', 'Rp 12.500', 'Nama Cipendok berasal dari legenda lokal tentang pencarian keris pusaka milik Raden Ranusentika yang cincin pengikat warangkanya (pendok) jatuh dan terlempar ke dalam pusaran air terjun ini selama masa pemerintahan kolonial purba.', 'Buka setiap hari mulai pukul 07.00 - 16.00 WIB. Karena lokasinya berada di tengah hutan lindung, sering kali terdengar suara satwa langka seperti Elang Jawa dan Owa Jawa. Bawa baju ganti jika berencana mendekat ke kolam air terjun karena hempasan angin dan embun airnya sangat kuat.', '2026-05-22 12:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `pesan_kontak`
--

CREATE TABLE `pesan_kontak` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `no_wa` varchar(20) DEFAULT NULL,
  `topik` varchar(100) DEFAULT NULL,
  `pesan` text NOT NULL,
  `tanggal` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesan_kontak`
--

INSERT INTO `pesan_kontak` (`id`, `nama`, `email`, `no_wa`, `topik`, `pesan`, `tanggal`) VALUES
(1, 'Imam', 'babykoalaaa@gmail.com', '082156789955', 'Kerjasama / Partnership', 'ayo kita berkerja sama ', '2026-05-21 15:54:26'),
(2, 'Baby Koala', 'baby@gmail.com', '082156789958', 'Laporkan Bug / Error', 'File admin.css bawaan yang lama dirancang untuk tema sidebar berwarna gelap (sehingga warna teks bawaannya adalah putih). Ketika kita memperbarui sidebar menjadi warna putih cerah (bg-white) agar terlihat modern, teksnya tetap berwarna putih sehingga menjadi seolah-olah \"menghilang\" (putih di atas putih).', '2026-05-21 15:55:53'),
(4, 'izul', 'izulrobet@gmail.com', '082156789966', 'Kerjasama / Partnership', 'Ayo Kita Bekerja sama', '2026-05-22 03:11:20');

-- --------------------------------------------------------

--
-- Table structure for table `ulasan`
--

CREATE TABLE `ulasan` (
  `id` int(11) NOT NULL,
  `id_destinasi` int(11) NOT NULL,
  `nama_pengunjung` varchar(100) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `komentar` text NOT NULL,
  `tanggal` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `destinasi`
--
ALTER TABLE `destinasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_destinasi` (`id_destinasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `destinasi`
--
ALTER TABLE `destinasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pesan_kontak`
--
ALTER TABLE `pesan_kontak`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ulasan`
--
ALTER TABLE `ulasan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ulasan`
--
ALTER TABLE `ulasan`
  ADD CONSTRAINT `ulasan_ibfk_1` FOREIGN KEY (`id_destinasi`) REFERENCES `destinasi` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
