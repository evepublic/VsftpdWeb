-- MySQL dump 10.13  Distrib 5.7.23, for Linux (x86_64)
--
-- Host: localhost    Database: vsftpd
-- ------------------------------------------------------
-- Server version	5.7.23-0ubuntu0.18.04.1

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
-- Table structure for table `accounts`
--

DROP TABLE IF EXISTS `accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `perm` varchar(50) NOT NULL DEFAULT '0',
  `path` varchar(50) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts`
--

LOCK TABLES `accounts` WRITE;
/*!40000 ALTER TABLE `accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `accounts_security_test`
--

DROP TABLE IF EXISTS `accounts_security_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `accounts_security_test` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `perm` varchar(50) NOT NULL DEFAULT '0',
  `path` varchar(50) NOT NULL DEFAULT 'none',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=10014 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `accounts_security_test`
--

LOCK TABLES `accounts_security_test` WRITE;
/*!40000 ALTER TABLE `accounts_security_test` DISABLE KEYS */;
INSERT INTO `accounts_security_test` VALUES (10001,'test_plain','test','w','none'),(10002,'test_crypt_std_des','salSp1wOPp6fk','w','none'),(10003,'test_crypt_ext_des','_c44.saltPp2gRjSZwDw','w','none'),(10004,'test_crypt_md5','$1$salt/sal$gR0DUSRScivG0mCGAaTbh.','w','none'),(10005,'test_crypt_blowfish','$2y$08$hcpQYiTtMkAMEtAySLYQPeRnmJbWfa0N1mtvsgVF931MPT3LFpUXi','w','none'),(10006,'test_crypt_sha256','$5$rounds=25000$salt/salt/salt/s$4VlclyKgfARH88mcRTfskiZpi5sF0CAajRJNlAkh0A/','w','none'),(10007,'test_crypt_sha512','$6$rounds=25000$salt/salt/salt/s$ihxN/vMwKCKtcaFT0V45TCOLQmoD0ASV3cIT8EEd3ksYiLJ5Xv3rKbJK32O0sMCuQRLJbNfOiUsccLoMeX.3P/','w','none'),(10008,'test_crypt_fallback','salSp1wOPp6fk','w','none'),(10009,'test_mysql','*94BDCEBE19083CE2A1F959FD02F964C7AF4CFC29','w','none'),(10010,'test_md5','098f6bcd4621d373cade4e832627b4f6','w','none'),(10011,'test_sha1','a94a8fe5ccb19ba61c4c0873d391e987982fbbd3','w','none'),(10012,'test_joomla15','65f7ebc94befb05a5c1fb3f008014271:salt/salt/salt/salt/salt/salt/sa','w','none'),(10013,'test_drupal7','$S$D9iP2d5E3rRuh8Ebap.9rdXMzbVENpUZC0wQlgt8pocSAfoNO/Kn','w','none');
/*!40000 ALTER TABLE `accounts_security_test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail`
--

DROP TABLE IF EXISTS `mail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `users_id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail`
--

LOCK TABLES `mail` WRITE;
/*!40000 ALTER TABLE `mail` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `security`
--

DROP TABLE IF EXISTS `security`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `security` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subcode` varchar(50) DEFAULT NULL,
  `param` varchar(50) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code__subcode` (`code`,`subcode`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `security`
--

LOCK TABLES `security` WRITE;
/*!40000 ALTER TABLE `security` DISABLE KEYS */;
INSERT INTO `security` VALUES (1,'Plain text','PLAIN',NULL,NULL,NULL,NULL),(2,'Crypt Standard DES','CRYPT','STD_DES',NULL,NULL,NULL),(3,'Crypt Extended DES','CRYPT','EXT_DES','25000','iteration count: range 1 - 16,777,215',NULL),(4,'Crypt MD5','CRYPT','MD5',NULL,NULL,NULL),(5,'Crypt Blowfish','CRYPT','BLOWFISH','08','iteration count: range 04-31',NULL),(6,'Crypt SHA256','CRYPT','SHA256','25000','rounds: default=5000, range 1000 - 999,999,999',NULL),(7,'Crypt SHA512','CRYPT','SHA512','25000','rounds: default=5000, range 1000 - 999,999,999',1),(8,'Crypt Default','CRYPT','FALLBACK',NULL,NULL,NULL),(9,'MYSQL','MYSQL',NULL,NULL,NULL,NULL),(10,'MD5','MD5',NULL,NULL,NULL,NULL),(11,'SHA1','SHA1',NULL,NULL,NULL,NULL),(12,'Joomla 1.5','JOOMLA15',NULL,NULL,NULL,NULL),(13,'Drupal 7','DRUPAL7',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `security` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `defval` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','Demo',''),(2,'user_path','/mnt/ftpusers/',''),(3,'disk1','/mnt/ftpusers/','disk 1'),(4,'disk2','/mnt/ftpusers/','disk 2'),(5,'disk3','/','root'),(6,'log_path','/var/log/xferlog',''),(7,'mail_server','mail.mail.com',''),(8,'mail_port','25',''),(9,'mail_user','username@mail.com',''),(10,'mail_password','mailpassword',''),(11,'mail_from','FTP Report','');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$T6qqCVyu9/eV5EpxwkjKpe9DydXdiydMQTM5awThSAQ6Rcve4m0R2');
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
