-- ------------------------------------------------------
-- Database: db_perpustakaan
-- Perpustakaan SMK Negeri 1 Tirtamulya
-- Tanggal Dibuat: 2026-09-17 08:01:10
-- ------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `db_perpustakaan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_perpustakaan`;

-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: perpustakaan_smk
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `buku`
--

DROP TABLE IF EXISTS `buku`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `buku` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_buku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_inventaris` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penulis` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `penerbit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_terbit` year DEFAULT NULL,
  `kelas` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kurikulum` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sumber` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_masuk` date DEFAULT NULL,
  `nomor_rak` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` enum('lks_paket','referensi','karya_fiksi','umum') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'umum',
  `isbn` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stok` int NOT NULL DEFAULT '1',
  `stok_tersedia` int NOT NULL DEFAULT '1',
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `buku_isbn_unique` (`isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `buku`
--

LOCK TABLES `buku` WRITE;
/*!40000 ALTER TABLE `buku` DISABLE KEYS */;
INSERT INTO `buku` VALUES (1,'BK-0001','INV/2021/0001','Algoritma & Pemrograman','Prof. Rinaldi Munir','Informatika',2021,'X','Kurikulum Merdeka','BOS 2021','Umum','2026-09-15','Rak R-1','referensi','978-602-1152-40-1',5,0,'covers/Ft0sgg34jFmtv9gMvk4CPRKgvyIFBRpBfCZ8xVns.png',NULL,'2026-09-15 10:37:47','2026-09-15 23:03:36'),(2,'BK-0002','INV/2022/0002','Fisika Modern Kelas XI','Kementerian Pendidikan','Kemdikbud',2022,'XI','Kurikulum Merdeka','BOS 2022','Umum','2026-09-15','Rak R-1','lks_paket','978-602-282-900-2',30,28,NULL,NULL,'2026-09-15 10:37:47','2026-09-16 00:33:15'),(3,'BK-0003','INV/2005/0003','Laskar Pelangi','Andrea Hirata','Bentang Pustaka',2005,'Semua','Kurikulum Merdeka','BOS 2005','Umum','2026-09-15','Rak R-1','karya_fiksi','978-979-1637-68-8',3,2,NULL,NULL,'2026-09-15 10:37:47','2026-09-16 00:33:15'),(4,'BK-0004','INV/2020/0004','Panduan Praktis Jaringan Komputer','Agus Wibowo','Andi Publisher',2020,'Semua','Kurikulum Merdeka','BOS 2020','Umum','2026-09-15','Rak R-1','referensi','978-979-29-6400-1',4,4,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(5,'BK-0005','INV/2022/0005','Matematika Kelas XII','Kementerian Pendidikan','Kemdikbud',2022,'Semua','Kurikulum Merdeka','BOS 2022','Umum','2026-09-15','Rak R-2','lks_paket','978-602-282-901-9',25,23,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(6,'BK-0006','INV/2019/0006','Pengantar Akuntansi Masa Kini','Warren Reeve Duchac','Salemba Empat',2019,'Semua','Kurikulum Merdeka','BOS 2019','Umum','2026-09-15','Rak R-2','referensi','978-979-061-520-1',6,5,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(7,'BK-0007','INV/2020/0007','Dasar Jaringan Komputer','Supriyanto','Yudhistira',2020,'Semua','Kurikulum Merdeka','BOS 2020','Umum','2026-09-15','Rak R-2','referensi','978-979-391-750-3',4,3,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(8,'BK-0008','INV/2021/0008','Bahasa Indonesia Kelas X','Kementerian Pendidikan','Kemdikbud',2021,'Semua','Kurikulum Merdeka','BOS 2021','Umum','2026-09-15','Rak R-2','lks_paket','978-602-282-902-6',30,29,NULL,NULL,'2026-09-15 10:37:47','2026-09-16 03:24:33'),(9,'BK-0009','INV/2006/0009','Bumi Manusia','Pramoedya Ananta Toer','Lentera Dipantara',2006,'Semua','Kurikulum Merdeka','BOS 2006','Umum','2026-09-15','Rak R-3','karya_fiksi','978-979-99738-4-0',2,1,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(10,'BK-0010','INV/2021/0010','Pemrograman Web dengan PHP','Budi Raharjo','Informatika',2021,'Semua','Kurikulum Merdeka','BOS 2021','Umum','2026-09-15','Rak R-3','referensi','978-602-1152-41-8',3,3,NULL,NULL,'2026-09-15 10:37:47','2026-09-15 22:53:29'),(14,'NO','TANGGAL','PENGARANG','JUDUL BUKU','PENERBIT',2026,'Semua','KURIKULUM','NO INVENTARIS','SUMBER','2026-09-16','RAK-01','umum',NULL,1,1,NULL,NULL,'2026-09-15 23:43:06','2026-09-15 23:43:06'),(15,'1','45138','Agus Trihatmoko','Kewirausahaan','Upp Stim',2017,'XII','2013','338.04','Umum','2026-09-16','RAK-01','umum',NULL,1,0,NULL,NULL,'2026-09-15 23:43:06','2026-09-15 23:54:00'),(16,'215','45338','Angelia Merici Fina Indriani','Dasar Dasar Akuntansi Dan Keuangan Lembaga','Liniswara',2022,'X','Merdeka','657.48','Umum','2026-09-16','RAK-01','umum',NULL,18,17,NULL,NULL,'2026-09-15 23:43:07','2026-09-16 03:18:10'),(17,'227','46325','Taryana','Kumpulan Cerpen (Kertukan Pintu)','Gemala',2022,'Semua','-','813','Umum','2026-09-16','RAK-01','umum',NULL,10,10,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(18,'228','46160','Taryana','Kumpulan Puisi (ranting apelangi)','Gemala',2022,'Semua','-','813','Hibah','2026-09-16','RAK-01','umum',NULL,10,10,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(19,'241','46254','Boy Candra','Bu, Tidak Ada Teman Menangis Malam Ini','Grasindo Map plus Bandung',2023,'Semua','-','813','Hibah','2026-09-16','RAK-01','umum',NULL,1,1,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(20,'303','45989','Anang Santoso','Bahasa Indonesia','Universitas Terbuka',2021,'Semua','-','410','Hibah','2026-09-16','RAK-01','umum',NULL,1,0,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:54:27'),(21,'315','46003','Eko Adi Saputro','Koding Dan KA','Andi Yogyakarta',2025,'X','Nasional','004.69','Bos 2025','2026-09-16','RAK-01','umum',NULL,72,72,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(22,'330','46008','Rohmat Chozin','Pendidikan Agama Islam Dan Budi Pekerti','Kemendikbud',2022,'XII','Nasional','297','Bos 2025','2026-09-16','RAK-01','umum',NULL,100,100,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(23,'344','46020','Rohmat Chizin','Pendidikan Agama Islam Dan Budi Pekerti','Kemendikbud',2026,'XII','Nasional','297','Bos 2025','2026-09-16','RAK-01','umum',NULL,36,36,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(24,'369','46085','Ibnu Indrawati','Dasar-Dasar Teknik Jaringan Komputer dan Telokomunikasi','Kemendikbud',2023,'X','Nasional','624.28','Bos 2025','2026-09-16','RAK-01','umum',NULL,5,5,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07'),(25,'373','INV/2026/0011','Eni Nuraeni','Projek Ipas','Kemendikbud',2023,'X','Nasional','500','Bos 2025','2026-09-16','RAK-01','umum',NULL,402,402,NULL,NULL,'2026-09-15 23:43:07','2026-09-15 23:43:07');
/*!40000 ALTER TABLE `buku` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2026_09_15_172642_create_buku_table',1),(6,'2026_09_15_172642_create_peminjam_table',1),(7,'2026_09_15_172642_create_peminjaman_table',1),(8,'2026_09_15_172643_add_role_avatar_to_users_table',1),(9,'2026_09_15_172643_create_pengunjung_table',1),(10,'2026_09_15_180222_add_username_to_users_table',2),(11,'2026_09_16_130000_add_detail_fields_to_buku_table',3);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjam`
--

DROP TABLE IF EXISTS `peminjam`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjam` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `kelas_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nis_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telepon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjam`
--

LOCK TABLES `peminjam` WRITE;
/*!40000 ALTER TABLE `peminjam` DISABLE KEYS */;
INSERT INTO `peminjam` VALUES (9,'Entus Azi Bachtiar','siswa','X IPA 3',NULL,NULL,NULL,'2026-09-15 19:42:27','2026-09-15 19:42:27'),(13,'Husen','siswa','X IPA 5',NULL,NULL,NULL,'2026-09-15 20:35:06','2026-09-15 20:35:06'),(14,'Syahdan','siswa','X IPA 5',NULL,NULL,NULL,'2026-09-15 20:56:28','2026-09-15 20:56:28'),(15,'Upin','siswa','X IPA 7',NULL,NULL,NULL,'2026-09-15 20:58:39','2026-09-15 20:58:39'),(16,'Dani','siswa','X IPA 7','0232038232','0383874762','Jl.Sudirman','2026-09-15 21:04:49','2026-09-15 21:04:49'),(18,'Desi Salaswati','guru','Guru Matematika','8372626283618','0818282837827','Jl.Sudirman Raya','2026-09-16 03:18:10','2026-09-16 03:18:10'),(19,'Rina Novianti','guru','Guru Fisika','02893819238','0811128392293','Jl. Raya Karawang Timur','2026-09-16 03:24:33','2026-09-16 03:24:33'),(20,'Keanu Rasyada','guru','Kepala Sekolah','02837719273','02823728718372','Kota Bogor','2026-09-16 03:34:13','2026-09-16 03:34:13');
/*!40000 ALTER TABLE `peminjam` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peminjaman`
--

DROP TABLE IF EXISTS `peminjaman`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `peminjaman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `peminjam_id` bigint unsigned NOT NULL,
  `buku_id` bigint unsigned NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `tanggal_kembali_aktual` date DEFAULT NULL,
  `status` enum('dipinjam','kembali','terlambat') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dipinjam',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peminjaman_peminjam_id_foreign` (`peminjam_id`),
  KEY `peminjaman_buku_id_foreign` (`buku_id`),
  CONSTRAINT `peminjaman_buku_id_foreign` FOREIGN KEY (`buku_id`) REFERENCES `buku` (`id`) ON DELETE CASCADE,
  CONSTRAINT `peminjaman_peminjam_id_foreign` FOREIGN KEY (`peminjam_id`) REFERENCES `peminjam` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peminjaman`
--

LOCK TABLES `peminjaman` WRITE;
/*!40000 ALTER TABLE `peminjaman` DISABLE KEYS */;
INSERT INTO `peminjaman` VALUES (9,9,1,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 20:13:49','2026-09-15 20:13:49'),(10,9,8,'2026-09-01','2026-09-14','2026-09-16','terlambat',NULL,'2026-09-15 22:12:02','2026-09-15 22:12:22'),(11,13,1,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 23:03:11','2026-09-15 23:03:11'),(12,14,1,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 23:03:25','2026-09-15 23:03:25'),(13,15,1,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 23:03:36','2026-09-15 23:03:36'),(14,16,15,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 23:54:00','2026-09-15 23:54:00'),(15,16,20,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-15 23:54:27','2026-09-15 23:54:27'),(20,18,16,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-16 03:18:10','2026-09-16 03:18:10'),(21,19,8,'2026-09-16','2026-09-23',NULL,'dipinjam',NULL,'2026-09-16 03:24:33','2026-09-16 03:24:33');
/*!40000 ALTER TABLE `peminjaman` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pengunjung`
--

DROP TABLE IF EXISTS `pengunjung`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pengunjung` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipe` enum('guru','siswa') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'siswa',
  `kelas_jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nis_nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_kunjungan` date NOT NULL,
  `waktu_masuk` time NOT NULL,
  `waktu_keluar` time DEFAULT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pengunjung`
--

LOCK TABLES `pengunjung` WRITE;
/*!40000 ALTER TABLE `pengunjung` DISABLE KEYS */;
INSERT INTO `pengunjung` VALUES (1,'Hendra Wijaya','guru','Wakil Kepala Sekolah',NULL,'2026-09-15','08:15:00',NULL,NULL,'2026-09-15 10:37:47','2026-09-15 10:37:47'),(2,'Riska Amelia','siswa','X RPL 2',NULL,'2026-09-15','09:30:00',NULL,NULL,'2026-09-15 10:37:47','2026-09-15 10:37:47'),(3,'Bambang Prakoso','guru','Guru B. Indonesia',NULL,'2026-09-15','10:05:00',NULL,NULL,'2026-09-15 10:37:47','2026-09-15 10:37:47'),(4,'Ahmad Fauzi','siswa','XI RPL 1',NULL,'2026-09-14','09:00:00',NULL,NULL,'2026-09-15 10:37:47','2026-09-15 10:37:47'),(5,'Siti Aminah','siswa','XII AKL 2',NULL,'2026-09-14','13:00:00',NULL,NULL,'2026-09-15 10:37:47','2026-09-15 10:37:47'),(6,'Entus Azi Bachtiar','siswa','X IPA 3',NULL,'2026-09-16','02:43:00',NULL,'tidur','2026-09-15 19:43:23','2026-09-15 19:43:23'),(11,'Husen','siswa','X IPA 5',NULL,'2026-09-16','03:35:06',NULL,'Membaca buku di tempat','2026-09-15 20:35:06','2026-09-15 20:35:06'),(12,'Syahdan','siswa','X IPA 5',NULL,'2026-09-16','03:56:28',NULL,'Rumpi','2026-09-15 20:56:28','2026-09-15 20:56:28'),(13,'Upin','siswa','X IPA 7',NULL,'2026-09-16','03:57:00',NULL,'Membaca','2026-09-15 20:58:39','2026-09-15 20:58:39'),(14,'Dani','siswa','X IPA 7','0232038232','2026-09-16','04:04:49',NULL,'Membaca buku di tempat','2026-09-15 21:04:49','2026-09-15 21:04:49'),(16,'Desi Salaswati','guru','Guru Matematika','8372626283618','2026-09-16','10:18:10',NULL,'Meminjam 1 buku: Angelia Merici Fina Indriani','2026-09-16 03:18:10','2026-09-16 03:18:10'),(17,'Rina Novianti','guru','Guru Fisika','02893819238','2026-09-16','10:24:33',NULL,'Meminjam 1 buku: Bahasa Indonesia Kelas X','2026-09-16 03:24:33','2026-09-16 03:24:33'),(18,'Rina Novianti','guru','Guru Fisika','02893819238','2026-09-16','10:24:00',NULL,'Membaca buku di tempat','2026-09-16 03:25:11','2026-09-16 03:25:11'),(19,'Desi Salaswati','guru','Guru Matematika','8372626283618','2026-09-16','10:26:00',NULL,'Membaca buku di tempat','2026-09-16 03:27:16','2026-09-16 03:27:16'),(20,'Keanu Rasyada','guru','Kepala Sekolah','02837719273','2026-09-16','10:33:00',NULL,'Membaca buku di tempat','2026-09-16 03:34:13','2026-09-16 03:34:13');
/*!40000 ALTER TABLE `pengunjung` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pustakawan',
  `jabatan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Budi Santoso','admin_smk','budi@smknusantara.sch.id','pustakawan','Kepala Pustaka',NULL,NULL,'$2y$10$tYBKiP8v9vN66o3vQF9oIuYtQ15m5/.grdalT6wdaUYr2sLQMURa2',NULL,'2026-09-15 10:37:47','2026-09-15 11:06:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-17 15:00:36
