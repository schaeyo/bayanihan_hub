-- MySQL dump 10.13  Distrib 8.0.41, for Win64 (x86_64)
--
-- Host: localhost    Database: bayanihan_hub
-- ------------------------------------------------------
-- Server version	8.0.41

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `analytics_data`
--

DROP TABLE IF EXISTS `analytics_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `analytics_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `male_population` int NOT NULL,
  `female_population` int NOT NULL,
  `occupied_residential` int NOT NULL,
  `vacant_residential` int NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `children_male` int DEFAULT '0',
  `children_female` int DEFAULT '0',
  `youth_male` int DEFAULT '0',
  `youth_female` int DEFAULT '0',
  `adults_male` int DEFAULT '0',
  `adults_female` int DEFAULT '0',
  `seniors_male` int DEFAULT '0',
  `seniors_female` int DEFAULT '0',
  `single_family_units` int DEFAULT '0',
  `multi_family_units` int DEFAULT '0',
  `apartment_units` int DEFAULT '0',
  `occupied_units` int DEFAULT '0',
  `vacant_units` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `analytics_data`
--

LOCK TABLES `analytics_data` WRITE;
/*!40000 ALTER TABLE `analytics_data` DISABLE KEYS */;
INSERT INTO `analytics_data` VALUES (1,6000,5000,1500,740,'2025-04-18 02:01:21',0,0,0,0,0,0,0,0,0,0,0,0,0),(2,6000,5000,1500,740,'2025-04-18 10:13:45',0,0,0,0,0,0,0,0,0,0,0,0,0),(3,6000,5000,500,740,'2025-04-19 15:48:31',0,0,0,0,0,0,0,0,0,0,0,0,0),(4,6000,6000,1200,300,'2025-04-28 03:23:57',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(5,5000,4900,1200,300,'2025-04-28 03:24:17',500,300,1200,1300,2800,2600,500,700,750,350,400,0,0),(6,6000,6000,1200,300,'2025-04-28 03:29:31',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(7,6000,6000,1200,300,'2025-04-28 03:30:56',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(8,6000,6000,1200,300,'2025-04-28 03:35:12',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(9,6000,6000,1200,300,'2025-04-28 03:35:47',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(10,6000,6000,1200,300,'2025-04-28 03:38:29',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(11,6000,6000,1200,300,'2025-04-28 03:40:38',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(12,6000,6000,1200,300,'2025-04-28 03:42:31',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(13,6000,6000,1200,300,'2025-04-28 03:42:33',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(14,6000,6000,1200,300,'2025-04-28 03:43:13',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(15,6000,6000,1200,300,'2025-04-28 03:43:22',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(16,6000,6000,1200,300,'2025-04-28 03:43:49',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(17,6000,6000,1200,300,'2025-04-28 03:45:56',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(18,6000,6000,1200,300,'2025-04-28 03:46:15',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(19,6000,6000,1200,300,'2025-04-28 03:46:48',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0),(20,6000,6000,1200,300,'2025-04-28 04:41:45',1500,1400,1200,1300,2800,2600,500,700,750,350,400,0,0);
/*!40000 ALTER TABLE `analytics_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `file_path` varchar(255) DEFAULT NULL,
  `schedule_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `category` enum('Barangay Events','Public Notice','Emergency Alerts','Community Program') NOT NULL DEFAULT 'Barangay Events',
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (30,'LIBRENG TULI','Libreng Tuli para sa mga supot na mamamayan ng qc','announcement/announcement_680ede315fa738.67697261.jpg','2025-07-12','2025-04-28 01:47:29','Community Program','2025-04-28 10:05:43'),(31,'? Viva Santa Lucia‼️','? Happy Fiesta Sta. Lucia‼️\r\nGreetings from:\r\nRM PERFORMANCE TEAM &\r\nRM SK Team','announcement/announcement_680edee64d7a01.19255258.jpg','2025-04-28','2025-04-28 01:50:30','Barangay Events',NULL),(34,'FAMILY PLANNING','Ginanap ngayon March 19, 2025, ganap na 8:00 am hanggang 12:00 pm sa ating Barangay Basketball Court.\r\nIto po ay programa ng ating Coun. Joseph Visaya at Eagles Q Bagwis Fairview Quatro, Executive Eagles Club.\r\nSa pakikipagtulungan ni Kapitan Ruel S. Marpa  at Barangay Council, Quezon City Health Department at GAD Sta. Lucia. Kasamang nakiisa sina Kgd. Tess Bawag , Kgd. JaJa Galarpe , Kgd. Noeme Salonga, Kgd. Jenny Dela Torre , Secretary April Lane Banaba at Treasurer Alyssa Kaye Garduque .','announcement/announcement_680ee5a246d667.96922107.jpg','2025-04-28','2025-04-28 02:19:14','Barangay Events',NULL);
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `appointment_date` date NOT NULL,
  `message` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(20) DEFAULT 'Pending',
  `services` enum('Barangay ID','Police Assistance','Health Services','Senior Citizen Services','Business Registration','Funeral Request','Barangay Inquiries and Requests','Event Permit','National ID Registration Assistance','Mediation / Settlement','Complaint Filing') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,'son chaeyoung','mnavarette00@gmail.com','','2025-04-28','may loko loko','2025-04-28 04:48:56','Pending','Barangay ID');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `emergency_reports`
--

DROP TABLE IF EXISTS `emergency_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `emergency_reports` (
  `emergency_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `report_date` date NOT NULL,
  `report_time` time NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `type_of_emergency` enum('Fire','Medical','Crime','Natural Disaster','Other') NOT NULL,
  `location` text NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `user_id` int NOT NULL,
  PRIMARY KEY (`emergency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `emergency_reports`
--

LOCK TABLES `emergency_reports` WRITE;
/*!40000 ALTER TABLE `emergency_reports` DISABLE KEYS */;
INSERT INTO `emergency_reports` VALUES (1,'son','navarette','chaeyoung','','2025-04-28','12:48:00','09232425857','mnavarette00@gmail.com','emergency_photos/sunog.jpg','Fire','barangay hall','sunog','2025-04-28 04:48:31','Pending',2);
/*!40000 ALTER TABLE `emergency_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `feedback_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `feedback_date` date NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `rating` enum('5 Very Satisfied','4 Satisfied','3 Neutral','2 Dissatisfied','1 Very Dissatisfied') NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Reviewed') NOT NULL DEFAULT 'Pending',
  `user_id` int NOT NULL,
  PRIMARY KEY (`feedback_id`),
  CONSTRAINT `feedback_chk_1` CHECK ((`rating` between 1 and 5))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
INSERT INTO `feedback` VALUES (1,'son','navarette','chaeyoung','','2025-04-28','09232425857','mnavarette00@gmail.com','korean','5 Very Satisfied','nice','2025-04-28 04:49:13','Pending',2),(2,'son','navarette','chaeyoung','','2025-04-28','09232425857','mnavarette00@gmail.com','korean','5 Very Satisfied','very nice','2025-04-28 04:54:54','Pending',2),(3,'son','navarette','chaeyoung','','2025-04-28','09232425857','mnavarette00@gmail.com','korean','5 Very Satisfied','nc','2025-04-28 04:55:34','Pending',2);
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `incident_report`
--

DROP TABLE IF EXISTS `incident_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `incident_report` (
  `incident_id` int NOT NULL,
  `request_id` int NOT NULL,
  `resident_name` varchar(100) NOT NULL,
  `category` varchar(50) NOT NULL,
  `status` enum('APPROVED','REJECT','PENDING') NOT NULL,
  `DATE` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`incident_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `incident_report`
--

LOCK TABLES `incident_report` WRITE;
/*!40000 ALTER TABLE `incident_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `incident_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('unread','read') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `password_resets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES (1,4,'736398','2025-04-25 20:25:10',0,'2025-04-25 18:15:10'),(2,4,'653163','2025-04-25 20:28:09',0,'2025-04-25 18:18:09'),(3,4,'592741','2025-04-25 20:30:33',0,'2025-04-25 18:20:33'),(4,4,'643934','2025-04-25 20:34:13',0,'2025-04-25 18:24:13'),(5,4,'988438','2025-04-25 20:36:27',0,'2025-04-25 18:26:27'),(6,4,'674581','2025-04-25 20:38:36',0,'2025-04-25 18:28:36'),(7,4,'844666','2025-04-25 20:52:28',0,'2025-04-25 18:42:28'),(8,4,'843160','2025-04-25 20:58:31',0,'2025-04-25 18:48:31'),(9,4,'287832','2025-04-25 21:03:41',0,'2025-04-25 18:53:41'),(10,4,'508728','2025-04-25 21:06:38',0,'2025-04-25 18:56:38');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `requests`
--

DROP TABLE IF EXISTS `requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `requests` (
  `request_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `birthdate` date NOT NULL,
  `age` int DEFAULT NULL,
  `birth_place` varchar(100) NOT NULL,
  `citizenship` varchar(50) NOT NULL,
  `civil_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `gender` enum('Male','Female','LGBTQIA+') NOT NULL,
  `email` varchar(100) NOT NULL,
  `residence_since` date DEFAULT NULL,
  `residence_duration` int DEFAULT NULL,
  `type_of_request` enum('Barangay Clearance','Business Permit','Indigency Certificate','Other') NOT NULL,
  `valid_id_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `date_submitted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `requests`
--

LOCK TABLES `requests` WRITE;
/*!40000 ALTER TABLE `requests` DISABLE KEYS */;
INSERT INTO `requests` VALUES (1,'son','navarette','chaeyoung','','1999-04-23',26,'korea','korean','Single','Male','mnavarette00@gmail.com','2025-04-28',1,'Barangay Clearance','req_photos_resident/announcement.webp','2025-04-28 04:48:03','Pending','2025-04-28 12:48:03');
/*!40000 ALTER TABLE `requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `role` enum('ADMIN','RESIDENT') DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `name_extension` varchar(10) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `civil_status` enum('Single','Married','Widowed','Separated','Divorced') NOT NULL,
  `gender` enum('Male','Female','LGBTQIA+') NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `occupation` varchar(100) DEFAULT NULL,
  `barangay` varchar(100) DEFAULT NULL,
  `profile_photo` varchar(255) DEFAULT 'default.jpg',
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(100) DEFAULT NULL,
  `residence_since` varchar(255) DEFAULT NULL,
  `house_address` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `reset_token_hash` varchar(64) DEFAULT NULL,
  `reset_token_expires_at` datetime DEFAULT NULL,
  `show_verification` tinyint(1) DEFAULT '0',
  `verify_email` tinyint(1) DEFAULT '0',
  `verification_code` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `phone_number` (`phone_number`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `reset_token_hash` (`reset_token_hash`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'ADMIN','marvin','nabos','navarette','','2004-01-23','Single','Male','mnavarette45@gmail.com','09509344458','student','Sta. Lucia','default.jpg','$2y$10$IGyTlJSOGTXcYhmAUDk2Oeh6jcAwA/wDrFTRxOy2SANsKlN7/Cuqa','2025-04-28 01:24:59','2025-04-28 01:24:59','marvin45','2024','409 east berkely st.','chae1.jpg',NULL,NULL,0,0,NULL,NULL),(2,'RESIDENT','son','navarette','chaeyoung','','1999-04-23','Married','Male','mnavarette00@gmail.com','09232425857','kpop','Sta. Lucia','default.jpg','$2y$10$pMw/tvj0O6/eZUht/BSSK.Qg54F0KrGNWOtTdij9JrtUNZnRq20ra','2025-04-28 04:45:30','2025-04-28 04:45:30','chaey','2020','409 east berkely st.','ch.jpeg',NULL,NULL,0,0,NULL,NULL);
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

-- Dump completed on 2025-04-29 11:13:27
