-- MySQL dump 10.13  Distrib 5.7.42, for Linux (x86_64)
--
-- Host: localhost    Database: gokleen
-- ------------------------------------------------------
-- Server version	5.7.42-0ubuntu0.18.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address_change_requests`
--

DROP TABLE IF EXISTS `address_change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `address_change_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flat_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `flat_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address_change_requests`
--

LOCK TABLES `address_change_requests` WRITE;
/*!40000 ALTER TABLE `address_change_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `address_change_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_banner_bg.jpg',
  `button_text` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `button_action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `overlay_color` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#269BDA',
  `sort_order` tinyint(4) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Discount of 200','Now upto 40% off','/storage/images/mobile_app/ban-1.jpg','Get Deal','deal','#713939',1,1,'2020-09-19 01:40:33','2021-07-29 10:14:30'),(2,'Doorstep Car Wash','Doorstep Car Wash','/storage/images/mobile_app/ban-2.jpg','#','#','#AC4747',2,1,'2021-07-29 10:15:28','2021-07-29 10:15:28'),(3,'Monthly Packages','Monthly Packages','/storage/images/mobile_app/ban-3.jpg','#','#','#924A4A',3,1,'2021-07-29 10:16:17','2021-07-29 10:16:17');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `base_prices`
--

DROP TABLE IF EXISTS `base_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `base_prices` (
  `car_type_id` bigint(20) unsigned NOT NULL,
  `wash_qty` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL,
  KEY `base_prices_wash_qty_index` (`wash_qty`),
  KEY `base_prices_car_type_index` (`car_type_id`),
  CONSTRAINT `base_prices_car_type_id_foreign` FOREIGN KEY (`car_type_id`) REFERENCES `car_types` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `base_prices`
--

LOCK TABLES `base_prices` WRITE;
/*!40000 ALTER TABLE `base_prices` DISABLE KEYS */;
INSERT INTO `base_prices` VALUES (2,1,450.00),(1,1,500.00),(3,1,400.00),(1,2,850.00),(2,2,750.00),(3,2,650.00),(1,3,975.00),(1,4,1200.00),(1,5,1500.00),(1,6,1800.00),(2,3,925.00),(2,4,1000.00),(2,5,1250.00),(2,6,1500.00),(3,3,725.00),(3,4,800.00),(3,5,1000.00),(3,6,1200.00),(1,7,2100.00),(2,7,1750.00),(3,7,1400.00),(1,8,2400.00),(1,9,2700.00),(1,10,3000.00),(1,11,3300.00),(1,13,3900.00),(2,8,2000.00),(2,9,2250.00),(2,10,2500.00),(2,11,2750.00),(2,13,3250.00),(2,14,3500.00),(3,8,1600.00),(3,9,1800.00),(3,10,2000.00),(3,11,2200.00),(3,13,2600.00),(3,14,2800.00),(1,12,3600.00),(2,12,3000.00),(3,12,2400.00),(4,1,450.00),(4,2,750.00),(4,3,925.00),(4,4,1000.00),(4,5,1250.00),(4,6,1500.00),(4,7,1750.00),(4,8,2000.00),(4,9,2250.00),(4,10,2500.00),(4,11,2750.00),(4,12,3000.00),(4,13,3250.00),(4,14,3500.00),(1,14,4200.00);
/*!40000 ALTER TABLE `base_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bookings`
--

DROP TABLE IF EXISTS `bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_type` enum('single','bulk') COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` bigint(20) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_car_id` bigint(20) unsigned NOT NULL,
  `service_unit_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_sessions_session_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bookings`
--

LOCK TABLES `bookings` WRITE;
/*!40000 ALTER TABLE `bookings` DISABLE KEYS */;
INSERT INTO `bookings` VALUES (4,'BK1646746877','single',3,2,1,3,1,'pending','2022-03-08 13:41:17','2022-03-09 09:31:53',NULL),(10,'BK1646821500','bulk',11,2,1,3,1,'created','2022-03-09 10:25:00','2022-03-09 10:25:00',NULL),(11,'BK1647350219','bulk',18,8,4,5,1,'created','2022-03-15 13:16:59','2022-03-15 13:16:59',NULL),(12,'BK1647401585','bulk',17,7,3,5,1,'created','2022-03-16 03:33:05','2022-03-16 03:33:05',NULL),(13,'BK1647418397','bulk',19,9,5,5,1,'created','2022-03-16 08:13:17','2022-03-16 08:13:17',NULL),(15,'BK1647429319','bulk',20,6,2,5,1,'created','2022-03-16 11:15:19','2022-03-16 11:15:19',NULL),(16,'BK1647434652','bulk',22,10,6,5,1,'created','2022-03-16 12:44:12','2022-03-16 12:44:12',NULL),(17,'BK1648447422','bulk',28,11,7,3,1,'created','2022-03-28 06:03:42','2022-03-28 06:03:42',NULL),(18,'BK1648452584','single',33,12,9,3,1,'created','2022-03-28 07:29:44','2022-03-28 07:29:44',NULL),(19,'BK1679377881','bulk',36,2,1,3,1,'created','2023-03-21 05:51:21','2023-03-21 05:51:21',NULL),(20,'BK1679378380','bulk',37,2,11,3,1,'created','2023-03-21 05:59:40','2023-03-21 05:59:40',NULL);
/*!40000 ALTER TABLE `bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_brands`
--

DROP TABLE IF EXISTS `car_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_brands` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_car_brand_logo.png',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_brands`
--

LOCK TABLES `car_brands` WRITE;
/*!40000 ALTER TABLE `car_brands` DISABLE KEYS */;
INSERT INTO `car_brands` VALUES (1,'Hyundai','hyundai','/storage/car_brands/hyundai.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(2,'Honda','honda','/storage/car_brands/honda.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(3,'Maruti','maruti','/storage/car_brands/maruti.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(4,'Toyota','toyota','/storage/car_brands/toyota.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(5,'Nissan','nissan','/storage/car_brands/nissan.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(6,'Volkswagen','volkswagen','/storage/car_brands/volkswagen.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(7,'Ford','ford','/storage/car_brands/ford.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(8,'Skoda','skoda','/storage/car_brands/skoda.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(9,'Chevrolet','chevrolet','/storage/car_brands/chevrolet.png',1,'2017-12-11 17:06:00','2022-02-09 05:34:20',NULL),(10,'Renault','renault','/storage/car_brands/renault.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(11,'Mahindra','mahindra','/storage/car_brands/mahindra.png',1,'2017-12-11 17:06:00','2022-02-09 05:52:20',NULL),(12,'TATA','tata','/storage/car_brands/tata.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(13,'Mercedes','mercedes','/storage/car_brands/mercedes.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(14,'Fiat','fiat','/storage/car_brands/fiat.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(15,'Porsche','porsche','/storage/car_brands/porsche.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(16,'Audi','audi','/storage/car_brands/audi.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(17,'BMW','bmw','/storage/car_brands/bmw.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(18,'Mitsubishi','mitsubishi','/storage/car_brands/mitsubishi.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(19,'Volvo','volvo','/storage/car_brands/volvo.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(20,'Land Rover','land-rover','/storage/car_brands/land-rover.png',1,'2017-12-11 17:06:00','2017-12-11 17:06:00',NULL),(21,'Test','test','/storage/car_brands/maruti.png',0,'2017-12-13 16:30:00','2020-10-16 07:37:07',NULL),(22,'Datsun','datsun','/storage/car_brands/datsun.png',1,'2017-12-27 10:57:00','2017-12-27 10:57:00',NULL),(23,'Hindustan Motors','hindustan-motors','/storage/car_brands/hindustan-motors.png',1,'2018-10-11 14:25:00','2018-11-19 09:45:00',NULL),(24,'JAGUAR','jaguar','/storage/car_brands/jaguar.png',1,'2019-07-08 12:20:00','2019-07-08 12:20:00',NULL),(25,'ISUZU','isuzu','/storage/car_brands/isuzu.png',1,'2019-10-04 11:39:00','2019-10-04 11:39:00',NULL),(26,'LEXUS','lexus','/storage/car_brands/lexus.png',1,'2019-12-11 12:48:00','2019-12-11 12:48:00',NULL),(27,'KIA','kia','/storage/car_brands/kia.png',1,'2019-12-11 15:16:00','2019-12-11 15:16:00',NULL),(28,'MG','mg','/storage/car_brands/mg.png',1,'2020-02-17 05:38:00','2020-02-17 05:38:00',NULL);
/*!40000 ALTER TABLE `car_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_colors`
--

DROP TABLE IF EXISTS `car_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_colors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_colors`
--

LOCK TABLES `car_colors` WRITE;
/*!40000 ALTER TABLE `car_colors` DISABLE KEYS */;
INSERT INTO `car_colors` VALUES (1,'ASH','ash','',1,'2017-12-11 17:06:04','2022-02-09 05:07:41'),(2,'BEIGE','beige','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(3,'BLACK','black','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(4,'BLUE','blue','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(5,'BROWN','brown','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(6,'BROWN','brown','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(7,'BROWN METALIC','brown-metalic','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(8,'CHAMPAGNE','champagne','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(9,'GOLDEN','golden','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(10,'GOLDEN DUST','golden-dust','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(11,'GOLDEN PLATINUM','golden-platinum','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(12,'GREEN','green','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(13,'GREEN LIME','green-lime','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(14,'GREY','grey','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(15,'MAROON','maroon','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(16,'ORANGE','orange','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(17,'PINK','pink','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(18,'PURPLE','purple','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(19,'RED','red','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(20,'RED PEARL','red-pearl','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(21,'SILVER','silver','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(22,'SILVER METALIC','silver-metalic','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(23,'STAR DUST','star-dust','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(24,'TITANIUM','titanium','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(25,'WHITE','white','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(26,'WHITE PEARL','white-pearl','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(27,'YELLOW','yellow','',1,'2017-12-11 17:06:04','2017-12-11 17:06:04'),(28,'Aquamarine','aquamarine','',1,'2018-02-21 02:03:18','2018-02-21 02:03:18'),(31,'STEEL','steel',NULL,1,'2022-02-09 06:32:17','2022-02-09 06:32:17');
/*!40000 ALTER TABLE `car_colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `car_types`
--

DROP TABLE IF EXISTS `car_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `car_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_car_type_image.png',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_types`
--

LOCK TABLES `car_types` WRITE;
/*!40000 ALTER TABLE `car_types` DISABLE KEYS */;
INSERT INTO `car_types` VALUES (1,'SUV','suv','/storage/car_types/suv.png',1,NULL,NULL,NULL),(2,'SEDAN','sedan','/storage/car_types/sedan.png',1,NULL,NULL,NULL),(3,'HATCHBACK','hatchback','/storage/car_types/hatchback.png',1,NULL,NULL,NULL),(4,'CROSSOVER','crossover','/storage/car_types/crossover.png',1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `car_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `car_brand_id` bigint(20) unsigned NOT NULL,
  `car_type_id` bigint(20) unsigned NOT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default_car_image.png',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=193 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (1,'DISCOVERY','discovery',20,1,'/storage/cars/car_1.jpg',1,NULL,NULL,NULL),(2,'DISCOVERY SPORT','discovery-sport',20,1,'/storage/cars/car_2.jpg',1,NULL,NULL,NULL),(3,'RANGE ROVER','range-rover',20,1,'/storage/cars/car_3.jpg',1,NULL,NULL,NULL),(4,'RANGE ROVER EVOQUE','range-rover-evoque',20,1,'/storage/cars/car_4.jpg',1,NULL,NULL,NULL),(5,'RANGE ROVER SPORT','range-rover-sport',20,1,'/storage/cars/car_5.jpg',1,NULL,NULL,NULL),(6,'S60','s60',19,2,'/storage/cars/car_6.jpg',1,NULL,NULL,NULL),(7,'S80','s80',19,2,'/storage/cars/car_7.jpg',1,NULL,NULL,NULL),(8,'S90','s90',19,2,'/storage/cars/car_8.jpg',1,NULL,NULL,NULL),(9,'V40','v40',19,2,'/storage/cars/car_9.jpg',1,NULL,NULL,NULL),(10,'V40','v40',19,2,'/storage/cars/car_10.jpg',1,NULL,NULL,NULL),(11,'V90','v90',19,2,'/storage/cars/car_11.jpg',1,NULL,'2022-02-09 10:46:56',NULL),(12,'XC60','xc60',19,1,'/storage/cars/car_12.jpg',1,NULL,NULL,NULL),(13,'XC90','xc90',19,1,'/storage/cars/car_13.jpg',1,NULL,NULL,NULL),(14,'CEDIA','cedia',18,2,'/storage/cars/car_14.jpg',1,NULL,NULL,NULL),(15,'LANCER','lancer',18,2,'/storage/cars/car_15.jpg',1,NULL,NULL,NULL),(16,'OUTLANDER','outlander',18,1,'/storage/cars/car_16.jpg',1,NULL,NULL,NULL),(17,'PAJERO','pajero',18,1,'/storage/cars/car_17.jpg',1,NULL,NULL,NULL),(18,'3 SERIES','3-series',17,2,'/storage/cars/car_18.jpg',1,NULL,NULL,NULL),(19,'5 SERIES','5-series',17,2,'/storage/cars/car_19.jpg',1,NULL,NULL,NULL),(20,'7 SERIES','7-series',17,2,'/storage/cars/car_20.jpg',1,NULL,NULL,NULL),(21,'i8','i8',17,2,'/storage/cars/car_21.jpg',1,NULL,NULL,NULL),(22,'X1','x1',17,1,'/storage/cars/car_22.jpg',1,NULL,NULL,NULL),(23,'X5','x5',17,1,'/storage/cars/car_23.jpg',1,NULL,NULL,NULL),(24,'X6','x6',17,1,'/storage/cars/car_24.jpg',1,NULL,NULL,NULL),(25,'Z4','z4',17,2,'/storage/cars/car_25.jpg',1,NULL,NULL,NULL),(26,'A3','a3',16,2,'/storage/cars/car_26.jpg',1,NULL,NULL,NULL),(27,'A4','a4',16,2,'/storage/cars/car_27.jpg',1,NULL,NULL,NULL),(28,'A5','a5',16,2,'/storage/cars/car_28.jpg',1,NULL,NULL,NULL),(29,'A6','a6',16,2,'/storage/cars/car_29.jpg',1,NULL,NULL,NULL),(30,'Q3','q3',16,1,'/storage/cars/car_30.jpg',1,NULL,NULL,NULL),(31,'Q7','q7',16,1,'/storage/cars/car_31.jpg',1,NULL,NULL,NULL),(32,'R8','r8',16,2,'/storage/cars/car_32.jpg',1,NULL,NULL,NULL),(33,'S5','s5',16,2,'/storage/cars/car_33.jpg',1,NULL,NULL,NULL),(34,'718','718',15,2,'/storage/cars/car_34.jpg',1,NULL,NULL,NULL),(35,'911','911',15,2,'/storage/cars/car_35.jpg',1,NULL,NULL,NULL),(36,'CAYENNE','cayenne',15,1,'/storage/cars/car_36.jpg',1,NULL,NULL,NULL),(37,'MACAN','macan',15,1,'/storage/cars/car_37.jpg',1,NULL,NULL,NULL),(38,'PANAMERA','panamera',15,2,'/storage/cars/car_38.jpg',1,NULL,NULL,NULL),(39,'500','500',14,2,'/storage/cars/car_39.jpg',1,NULL,NULL,NULL),(40,'ABARTH PUNTO','abarth-punto',14,3,'/storage/cars/car_40.jpg',1,NULL,NULL,NULL),(41,'AVVENTURA','avventura',14,1,'/storage/cars/car_41.jpg',1,NULL,NULL,NULL),(42,'AVVENTURA URBAN','avventura-urban',14,1,'/storage/cars/car_42.jpg',1,NULL,NULL,NULL),(43,'LINEA','linea',14,2,'/storage/cars/car_43.jpg',1,NULL,NULL,NULL),(44,'LINEA CLASSIC','linea-classic',14,2,'/storage/cars/car_44.jpg',1,NULL,NULL,NULL),(45,'PALIO','palio',14,2,'/storage/cars/car_45.jpg',1,NULL,NULL,NULL),(46,'PUNTO EVO','punto-evo',14,2,'/storage/cars/car_46.jpg',1,NULL,NULL,NULL),(47,'PUNTO PURE','punto-pure',14,3,'/storage/cars/car_47.jpg',1,NULL,NULL,NULL),(48,'BENZ A','benz-a',13,2,'/storage/cars/car_48.jpg',1,NULL,NULL,NULL),(49,'BENZ AMG','benz-amg',13,2,'/storage/cars/car_49.jpg',1,NULL,NULL,NULL),(50,'BENZ C','benz-c',13,2,'/storage/cars/car_50.jpg',1,NULL,NULL,NULL),(51,'BENZ E','benz-e',13,2,'/storage/cars/car_51.jpg',1,NULL,NULL,NULL),(52,'BENZ B','benz-b',13,2,'/storage/cars/car_52.jpg',1,NULL,NULL,NULL),(53,'BENZ GLA','benz-gla',13,1,'/storage/cars/car_53.jpg',1,NULL,NULL,NULL),(54,'BENZ GLC','benz-glc',13,1,'/storage/cars/car_54.jpg',1,NULL,NULL,NULL),(55,'BENZ S','benz-s',13,2,'/storage/cars/car_55.jpg',1,NULL,NULL,NULL),(56,'HEXA','hexa',12,1,'/storage/cars/car_56.jpg',1,NULL,NULL,NULL),(57,'INDICA VISTA','indica-vista',12,3,'/storage/cars/car_57.jpg',1,NULL,NULL,NULL),(58,'NANO','nano',12,3,'/storage/cars/car_58.jpg',1,NULL,NULL,NULL),(59,'NEXON','nexon',12,2,'/storage/cars/car_59.jpg',1,NULL,NULL,NULL),(60,'SUMO','sumo',12,1,'/storage/cars/car_60.jpg',1,NULL,NULL,NULL),(61,'TIAGO','tiago',12,2,'/storage/cars/car_61.jpg',1,NULL,NULL,NULL),(62,'TIGOR','tigor',12,1,'/storage/cars/car_62.jpg',1,NULL,NULL,NULL),(63,'ZEST','zest',12,2,'/storage/cars/car_63.jpg',1,NULL,NULL,NULL),(64,'BOLERO','bolero',11,1,'/storage/cars/car_64.jpg',1,NULL,NULL,NULL),(65,'JEEP','jeep',11,1,'/storage/cars/car_65.jpg',1,NULL,NULL,NULL),(66,'QUANTO','quanto',11,1,'/storage/cars/car_66.jpg',1,NULL,NULL,NULL),(67,'SCORPIO','scorpio',11,1,'/storage/cars/car_67.jpg',1,NULL,NULL,NULL),(68,'TUV 300','tuv-300',11,1,'/storage/cars/car_68.jpg',1,NULL,NULL,NULL),(69,'XUV 500','xuv-500',11,1,'/storage/cars/car_69.jpg',1,NULL,NULL,NULL),(70,'THAR','thar',11,1,'/storage/cars/car_70.jpg',1,NULL,NULL,NULL),(71,'KUV 100','kuv-100',11,4,'/storage/cars/car_71.jpg',1,NULL,'2022-03-15 12:29:33',NULL),(72,'XYLO','xylo',11,1,'/storage/cars/car_72.jpg',1,NULL,NULL,NULL),(73,'VERITO','verito',11,1,'/storage/cars/car_73.jpg',1,NULL,NULL,NULL),(74,'NUVO SPORT','nuvo-sport',11,1,'/storage/cars/car_74.jpg',1,NULL,NULL,NULL),(75,'CAPTUR','captur',10,1,'/storage/cars/car_75.jpg',1,NULL,NULL,NULL),(76,'DUSTER','duster',10,1,'/storage/cars/car_76.jpg',1,NULL,NULL,NULL),(77,'KWID','kwid',10,3,'/storage/cars/car_77.jpg',1,NULL,NULL,NULL),(78,'LODGY','lodgy',10,1,'/storage/cars/car_78.jpg',1,NULL,NULL,NULL),(79,'PULSE','pulse',10,3,'/storage/cars/car_79.jpg',1,NULL,NULL,NULL),(80,'FABIA','fabia',8,3,'/storage/cars/car_80.jpg',1,NULL,NULL,NULL),(81,'KODIAQ','kodiaq',8,1,'/storage/cars/car_81.jpg',1,NULL,NULL,NULL),(82,'OCTAVIA','octavia',8,1,'/storage/cars/car_82.jpg',1,NULL,NULL,NULL),(83,'RAPID','rapid',8,1,'/storage/cars/car_83.jpg',1,NULL,NULL,NULL),(84,'SUPERB','superb',8,2,'/storage/cars/car_84.jpg',1,NULL,'2022-09-21 06:31:49',NULL),(85,'KAROQ','karoq',8,1,'/storage/cars/car_85.jpg',1,NULL,NULL,NULL),(86,'MICRA','micra',5,3,'/storage/cars/car_86.jpg',1,NULL,NULL,NULL),(87,'SUNNY','sunny',5,2,'/storage/cars/car_87.jpg',1,NULL,NULL,NULL),(88,'TERRANO','terrano',5,1,'/storage/cars/car_88.jpg',1,NULL,NULL,NULL),(89,'PATROL','patrol',5,1,'/storage/cars/car_89.jpg',1,NULL,NULL,NULL),(90,'X TRAIL','x-trail',5,1,'/storage/cars/car_90.jpg',1,NULL,NULL,NULL),(91,'CAMRY','camry',4,2,'/storage/cars/car_91.jpg',1,NULL,NULL,NULL),(92,'COROLLA','corolla',4,2,'/storage/cars/car_92.jpg',1,NULL,NULL,NULL),(93,'CROSS','cross',4,3,'/storage/cars/car_93.jpg',1,NULL,NULL,NULL),(94,'ETIOS','etios',4,2,'/storage/cars/car_94.jpg',1,NULL,NULL,NULL),(95,'FORTUNER','fortuner',4,1,'/storage/cars/car_95.jpg',1,NULL,NULL,NULL),(96,'INNOVA','innova',4,1,'/storage/cars/car_96.jpg',1,NULL,NULL,NULL),(97,'LIVA','liva',4,2,'/storage/cars/car_97.jpg',1,NULL,NULL,NULL),(98,'LAND CRUISER','land-cruiser',4,1,'/storage/cars/car_98.jpg',1,NULL,NULL,NULL),(99,'ACCORD','accord',2,2,'/storage/cars/car_99.jpg',1,NULL,NULL,NULL),(100,'AMAZE','amaze',2,2,'/storage/cars/car_100.jpg',1,NULL,NULL,NULL),(101,'BRIO','brio',2,3,'/storage/cars/car_101.jpg',1,NULL,NULL,NULL),(102,'BR-V','br-v',2,1,'/storage/cars/car_102.jpg',1,NULL,NULL,NULL),(103,'CITY','city',2,2,'/storage/cars/car_103.jpg',1,NULL,NULL,NULL),(104,'CIVIC','civic',2,2,'/storage/cars/car_104.jpg',1,NULL,NULL,NULL),(105,'CR-V','cr-v',2,1,'/storage/cars/car_105.jpg',1,NULL,NULL,NULL),(106,'JAZZ','jazz',2,2,'/storage/cars/car_106.jpg',1,NULL,NULL,NULL),(107,'WR-V','wr-v',2,1,'/storage/cars/car_107.jpg',1,NULL,NULL,NULL),(108,'CRETA','creta',1,1,'/storage/cars/car_108.jpg',1,NULL,NULL,NULL),(109,'ELANTRA','elantra',1,2,'/storage/cars/car_109.jpg',1,NULL,NULL,NULL),(110,'ELITE i10','elite-i10',1,3,'/storage/cars/car_110.jpg',1,NULL,NULL,NULL),(111,'EON','eon',1,3,'/storage/cars/car_111.jpg',1,NULL,NULL,NULL),(112,'GRAND i10','grand-i10',1,3,'/storage/cars/car_112.jpg',1,NULL,NULL,NULL),(113,'i20 ACTIVE','i20-active',1,3,'/storage/cars/car_113.jpg',1,NULL,NULL,NULL),(114,'SANTRO','santro',1,3,'/storage/cars/car_114.jpg',1,NULL,NULL,NULL),(115,'VERNA','verna',1,2,'/storage/cars/car_115.jpg',1,NULL,NULL,NULL),(116,'XCENT','xcent',1,2,'/storage/cars/car_116.jpg',1,NULL,NULL,NULL),(117,'A STAR','a-star',3,3,'/storage/cars/car_117.jpg',1,NULL,NULL,NULL),(118,'ALTO','alto',3,3,'/storage/cars/car_118.jpg',1,NULL,NULL,NULL),(119,'BALENO','baleno',3,3,'/storage/cars/car_119.jpg',1,NULL,NULL,NULL),(120,'BREZZA','brezza',3,1,'/storage/cars/car_120.jpg',1,NULL,NULL,NULL),(121,'CELERIO','celerio',3,3,'/storage/cars/car_121.jpg',1,NULL,NULL,NULL),(122,'CIAZ','ciaz',3,2,'/storage/cars/car_122.jpg',1,NULL,NULL,NULL),(123,'ERTIGA','ertiga',3,2,'/storage/cars/car_123.jpg',1,NULL,NULL,NULL),(124,'IGNIS','ignis',3,3,'/storage/cars/car_124.jpg',1,NULL,NULL,NULL),(125,'OMNI','omni',3,3,'/storage/cars/car_125.jpg',1,NULL,NULL,NULL),(126,'SWIFT','swift',3,3,'/storage/cars/car_126.jpg',1,NULL,NULL,NULL),(127,'SWIFT DZIRE','swift-dzire',3,2,'/storage/cars/car_127.jpg',1,NULL,NULL,NULL),(128,'SX4','sx4',3,2,'/storage/cars/car_128.jpg',1,NULL,NULL,NULL),(129,'WAGON-R','wagon-r',3,3,'/storage/cars/car_129.jpg',1,NULL,NULL,NULL),(130,'BEAT','beat',9,3,'/storage/cars/car_130.jpg',1,NULL,NULL,NULL),(131,'CAPTIVA','captiva',9,1,'/storage/cars/car_131.jpg',1,NULL,NULL,NULL),(132,'CRUZE','cruze',9,2,'/storage/cars/car_132.jpg',1,NULL,NULL,NULL),(133,'ENJOY','enjoy',9,3,'/storage/cars/car_133.jpg',1,NULL,NULL,NULL),(134,'OPTRA','optra',9,2,'/storage/cars/car_134.jpg',1,NULL,NULL,NULL),(135,'OPTRA ELITE','optra-elite',9,2,'/storage/cars/car_135.jpg',1,NULL,NULL,NULL),(136,'TAVERA','tavera',9,1,'/storage/cars/car_136.jpg',1,NULL,NULL,NULL),(137,'UVA','uva',9,3,'/storage/cars/car_137.jpg',1,NULL,NULL,NULL),(138,'SPARK','spark',9,3,'/storage/cars/car_138.jpg',1,NULL,NULL,NULL),(139,'VENTO','vento',6,2,'/storage/cars/car_139.jpg',1,NULL,NULL,NULL),(140,'JETTA','jetta',6,2,'/storage/cars/car_140.jpg',1,NULL,NULL,NULL),(141,'POLO','polo',6,3,'/storage/cars/car_141.jpg',1,NULL,NULL,NULL),(142,'TIGUAN','tiguan',6,1,'/storage/cars/car_142.jpg',1,NULL,NULL,NULL),(143,'BEATLE','beatle',6,1,'/storage/cars/car_143.jpg',1,NULL,NULL,NULL),(144,'PASSAT','passat',6,2,'/storage/cars/car_144.jpg',1,NULL,NULL,NULL),(145,'AMEO','ameo',6,2,'/storage/cars/car_145.jpg',1,NULL,NULL,NULL),(146,'ASPIRE','aspire',7,2,'/storage/cars/car_146.jpg',1,NULL,NULL,NULL),(147,'ECOSPORT','ecosport',7,2,'/storage/cars/car_147.jpg',1,NULL,'2022-03-15 12:41:39',NULL),(148,'ENDEAVOUR','endeavour',7,1,'/storage/cars/car_148.jpg',1,NULL,NULL,NULL),(149,'FIGO','figo',7,3,'/storage/cars/car_149.jpg',1,NULL,NULL,NULL),(150,'MUSTANG','mustang',7,1,'/storage/cars/car_150.jpg',1,NULL,NULL,NULL),(151,'TREND','trend',7,3,'/storage/cars/car_151.jpg',1,NULL,NULL,NULL),(152,'FIESTA','fiesta',7,2,'/storage/cars/car_152.jpg',1,NULL,NULL,NULL),(153,'Test1','test1',21,1,'/storage/cars/car_153.jpg',1,NULL,NULL,NULL),(154,'RITZ','ritz',3,3,'/storage/cars/car_154.jpg',1,NULL,NULL,NULL),(155,'IKON','ikon',7,2,'/storage/cars/car_155.jpg',1,NULL,NULL,NULL),(156,'S-CROSS','s-cross',3,1,'/storage/cars/car_156.jpg',1,NULL,NULL,NULL),(157,'800','800',3,3,'/storage/cars/car_157.jpg',1,NULL,NULL,NULL),(158,'ZEN','zen',3,3,'/storage/cars/car_158.jpg',1,NULL,NULL,NULL),(159,'ACCENT','accent',1,2,'/storage/cars/car_159.jpg',1,NULL,NULL,NULL),(160,'MINI COOPER','mini-cooper',17,3,'/storage/cars/car_160.jpg',1,NULL,NULL,NULL),(161,'ZEN ESTILO','zen-estilo',3,3,'/storage/cars/car_161.jpg',1,NULL,NULL,NULL),(162,'TITANIUM','titanium',7,1,'/storage/cars/car_162.jpg',1,NULL,NULL,NULL),(163,'BOLT','bolt',12,3,'/storage/cars/car_163.jpg',1,NULL,NULL,NULL),(164,'BENTLY','bently',6,1,'/storage/cars/car_164.jpg',1,NULL,NULL,NULL),(165,'TUCSON','tucson',1,1,'/storage/cars/car_165.jpg',1,NULL,NULL,NULL),(166,'FLUENCE','fluence',10,2,'/storage/cars/car_166.jpg',1,NULL,NULL,NULL),(167,'REDI-GO','redi-go',22,3,'/storage/cars/car_167.jpg',1,NULL,NULL,NULL),(168,'GO','go',22,2,'/storage/cars/car_168.jpg',1,NULL,NULL,NULL),(169,'X3','x3',17,1,'/storage/cars/car_169.jpg',1,NULL,NULL,NULL),(170,'CLASSIC','classic',7,2,'/storage/cars/car_170.jpg',1,NULL,NULL,NULL),(171,'MOBILIO','mobilio',2,1,'/storage/cars/car_171.jpg',1,NULL,NULL,NULL),(172,'LOGAN','logan',11,2,'/storage/cars/car_172.jpg',1,NULL,NULL,NULL),(173,'Ambassador','ambassador',23,2,'/storage/cars/car_173.jpg',1,NULL,NULL,NULL),(174,'Safari','safari',12,1,'/storage/cars/car_174.jpg',1,NULL,NULL,NULL),(175,'ACE','ace',12,1,'/storage/cars/car_175.jpg',1,NULL,NULL,NULL),(176,'ALTURAS G4','alturas-g4',11,1,'/storage/cars/car_176.jpg',1,NULL,NULL,NULL),(177,'VENUE','venue',1,1,'/storage/cars/car_177.jpg',1,NULL,NULL,NULL),(178,'SCALA','scala',10,2,'/storage/cars/car_178.jpg',1,NULL,NULL,NULL),(179,'MARAZZO','marazzo',11,1,'/storage/cars/car_179.jpg',1,NULL,NULL,NULL),(180,'XF','xf',24,1,'/storage/cars/car_180.jpg',1,NULL,NULL,NULL),(181,'MANZA','manza',12,2,'/storage/cars/car_181.jpg',1,NULL,NULL,NULL),(182,'CRYSTA','crysta',4,1,'/storage/cars/car_182.jpg',1,NULL,NULL,NULL),(183,'MU7','mu7',25,1,'/storage/cars/car_183.jpg',1,NULL,NULL,NULL),(184,'GLANZA','glanza',4,3,'/storage/cars/car_184.jpg',1,NULL,NULL,NULL),(185,'TIGUAN','tiguan',6,1,'/storage/cars/car_185.jpg',1,NULL,NULL,NULL),(186,'NX300','nx300',26,1,'/storage/cars/car_186.jpg',1,NULL,NULL,NULL),(187,'SELTOS','seltos',27,1,'/storage/cars/car_187.jpg',1,NULL,NULL,NULL),(188,'ESTILO','estilo',3,3,'/storage/cars/8e36407e2648c7e19c10fc4163cbcb67.jpg',1,NULL,NULL,NULL),(189,'XL6','xl6',3,1,'/storage/cars/27c4591e8326d8c14086c764907491db.jpg',1,NULL,NULL,NULL),(190,'HECTOR','hector',28,1,'/storage/cars/edd576fd737095cb4df3ef3c733e7706.jpg',1,NULL,NULL,NULL),(191,'S-PRESSO','s-presso',3,3,'/storage/cars/a5171904d67c71ac70573888e2aeff46.jpg',1,NULL,NULL,NULL),(192,'XC40','xc40',19,1,'/storage/cars/a4bbcfbda34be376cf290acd54163cf6.jpg',1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_code_unique` (`code`),
  UNIQUE KEY `cities_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'KOCHI','KOCH','kochi','/storage/cities/kochi.png',1,'2020-09-18 12:01:26','2020-09-18 12:01:26',NULL);
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_cars`
--

DROP TABLE IF EXISTS `customer_cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customer_cars` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `car_id` bigint(20) unsigned NOT NULL,
  `number_plate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_cars_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customer_cars`
--

LOCK TABLES `customer_cars` WRITE;
/*!40000 ALTER TABLE `customer_cars` DISABLE KEYS */;
INSERT INTO `customer_cars` VALUES (1,'car_N05tmoO8PTj',2,2,'123457','BLACK',1,'2022-02-10 01:39:47','2022-02-10 01:44:30',NULL),(2,'car_m2sWw35EuC5',6,71,'kl31k6868','RED',1,'2022-03-15 04:45:55','2022-03-15 04:45:55',NULL),(3,'car_o4n6CduTlcF',7,147,'KL-22-P-0136','BLACK',1,'2022-03-15 12:40:54','2022-03-15 12:40:54',NULL),(4,'car_JJ7ibsJF6p0',8,108,'MH31FA 4010','BLACK',1,'2022-03-15 13:07:46','2022-03-15 13:07:46',NULL),(5,'car_AFmlaE2Nlwx',9,126,'KL-39-L-0001','RED',1,'2022-03-16 08:10:54','2022-03-16 08:10:54',NULL),(6,'car_NLSJ0YJ8lC0',10,120,'FAKE','ASH',1,'2022-03-16 12:38:32','2022-03-16 12:38:32',NULL),(7,'car_BKbAABBGeU5',11,96,'45738','SILVER',1,'2022-03-26 12:57:14','2022-03-26 12:57:14',NULL),(8,'car_3tTpc0GtYmp',11,110,'47538','BEIGE',1,'2022-03-26 13:26:55','2022-03-26 13:26:55',NULL),(9,'car_myTK8Zp9U2x',12,113,'kl32l7613','STAR DUST',1,'2022-03-28 06:56:03','2022-03-28 06:56:03',NULL),(10,'car_3DlbvPIqx9x',12,96,'kl101520','BEIGE',1,'2022-03-28 07:02:30','2022-03-28 07:02:30',NULL),(11,'car_kEcqerJctzO',2,126,'KL-11-AA-1234','BLUE',1,'2023-03-21 05:54:36','2023-03-21 05:54:36',NULL);
/*!40000 ALTER TABLE `customer_cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `discounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `item_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `discount_type` enum('fixed','percentage') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percentage',
  `discount_value` int(10) unsigned NOT NULL,
  `discount_method` enum('auto','coupon') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto',
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valid_from` datetime NOT NULL,
  `valid_until` datetime NOT NULL,
  `maximum_discount_value` int(10) unsigned NOT NULL DEFAULT '0',
  `minimum_cart_amount` int(10) unsigned NOT NULL DEFAULT '0',
  `is_private` tinyint(1) NOT NULL DEFAULT '1',
  `user_group_id` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `discounts_item_type_item_id_index` (`item_type`,`item_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,'Privileged',NULL,'App\\Models\\Product',1,'percentage',10,'auto',NULL,'2020-02-12 00:00:00','2024-02-14 00:00:00',1000,500,1,1,1,NULL,'2022-02-11 05:46:53',NULL),(2,'Privileged',NULL,'App\\Models\\Plan',2,'percentage',10,'auto',NULL,'2022-02-01 18:25:09','2022-06-30 18:25:13',1000,0,1,1,1,'2022-02-22 07:24:28','2022-02-24 03:25:55',NULL);
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
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
-- Table structure for table `invoice_items`
--

DROP TABLE IF EXISTS `invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoice_items` (
  `invoice_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(10) unsigned NOT NULL,
  `price` double(10,2) NOT NULL,
  `sub_total` double(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoice_items`
--

LOCK TABLES `invoice_items` WRITE;
/*!40000 ALTER TABLE `invoice_items` DISABLE KEYS */;
INSERT INTO `invoice_items` VALUES (3,1,1,405.00,405.00),(3,2,1,100.00,100.00),(11,1,4,247.50,990.00),(11,2,1,100.00,100.00),(17,1,4,250.00,1000.00),(17,2,1,100.00,100.00),(18,1,8,300.00,2400.00),(18,2,1,100.00,100.00),(19,1,12,200.00,2400.00),(19,2,1,100.00,100.00),(20,1,2,375.00,750.00),(20,2,1,100.00,100.00),(22,1,4,300.00,1200.00),(22,2,1,100.00,100.00),(23,1,1,450.00,450.00),(23,2,1,100.00,100.00),(28,1,4,300.00,1200.00),(33,1,1,400.00,400.00),(34,1,1,450.00,450.00),(34,2,1,100.00,100.00),(35,1,1,450.00,450.00),(35,2,1,100.00,100.00),(36,1,4,300.00,1200.00),(36,2,1,100.00,100.00),(37,1,4,200.00,800.00),(38,1,8,300.00,2400.00),(38,2,1,100.00,100.00),(39,1,8,300.00,2400.00),(39,2,1,100.00,100.00);
/*!40000 ALTER TABLE `invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invoices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_car_id` bigint(20) unsigned NOT NULL,
  `order_type` enum('single','bulk') COLLATE utf8mb4_unicode_ci NOT NULL,
  `subscription_id` bigint(20) unsigned DEFAULT NULL,
  `billing_cycle` int(10) unsigned DEFAULT NULL,
  `billing_cycle_starts` datetime DEFAULT NULL,
  `billing_cycle_ends` datetime DEFAULT NULL,
  `total_price` double(10,2) NOT NULL,
  `payment_mode` enum('online','offline') COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `refund_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `booking_completed` tinyint(1) NOT NULL DEFAULT '0',
  `status` enum('created','pending','failed','paid','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_invoice_id_customer_id_customer_car_id_status_index` (`invoice_id`,`customer_id`,`customer_car_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (3,'GK1645437639',2,1,'single',NULL,NULL,NULL,NULL,505.00,'offline','2022-02-21 15:30:26','2022-02-21 15:30:29','FREE',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 1, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 450, \"has_discount\": true, \"product_type\": \"Primary\", \"discounted_price\": 405}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"date_time\": \"2022-02-22 15:30:18\"}',1,'paid','2022-02-21 04:30:39','2022-03-09 10:00:44',NULL),(11,'GK1646800471',2,1,'bulk',4,1,'2022-03-13 00:00:00','2022-04-09 23:59:59',1090.00,'offline','2022-03-09 23:55:59','2022-03-12 11:30:00','1236655',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 275, \"has_discount\": true, \"product_type\": \"Primary\", \"discounted_price\": 247.5}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"07:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-09 04:34:31','2022-03-09 10:25:00',NULL),(17,'GK1647350032',7,3,'bulk',7,1,'2022-03-21 00:00:00','2022-04-17 23:59:59',1100.00,'offline','2022-03-20 23:55:59','2022-03-16 05:55:00','1234566778',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 250, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"08:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-15 13:13:52','2022-03-16 03:33:05',NULL),(18,'GK1647350046',8,4,'bulk',8,1,'2022-03-17 00:00:00','2022-04-13 23:59:59',2500.00,'offline','2022-03-15 23:55:59','2022-03-16 05:55:00','3243325356',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 8, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-15 13:14:06','2022-03-15 13:16:59',NULL),(19,'GK1647418346',9,5,'bulk',9,1,'2022-03-17 00:00:00','2022-04-13 23:59:59',2500.00,'offline','2022-03-15 23:55:59','2022-03-16 05:55:00','1234556665545',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 12, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 200, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"08:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"07:00:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"09:00:00\", \"selected\": true}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-16 08:12:26','2022-03-16 08:13:17',NULL),(20,'GK1647419127',6,2,'bulk',10,1,'2022-03-17 00:00:00','2022-04-13 23:59:59',850.00,'offline','2022-03-15 23:55:59','2022-03-16 05:55:00','12345663331',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 2, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 375, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"10:00:00\", \"selected\": true}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-16 08:25:27','2022-03-16 11:15:19',NULL),(22,'GK1647434465',10,6,'bulk',11,1,'2022-03-02 00:00:00','2022-03-29 23:59:59',1300.00,'offline','2022-02-28 23:55:59','2022-03-01 18:10:04','1234567890',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-16 12:41:05','2022-03-16 12:44:12',NULL),(23,'GK1648196040',2,1,'single',NULL,NULL,NULL,NULL,550.00,'online','2022-03-25 13:44:01','2022-03-25 13:44:18','pay_JBFh0DuBeodSaI',NULL,'order_JBFgoG5WqNPnvW','{\"items\": [{\"id\": 1, \"qty\": 1, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 500, \"has_discount\": true, \"product_type\": \"Primary\", \"discounted_price\": 450}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}], \"razorpay\": {\"razorpay_order_id\": \"order_JBFgoG5WqNPnvW\", \"razorpay_signature\": \"0ca40e0ac7b3cb4f21ad7d81461d4ea03c029ea620a548d0bd39b0bfdec28c32\", \"razorpay_payment_id\": \"pay_JBFh0DuBeodSaI\"}}','{\"date_time\": \"2022-03-26 13:43:00\"}',0,'paid','2022-03-25 08:14:01','2022-03-25 08:14:18',NULL),(28,'GK1322974045',11,7,'bulk',16,1,'2022-03-31 00:00:00','2022-04-27 23:59:59',1200.00,'online','2022-03-30 23:59:59','2022-03-26 18:54:29','pay_JBjVfTYD4ESFJN',NULL,'order_JBjRsvTFeOhVRH','{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}], \"razorpay\": {\"razorpay_order_id\": \"order_JBjRsvTFeOhVRH\", \"razorpay_signature\": \"3e30c455f1eaa11df07c31465613469e359aa79cc97e1c266dfaeae853636777\", \"razorpay_payment_id\": \"pay_JBjVfTYD4ESFJN\"}}','{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"17:10:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2022-03-26 13:20:42','2022-03-28 06:03:42',NULL),(33,'GK1648452181',12,9,'single',NULL,NULL,NULL,NULL,400.00,'online','2022-03-28 12:53:02','2022-03-28 12:56:46','pay_JCQTtDGlcRcpmu',NULL,'order_JCQQJb9ep1zMtj','{\"items\": [{\"id\": 1, \"qty\": 1, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 400, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}], \"razorpay\": {\"razorpay_order_id\": \"order_JCQQJb9ep1zMtj\", \"razorpay_signature\": \"3bb63ee57b9de43f5759ae52c8c8098369498a6f93316346652987a4998c464e\", \"razorpay_payment_id\": \"pay_JCQTtDGlcRcpmu\"}}','{\"date_time\": \"2022-03-29 17:15:00\"}',1,'paid','2022-03-28 07:23:02','2022-03-28 07:29:44',NULL),(34,'GK1679313579',2,1,'single',NULL,NULL,NULL,NULL,550.00,'online','2023-03-20 17:29:40',NULL,NULL,NULL,'order_LTlqWzPY8hdpc6','{\"items\": [{\"id\": 1, \"qty\": 1, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 500, \"has_discount\": true, \"product_type\": \"Primary\", \"discounted_price\": 450}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"date_time\": \"2023-03-23 16:29:00\"}',0,'created','2023-03-20 11:59:40','2023-03-20 11:59:40',NULL),(35,'GK1679313582',2,1,'single',NULL,NULL,NULL,NULL,550.00,'online','2023-03-20 17:29:43',NULL,NULL,NULL,'order_LTlqa3WsznLeYo','{\"items\": [{\"id\": 1, \"qty\": 1, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 500, \"has_discount\": true, \"product_type\": \"Primary\", \"discounted_price\": 450}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"date_time\": \"2023-03-23 16:29:00\"}',0,'created','2023-03-20 11:59:43','2023-03-20 11:59:43',NULL),(36,'GK1235907769',2,1,'bulk',4,2,'2023-03-09 00:00:00','2023-04-05 23:59:59',1300.00,'offline','2023-03-11 23:55:59','2023-03-08 11:20:58','132143',NULL,NULL,'{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"07:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2023-03-21 05:48:43','2023-03-21 05:57:50',NULL),(37,'GK1679378336',2,11,'bulk',17,1,'2023-03-21 00:00:00','2023-04-17 23:59:59',800.00,'online','2022-03-20 23:55:59','2023-03-20 11:30:15','123134',NULL,'124242','{\"items\": [{\"id\": 1, \"qty\": 4, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 200, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": true}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',1,'paid','2023-03-21 05:58:56','2023-03-21 05:59:40',NULL),(38,'GK1593348108',2,1,'bulk',18,1,'2023-05-25 00:00:00','2023-06-21 23:59:59',2500.00,'online','2023-05-24 23:59:59',NULL,NULL,NULL,'order_Lt0EYhPDCgWcv1','{\"items\": [{\"id\": 1, \"qty\": 8, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',0,'created','2023-05-23 06:19:10','2023-05-23 06:19:10',NULL),(39,'GK1629537887',2,1,'bulk',19,1,'2023-05-25 00:00:00','2023-06-21 23:59:59',2500.00,'online','2023-05-24 23:59:59',NULL,NULL,NULL,'order_Lt0Eejv4czB3XK','{\"items\": [{\"id\": 1, \"qty\": 8, \"code\": \"GK-EXT-1W\", \"name\": \"Single Body Wash\", \"price\": 300, \"has_discount\": false, \"product_type\": \"Primary\", \"discounted_price\": 0}, {\"id\": 2, \"qty\": 1, \"code\": \"GK-INT-1W\", \"name\": \"Interior Wash\", \"price\": 100, \"has_discount\": false, \"product_type\": \"Addon\", \"discounted_price\": 0}]}','{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}',0,'created','2023-05-23 06:19:15','2023-05-23 06:19:15',NULL);
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2014_10_12_200000_add_two_factor_columns_to_users_table',1),(4,'2018_11_06_222923_create_transactions_table',1),(5,'2018_11_07_192923_create_transfers_table',1),(6,'2018_11_15_124230_create_wallets_table',1),(7,'2019_08_19_000000_create_failed_jobs_table',1),(8,'2019_12_14_000001_create_personal_access_tokens_table',1),(9,'2021_11_02_202021_update_wallets_uuid_table',1),(10,'2022_01_31_133546_create_sessions_table',1),(11,'2022_02_05_153748_create_settings_table',1),(12,'2022_02_08_045304_create_cities_table',2),(13,'2022_02_08_045702_create_car_brands_table',3),(14,'2022_02_08_045719_create_car_types_table',3),(15,'2022_02_08_045728_create_cars_table',3),(16,'2022_02_08_050351_create_car_colors_table',4),(17,'2022_02_08_062032_create_customer_cars_table',5),(18,'2022_02_08_062548_create_base_prices_table',6),(19,'2022_02_08_062612_create_products_table',6),(20,'2022_02_08_063653_create_product_prices_table',7),(21,'2022_02_08_064327_create_banners_table',8),(22,'2022_02_08_064525_create_plans_table',9),(23,'2022_02_08_072333_create_user_groups_table',10),(24,'2022_02_08_072514_create_discounts_table',10),(25,'2022_02_08_073522_create_address_change_requests_table',11),(26,'2022_02_08_073538_create_profile_change_requests_table',11),(27,'2022_02_08_075929_create_invoices_table',12),(28,'2022_02_08_111109_create_subscriptions_table',13),(29,'2022_02_08_113841_create_subscription_items_table',14),(30,'2022_02_08_115618_create_invoice_items_table',14),(31,'2022_02_08_121429_create_bookings_table',15),(32,'2022_02_08_121445_create_schedules_table',15),(33,'2022_02_08_123348_create_schedule_items_table',15),(34,'2022_02_10_081909_create_washes_table',16),(35,'2013_04_09_062329_create_revisions_table',17),(36,'2022_03_10_123816_create_refunds_table',18),(37,'2022_03_10_155344_create_notifications_table',18),(38,'2022_03_14_174828_create_referrals_table',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (5,'App\\Models\\Customer',11,'ecbaa9cafd5b8670','14d6e5a5cca24bdf3def7c7ce338668f4295bca7795f779aa69e29a2f7838db0','[\"*\"]','2022-03-26 12:55:36','2022-03-26 12:54:03','2022-03-26 12:55:36'),(6,'App\\Models\\Customer',11,'ecbaa9cafd5b8670','10cb00ade4966c52f30c7312d6ea31be51986e697c2697380386aa9a5fd5f502','[\"*\"]','2022-03-26 13:00:12','2022-03-26 12:55:36','2022-03-26 13:00:12'),(7,'App\\Models\\Customer',11,'ecbaa9cafd5b8670','2b8de0724db6c6bd7214dd25308f5d449c995b05e07e1cb41e6396aae3fbea8b','[\"*\"]','2022-03-26 13:08:49','2022-03-26 13:08:06','2022-03-26 13:08:49'),(8,'App\\Models\\Customer',11,'ecbaa9cafd5b8670','95ef2338e0e454a217d03ee6fb9f29ed493e71134dbbed57fcb8d7f5969f66da','[\"*\"]','2022-03-27 06:06:46','2022-03-26 13:10:17','2022-03-27 06:06:46'),(12,'App\\Models\\Customer',12,'5018a52c99429b4b','03fb0c7a2472b4ef36dfc6f8bf1603e27c64ee464db184a75f1fc00d3cb74512','[\"*\"]','2022-03-28 07:14:27','2022-03-28 07:13:42','2022-03-28 07:14:27'),(13,'App\\Models\\Customer',12,'5018a52c99429b4b','8e1e896904da129d2d45269fc161c70f065657fd6d1ab102b67d5e15c3b58b25','[\"*\"]','2022-03-28 07:34:07','2022-03-28 07:14:44','2022-03-28 07:34:07'),(63,'App\\Models\\Customer',14,'e4978b65ee16b908','7bba4cad9c733e71c1d764b918ac0093a65bc804f77dbf0b8c32a6831ebb1d5b','[\"*\"]','2023-05-16 09:03:44','2023-05-16 09:02:11','2023-05-16 09:03:44'),(64,'App\\Models\\Customer',14,'android','e438b9d1baadf9451ed6b6c8130d07c1cf808ac582d36e8a603fc400560155e6','[\"*\"]',NULL,'2023-05-16 09:03:44','2023-05-16 09:03:44'),(73,'App\\Models\\Customer',13,'1bf305f2b1f3a153','ce886e8a9bff4d03746a84afdc0cf9686de1776072c301e5c28e8a1edb63519f','[\"*\"]','2023-05-20 05:19:51','2023-05-20 05:19:35','2023-05-20 05:19:51'),(77,'App\\Models\\Customer',15,'1bf305f2b1f3a153','9abf5b450efca359ac8dae7efaa96f380e7d15c80ee24a8fc61e783aaed8743c','[\"*\"]','2023-05-21 10:41:50','2023-05-21 10:41:49','2023-05-21 10:41:50'),(82,'App\\Models\\Customer',2,'c8a5af5eeedacc50','d2edf59e54d41ce232dbeeb7e06e54f4efaaf892ad11bc1000b0f590e84b17ac','[\"*\"]','2023-05-25 01:25:11','2023-05-25 01:23:59','2023-05-25 01:25:11'),(87,'App\\Models\\Customer',16,'c8a5af5eeedacc50','59bd9e09f5ae14c1c0d63c3be77af7551e565a5894cc19f0047ff51615779248','[\"*\"]','2023-05-25 07:57:55','2023-05-25 01:44:43','2023-05-25 07:57:55'),(89,'App\\Models\\Customer',17,'IOS','793c46932291594bb4cb88a14b488fcabd16b006af74f491f64dff4fa652029a','[\"*\"]','2023-05-25 06:38:19','2023-05-25 06:37:44','2023-05-25 06:38:19'),(90,'App\\Models\\Customer',17,'IOS','bd9f7a53457c69c6ccc2b836369bc434704ec7c9a3dbb71034922e56a4730850','[\"*\"]','2023-05-25 06:39:00','2023-05-25 06:38:19','2023-05-25 06:39:00');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `plans`
--

DROP TABLE IF EXISTS `plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `razorpay_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wash_qty_per` enum('weekly','monthly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'weekly',
  `wash_qty` int(10) unsigned NOT NULL,
  `product_group` enum('wash','service') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wash',
  `sort_order` int(10) unsigned NOT NULL DEFAULT '1',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `plans_code_unique` (`code`),
  KEY `plans_wash_qty_per_index` (`wash_qty_per`),
  KEY `plans_product_group_index` (`product_group`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `plans`
--

LOCK TABLES `plans` WRITE;
/*!40000 ALTER TABLE `plans` DISABLE KEYS */;
INSERT INTO `plans` VALUES (1,'Monthly 2 Wash','GK-MN-2W',NULL,'Monthly 2 Body Washes,Total 2 Washes/Month','monthly',2,'wash',4,0,1,'2020-09-22 10:25:04','2022-02-10 06:28:33',NULL),(2,'Weekly 1 Wash','GK-WK-1W',NULL,'Weekly 1 Body Wash,Total 4 Washes/Month','weekly',1,'wash',1,1,1,'2020-09-22 10:27:33','2022-03-15 13:12:45',NULL),(3,'Weekly 2 Wash','GK-WK-2W',NULL,'Weekly 2 Body Washes,Total 8 Washes/Month','weekly',2,'wash',2,0,1,'2020-09-22 10:31:20','2022-03-15 13:12:50',NULL),(4,'Weekly 3 Wash','GK-WK-3W',NULL,'Weekly 3 Body Washes,Total 12 Washes/Month','weekly',3,'wash',3,0,1,'2020-09-22 10:31:53','2022-03-15 13:12:56',NULL);
/*!40000 ALTER TABLE `plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_prices`
--

DROP TABLE IF EXISTS `product_prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_prices` (
  `product_id` bigint(20) unsigned NOT NULL,
  `car_type_id` bigint(20) unsigned NOT NULL,
  `price` double(10,2) unsigned NOT NULL,
  KEY `product_prices_car_type_id_foreign` (`car_type_id`),
  KEY `product_id_index` (`product_id`),
  CONSTRAINT `product_prices_car_type_id_foreign` FOREIGN KEY (`car_type_id`) REFERENCES `car_types` (`id`),
  CONSTRAINT `product_prices_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_prices`
--

LOCK TABLES `product_prices` WRITE;
/*!40000 ALTER TABLE `product_prices` DISABLE KEYS */;
INSERT INTO `product_prices` VALUES (2,1,100.00),(2,2,100.00),(2,3,100.00),(1,3,400.00),(1,2,450.00),(1,1,500.00),(1,4,450.00),(3,4,50.00),(3,3,50.00),(3,2,50.00),(3,1,50.00),(2,4,100.00);
/*!40000 ALTER TABLE `product_prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `product_type` enum('primary','addon') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'primary',
  `product_group` enum('wash','service') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'wash',
  `sort_order` bigint(11) DEFAULT '1',
  `is_popular` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_code_unique` (`code`),
  KEY `products_product_type_index` (`product_type`),
  KEY `products_product_group_index` (`product_group`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Single Body Wash','GK-EXT-1W','1 Body Wash/Booking','primary','wash',1,1,1,'2020-09-19 05:14:55','2022-03-11 05:29:54',NULL),(2,'Interior Wash','GK-INT-1W','Interior Wash','addon','wash',2,1,1,'2020-09-19 05:15:42','2021-08-19 06:56:23',NULL),(3,'Wax Polish','GK-WAX-1W','Wax Polish','addon','wash',3,0,0,'2020-11-29 17:53:00','2022-02-10 06:51:59',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `profile_change_requests`
--

DROP TABLE IF EXISTS `profile_change_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `profile_change_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `entity_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `profile_change_requests`
--

LOCK TABLES `profile_change_requests` WRITE;
/*!40000 ALTER TABLE `profile_change_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `profile_change_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `referrals`
--

DROP TABLE IF EXISTS `referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `referrals` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `referred_customer_id` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `referrals`
--

LOCK TABLES `referrals` WRITE;
/*!40000 ALTER TABLE `referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refunds`
--

DROP TABLE IF EXISTS `refunds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `refunds` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `booking_id` bigint(20) unsigned NOT NULL,
  `refund_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'full',
  `amount` double NOT NULL,
  `data` json DEFAULT NULL,
  `processed_by` bigint(20) unsigned NOT NULL,
  `refund_date` datetime DEFAULT NULL,
  `transaction_id` bigint(20) unsigned DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'initiated',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refunds`
--

LOCK TABLES `refunds` WRITE;
/*!40000 ALTER TABLE `refunds` DISABLE KEYS */;
/*!40000 ALTER TABLE `refunds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `revisions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `revisionable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revisions_revisionable_id_revisionable_type_index` (`revisionable_id`,`revisionable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_items`
--

DROP TABLE IF EXISTS `schedule_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedule_items` (
  `schedule_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_items`
--

LOCK TABLES `schedule_items` WRITE;
/*!40000 ALTER TABLE `schedule_items` DISABLE KEYS */;
INSERT INTO `schedule_items` VALUES (1,1,1,'completed',NULL),(1,2,1,'pending','vehicle_not_available'),(22,1,1,'completed',NULL),(22,2,1,'completed',NULL),(23,1,1,'completed',NULL),(23,2,0,'pending',NULL),(24,1,1,'pending',NULL),(24,2,0,'pending',NULL),(25,1,1,'pending',NULL),(25,2,0,'pending',NULL),(26,1,1,'pending',NULL),(26,2,1,'pending',NULL),(27,1,1,'pending',NULL),(27,2,0,'pending',NULL),(28,1,1,'pending',NULL),(28,2,0,'pending',NULL),(29,1,1,'pending',NULL),(29,2,0,'pending',NULL),(30,1,1,'pending',NULL),(30,2,0,'pending',NULL),(31,1,1,'pending',NULL),(31,2,0,'pending',NULL),(32,1,1,'pending',NULL),(32,2,0,'pending',NULL),(33,1,1,'pending',NULL),(33,2,0,'pending',NULL),(34,1,1,'pending',NULL),(34,2,1,'pending',NULL),(35,1,1,'pending',NULL),(35,2,0,'pending',NULL),(36,1,1,'pending',NULL),(36,2,0,'pending',NULL),(37,1,1,'pending',NULL),(37,2,0,'pending',NULL),(38,1,1,'pending',NULL),(38,2,1,'pending',NULL),(39,1,1,'pending',NULL),(39,2,0,'pending',NULL),(40,1,1,'pending',NULL),(40,2,0,'pending',NULL),(41,1,1,'pending',NULL),(41,2,0,'pending',NULL),(42,1,1,'pending',NULL),(42,2,0,'pending',NULL),(43,1,1,'pending',NULL),(43,2,0,'pending',NULL),(44,1,1,'pending',NULL),(44,2,0,'pending',NULL),(45,1,1,'pending',NULL),(45,2,0,'pending',NULL),(46,1,1,'pending',NULL),(46,2,0,'pending',NULL),(47,1,1,'pending',NULL),(47,2,0,'pending',NULL),(48,1,1,'pending',NULL),(48,2,0,'pending',NULL),(49,1,1,'pending',NULL),(49,2,0,'pending',NULL),(52,1,1,'pending',NULL),(52,2,1,'pending',NULL),(53,1,1,'pending',NULL),(53,2,0,'pending',NULL),(54,1,1,'pending',NULL),(54,2,1,'pending',NULL),(55,1,1,'pending',NULL),(55,2,0,'pending',NULL),(56,1,1,'pending',NULL),(56,2,0,'pending',NULL),(57,1,1,'pending',NULL),(57,2,0,'pending',NULL),(58,1,1,'pending',NULL),(59,1,1,'pending',NULL),(60,1,1,'pending',NULL),(61,1,1,'pending',NULL),(62,1,1,'completed',NULL),(63,1,1,'completed',NULL),(63,2,1,'completed',NULL),(64,1,1,'pending',NULL),(64,2,0,'pending',NULL),(65,1,1,'pending',NULL),(65,2,0,'pending',NULL),(66,1,1,'pending',NULL),(66,2,0,'pending',NULL),(67,1,1,'pending',NULL),(68,1,1,'pending',NULL),(69,1,1,'pending',NULL),(70,1,1,'pending',NULL);
/*!40000 ALTER TABLE `schedule_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedules`
--

DROP TABLE IF EXISTS `schedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `schedules` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` bigint(20) unsigned DEFAULT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_car_id` bigint(20) unsigned NOT NULL,
  `service_unit_id` bigint(20) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `data` json DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedules`
--

LOCK TABLES `schedules` WRITE;
/*!40000 ALTER TABLE `schedules` DISABLE KEYS */;
INSERT INTO `schedules` VALUES (1,'WS1646746877',4,2,1,3,'2022-02-22 15:30:18','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','cancelled',NULL,'2022-03-08 13:41:17','2022-03-25 08:27:08',NULL),(22,'WS1320208381',10,2,1,3,'2022-03-14 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','completed',NULL,'2022-03-09 10:25:00','2022-03-25 07:38:54',NULL),(23,'WS1363859951',10,2,1,3,'2022-03-21 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','completed',NULL,'2022-03-09 10:25:00','2022-03-26 08:58:01',NULL),(24,'WS1640468189',10,2,1,3,'2022-03-28 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-09 10:25:00','2022-03-25 07:38:54',NULL),(25,'WS1162786385',10,2,1,3,'2022-04-04 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-09 10:25:00','2022-03-25 07:38:54',NULL),(26,'WS1577009741',11,8,4,5,'2022-03-21 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(27,'WS1116833025',11,8,4,5,'2022-03-28 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(28,'WS1592036102',11,8,4,5,'2022-04-04 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(29,'WS1380241806',11,8,4,5,'2022-04-11 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(30,'WS1292667258',11,8,4,5,'2022-03-17 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(31,'WS1505638460',11,8,4,5,'2022-03-24 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(32,'WS1388071755',11,8,4,5,'2022-03-31 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:54',NULL),(33,'WS1534000006',11,8,4,5,'2022-04-07 06:00:00','{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-15 13:16:59','2022-03-25 07:38:55',NULL),(34,'WS1560593149',12,7,3,5,'2022-03-21 08:00:00','{\"address\": {\"area\": \"Ruby Ln, Thammanam, Ernakulam, Kerala 682032, India\", \"address\": \"BCG GOLDEN ORCHID\", \"city_id\": 1, \"approved\": true, \"house_no\": \"2A\", \"latitude\": 9.9876274, \"longitude\": 76.3079131, \"house_name\": \"BCG GOLDEN ORCHID\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 03:33:05','2022-03-25 07:38:55',NULL),(35,'WS1175492556',12,7,3,5,'2022-03-28 08:00:00','{\"address\": {\"area\": \"Ruby Ln, Thammanam, Ernakulam, Kerala 682032, India\", \"address\": \"BCG GOLDEN ORCHID\", \"city_id\": 1, \"approved\": true, \"house_no\": \"2A\", \"latitude\": 9.9876274, \"longitude\": 76.3079131, \"house_name\": \"BCG GOLDEN ORCHID\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 03:33:05','2022-03-25 07:38:55',NULL),(36,'WS1646913928',12,7,3,5,'2022-04-04 08:00:00','{\"address\": {\"area\": \"Ruby Ln, Thammanam, Ernakulam, Kerala 682032, India\", \"address\": \"BCG GOLDEN ORCHID\", \"city_id\": 1, \"approved\": true, \"house_no\": \"2A\", \"latitude\": 9.9876274, \"longitude\": 76.3079131, \"house_name\": \"BCG GOLDEN ORCHID\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 03:33:05','2022-03-25 07:38:55',NULL),(37,'WS1150234969',12,7,3,5,'2022-04-11 08:00:00','{\"address\": {\"area\": \"Ruby Ln, Thammanam, Ernakulam, Kerala 682032, India\", \"address\": \"BCG GOLDEN ORCHID\", \"city_id\": 1, \"approved\": true, \"house_no\": \"2A\", \"latitude\": 9.9876274, \"longitude\": 76.3079131, \"house_name\": \"BCG GOLDEN ORCHID\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 03:33:05','2022-03-25 07:38:55',NULL),(38,'WS1615286898',13,9,5,5,'2022-03-21 08:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(39,'WS1216259271',13,9,5,5,'2022-03-28 08:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(40,'WS1204682862',13,9,5,5,'2022-04-04 08:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(41,'WS1128399833',13,9,5,5,'2022-04-11 08:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(42,'WS1271771840',13,9,5,5,'2022-03-17 07:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(43,'WS1307163865',13,9,5,5,'2022-03-24 07:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(44,'WS1290527519',13,9,5,5,'2022-03-31 07:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(45,'WS1273990707',13,9,5,5,'2022-04-07 07:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(46,'WS1514172859',13,9,5,5,'2022-03-19 09:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(47,'WS1570250391',13,9,5,5,'2022-03-26 09:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(48,'WS1287881608',13,9,5,5,'2022-04-02 09:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(49,'WS1545138006',13,9,5,5,'2022-04-09 09:00:00','{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 08:13:17','2022-03-25 07:38:55',NULL),(52,'WS1581094320',15,6,2,5,'2022-03-23 10:00:00','{\"address\": {\"area\": \"Infopark Rd, Kusumagiri, Kakkanad, Kerala 682030, India\", \"address\": \"Ncc greenvalley, infopark Road,kusumagiri, opp novotel Hotel,\", \"city_id\": 1, \"approved\": true, \"house_no\": \"a\", \"latitude\": 10.0158151, \"longitude\": 76.3616031, \"house_name\": \"NCC GREEN VALLEY\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 11:15:19','2022-03-25 07:38:55',NULL),(53,'WS1331145561',15,6,2,5,'2022-04-06 10:00:00','{\"address\": {\"area\": \"Infopark Rd, Kusumagiri, Kakkanad, Kerala 682030, India\", \"address\": \"Ncc greenvalley, infopark Road,kusumagiri, opp novotel Hotel,\", \"city_id\": 1, \"approved\": true, \"house_no\": \"a\", \"latitude\": 10.0158151, \"longitude\": 76.3616031, \"house_name\": \"NCC GREEN VALLEY\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-16 11:15:19','2022-03-25 07:38:55',NULL),(54,'WS1555018169',16,10,6,5,'2022-03-07 06:00:00','{\"address\": {\"area\": \"KKRA-110, AKG Rd, Maradu, Kochi, Kerala 682038, India\", \"address\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"city_id\": 1, \"approved\": true, \"house_no\": \"KRRA 110\", \"latitude\": 9.931747, \"longitude\": 76.325495, \"house_name\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 12:44:12','2022-03-25 07:38:55',NULL),(55,'WS1257328185',16,10,6,5,'2022-03-14 06:00:00','{\"address\": {\"area\": \"KKRA-110, AKG Rd, Maradu, Kochi, Kerala 682038, India\", \"address\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"city_id\": 1, \"approved\": true, \"house_no\": \"KRRA 110\", \"latitude\": 9.931747, \"longitude\": 76.325495, \"house_name\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 12:44:12','2022-03-25 07:38:55',NULL),(56,'WS1380349290',16,10,6,5,'2022-03-21 06:00:00','{\"address\": {\"area\": \"KKRA-110, AKG Rd, Maradu, Kochi, Kerala 682038, India\", \"address\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"city_id\": 1, \"approved\": true, \"house_no\": \"KRRA 110\", \"latitude\": 9.931747, \"longitude\": 76.325495, \"house_name\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 12:44:12','2022-03-25 07:38:55',NULL),(57,'WS1152707364',16,10,6,5,'2022-03-28 06:00:00','{\"address\": {\"area\": \"KKRA-110, AKG Rd, Maradu, Kochi, Kerala 682038, India\", \"address\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"city_id\": 1, \"approved\": true, \"house_no\": \"KRRA 110\", \"latitude\": 9.931747, \"longitude\": 76.325495, \"house_name\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"address_type\": \"house\"}}','created',NULL,'2022-03-16 12:44:12','2022-03-25 07:38:55',NULL),(58,'WS1185758411',17,11,7,3,'2022-03-31 17:10:00','{\"address\": {\"area\": \"Vennala High School Rd, Chembumukku, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"venalla high school\", \"city_id\": 1, \"approved\": true, \"house_no\": \"Geo Villa 10\", \"latitude\": \"10.0053235\", \"longitude\": \"76.31929000000001\", \"house_name\": \"KITCO\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-28 06:03:42','2022-03-28 06:03:42',NULL),(59,'WS1629023709',17,11,7,3,'2022-04-07 17:10:00','{\"address\": {\"area\": \"Vennala High School Rd, Chembumukku, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"venalla high school\", \"city_id\": 1, \"approved\": true, \"house_no\": \"Geo Villa 10\", \"latitude\": \"10.0053235\", \"longitude\": \"76.31929000000001\", \"house_name\": \"KITCO\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-28 06:03:42','2022-03-28 06:03:42',NULL),(60,'WS1196146657',17,11,7,3,'2022-04-14 17:10:00','{\"address\": {\"area\": \"Vennala High School Rd, Chembumukku, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"venalla high school\", \"city_id\": 1, \"approved\": true, \"house_no\": \"Geo Villa 10\", \"latitude\": \"10.0053235\", \"longitude\": \"76.31929000000001\", \"house_name\": \"KITCO\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-28 06:03:42','2022-03-28 06:03:42',NULL),(61,'WS1357329405',17,11,7,3,'2022-04-21 17:10:00','{\"address\": {\"area\": \"Vennala High School Rd, Chembumukku, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"venalla high school\", \"city_id\": 1, \"approved\": true, \"house_no\": \"Geo Villa 10\", \"latitude\": \"10.0053235\", \"longitude\": \"76.31929000000001\", \"house_name\": \"KITCO\", \"address_type\": \"flat\"}}','created',NULL,'2022-03-28 06:03:42','2022-03-28 06:03:42',NULL),(62,'WS1648452584',18,12,9,3,'2022-03-28 17:15:00','{\"address\": {\"area\": \"Civil Line Rd, Padivattom, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"babbab\", \"city_id\": 1, \"approved\": true, \"house_no\": \"123\", \"latitude\": \"10.0083045\", \"longitude\": \"76.3160731\", \"house_name\": \"go\", \"address_type\": \"office\"}}','completed',NULL,'2022-03-28 07:29:44','2022-03-28 07:34:32',NULL),(63,'WS1366537757',19,2,1,3,'2023-03-13 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','completed',NULL,'2023-03-21 05:51:21','2023-03-21 05:52:20',NULL),(64,'WS1253715718',19,2,1,3,'2023-03-20 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:51:21','2023-03-21 05:51:21',NULL),(65,'WS1574876266',19,2,1,3,'2023-03-27 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:51:21','2023-03-21 05:51:21',NULL),(66,'WS1619079746',19,2,1,3,'2023-04-03 07:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:51:21','2023-03-21 05:51:21',NULL),(67,'WS1381133802',20,2,11,3,'2023-03-22 00:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:59:40','2023-03-21 05:59:40',NULL),(68,'WS1265879143',20,2,11,3,'2023-03-29 00:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:59:40','2023-03-21 05:59:40',NULL),(69,'WS1221128724',20,2,11,3,'2023-04-05 00:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:59:40','2023-03-21 05:59:40',NULL),(70,'WS1486872983',20,2,11,3,'2023-04-12 00:00:00','{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}}','created',NULL,'2023-03-21 05:59:40','2023-03-21 05:59:40',NULL);
/*!40000 ALTER TABLE `schedules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('15SQD5TWh6HzOXW3dw2mqXCEnikMKg94W1VEka4L',NULL,'172.70.111.52','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0NjaTlYUDZ4Rkp4U29hNk1ZUVM0dWlMVGZObHZXRjExVE1wZnpvMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683061302),('1VmaR9Av7mAKNHVnoULIEBPaXD7V70ipihx6yduF',NULL,'172.70.34.17','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRmhhcEVjbUdWekV1SldUMHprSWlEWjhZV3lTZjl2VDBTalhPZjZVVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684947337),('3fJICdqmZzU1Cui3TcFgGxfwZDqnbMAfk6O61fhV',NULL,'172.70.92.134','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicVphMW5PQWtTeWtTcEEwQm15YTFRSXI2d1hRTEc5QTNnZnVsd1hRVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683407241),('3fJMXQw8ASh9Fz5B3pyKM7Z9ITkuNmaFdA4tNtEY',NULL,'172.68.19.150','Expanse, a Palo Alto Networks company, searches across the global IPv4 space multiple times per day to identify customers&#39; presences on the Internet. If you would like to be excluded from our scans, please send IP addresses/domains to: scaninfo@paloaltonetworks.com','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmR4VU90VU9Sc2REZ3pZRGlNSVNyaVZpazV0N1BzQ1BHdUVlVjBJUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682634776),('4pWTqhBbFYBmilIhXULb5cExv1FeLNbXanjryTqY',1,'172.71.186.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiZEFRa09tT3F6RnRvQ2VsZHg3YkZBU0RrQ1BTSVhrZHlkT0pFUnVpTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQyOiJodHRwczovL2Rldi5nb2tsZWVuY2FyLmNvbS9hZG1pbi9zY2hlZHVsZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTAkUG1TUmFPeFNFL0YyYjc3SEl1WjhoT1RUek9ZMXVCdlpEVGc5dGYzeTRxWU1qNm4xSmFnWHEiO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEwJFBtU1JhT3hTRS9GMmI3N0hJdVo4aE9UVHpPWTF1QnZaRFRnOXRmM3k0cVlNajZuMUphZ1hxIjt9',1685083383),('4TKnUDMDonyvUSR6Lwz0oNICZJK9EfkUb4gDV135',NULL,'172.70.34.86','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoid25tZUo5ZXY3Z1pTMHVXSGFhTWlRNGxiOVZHdVF6a0p0aGxQYUxhUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684944836),('5SnPzSrP6EtvUW8aSXx8ZA1q2eAztfk3I25kVtBu',NULL,'172.70.46.81','Mozlila/5.0 (Linux; Android 7.0; SM-G892A Bulid/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Moblie Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTGk0VGJwc0tKYmlvYWFPWEx1SmpBQzJ1S0pwVzZIUmc1ZU1DOElBOCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683164183),('7labjcpWVOpoRp9Uk0oMFwsjcwu7KswG0N2S4Ser',NULL,'108.162.227.78','Mozilla/5.0 (X11; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZEQ0UUV0UmNJQTBiaDdGOHUxcVNrejlpbDBQamhnMURqSHVLZFMwUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683136280),('7NZrrD5TExOTiqDd6ZqzkxVJnkszhrZlSfAg3K8F',NULL,'108.162.227.90','Mozlila/5.0 (Linux; Android 7.0; SM-G892A Bulid/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Moblie Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoib3dncFJTZnNzUFRqYjRWb3BxRFFZY3NxeFdiTGZrTEtpMFFsVzVsViI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684322783),('8TfCSoMxYyqvZvLpXgwuNvOm85hMzmTIqqSBbyem',NULL,'108.162.242.33','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicWk3eXJZc1hKbmRqcVRUMzIxMVlJMzFCNkx6Y0lJRllwdGU4SXVMMSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683025952),('ahZQhlJM25FmNVQv9cpkutpvhZJ4bM85kzFWGt3s',NULL,'172.70.143.59','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQTg2cnp4WTdCODZnUDR4ZnVyeUNWaEFJOW9VZFFRT3J1a1l6aWRlSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9fQ==',1684998507),('aL88LhYTcNYkZ4bfybaXZelnEH5j5EvVn2SOC8BP',NULL,'172.70.111.57','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnVTajVXWUZYeWV3aXFzTFhJZ2x6dlBPRnRJamZMcG5LM09nVG1aYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683063427),('aPa72CB1YMlP1w6de8S3iqrvj272BmecLuRL7H6z',NULL,'162.158.111.24','quic-go-HTTP/3','YToyOntzOjY6Il90b2tlbiI7czo0MDoicnkzQ3U3bnZIREFwcFpLak9RbGFsdnNadUVuVzNYSVFCd0dha0lBNiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683881975),('B0MFhNonaBkt7PnCBi237WLHqoeHzJluW1SLtvoj',NULL,'172.70.42.242','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibnFvQ2JtbG5TajNQQUtIQVVEV3A0enY4OUlVbGRHc0pKQk5jR3BxeCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684043154),('BqOv39GSc8EppJNYm2oKPxPgxw3MO1RvJeAQEOIx',NULL,'172.70.127.7','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiR2F2bkM4OFRzTkxyZzUxdXgydmlEcUE1c0JvY3FEQmdzME1JcmZNMiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684556837),('bzV7tje18Juaa07cgmJG2IYfgh5vMQpCUDmeF3iM',NULL,'172.70.34.21','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUXVMbFlmZDgwZEs4VkxFVUg2N3ZpMWE1dmtNTjNNVkF3a1Y3TVZGRiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684945656),('CboRda3SUOVn8rzdW5xVKNpNt8SAKxfJ1fpLumN8',NULL,'172.70.175.100','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSlJyalFtakFQMzB1T0ZhNG1RUGpKcnlZS0hvS2tYUWkzWlJBM09kNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684042645),('cJP4pSB9NbcGJ8BBQaAwvcyM68j1vz1EwGIWvPSQ',NULL,'108.162.227.78','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiMU9qSkNXWHBRc0pBamRJb1dOWmZkVHEyT25GN2ZiMFJHbzBOZENWZCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682957364),('CNKQPGOwMihAm3bWjazuMEdLtBZOW9TU7zi1t6mX',NULL,'172.70.189.177','Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/81.0.4044.129 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ3hIN1JxOHo4TmxLdkd6dkoyUW43a3RPTWVUSkVqSnNwYmZ6MEd4VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684183089),('DEnx3nmRgC1IJ96kqxyM9F56ImgzaUg0YghtMwNC',NULL,'172.68.34.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko; compatible; BW/1.1; bit.ly/3eZNDnO; dc68fd7ef3) Chrome/84.0.4147.105 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWmEzdDQ2M3MwdVVqT0w2QlVRbXpRcjJWYW90TTVJVE5HTzY1SEYxYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683669867),('DizrGyFTI86XucDenYLTFfMJscUwpfspKYIugOnF',NULL,'108.162.241.55','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVBWMVk4ak9odVBsZ211VHJsMlJUZGZSM2xNUDNlcVRkV3B4ZDQzYiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683063426),('DYaQMw6INtq3WqrXq2krxZI0moXmTaoWAZEmQM7Z',NULL,'172.68.34.234','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko; compatible; BW/1.1; bit.ly/3eZNDnO; dc68fd7ef3) Chrome/84.0.4147.105 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibGtzWjQyYU5wbDM1ck1pZ295SUxFOEpPcGx2bFZEY0tqSzNRY1JzdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683669866),('e4T7XdtdmsME1LnXZ6ouNe8n1HcknBiJqNROE249',NULL,'172.70.35.53','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZG5LWWRibUREZnA1UEJkRTk0Q0U5UXFqemQ3UFphcWY3RTlFN2tVcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684944835),('E6YHSXD93OmfX2k0YeGMmjlX3MOMSMq6NvXDVC8l',NULL,'172.71.254.182','Expanse, a Palo Alto Networks company, searches across the global IPv4 space multiple times per day to identify customers&#39; presences on the Internet. If you would like to be excluded from our scans, please send IP addresses/domains to: scaninfo@paloaltonetworks.com','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSWFhYWxaakh1cVVVY2hIOVBIY1V1OUF3RVN5SjBqSWhQRU1Rb29hMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684242842),('e7pJmKOsHEGHJvehJvLGXkai9uTKSvRtMFgY5ISi',NULL,'172.69.58.247','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzF3VWtQRmZMR1VOMjk2bVFrQzZoTk5vQ1lub0NtYkxWcWpiSFhtNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683928682),('e9xuXHAWzKuSUH8t4GPE5xKfK7x7tZ1iHjyx6UUo',NULL,'162.158.178.46','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZzRsMXM1S2pUSnJBYmNYTVMzN0FYSEM1d3JISHlIMjZoVjNxdnhZVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683934078),('EMRvL2WeWx4Gz4aE1IU5yIwtpnZ85TnLjNIB7Wde',NULL,'172.71.210.199','','YTozOntzOjY6Il90b2tlbiI7czo0MDoidzY5c25WMlA4QjRldHhhUlFLSlV4NzJFN09aNE1FblVORUIzb3hzNSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684550228),('eOFanuOmNbwBPMNACBiQ8NZEvgfF6KFR2p84coLS',NULL,'162.158.107.13','Mozilla/5.0 (Linux; Android 11; SAMSUNG SM-G981U1) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/16.0 Chrome/92.0.4515.166 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1BTb2dDU21CS2p0ZklUODZpdnBWYzAxMTlaUDdRMU9oS0tJdzVXQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684443053),('eyVXbYpEdsLxgxLYcfdwOulUdroVsKOMiHd1oAbL',NULL,'172.71.211.8','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZzRGN1ZmS1lSSDZPQ3pxcVphSFlNWmpnbVhPUmJFRTNqaDhQcElDaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684550227),('EzW1s94KJAoab2EDRENFU4P74SD4Rv5DVqB4Uj4P',NULL,'172.71.135.79','','YTozOntzOjY6Il90b2tlbiI7czo0MDoid0N5bGE0NWNWa3U1Nmc0S0dxd2dWb2VnWkJHZ1BBOFBwa0hRUThBZyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682753543),('fARgOHeoIIebkBZyFy6c2rhlqRMbCHuRmG9Gmozm',NULL,'172.69.22.93','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoibnhFU24xSTlhVXUyTVQyQTJYUTQxSWxIZWJPQjFMU1A5VzhoSW1wTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683340687),('FEy7wSezw08n0iqPbRK7tuYnJqaJlhYSvKOD7ZCR',1,'162.158.162.12','Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Mobile Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoieVZlWHhoN085a3ZZWDRoa2t5NGU3ZDRSaTJOQmJ5cHNrems5VzJxcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL2N1c3RvbWVycyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRQbVNSYU94U0UvRjJiNzdISXVaOGhPVFR6T1kxdUJ2WkRUZzl0ZjN5NHFZTWo2bjFKYWdYcSI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkUG1TUmFPeFNFL0YyYjc3SEl1WjhoT1RUek9ZMXVCdlpEVGc5dGYzeTRxWU1qNm4xSmFnWHEiO30=',1684978770),('gaH7q3OMsqICgLJkKegJZTaw9xUYrqETJcKKYlCH',NULL,'172.69.23.113','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoieGNEOWRzSWxUaTJxSmJQbFhxNk5UMThtekZuQmJtT0ZyQWlnOThIbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683341117),('GRzJUG2Zx3GeLw7xY04pGNNsgfTMWF7mOV6nYYbX',NULL,'172.71.154.100','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMDJOQ203MTF5dGJ3VzA5Z3RKcGF3bmhqcDBhaENZTWtrQ3JVbThjWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683341102),('HaBoZQfUQIVg1Doi5OkOq9O9Z5DZnEAd5TPCF92P',NULL,'172.70.142.86','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiMUJHQ1BwcDROWTE4cjNadW1wTDNyd3k1TFlia1pYbTU3Q0FFRmpJaSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683784735),('HiD2VqHcz2ZTnGPYCOOX1QOPkZsaD5JryEAjunLh',NULL,'172.71.122.197','','YTozOntzOjY6Il90b2tlbiI7czo0MDoieW5OeFJuV21GcE5Na01kU2FPN1BaUklCbnVnNjhpaGFuNkU3VzZJTCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682792986),('HLXbBwKIbAjX1KiSc4oXadl0ayJOsIDIqWFgkC2l',NULL,'172.71.102.109','python-requests/2.27.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVW5wNWcwcVdHV2FQZVg3QjhnV2xMSkZJbEhDa1FTN2dMS3dUdzg2WCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683179230),('hMUxkwTF5Wi3zErS0CWcQ0nEu8amyXfgWcLnzCDJ',NULL,'172.70.100.234','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS0VQRHFMU1lqeG1GRDU5ZXU4alBPOEpTaEJoUm5SdXAyN3Jyb2prWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684663803),('hszkjMzcTH8wSX8E4djRpk8b9Her3IE541j5ykrA',NULL,'172.70.39.45','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ1ZKWmJkQVpZVHFnSjYzQjJwcjFvNldzaEI4WXRCR1VhWGt1Yk40SyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684043154),('HzfcrK5hflvmI3YEhAu8WTrTvvBe9ya0Qb2J1MWo',NULL,'172.70.130.78','','YTozOntzOjY6Il90b2tlbiI7czo0MDoicGV1ajE5bWdmaHR1eTJZZ0tOQVdUYjRlbFRNTzRabko2cmtCc2pVdCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684556837),('ID1IqhSMKzUoAu1CjAHaibAvPQYy6JMevJWjntPG',NULL,'172.71.210.214','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiNEpFTXZIZ1h6bFNieEhVZUxTekJTVmhkOWpvcnFYcXpnOFpXOVhXWiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683934077),('ignvDkfC2PDT2id5aFdIPaZcf48cXgZeUQFa85Wd',NULL,'172.70.142.192','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3; rv:52.6.0) Gecko/20100101 Firefox/52.6.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoicFlaWm9GTDIwSWN4dEdjcXF6STU2b2F0ZHduaVhHUW0wMGJzaEJkUiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683257033),('ijSmzF6vrkdZKeIhJwjR639mz6sMOUbQ5hZ9Pm1P',NULL,'108.162.241.10','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHpXUWxpdWNpQk1LUW41SDM0cHo0YzZieW12Q1U2R00yTEJVN3dvaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684986167),('jDlxUePo5lL2mges5ZHq1YQDVXAwIsLcswf5P36r',NULL,'172.70.110.24','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoidldOZW9yQTJ2ZG9zNTNyVXlTcDE2MUxnMkoxVVZKZTRxaTNZcDlURSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683132340),('JpOJFI451LHqBAsHfh2XrXFriJRE7zerPU109sLW',NULL,'162.158.107.8','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/106.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZlVnOXlEZU1Xblg5Z1ZsMkdSQjZ5MHJWcHFieFNJMG82S0RpVkd3VSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684135807),('JUCNtyaqqfPL1oYqGMUeeHLD2sV8Y6T8r8ksekP0',NULL,'172.70.147.119','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiM1VwcDR6dWFwNnlrbU4xZU9PVWREeklvdExXT3pMUFBIRHIxc3FjaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684240439),('jXSLIRGIE3UYXhaA6poPlQ8qWkuYME4N2PUpJIsq',NULL,'162.158.2.170','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4859.172 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYTM4S2Q0QmRjSjZ0TzVIRTVURUFqYTN5NEo5ZHY1QWhFUnRLSEx2QyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684028270),('k5GDHhCfbGdNa7CDM35HSz5Z6ZBgtFRuihv9YFQ5',NULL,'172.70.111.51','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoic0hUb1Z0NkRtNG1RRm9wekxkYUhwVGJyR2R5Ymc3cjRQdGhSY1FNQyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683063427),('kLuP4uL5FtrOPFjIO4NkIQM4LZbWmq4mJX6GPJ9K',NULL,'172.70.174.109','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQWY1OVhWbEVkakFtTlZtRmY5c25heE5yNFpjMnBQVkVPZWljclhqVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684042643),('KO0mQWxzDU6J5Z69RULPNFsUg311CKxPHu6sH5u9',NULL,'172.70.251.178','quic-go-HTTP/3','YToyOntzOjY6Il90b2tlbiI7czo0MDoiSXdhbDMxVUdiMFJwbXV5RUVqWmwwR3pJd1ljTkxKUGlOcDBiQWV1ayI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682662103),('kVqe6MjaypVF5iQmLfn59lNFMaDf7xra43U5y4Vy',NULL,'162.158.79.203','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiM0k5MzFUQ2lQeW9pdHh5R2dFQXRiSDN1VlFkdWVmUUVLbUozQmJOWiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684423021),('KWySf2Opw1k8K0J7Q1WJo9K8uphd91UmPSbnsoOC',3,'162.158.190.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiQWpNV1RpTG9aU21EcXFsN3VmdVJRYjkxVVZNTUFHMXhVMHMwQjFHeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL2Rhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkbHRXaFpHR2k2c2pzMzhia00wRnl6Lm4vZ2RQQk1FODBOVFVWOVFYR3p2dGhkMTk0Y1ZsRDYiO30=',1684240749),('L2PhVQEjle2GGiEIRvVQ2Nr6Ljd1tlnwrPl4dxA1',NULL,'108.162.241.10','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSmRRRzZDVWFadlp4c2ZVd29mNENRckJHYVBjWU55djhUbWhybm5YOSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684986167),('LBKeqrLolVE7cvzDLwZFUbQxepzQKk3kAwfOduD6',NULL,'172.71.158.111','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoiN3l1ODBkd0ZUWDRkeEt0NjdUWW5DREx6Tjg4elBudWxJdlpZN1hRUSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683340663),('lciHGI5Kd7kmKRUaDZ5a46YCAdTx8mrDdyh33gAs',NULL,'172.71.11.58','Expanse, a Palo Alto Networks company, searches across the global IPv4 space multiple times per day to identify customers&#39; presences on the Internet. If you would like to be excluded from our scans, please send IP addresses/domains to: scaninfo@paloaltonetworks.com','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ3pld2pFNjBwRXh5MWxzejRSZG1sd1NkWGNpVmU4bmFlMTg2eFR3MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682439983),('lFPMqXNWNSpqfGO32fTPtZhOkPxPbBPkmAWVEtO0',NULL,'172.69.23.113','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUlhZMWpYUUgySXBpeFc1RHg3OHlSajZBcmEzcUEwbVJUZFBsTzJhaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683340706),('lgv81MwUUw0Fp2PpqH8JMzks3u6YJDYW7dI2gQj4',1,'172.70.242.194','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiUUpVRUE5bVpac3ZXU1lDQ1U4dE9VMURKTmRWalNxdlRYa0o0R291UyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL2Rhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRQbVNSYU94U0UvRjJiNzdISXVaOGhPVFR6T1kxdUJ2WkRUZzl0ZjN5NHFZTWo2bjFKYWdYcSI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkUG1TUmFPeFNFL0YyYjc3SEl1WjhoT1RUek9ZMXVCdlpEVGc5dGYzeTRxWU1qNm4xSmFnWHEiO30=',1685085138),('LHqZTGoULF0V4wBO2wb7iM2nV8Ymr23iN4b3Zyn9',NULL,'172.71.222.109','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFp2d0M5akNUOXJSaHZDVUt0TW10TnFOOFhkUVRhVXhTSTloMlY1OSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684042644),('LWWFLOpcDcmBzFq3En6iXD4IzM4dYM7sAb5pkPJz',NULL,'172.70.43.111','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidGh4b1lRYXhxOTZnMkpUbzdlRjEyR1lxdFZva0xQTlJVNDk5cWh3VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684043155),('MU1FIzzx3SwVbjatlqj6XIemmaN4ohtH2Nw7ejbu',NULL,'172.71.11.58','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQ2RhcWhua283Q0RMeHhsaG9zcGl4ZkIzTDBDM0ZRUHg0WlpxN1p0SiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684523708),('n1ucZBeagD95BZ39T9c1JbyarPsxabU1fOXmN1vo',3,'172.70.147.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZWlYMm9MN25YUUYyZEtpS0p4NUpubTl5WlQ3bmR1VnozNXBQVG1heiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL3NjaGVkdWxlcz9qamZrPW5qa2YiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTAkbHRXaFpHR2k2c2pzMzhia00wRnl6Lm4vZ2RQQk1FODBOVFVWOVFYR3p2dGhkMTk0Y1ZsRDYiO3M6MjE6InBhc3N3b3JkX2hhc2hfc2FuY3R1bSI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2Ijt9',1685015300),('n6jjRCqFrjWYQWA0R5ua16736TdPudbYqqY2QFVV',NULL,'172.70.38.14','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoicUN4bmVKYTdwbW5YZFBsUDR6MEdkZTRoeHV1QXRQT1BKTG1ibm52biI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682936165),('NCw22ME0B77PNwcNzZ2aJGUf6F1ic0boTqZnZJzV',NULL,'172.70.34.16','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiU1RmbFYxZW10MEJwbFZOV29PY1VNVjZjSVRTQ3ozbDZ1UEhzcGZ1TyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684945657),('ngNl1LqzkNEmceejczDmseNM2c0A0xA5AWaFEtf2',NULL,'162.158.79.203','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiY3o4VlZQUGdjRllSNGlhOFE0SDRoWW1CM1JDbjdtSG1ZbzFGR2hWSSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684423021),('nSBGdob4IeBiW0zOjMUJAPnal9TDTKZ2GQuZ0mRb',3,'162.158.190.58','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiTTdKWXVDRzhrcHpSS0ZPSWE2NG5DcVVBbTVmUUEyMGUzaWhteEVIbSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2IjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL3NjaGVkdWxlcyI7fX0=',1685087210),('NWqw5A5slG1ftT6AZV5mXkXcH9TE1YhI760rCUoT',NULL,'172.70.111.52','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWlMUUNjQ3FCR1ZrbWo4OGZYWU9TakpJb3JlV2t4ZHMxclNRRmZ2ZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683061302),('OemRC3g55nc2SbzwmuTQh27JCrmwBBMjIh9kGAOc',NULL,'162.158.2.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZElVTkRTYU5uQjNjMUJuaWc1dE5GVzRoVWxueTZmTnNLb0xQZURLUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683181726),('Owwv645XYG6hi54kPqf4fYBtKuZceG0AF9YTqJYL',NULL,'172.71.154.72','','YTozOntzOjY6Il90b2tlbiI7czo0MDoidFJBSXpqZVplbVhqdTRMVmNJOWE1bkRwcGYxWjFXNXpLSU5vem5PSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683340630),('p63ckBqy30cfK77hmb8JLCiBEPiLEBr5cbmDT2MO',NULL,'172.70.143.63','WhatsApp/2.2317.9 W','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYWNMRXZuOXUzVWVDb01zVkpqSE9PeUIwYWdKZENFZWtNQnVZT2lOcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684239853),('Pck7kZb2JSwlO6n8WJsSZiHqweCpwMcIJ4HXVE8H',NULL,'108.162.227.78','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoic3AzSWZYM1diSnVQbFhLWEdObnZyVGRGMEt5OHJzWWNRZ2pWMVV0aCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9fQ==',1684243328),('PHkfd97RQvJk48RjmMWGB6zSGQK3ebj9ENpXCs62',NULL,'172.68.18.140','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSTVoVFNaZEVKT2VUOVFON2k3REcyREliVExXR05uUHVrcDBaUmNLZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684523708),('PjtMcaVyiHGJ7tG1uyHuH3uE14fk2jILHTMxrO10',NULL,'108.162.241.54','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoielR1b3VXQ01OUFVxUVZ2SEp5dDNLUmhYZkY3NDhwMTRHSjR3c0tzcCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683025952),('PJzQBYMFAvMJXcagX04Eic1BhVL4vb3aBJIQLm5p',NULL,'172.70.34.98','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQXVuMjBidU5kdzE5aGZtVURvM0thTk9ua2w5OHZhOFBYajhDa2VWNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684947338),('PLlEfelLHjKzeCpEmxl84odhEVUtkD6YjMmh3OFf',NULL,'108.162.242.77','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiYURDbFUzNFB3aklaQzFlckhIaGVJMnY2R1NyclhWSW1UWksySGxvaCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684986168),('PM69iz7Y6UWBSm1OaOa9H2qo0TQoQEmBqBSiJqcz',NULL,'172.70.39.46','Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.71 (KHTML like Gecko) WebVideo/1.0.1.10 Version/7.0 Safari/537.71','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUTNUWjdUNjFvVE11aVd4TjB4NDBJVWpnVTBlVjF1YWlwNXNmMDBETSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684490824),('ptnw6AP204foBB5Hjck1FMg8lFyGHySLvLOJYRPr',NULL,'108.162.227.31','Mozlila/5.0 (Linux; Android 7.0; SM-G892A Bulid/NRD90M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/60.0.3112.107 Moblie Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiVXFlWmVDS2xJbjk3SlJqOXI4ZTZaUnVBMTVsUG8wMjlsam5sSDVzVyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684990076),('q1mCanLk47ksdI5FgJ2PTJWcuDTzhqSQju8vtijm',NULL,'172.70.111.52','Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUlzQXJVempmS3F5SGF5WkJGcENveTdqeVZPWXNHY2RiZm5LaEJPMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683132340),('Qb57nhJcbZUHgaIimT60fzZ3GtZQgUd7BT3QZOct',3,'172.70.147.118','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoibXZtS09PSFFjeWdnNmFSTzkyVWRyOUxkVm9kSTh3MkEwN0lpc0RYVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL3NjaGVkdWxlcz9oamZoZT1oamVmaWdnZml1Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2IjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7fQ==',1684487499),('QC7DFWOrTRIfopdcWyQETuWUuIeGH6NMy2OFZ57X',1,'172.69.239.137','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/112.0.0.0 Safari/537.36','YTo3OntzOjY6Il90b2tlbiI7czo0MDoiY3NYZ3NuRUF4bllRT0xleDJLUGxDZXVBMm1BZlliVUdueGdpWHNCZCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwczovL2Rldi5nb2tsZWVuY2FyLmNvbS9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRQbVNSYU94U0UvRjJiNzdISXVaOGhPVFR6T1kxdUJ2WkRUZzl0ZjN5NHFZTWo2bjFKYWdYcSI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkUG1TUmFPeFNFL0YyYjc3SEl1WjhoT1RUek9ZMXVCdlpEVGc5dGYzeTRxWU1qNm4xSmFnWHEiO30=',1682614957),('QGKuUaX588ddCRXqJSvapgV7jwoF6mjfLgc32TK3',NULL,'172.70.143.63','PostmanRuntime/7.32.2','YTozOntzOjY6Il90b2tlbiI7czo0MDoiSzZzTTVzNXl6U21oeFJNeFN3TTNBSm51V0ZTTkIzQ0d6a3Y0WldGRSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684241908),('Qw5IzixbvTbzo2wOCMRM2xmuXruJ3C9dm9kTauGh',NULL,'108.162.241.11','Mozilla/5.0 (X11; CrOS i686 4319.74.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiekxmSW5NUVpQMVRNWGVNTE55V3Q3TUlvRG1VMzQ2WmYzUHZxaEdyMiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684902002),('r769cVmAn4COrhxWgwlkncjq9dvUrYCavQuVfAN8',NULL,'108.162.241.54','Mozilla/5.0 (X11; CrOS i686 4319.74.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.57 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoidHhzd3BqMWR4OXNrbmwwVzJYU2NFVUFKam45Nnc5Y3h6bzROdGxUUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684902003),('RsqwA1jcymppEgjmY0eacfOSwNUqJc8JIsoFGRyo',NULL,'172.71.214.144','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUzZVNHk5aTNtajRsMGphMGU0bmNLT2FXQXNxY3hyR0lNMUg1a0pCMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682777754),('TgIMVTweZnpYO9BvWbfvO1D2TihFBz9BQp1UZ4vA',NULL,'172.70.178.92','','YTozOntzOjY6Il90b2tlbiI7czo0MDoibFV1ajVGUTk2a09VSTVwRXc0c0piMjFCM0ZlbTRJclBMem1uM3lMSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683928682),('TNosGaeT6RBpa9zygfE7lAWAvb3DU9Vz4PuAloMA',NULL,'162.158.155.72','Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiQ1o3Y2tqWkRWblkzUm10cHVmNHhYZUhpRURTb3pIQXN0RHJWdmtObiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683958014),('U8kujOS355MWdaSdNqvPx8SK077vSbQZGuNb4Itr',NULL,'172.71.126.35','','YTozOntzOjY6Il90b2tlbiI7czo0MDoicTNoemhGRjZuaXZ4dnBZdUs5S0NLNEF2WHhWZjlqVU5hRzF2NzJoQiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682792986),('uRmdqCg3fyg3GqMKBVJBLS3iVRUVtI4oJSTgfwPw',NULL,'172.70.250.22','webprosbot/2.0 (+mailto:abuse-6337@webpros.com)','YTozOntzOjY6Il90b2tlbiI7czo0MDoiS2w3TGlxaGhBVXN5NlpkdmtrMldIM2NYRFZlQVlJVmRlVmFmTFlxNiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684480154),('USoPbXrf7x3Hl0xiefQrirCLMpuI5MMEUhOwsxZS',3,'162.158.190.23','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOThEVmJlN1JLTzBkalBZTEo0aUMwQ00xcXV2VmpaTmY5OFpHN0FVYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2IjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7fQ==',1684916858),('UuRZYI09WEyvR9K30E95zjF6h8xAR4v9Zv9A6NEb',NULL,'162.158.178.46','','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZlJSUW5Xc2NieDdRdGtQM3ZRQVhWOXd4dXNOSmZ0c256SWxFR3p4QSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682777754),('VAZaAEi7joXCDxZ4yMUAgSJCjIWdd5Kc0Imo7ppr',NULL,'162.158.2.170','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4859.172 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZW9iN0NONVJVdnBZd0lkNWQ2OFh5QUpwVGZxYnRrSGdPampZajE0ZiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684028270),('VDjDrx172DYOUysndJrXseb1bd6v3yQrmoMu8OSq',NULL,'172.70.42.63','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiRWk4cVRqZ2k4TXZNeVNWSGJXUFFod2RYbjhMTGtEUXpBVDNRZTNUVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684042644),('VGyBzpUz2DMRx8wpuksZoSFywm6lALTyV2tn5kzA',NULL,'108.162.216.126','','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkZ6ZGp4cWRLSWd0QnJDaXlqa0ozeDZvVWo4a2pCTFE5SUF5eUxrcSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjY6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684663802),('VK7VOLAsMZ6IwN3nGo45tadtxqPU0LJYFtcgjL0j',NULL,'172.70.34.25','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiWnVCQWJMY1QybU9ITHViY0d3MDA3Smh6cWJoekxOQnFWeVI1bklFcyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684944836),('Vw9zyZOFFKYjVWREBe44gd8LWXWAxCgd247T8fio',NULL,'162.158.126.5','webprosbot/2.0 (+mailto:abuse-6337@webpros.com)','YToyOntzOjY6Il90b2tlbiI7czo0MDoiVVVGUTZ4MGhTNGl3c1dqSVdrZjhnb1B1R1ROZDFtQmgzTkN6TjJ2WSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683847460),('wa1kMbQCE5xAOp07dzVjC8MPlrmcYUs3SNQjLOen',NULL,'172.70.174.14','Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_4; en-US) AppleWebKit/534.1 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/534.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTnhQNTIxdWk0dDdEbHBUZjk2a3FmNU9xQ0QxNFhGek9jSVlROWwzYyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1684043155),('wwV8z0f4XOe6XqjLyKgA2DCkyGvtLBfS6n9syh6J',NULL,'108.162.227.89','Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2227.0 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiWUFhRUE3eHNJRlJnU0lUcmhvdjFJSWRNaUJUUnhjQjVHN1FHc0V6WSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1682957364),('y3IuLxnXMmwYiL3Yjp7VffK6fJVg4iZMm1Nal8Wu',3,'162.158.189.177','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiZElVd1l2ZlFib2Z1S0tWOHFOSnNTa0x4eDJ3dGlTYnl5cjNvYUhheSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTQ6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL3NjaGVkdWxlcz9ua2hua2w9YmprayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7czoyMToicGFzc3dvcmRfaGFzaF9zYW5jdHVtIjtzOjYwOiIkMnkkMTAkbHRXaFpHR2k2c2pzMzhia00wRnl6Lm4vZ2RQQk1FODBOVFVWOVFYR3p2dGhkMTk0Y1ZsRDYiO30=',1684753395),('Y97kyXYw3C97Ysb6A22fllw4Hyr2WLOapkQIh0QC',3,'162.158.87.59','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWUpLU0tVZVRITWRkZENMTG5WaXVJS01SRWRjRHo2d3E2VzZqZ2t3MyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2IjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7fQ==',1684399824),('ynlzLaJWnXofZsmaLtFKjxQ49QVeSXa3dbTQlP2T',NULL,'172.70.111.78','Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.2 Safari/537.36','YToyOntzOjY6Il90b2tlbiI7czo0MDoiQWQwT1RJbG9DcVVuV2pueDdCZzNSWGprZ3BzcVhBenN3dHNNb29yciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683958014),('z5b8N7PgxzOaRbnrZj2FXPHVrjMen2YBoHATxEDz',NULL,'108.162.241.11','python-requests/2.26.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiZklFWGpFdEh1TEM3S2FnSlRjWEZFWVRTU1E3Z01FRnF2ZVVsMmR6MSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683025952),('ZlNk8vxHmjuweOME9mPSBZOaS5Ff043yyupnNm1r',3,'162.158.23.61','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSGF5OHZZUG4wOXFuMG56YmtxMHNNUGdKNjFlOHJDOFlVNzRxWk5LSCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MztzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEwJGx0V2haR0dpNnNqczM4YmtNMEZ5ei5uL2dkUEJNRTgwTlRVVjlRWEd6dnRoZDE5NGNWbEQ2IjtzOjIxOiJwYXNzd29yZF9oYXNoX3NhbmN0dW0iO3M6NjA6IiQyeSQxMCRsdFdoWkdHaTZzanMzOGJrTTBGeXoubi9nZFBCTUU4ME5UVVY5UVhHenZ0aGQxOTRjVmxENiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTg6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2FkbWluL3NjaGVkdWxlcz9oZ2p1Z3VpPWhndWlndWkiO319',1684774852),('ZlSw58kGEDFa8xk8WnXOx9W3ZxQuVr4cZF9jNbS8',NULL,'172.71.160.43','Mozilla/5.0 (Windows NT 6.3; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQVZtaG55T0FWY2dib1RsVmhNNkh5dGJxUGdvMllTbjZ1YnhvVzJUbyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHBzOi8vZGV2Lmdva2xlZW5jYXIuY29tL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1683349248);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `group` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked` tinyint(1) NOT NULL,
  `payload` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `settings_group_index` (`group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscription_items`
--

DROP TABLE IF EXISTS `subscription_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscription_items` (
  `subscription_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscription_items`
--

LOCK TABLES `subscription_items` WRITE;
/*!40000 ALTER TABLE `subscription_items` DISABLE KEYS */;
INSERT INTO `subscription_items` VALUES (1,1,4),(1,2,2),(2,1,4),(2,2,1),(3,1,4),(3,2,1),(4,1,4),(4,2,1),(5,1,2),(6,1,2),(6,2,1),(7,1,16),(7,2,1),(8,1,32),(8,2,2),(9,1,12),(9,2,3),(10,1,2),(10,2,1),(11,1,4),(11,2,1),(12,1,4),(13,1,4),(14,1,4),(15,1,4),(16,1,4),(17,1,4),(18,1,8),(19,1,8),(19,2,1),(18,2,1);
/*!40000 ALTER TABLE `subscription_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_car_id` bigint(20) unsigned NOT NULL,
  `plan_id` bigint(20) unsigned NOT NULL,
  `payment_mode` enum('online','offline') COLLATE utf8mb4_unicode_ci NOT NULL,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `next_renew_date` datetime DEFAULT NULL,
  `last_renewed_date` datetime DEFAULT NULL,
  `total_billing_cycles` int(11) NOT NULL DEFAULT '12',
  `completed_billing_cycles` int(11) NOT NULL DEFAULT '0',
  `preferences` json NOT NULL,
  `status` enum('created','pending','expired','active','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_customer_id_customer_car_id_status_index` (`customer_id`,`customer_car_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
INSERT INTO `subscriptions` VALUES (4,'SUB1646800318',2,1,2,'offline','2022-03-10 00:00:00',NULL,'2023-04-06 00:00:00','2023-03-08 00:00:00',24,2,'{\"days\": [{\"name\": \"Monday\", \"time\": \"07:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','cancelled','2022-03-09 04:31:58','2023-05-23 06:18:10',NULL),(7,'SUB1647348313',7,3,2,'offline','2022-03-21 00:00:00',NULL,'2022-04-18 00:00:00','2022-03-16 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"08:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-15 12:45:13','2022-03-15 13:14:38',NULL),(8,'SUB1647349842',8,4,3,'offline','2022-03-16 00:00:00',NULL,'2022-04-14 00:00:00','2022-03-16 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-15 13:10:42','2022-03-15 13:14:55',NULL),(9,'SUB1647418334',9,5,4,'offline','2022-03-16 00:00:00',NULL,'2022-05-12 00:00:00','2022-03-16 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"08:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"07:00:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"09:00:00\", \"selected\": true}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-16 08:12:14','2022-03-16 09:46:19',NULL),(10,'SUB1647419106',6,2,1,'online','2022-03-16 00:00:00',NULL,'2022-04-14 00:00:00','2022-03-16 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"10:00:00\", \"selected\": true}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-16 08:25:06','2022-03-16 08:26:13',NULL),(11,'SUB1647434435',10,6,2,'offline','2022-03-01 00:00:00',NULL,'2022-03-30 00:00:00','2022-03-01 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"06:00:00\", \"selected\": true}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-16 12:40:35','2022-03-16 12:42:39',NULL),(16,'SUB1307921806',11,7,2,'online','2022-03-31 00:00:00',NULL,'2022-04-28 00:00:00','2022-03-26 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"17:10:00\", \"selected\": true}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2022-03-26 13:20:41','2022-03-26 13:24:29',NULL),(17,'SUB1679378151',2,11,2,'online','2022-03-21 00:00:00',NULL,'2023-04-18 00:00:00','2023-03-20 00:00:00',12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": true}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','active','2023-03-21 05:55:51','2023-03-21 05:59:21',NULL),(18,'SUB1337617389',2,1,3,'online','2023-05-25 00:00:00',NULL,'2023-06-22 00:00:00',NULL,12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','created','2023-05-23 06:19:09','2023-05-23 06:19:10',NULL),(19,'SUB1233091522',2,1,3,'online','2023-05-25 00:00:00',NULL,'2023-06-22 00:00:00',NULL,12,1,'{\"days\": [{\"name\": \"Monday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Tuesday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Wednesday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Thursday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Friday\", \"time\": \"10:18:00\", \"selected\": true}, {\"name\": \"Saturday\", \"time\": \"00:00:00\", \"selected\": false}, {\"name\": \"Sunday\", \"time\": \"00:00:00\", \"selected\": false}]}','created','2023-05-23 06:19:14','2023-05-23 06:19:16',NULL);
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `payable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payable_id` bigint(20) unsigned NOT NULL,
  `wallet_id` bigint(20) unsigned NOT NULL,
  `type` enum('deposit','withdraw') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(64,0) NOT NULL,
  `confirmed` tinyint(1) NOT NULL,
  `meta` json DEFAULT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  KEY `transactions_payable_type_payable_id_index` (`payable_type`,`payable_id`),
  KEY `payable_type_payable_id_ind` (`payable_type`,`payable_id`),
  KEY `payable_type_ind` (`payable_type`,`payable_id`,`type`),
  KEY `payable_confirmed_ind` (`payable_type`,`payable_id`,`confirmed`),
  KEY `payable_type_confirmed_ind` (`payable_type`,`payable_id`,`type`,`confirmed`),
  KEY `transactions_type_index` (`type`),
  KEY `transactions_wallet_id_foreign` (`wallet_id`),
  CONSTRAINT `transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transfers`
--

DROP TABLE IF EXISTS `transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transfers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `from_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_id` bigint(20) unsigned NOT NULL,
  `to_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_id` bigint(20) unsigned NOT NULL,
  `status` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'transfer',
  `status_last` enum('exchange','transfer','paid','refund','gift') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deposit_id` bigint(20) unsigned NOT NULL,
  `withdraw_id` bigint(20) unsigned NOT NULL,
  `discount` decimal(64,0) NOT NULL DEFAULT '0',
  `fee` decimal(64,0) NOT NULL DEFAULT '0',
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transfers_uuid_unique` (`uuid`),
  KEY `transfers_from_type_from_id_index` (`from_type`,`from_id`),
  KEY `transfers_to_type_to_id_index` (`to_type`,`to_id`),
  KEY `transfers_deposit_id_foreign` (`deposit_id`),
  KEY `transfers_withdraw_id_foreign` (`withdraw_id`),
  CONSTRAINT `transfers_deposit_id_foreign` FOREIGN KEY (`deposit_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `transfers_withdraw_id_foreign` FOREIGN KEY (`withdraw_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transfers`
--

LOCK TABLES `transfers` WRITE;
/*!40000 ALTER TABLE `transfers` DISABLE KEYS */;
/*!40000 ALTER TABLE `transfers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES (1,'Long Standing Customers',1,NULL,NULL);
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `unique_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','service_unit','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile_verified_at` timestamp NULL DEFAULT NULL,
  `alt_mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city_id` bigint(20) unsigned NOT NULL,
  `service_unit_id` bigint(20) unsigned DEFAULT NULL,
  `user_group_id` bigint(20) unsigned DEFAULT NULL,
  `preferences` json DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expire_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint(20) unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  UNIQUE KEY `users_unique_code_unique` (`unique_code`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'KOCH000001','Surendra','Sahi','admin','sahi@nearchip.com','2022-02-10 00:10:51','7396243696','2022-02-10 00:10:51',NULL,1,NULL,NULL,NULL,NULL,NULL,'b0PR7qoRob0QcUrJciudK3i6WLiun8bDUTbfUPGCtZWB3ZSJLD2CXnYk51bC',NULL,NULL,1,NULL,'$2y$10$PmSRaOxSE/F2b77HIuZ8hOTTzOY1uBvZDTg9tf3y4qYMj6n1JagXq',NULL,NULL,NULL,'2022-02-10 00:10:51',NULL),(2,'KOCH000002','Rijoe','J','customer','rijokh9@gmail.com','2023-05-25 01:28:24','7510377707','2023-05-25 01:28:24',NULL,1,3,NULL,'{\"address\": {\"area\": \"Wayanad, Kerala, India\", \"address\": \"Wayanad, Kerala, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"G1\", \"latitude\": 11.703206, \"longitude\": 76.0833999, \"house_name\": \"Wayside Residency\", \"address_type\": \"flat\"}, \"fcm_tokens\": [\"fMn3CrbMTMqiAfzFvtOu3u:APA91bEKmn804TuihIkRdXY4z_YuvEhOw2JIN84Xu4MGe2lK9wjXSm1pEiKK-FyaH0ddx-p7cb7eYTwRy7tVq68fgN8Z1eZGR0xMRCVy7NpWjaC2KHn5xfSWttTxm4wR1zL8Eyl96tq-\", \"d3ANmkQkSVeZ1pKpYE0Nh-:APA91bER9WpLgeV8IolSo48IVs03twdPkINsFEvo7W3NZ64Bb6Xt0CCxdt4T6H2Nzv24t-nnkjR8KHMARe9qQZ9orhx92MX6sFrxQxjN9F1h5-iY2XjR_Tk-3qLUOEDhem74l6doriYX\", \"f1LWXbcNQvGBMM_I9aWXdU:APA91bFChMnsDZB5mVhfpfBPFfTKc8rGZjy7qMviQZD0rIrmHfqgQlKabdIJhjtx0iQ9CRCsdhgY2SlJLYw1p3jf1XuQFCvulpmmysS8rAHWajm1Fp6Yd_ywXWNkt-wntdg3yXQqi7AZ\", \"cjJcZI1URzWenc2U_hkKF7:APA91bEftzXjLf7AGY0O9f-7Mg1P8f_kWdTXvRRLd8mSJ9DgnmOSv_03iI0_PrvEK0fpcjiqM6iSnLMGuyEIFnGgR2rQMu_ftgiwYPJdU22Wqp43QRYy8qxoRjKTIzZ-9FB9GccxYxYZ\", \"fnve2gUrRFWKvOZ7El8-2f:APA91bFe-SB0-TuJdyST-YvgQQQe3iYYRmkqZSxPuzJZrWaG6BbnH711N89XCDQAvsPlQCvEpU1LFo7wp2Jy5LApkyhSLfefhKH4RLJZGlrQHzwEcC6jvSoKhOGztPEVQsZQPU9rU5Cy\", \"cx3G5h2KSCWDGuT8NxoWP0:APA91bGNMPxMehquq4Ot-2LxMF2zctLq6nGEhXFL9vDGN-ReMXS5qSbP5g7MMCSJP5xWmsRc4qnBNCxMLVuS4ZxdBhM6SZzA3OV43p5_r0rpopO-LcKAZLrX8ufoN0z2CDZ1HB1YwWi9\"]}','4722','2023-05-25 01:33:30',NULL,NULL,NULL,1,'Office','$2y$10$TDdOaVTePspWS7kKaioqQ.NlH8xmWQmP0veG21BRlPw.foPX14ure',NULL,NULL,'2022-02-09 23:31:33','2023-05-25 01:28:24',NULL),(3,'KOCH000003','SU3_NIGHT','KL-07-BZ-8081','service_unit','su3@gmail.com','2023-03-16 09:07:24','1234567890','2023-03-16 09:07:24',NULL,1,NULL,NULL,NULL,NULL,NULL,'WGWCQe0cVgi95Dqt8vYK7IDYYWZMK8IwjnWN856EtmLnejjsPwDBVjnAAO1o',NULL,NULL,1,NULL,'$2y$10$ltWhZGGi6sjs38bkM0Fyz.n/gdPBME80NTUV9QXGzvthd194cVlD6',NULL,NULL,'2022-02-09 23:59:47','2023-03-16 09:07:24',NULL),(5,'KOCH000005','SU1_DAY','KL-39-K-1243','service_unit','su1day@gmail.com','2022-02-10 00:19:14','4568785412','2022-02-10 00:19:14',NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$4rYP/1ep8n6YXeYziO1g6O8aeiDL3zdTX9xxokOmsRXjMtAq2lMsa',NULL,NULL,'2022-02-10 00:19:14','2022-02-10 00:19:14',NULL),(6,'KOCH000006','Indu','krishna','customer','send2indu@gmail.com','2022-03-15 04:44:42','9496327517','2022-03-15 04:44:42',NULL,1,5,NULL,'{\"address\": {\"area\": \"Infopark Rd, Kusumagiri, Kakkanad, Kerala 682030, India\", \"address\": \"Ncc greenvalley, infopark Road,kusumagiri, opp novotel Hotel,\", \"city_id\": 1, \"approved\": true, \"house_no\": \"a\", \"latitude\": 10.0158151, \"longitude\": 76.3616031, \"house_name\": \"NCC GREEN VALLEY\", \"address_type\": \"flat\"}}',NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$sjYD0un83ZzpFEqNun.9ZejY3lZW8O7CH3thQNSu2eAg.939KD41W',NULL,NULL,'2022-03-15 04:44:42','2022-03-15 11:30:56',NULL),(7,'KOCH000007','Vipin','Vishwanathan','customer','vipinviswanath5@gmail.com','2022-03-15 12:37:09','9900398832','2022-03-15 12:37:09',NULL,1,5,NULL,'{\"address\": {\"area\": \"Ruby Ln, Thammanam, Ernakulam, Kerala 682032, India\", \"address\": \"BCG GOLDEN ORCHID\", \"city_id\": 1, \"approved\": true, \"house_no\": \"2A\", \"latitude\": 9.9876274, \"longitude\": 76.3079131, \"house_name\": \"BCG GOLDEN ORCHID\", \"address_type\": \"flat\"}}',NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$pTl.2Xv9wIK.ufp01M.5P.kJhHERZppdpvE7ZUOhrzS2n12okIemm',NULL,NULL,'2022-03-15 12:37:09','2022-03-15 12:39:58',NULL),(8,'KOCH000008','Sekhar','Chakingal','customer','sekhar_chakingal@yahoo.co.in','2022-03-15 13:05:09','7722075767','2022-03-15 13:05:09',NULL,1,5,NULL,'{\"address\": {\"area\": \"Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"address\": \"Purva Moonreach,Seaport - Airport Rd, near Indian Oil Petrol Pump, Thrikkakara, Kakkanad, Kerala 682030, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"1401\", \"latitude\": 10.0239993, \"longitude\": 76.3391152, \"house_name\": \"Purva Moonreach\", \"address_type\": \"flat\"}}',NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$ScGn4feKVgBFMh.rUhh5nu4Xdc/ck1qs.zrXc8JVqG.3SeqfLCY4S',NULL,NULL,'2022-03-15 13:05:09','2022-03-15 13:06:52',NULL),(9,'KOCH000009','Aji','N P','customer','ajinp4472@gmail.com','2022-03-16 08:09:21','9895084472','2022-03-16 08:09:21',NULL,1,5,NULL,'{\"address\": {\"area\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"address\": \"5/380B , Thiruvankulam P. O, Go Kleen Rd, near Hill Palace, Thrippunithura, Kochi, Kerala 682305, India\", \"city_id\": 1, \"approved\": true, \"house_no\": \"5/380b\", \"latitude\": 9.9490705, \"longitude\": 76.3649586, \"house_name\": \"Gokleen\", \"address_type\": \"house\"}}',NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$nyxdOBlrjRIh/DGqnlOK2u9Cxe1F.08825JoTZ5xI6ESgBXDoTmfO',NULL,NULL,'2022-03-16 08:09:21','2022-03-16 08:10:14',NULL),(10,'KOCH000010','Linda','Daniel','customer','sherlylinda100@gmail.com','2022-03-16 11:32:27','9037449531','2022-03-16 11:32:27',NULL,1,5,NULL,'{\"address\": {\"area\": \"KKRA-110, AKG Rd, Maradu, Kochi, Kerala 682038, India\", \"address\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"city_id\": 1, \"approved\": true, \"house_no\": \"KRRA 110\", \"latitude\": 9.931747, \"longitude\": 76.325495, \"house_name\": \"IPC GOSPEL HOME, MARADU P.O,ERNAKULAM\", \"address_type\": \"house\"}}',NULL,NULL,NULL,NULL,NULL,1,NULL,'$2y$10$7DXfkxV.ZXWrRJcqIoNrBO2kMZSRFYGJQtdBR/GBJ3qqSe1ryuip6',NULL,NULL,'2022-03-16 11:32:27','2022-03-16 12:37:42',NULL),(11,'KOCH000011','sabu','Mathew','customer','sabsmathew@gmail.com','2022-03-26 13:07:59','9048586000','2022-03-26 13:07:59',NULL,1,NULL,NULL,'{\"address\": {\"area\": \"Vennala High School Rd, Chembumukku, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"venalla high school\", \"city_id\": 1, \"approved\": true, \"house_no\": \"Geo Villa 10\", \"latitude\": \"10.0053235\", \"longitude\": \"76.31929000000001\", \"house_name\": \"KITCO\", \"address_type\": \"flat\"}, \"fcm_tokens\": [\"csw8A_D3TVuShiAX9yDEn3:APA91bFN-AIh_VxA_NVx-5iK2mkyPiXBTGPi9tAL6GSnAWPY3kLosk2A6HfRNKcfc7eRU8xb5gBAK7nrEJsRsgy3akMMY9kaLkD3fyRjAes_5bzuQCtc_tIygMNxOTZDsoVSAcQiQG1H\"]}','627514','2022-03-26 13:03:35',NULL,NULL,NULL,1,NULL,'$2y$10$X.oFPSBnENKGflimT9Dz7.lYHfh0LA09H3RbDd.37hnXggEFm4QHm',NULL,NULL,'2022-03-26 12:53:34','2022-03-27 06:06:46',NULL),(12,'KOCH000012','Rinu','R','customer','amsterdam.rinu@gmail.com','2022-03-28 07:14:14','9964651242','2022-03-28 07:14:14',NULL,1,3,NULL,'{\"address\": {\"area\": \"Civil Line Rd, Padivattom, Edappally, Ernakulam, Kerala 682028, India\", \"address\": \"babbab\", \"city_id\": 1, \"approved\": true, \"house_no\": \"123\", \"latitude\": \"10.0083045\", \"longitude\": \"76.3160731\", \"house_name\": \"go\", \"address_type\": \"office\"}, \"fcm_tokens\": [\"cTrNZOXnRymuXgmrBXMIcw:APA91bFqZCShQnEItgEEO-LxA07Eh4WPpmfJc5qdeVXcE-90rPLja9akvYj-WWRYDCj72wPUyBQq7kTrpAE_QzR-nnt48FZBUdktXbrGC3Xpkl9X6D9iYucQx6uKAaON64SyfjC6CJ6j\"]}','9266','2022-03-28 07:23:31',NULL,NULL,NULL,1,NULL,'$2y$10$pkU.fYEfdSSO7HLxA3VdSeINingEF4u5utZVMjScDLJzFn621KxKu',NULL,NULL,'2022-03-28 06:45:00','2022-03-28 07:14:15',NULL),(13,'KOCH000013','rahul',NULL,'customer','iosrahulkharwar@gmail.com',NULL,'7428736720','2023-05-20 05:19:35',NULL,1,NULL,NULL,'{\"address\": {\"area\": \"74Q4+J55, Mughalsarai Railway Settlement, Uttar Pradesh 232101, India\", \"address\": \"lanka\", \"approved\": false, \"house_no\": \"1102\", \"latitude\": \"25.2890199\", \"longitude\": \"83.1054984\", \"house_name\": \"rahul\", \"address_type\": null}, \"fcm_tokens\": [\"cjJcZI1URzWenc2U_hkKF7:APA91bEftzXjLf7AGY0O9f-7Mg1P8f_kWdTXvRRLd8mSJ9DgnmOSv_03iI0_PrvEK0fpcjiqM6iSnLMGuyEIFnGgR2rQMu_ftgiwYPJdU22Wqp43QRYy8qxoRjKTIzZ-9FB9GccxYxYZ\"]}','1321','2023-05-20 05:29:21',NULL,NULL,NULL,0,NULL,'123456789',NULL,NULL,'2023-03-20 12:49:48','2023-05-20 05:19:35',NULL),(14,'KOCH000014','Rahul',NULL,'customer','kharwarrahul248@gmail.com',NULL,'8899776655',NULL,NULL,1,NULL,NULL,'{\"fcm_tokens\": [\"dWY_lzkHTM-TYi7S5OgWH2:APA91bHOq59IQnatsJhn9TF9FxsyRzm6ZBx9-8LO5tmnvzemyd2daJ0dBiVt3BGbAzPD9_KReFWjNvI4d8gXeF_ZCXxxy0hVdy9KUfSs7YScQihg7AXxkCRLoTFWknOtQZnEy4D_bWdk\"]}','315023','2023-05-16 09:13:21',NULL,NULL,NULL,0,NULL,'1234567890',NULL,NULL,'2023-05-16 09:02:11','2023-05-16 09:03:44',NULL),(15,'KOCH000015','Amit',NULL,'customer','amtechnovation@gmail.com',NULL,'9871192371','2023-05-21 10:41:49',NULL,1,NULL,NULL,'{\"address\": {\"area\": \"1600 Amphitheatre Pkwy, Mountain View, CA 94043, USA\", \"address\": \"Super Mart\", \"approved\": false, \"house_no\": \"B-140\", \"latitude\": \"37.4220586\", \"longitude\": \"-122.084087\", \"house_name\": \"Villa\", \"address_type\": null}, \"fcm_tokens\": [\"fnve2gUrRFWKvOZ7El8-2f:APA91bFe-SB0-TuJdyST-YvgQQQe3iYYRmkqZSxPuzJZrWaG6BbnH711N89XCDQAvsPlQCvEpU1LFo7wp2Jy5LApkyhSLfefhKH4RLJZGlrQHzwEcC6jvSoKhOGztPEVQsZQPU9rU5Cy\"]}','9038','2023-05-21 10:51:37',NULL,NULL,NULL,1,NULL,'12345678',NULL,NULL,'2023-05-21 10:07:54','2023-05-21 10:41:49',NULL),(17,'KOCH000017','Nandan',NULL,'customer','nandan@gmail.com',NULL,'9999870382','2023-05-25 06:37:44',NULL,1,NULL,NULL,'{\"address\": {\"area\": \"324, Phase V, Udyog Vihar, Gurugram, Haryana 122016, India\", \"address\": \"Jharsa\", \"approved\": false, \"house_no\": \"29\", \"latitude\": \"28.4997047\", \"longitude\": \"77.0825505\", \"house_name\": \"Bhagwati appartment\", \"address_type\": null}}','220968','2023-05-25 06:47:38',NULL,NULL,NULL,1,NULL,'12345678',NULL,NULL,'2023-05-25 06:37:37','2023-05-25 06:38:19',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wallets`
--

DROP TABLE IF EXISTS `wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `wallets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `holder_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `holder_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `balance` decimal(64,0) NOT NULL DEFAULT '0',
  `decimal_places` smallint(5) unsigned NOT NULL DEFAULT '2',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wallets_holder_type_holder_id_slug_unique` (`holder_type`,`holder_id`,`slug`),
  UNIQUE KEY `wallets_uuid_unique` (`uuid`),
  KEY `wallets_holder_type_holder_id_index` (`holder_type`,`holder_id`),
  KEY `wallets_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wallets`
--

LOCK TABLES `wallets` WRITE;
/*!40000 ALTER TABLE `wallets` DISABLE KEYS */;
INSERT INTO `wallets` VALUES (1,'App\\Models\\Customer',2,'Default Wallet','default','2b4bd4f6-6649-437c-8483-d86172d753ac',NULL,'[]',0,2,'2022-03-15 03:48:35','2022-03-15 03:48:35'),(2,'App\\Models\\Customer',6,'Default Wallet','default','dd404fec-0844-4c79-9181-645437fd714b',NULL,'[]',0,2,'2022-03-15 04:44:42','2022-03-15 04:44:42'),(3,'App\\Models\\Customer',7,'Default Wallet','default','45c60cb1-6fe6-4f7f-9d66-7339e97774a1',NULL,'[]',0,2,'2022-03-15 12:37:10','2022-03-15 12:37:10'),(4,'App\\Models\\Customer',8,'Default Wallet','default','7959578e-b1b8-42f1-bd2e-b8b4450459af',NULL,'[]',0,2,'2022-03-15 13:05:09','2022-03-15 13:05:09'),(5,'App\\Models\\Customer',9,'Default Wallet','default','0ba3982d-c132-4cd2-b951-4bae15304e4f',NULL,'[]',0,2,'2022-03-16 08:09:22','2022-03-16 08:09:22'),(6,'App\\Models\\Customer',10,'Default Wallet','default','9d5b502f-5f4d-437b-ace6-dc4c4a3ac9b8',NULL,'[]',0,2,'2022-03-16 11:32:28','2022-03-16 11:32:28'),(7,'App\\Models\\Customer',11,'Default Wallet','default','4c1ca473-1aef-4282-8228-589a6873a4b0',NULL,'[]',0,2,'2022-03-26 12:57:02','2022-03-26 12:57:02'),(8,'App\\Models\\Customer',12,'Default Wallet','default','cf62919c-049c-4370-9174-f3eafa286536',NULL,'[]',0,2,'2022-03-28 07:06:58','2022-03-28 07:06:58'),(9,'App\\Models\\Customer',13,'Default Wallet','default','ea048897-fcb9-45c9-a76f-97fa55e17f13',NULL,'[]',0,2,'2023-03-20 12:50:46','2023-03-20 12:50:46'),(10,'App\\Models\\Customer',14,'Default Wallet','default','1f05a618-5a67-4236-a89d-ab010ac60b84',NULL,'[]',0,2,'2023-05-16 12:30:13','2023-05-16 12:30:13'),(11,'App\\Models\\Customer',15,'Default Wallet','default','07817ab7-952b-4462-8fc5-322d861cb2cc',NULL,'[]',0,2,'2023-05-21 10:11:13','2023-05-21 10:11:13'),(12,'App\\Models\\Customer',16,'Default Wallet','default','5f235e00-ab63-4f4c-a2e4-b65fbe5c6804',NULL,'[]',0,2,'2023-05-25 01:38:48','2023-05-25 01:38:48'),(13,'App\\Models\\Customer',17,'Default Wallet','default','3d31759a-f9cd-4a6c-ae7b-930b2f44dc68',NULL,'[]',0,2,'2023-05-25 06:38:34','2023-05-25 06:38:34');
/*!40000 ALTER TABLE `wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `washes`
--

DROP TABLE IF EXISTS `washes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `washes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `wash_qty` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `washes`
--

LOCK TABLES `washes` WRITE;
/*!40000 ALTER TABLE `washes` DISABLE KEYS */;
INSERT INTO `washes` VALUES (1,1),(2,2),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),(11,11),(12,12),(13,13),(14,14);
/*!40000 ALTER TABLE `washes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-05-26 13:57:02
