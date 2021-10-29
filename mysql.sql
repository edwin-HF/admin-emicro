-- MySQL dump 10.13  Distrib 8.0.27, for Linux (x86_64)
--
-- Host: localhost    Database: admin
-- ------------------------------------------------------
-- Server version	8.0.27-0ubuntu0.20.04.1

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
-- Table structure for table `admin_menus`
--

DROP TABLE IF EXISTS `admin_menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_menus` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `type` tinyint DEFAULT NULL,
  `pid` int unsigned DEFAULT NULL,
  `sort` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_menus`
--

LOCK TABLES `admin_menus` WRITE;
/*!40000 ALTER TABLE `admin_menus` DISABLE KEYS */;
INSERT INTO `admin_menus` VALUES (1,'首页','fa fa-home','/index','home',1,0,1,'2021-10-27 07:44:18','2021-10-27 07:44:18'),(2,'系统管理','fa fa-cogs','','system',1,0,2,'2021-10-27 07:48:26','2021-10-27 07:48:26'),(3,'菜单管理','fa fa-bars','/system/menus','system:menus',1,2,1,'2021-10-27 07:48:52','2021-10-27 07:48:52'),(4,'角色管理','fa fa-users','/system/roles','system:roles',1,2,2,'2021-10-27 07:49:20','2021-10-27 07:49:20'),(5,'管理员','fa fa-user','/system/users','system:users',1,2,3,'2021-10-27 07:49:48','2021-10-27 07:49:48'),(6,'权限管理','fa fa-sitemap','/system/permissions','system:permissions',1,2,4,'2021-10-27 07:50:15','2021-10-27 07:50:15');
/*!40000 ALTER TABLE `admin_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_permissions`
--

DROP TABLE IF EXISTS `admin_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_permissions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `pid` int unsigned NOT NULL DEFAULT '0',
  `sort` int unsigned NOT NULL DEFAULT '0',
  `type` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_permissions`
--

LOCK TABLES `admin_permissions` WRITE;
/*!40000 ALTER TABLE `admin_permissions` DISABLE KEYS */;
INSERT INTO `admin_permissions` VALUES (1,'首页','home',0,1,1,'2021-10-27 07:51:01','2021-10-27 07:51:01'),(2,'系统管理','system',0,2,1,'2021-10-27 07:51:18','2021-10-27 07:51:18'),(3,'菜单管理','system:menus',2,1,1,'2021-10-27 07:51:35','2021-10-27 07:51:35'),(4,'角色管理','system:roles',2,2,1,'2021-10-27 07:51:52','2021-10-27 07:51:52'),(5,'管理员','system:users',2,3,1,'2021-10-27 07:52:10','2021-10-27 07:52:10'),(6,'权限管理','system:permissions',2,4,1,'2021-10-27 07:52:32','2021-10-27 07:52:32'),(7,'新增','system:menus:add',3,1,2,'2021-10-27 07:53:00','2021-10-27 07:53:00'),(8,'编辑','system:menus:edit',3,2,2,'2021-10-27 07:53:24','2021-10-27 07:53:24'),(9,'删除','system:menus:delete',3,3,2,'2021-10-27 07:53:47','2021-10-27 07:53:47'),(10,'新增','system:roles:add',4,1,2,'2021-10-27 07:54:24','2021-10-27 07:54:24'),(11,'角色权限','system:roles:permission',4,2,2,'2021-10-27 07:54:45','2021-10-27 07:54:45'),(12,'编辑','system:roles:edit',4,3,2,'2021-10-27 07:55:08','2021-10-27 07:55:08'),(13,'删除','system:roles:delete',4,4,2,'2021-10-27 07:55:31','2021-10-27 07:55:31'),(14,'新增','system:users:add',5,1,2,'2021-10-27 07:55:56','2021-10-27 07:55:56'),(15,'编辑','system:users:edit',5,2,2,'2021-10-27 07:56:18','2021-10-27 07:56:18'),(16,'删除','system:users:delete',5,3,2,'2021-10-27 07:56:40','2021-10-27 07:56:40'),(17,'新增','system:permissions:add',6,1,2,'2021-10-27 07:57:07','2021-10-27 07:57:07'),(18,'编辑','system:permissions:edit',6,2,2,'2021-10-27 07:57:28','2021-10-27 07:57:28'),(19,'删除','system:permissions:delete',6,3,2,'2021-10-27 07:57:51','2021-10-27 07:57:51');
/*!40000 ALTER TABLE `admin_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_role_permissions`
--

DROP TABLE IF EXISTS `admin_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_role_permissions` (
  `role_id` int NOT NULL,
  `permission_id` int NOT NULL,
  PRIMARY KEY (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_role_permissions`
--

LOCK TABLES `admin_role_permissions` WRITE;
/*!40000 ALTER TABLE `admin_role_permissions` DISABLE KEYS */;
INSERT INTO `admin_role_permissions` VALUES (2,1),(2,2),(2,4),(2,5),(2,10),(2,11),(2,12),(2,14),(2,15),(2,16);
/*!40000 ALTER TABLE `admin_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_roles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_roles`
--

LOCK TABLES `admin_roles` WRITE;
/*!40000 ALTER TABLE `admin_roles` DISABLE KEYS */;
INSERT INTO `admin_roles` VALUES (0,'超级管理员','2021-10-27 15:39:23','2021-10-27 15:39:23'),(2,'系统管理员','2021-10-27 07:58:11','2021-10-27 07:58:11');
/*!40000 ALTER TABLE `admin_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_user_roles`
--

DROP TABLE IF EXISTS `admin_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_user_roles` (
  `user_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_user_roles`
--

LOCK TABLES `admin_user_roles` WRITE;
/*!40000 ALTER TABLE `admin_user_roles` DISABLE KEYS */;
INSERT INTO `admin_user_roles` VALUES (1,0),(2,2);
/*!40000 ALTER TABLE `admin_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_users`
--

DROP TABLE IF EXISTS `admin_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(45) DEFAULT NULL,
  `status` tinyint DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_users`
--

LOCK TABLES `admin_users` WRITE;
/*!40000 ALTER TABLE `admin_users` DISABLE KEYS */;
INSERT INTO `admin_users` VALUES (1,'admin','921a976c1964c52ff246b7949345e66f','3GZ1',1,'2021-10-22 03:28:31','2021-10-22 03:28:31'),(2,'cs','2ad78a214aae76363396dfc19ee473c7','X2hP',1,'2021-10-27 07:59:30','2021-10-27 07:59:30');
/*!40000 ALTER TABLE `admin_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-10-27 16:18:28
