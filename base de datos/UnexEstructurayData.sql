-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: unex
-- ------------------------------------------------------
-- Server version	8.0.40

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
-- Table structure for table `archivos_repositorio`
--

DROP TABLE IF EXISTS `archivos_repositorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos_repositorio` (
  `id` int NOT NULL AUTO_INCREMENT,
  `repositorio_id` int DEFAULT NULL,
  `ruta` varchar(255) DEFAULT NULL,
  `nombre_original` varchar(255) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repositorio_id` (`repositorio_id`),
  CONSTRAINT `archivos_repositorio_ibfk_1` FOREIGN KEY (`repositorio_id`) REFERENCES `repositorios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivos_repositorio`
--

LOCK TABLES `archivos_repositorio` WRITE;
/*!40000 ALTER TABLE `archivos_repositorio` DISABLE KEYS */;
INSERT INTO `archivos_repositorio` VALUES (20,10,'uploads/repositorio/1749129812_Fase1_XP_UNEX_Completa.docx','Fase1_XP_UNEX_Completa.docx','docx'),(21,10,'uploads/repositorio/1749129812_Planificacion_SCRUM_Plataforma_UNEX.docx','Planificacion_SCRUM_Plataforma_UNEX.docx','docx'),(22,10,'uploads/repositorio/1749129812_proyecto y tesis de grado  (1).docx','proyecto y tesis de grado  (1).docx','docx'),(23,10,'uploads/repositorio/1749130761_EXPOSICION PROYECTO eleanna.docx','EXPOSICION PROYECTO eleanna.docx','docx');
/*!40000 ALTER TABLE `archivos_repositorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chats`
--

DROP TABLE IF EXISTS `chats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario1_id` int NOT NULL,
  `usuario2_id` int NOT NULL,
  `usuario1_oculto` tinyint(1) DEFAULT '0',
  `usuario2_oculto` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario1_id` (`usuario1_id`,`usuario2_id`),
  KEY `usuario2_id` (`usuario2_id`),
  CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`usuario1_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`usuario2_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chats`
--

LOCK TABLES `chats` WRITE;
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
INSERT INTO `chats` VALUES (1,1,2,0,0),(11,1,4,0,0),(12,1,10,1,0),(13,2,4,0,0),(14,1,11,1,0),(25,1,12,0,0),(27,14,1,0,1),(29,1,1,0,0),(30,1,15,0,0);
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `publicacion_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `comentario` text NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `publicacion_id` (`publicacion_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios`
--

LOCK TABLES `comentarios` WRITE;
/*!40000 ALTER TABLE `comentarios` DISABLE KEYS */;
INSERT INTO `comentarios` VALUES (50,72,1,'Vayalo mi lideeeelll','2025-06-08 21:17:54');
/*!40000 ALTER TABLE `comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios_foro`
--

DROP TABLE IF EXISTS `comentarios_foro`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comentarios_foro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `foro_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `comentario` text NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `foro_id` (`foro_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `comentarios_foro_ibfk_1` FOREIGN KEY (`foro_id`) REFERENCES `foros` (`id`),
  CONSTRAINT `comentarios_foro_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios_foro`
--

LOCK TABLES `comentarios_foro` WRITE;
/*!40000 ALTER TABLE `comentarios_foro` DISABLE KEYS */;
INSERT INTO `comentarios_foro` VALUES (13,21,1,'que leguaje es mejor para crear sitios web, php o python?','2025-06-02 13:13:48',NULL),(14,21,1,'a mi en lo personal me gusta mas php','2025-06-02 13:14:07',13),(15,21,1,'a mi python','2025-06-22 01:53:08',13);
/*!40000 ALTER TABLE `comentarios_foro` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compartidos`
--

DROP TABLE IF EXISTS `compartidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compartidos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `publicacion_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `publicacion_original_id` (`publicacion_id`),
  CONSTRAINT `compartidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `compartidos_ibfk_2` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compartidos`
--

LOCK TABLES `compartidos` WRITE;
/*!40000 ALTER TABLE `compartidos` DISABLE KEYS */;
/*!40000 ALTER TABLE `compartidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `eventos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `creador_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (11,'Apertura del comedor','Regresa al comedor restaurado para el servicio de los estudiantes, docentes y personal de la UNELLEZ VPDS','2025-07-10','11:00:00',8.62292871,-70.24623351,'2025-07-02 23:32:55',17);
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foro_seguidores`
--

DROP TABLE IF EXISTS `foro_seguidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foro_seguidores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `foro_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `foro_id` (`foro_id`,`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foro_seguidores`
--

LOCK TABLES `foro_seguidores` WRITE;
/*!40000 ALTER TABLE `foro_seguidores` DISABLE KEYS */;
INSERT INTO `foro_seguidores` VALUES (7,21,17,'2025-06-07 18:57:24'),(9,21,1,'2025-07-03 09:32:44');
/*!40000 ALTER TABLE `foro_seguidores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `foros`
--

DROP TABLE IF EXISTS `foros`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `foros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text,
  `tipo` enum('general','tema','estudio') DEFAULT 'general',
  `creado_por` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` text,
  PRIMARY KEY (`id`),
  KEY `creado_por` (`creado_por`),
  CONSTRAINT `foros_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `foros`
--

LOCK TABLES `foros` WRITE;
/*!40000 ALTER TABLE `foros` DISABLE KEYS */;
INSERT INTO `foros` VALUES (21,'Programacion','foro creado con la finalidad de mejorar como programadores, con consejos, tips y rutas de aprendizajes, para los mas nuevos y tambien los mas expertos','tema',1,'2025-05-22 02:27:01','uploads/1747880820_programacionImagen.jpg');
/*!40000 ALTER TABLE `foros` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupo_usuarios`
--

DROP TABLE IF EXISTS `grupo_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupo_usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `grupo_id` (`grupo_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `grupo_usuarios_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `grupo_usuarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupo_usuarios`
--

LOCK TABLES `grupo_usuarios` WRITE;
/*!40000 ALTER TABLE `grupo_usuarios` DISABLE KEYS */;
INSERT INTO `grupo_usuarios` VALUES (27,9,2),(28,9,4),(29,9,10),(30,9,1);
/*!40000 ALTER TABLE `grupo_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `grupos`
--

DROP TABLE IF EXISTS `grupos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `grupos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `creado_por` int NOT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagen` text,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `creado_por` (`creado_por`),
  CONSTRAINT `grupos_ibfk_1` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `grupos`
--

LOCK TABLES `grupos` WRITE;
/*!40000 ALTER TABLE `grupos` DISABLE KEYS */;
INSERT INTO `grupos` VALUES (9,'desarrollo',2,'2025-04-05 17:28:17','uploads/1743874097_pc.png','2025-04-25 17:34:41');
/*!40000 ALTER TABLE `grupos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invitaciones_grupo`
--

DROP TABLE IF EXISTS `invitaciones_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invitaciones_grupo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_id` int NOT NULL,
  `invitado_id` int NOT NULL,
  `invitado_por` int NOT NULL,
  `estado` enum('pendiente','aceptada','rechazada') DEFAULT 'pendiente',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invitaciones_grupo`
--

LOCK TABLES `invitaciones_grupo` WRITE;
/*!40000 ALTER TABLE `invitaciones_grupo` DISABLE KEYS */;
INSERT INTO `invitaciones_grupo` VALUES (1,9,10,1,'aceptada','2025-04-26 01:02:39'),(2,9,1,2,'aceptada','2025-06-23 00:36:32');
/*!40000 ALTER TABLE `invitaciones_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `likes`
--

DROP TABLE IF EXISTS `likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `likes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `publicacion_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `publicacion_id` (`publicacion_id`,`usuario_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`publicacion_id`) REFERENCES `publicaciones` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `likes`
--

LOCK TABLES `likes` WRITE;
/*!40000 ALTER TABLE `likes` DISABLE KEYS */;
INSERT INTO `likes` VALUES (182,71,4,'2025-06-08 21:02:36'),(183,70,4,'2025-06-08 21:02:38'),(184,71,11,'2025-06-08 21:07:08'),(185,78,1,'2025-06-08 21:17:33'),(186,75,1,'2025-06-08 21:17:36'),(187,73,1,'2025-06-08 21:17:43'),(188,72,1,'2025-06-08 21:17:46'),(191,74,1,'2025-06-23 12:46:59');
/*!40000 ALTER TABLE `likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes`
--

DROP TABLE IF EXISTS `mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `emisor_id` int NOT NULL,
  `receptor_id` int NOT NULL,
  `mensaje` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `visto` tinyint(1) DEFAULT '0',
  `chat_id` int NOT NULL,
  `archivo` varchar(255) DEFAULT NULL,
  `grupo_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emisor_id` (`emisor_id`),
  KEY `receptor_id` (`receptor_id`),
  KEY `grupo_id` (`grupo_id`),
  CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`receptor_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `mensajes_ibfk_3` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes`
--

LOCK TABLES `mensajes` WRITE;
/*!40000 ALTER TABLE `mensajes` DISABLE KEYS */;
INSERT INTO `mensajes` VALUES (9,1,2,'hoooooooooooooooooooooolaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa','2025-03-11 19:44:57',1,1,NULL,NULL),(10,2,1,'hola gabriel','2025-03-11 20:27:04',1,1,NULL,NULL),(11,2,1,'como estas?','2025-03-12 16:53:20',1,1,NULL,NULL),(12,1,2,'yo bien y tu','2025-03-12 16:53:44',1,1,NULL,NULL),(30,1,4,'hola','2025-03-18 01:11:33',1,11,NULL,NULL),(35,1,2,'e','2025-03-19 02:37:10',1,1,NULL,NULL),(36,1,2,'responde','2025-03-19 02:37:28',1,1,NULL,NULL),(37,2,1,'que','2025-03-19 02:37:49',1,1,NULL,NULL),(38,2,1,'a','2025-03-19 02:46:10',1,1,NULL,NULL),(39,2,1,'e','2025-03-19 03:04:49',1,1,NULL,NULL),(40,1,2,'a','2025-03-19 03:05:14',1,1,'uploads/1714841630633.jpg',NULL),(74,2,1,'buena foto crack','2025-03-26 02:36:32',1,1,NULL,NULL),(75,1,2,'gracias','2025-03-26 02:36:39',1,1,NULL,NULL),(77,1,2,'ahora?','2025-03-26 02:43:16',1,1,NULL,NULL),(78,2,1,'si?','2025-03-26 02:43:38',1,1,NULL,NULL),(79,1,2,'','2025-03-26 02:45:48',1,1,'uploads/1742957148_67e36a5c0a81a.pdf',NULL),(80,1,2,'','2025-03-26 03:52:08',1,1,'uploads/1742961128_67e379e8c3565.doc',NULL),(90,1,2,'','2025-03-26 06:01:52',1,1,'uploads/1742968912_67e3985039825.jpg',NULL),(91,1,4,'','2025-03-26 06:19:08',1,11,'uploads/1742969948_67e39c5c86e3f.png',NULL),(92,4,1,'hola','2025-03-26 06:20:15',1,11,NULL,NULL),(93,4,1,'que lo que','2025-03-26 06:23:04',1,11,NULL,NULL),(94,1,4,'mano','2025-03-26 06:43:52',1,11,NULL,NULL),(95,1,4,'si?','2025-03-26 06:44:43',1,11,NULL,NULL),(96,4,1,'no','2025-03-26 06:44:55',1,11,NULL,NULL),(97,1,4,'hola bb','2025-03-26 06:45:05',1,11,NULL,NULL),(111,1,12,'hola corazon','2025-04-10 13:36:27',0,25,NULL,NULL),(115,1,11,'hello','2025-04-18 15:53:04',1,14,NULL,NULL),(116,1,11,'epale compai','2025-04-18 15:55:38',1,14,NULL,NULL),(117,1,11,'todo bien','2025-04-18 15:55:45',1,14,NULL,NULL),(118,1,11,'porque no se elimina todo','2025-04-18 15:56:56',1,14,NULL,NULL),(119,1,11,'a','2025-04-18 16:21:48',1,14,NULL,NULL),(120,1,11,'eo','2025-04-18 16:26:22',1,14,NULL,NULL),(121,1,11,'aaaa','2025-04-18 16:26:43',1,14,NULL,NULL),(122,1,11,'aaaaaaaaaaaaaaaaa','2025-04-18 16:28:52',1,14,NULL,NULL),(124,1,4,'epale mi loco todo bien cabeza e epija','2025-04-19 01:13:22',0,11,NULL,NULL),(126,2,1,'Hola, estoy interesado en: \"PC 256 gb almacenamiento\"','2025-05-27 18:17:45',1,1,NULL,NULL),(128,1,2,'te gusta?','2025-06-29 00:28:01',1,1,NULL,NULL),(129,1,12,'hola','2025-06-30 20:43:08',0,25,NULL,NULL),(130,1,15,'mi lidel','2025-06-30 20:43:17',0,30,NULL,NULL),(131,1,10,'epale','2025-06-30 20:43:29',0,12,NULL,NULL);
/*!40000 ALTER TABLE `mensajes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mensajes_grupo`
--

DROP TABLE IF EXISTS `mensajes_grupo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mensajes_grupo` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_id` int NOT NULL,
  `emisor_id` int NOT NULL,
  `mensaje` text,
  `archivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `grupo_id` (`grupo_id`),
  KEY `emisor_id` (`emisor_id`),
  CONSTRAINT `mensajes_grupo_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupos` (`id`),
  CONSTRAINT `mensajes_grupo_ibfk_2` FOREIGN KEY (`emisor_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mensajes_grupo`
--

LOCK TABLES `mensajes_grupo` WRITE;
/*!40000 ALTER TABLE `mensajes_grupo` DISABLE KEYS */;
INSERT INTO `mensajes_grupo` VALUES (6,9,1,'hola',NULL,'2025-04-07 13:39:06'),(7,9,4,'todo bien?',NULL,'2025-04-07 13:50:49'),(10,9,11,'epale',NULL,'2025-04-10 13:34:17'),(11,9,1,'','uploads/1751302307_Resumen Gerencia y mercadeo.pdf','2025-06-30 16:51:47');
/*!40000 ALTER TABLE `mensajes_grupo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notificaciones`
--

DROP TABLE IF EXISTS `notificaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notificaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `mensaje` text NOT NULL,
  `leida` tinyint(1) DEFAULT '0',
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notificaciones`
--

LOCK TABLES `notificaciones` WRITE;
/*!40000 ALTER TABLE `notificaciones` DISABLE KEYS */;
INSERT INTO `notificaciones` VALUES (5,1,'like','A AlejandroRivas le gustó tu publicación.',1,'2025-02-27 14:59:22'),(6,1,'comentario','AlejandroRivas comentó tu publicación.',1,'2025-02-27 14:59:31'),(7,1,'like','A MilitzaOviedo le gustó tu publicación.',1,'2025-03-19 02:51:44'),(8,2,'like','A GabrielRivas le gustó tu publicación.',1,'2025-03-19 03:07:45'),(9,1,'comentario','MilitzaOviedo comentó tu publicación.',1,'2025-03-19 03:08:42'),(10,1,'like','A MilitzaOviedo le gustó tu publicación.',1,'2025-03-19 03:10:35'),(12,2,'nuevo_creador','Ahora eres el nuevo creador del grupo:desarrollo',1,'2025-04-24 14:12:35'),(13,4,'comentario','GabrielRivas comentó tu publicación.',1,'2025-04-30 00:59:10'),(14,4,'comentario','GabrielRivas comentó tu publicación.',1,'2025-05-20 18:48:04'),(15,1,'like','A AlejandroRivas le gustó tu publicación.',1,'2025-06-09 01:02:36'),(16,1,'like','A AlejandroRivas le gustó tu publicación.',1,'2025-06-09 01:02:38'),(17,1,'like','A KarlaVilla le gustó tu publicación.',1,'2025-06-09 01:07:08'),(18,11,'like','A GabrielRivas le gustó tu publicación.',0,'2025-06-09 01:17:37'),(19,11,'like','A GabrielRivas le gustó tu publicación.',0,'2025-06-09 01:17:44'),(20,15,'like','A GabrielRivas le gustó tu publicación.',0,'2025-06-09 01:17:47'),(21,15,'comentario','GabrielRivas comentó tu publicación.',0,'2025-06-09 01:17:54'),(22,11,'like','A GabrielRivas le gustó tu publicación.',0,'2025-06-22 00:41:00'),(23,17,'compartir','GabrielRivas compartió tu publicación.',1,'2025-06-22 00:41:18'),(24,12,'like','A GabrielRivas le gustó tu publicación.',1,'2025-06-22 01:40:27'),(25,12,'like','A GabrielRivas le gustó tu publicación.',0,'2025-06-23 16:46:59');
/*!40000 ALTER TABLE `notificaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `productos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `precio` decimal(10,2) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `productos`
--

LOCK TABLES `productos` WRITE;
/*!40000 ALTER TABLE `productos` DISABLE KEYS */;
INSERT INTO `productos` VALUES (3,1,'Pendrives','Disponibles pendrives de 32/64/128 gb ',10.00,'uploads/1748201014_PENDRIVE-64GB-USB-2.0-600x600.jpg','2025-05-25 15:23:34'),(4,1,'Sbr 2024','Se vende SBR por motivos personales, NEGOCIABLE',600.00,'uploads/1748285937_SBR-NUEVA-rorssa-1200x800.png','2025-05-26 14:58:57');
/*!40000 ALTER TABLE `productos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publicaciones`
--

DROP TABLE IF EXISTS `publicaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publicaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `contenido` text,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `imagensubida` varchar(255) DEFAULT NULL,
  `compartidos` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `publicaciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publicaciones`
--

LOCK TABLES `publicaciones` WRITE;
/*!40000 ALTER TABLE `publicaciones` DISABLE KEYS */;
INSERT INTO `publicaciones` VALUES (54,11,'Mi lugar de paz','2025-06-08 18:09:32','uploads/karla4.jpg',0),(55,15,'desde USA','2025-06-08 18:19:31','uploads/olivar1.jpg',0),(56,1,'yo','2025-06-08 18:32:09','uploads/gabriel3.jpg',0),(57,12,'','2025-06-08 18:48:19','uploads/elideth3.jpg',0),(59,17,'? ¡Atención, futuros profesionales que transformarán el país! ?\r\n\r\n✨ ¡Tu camino hacia el éxito comienza en la Unellez! ✨\r\n\r\n??‍♀️ Descubre nuestra increíble Oferta Académica De Pregrado y forma parte de \"La Universidad que Siembra\" ?. ','2025-06-08 19:59:53','uploads/unellez2.png',0),(60,10,'que no falte el cafe','2025-06-09 00:34:59','uploads/franchesco1.jpg',0),(61,15,'','2025-06-09 00:36:23','uploads/olivar2.jpg',0),(62,11,'','2025-06-09 00:37:45','uploads/karla1.jpg',0),(63,1,'','2025-06-09 00:39:07','uploads/gabriel2.jpg',0),(64,1,'','2025-06-09 00:39:15','uploads/Gabriel1.jpg',0),(65,12,'','2025-06-09 00:40:30','uploads/elideth2.jpg',0),(66,17,'En un acto que refleja el compromiso de la comunidad unellista con el ambiente, se llevó a cabo la Jornada de Reforestación Unellez 2025, una iniciativa que ha logrado reunir a una amplia participación de diferentes sectores de la sociedad','2025-06-09 00:42:12','uploads/unellez1.png',0),(67,2,'','2025-06-09 00:44:39','uploads/militza1.jpg',0),(68,17,'? ¡Atención, futuros Magísteres y Doctores!?\r\n\r\n? ¿Sueñas con alcanzar nuevas cimas académicas? ¡Tu oportunidad ha llegado! ? La Universidad Nacional Experimental de los Llanos Occidentales \"Ezequiel Zamora\" (Unellez) ','2025-06-09 00:52:15','uploads/unellez3.png',0),(69,17,'Celebramos nuestra primer expoferia!!!!!','2025-06-09 00:54:14','uploads/unellez5.jpg',1),(70,1,'Antes de defender','2025-06-09 00:55:09','uploads/gabriel5.jpg',0),(71,1,'Depues de defender','2025-06-09 00:55:20','uploads/gabriel6.jpg',0),(72,15,'','2025-06-09 01:03:55','uploads/olivar3.jpg',0),(73,11,'','2025-06-09 01:04:31','uploads/karla2.jpg',0),(74,12,'','2025-06-09 01:08:56','uploads/elideth1.jpg',0),(75,11,'','2025-06-09 01:09:57','uploads/karla5.jpg',0),(76,11,'quierooooo','2025-06-09 01:10:13','uploads/karla3.jpg',0),(78,1,'','2025-06-09 01:16:03','uploads/gabriel4.jpg',0);
/*!40000 ALTER TABLE `publicaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportes`
--

DROP TABLE IF EXISTS `reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('usuario','publicacion','foro','venta','evento') NOT NULL,
  `reportado_id` int NOT NULL,
  `motivo` text NOT NULL,
  `fecha` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reportado_por` int NOT NULL,
  `estado` enum('pendiente','resuelto') DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `reportado_por` (`reportado_por`),
  CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`reportado_por`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportes`
--

LOCK TABLES `reportes` WRITE;
/*!40000 ALTER TABLE `reportes` DISABLE KEYS */;
INSERT INTO `reportes` VALUES (3,'venta',3,'el producto muestra imagenes de adultos','2025-06-08 00:30:12',17,'pendiente'),(4,'usuario',10,'usuario sube contenido inadecuado','2025-06-08 00:39:47',1,'pendiente'),(6,'publicacion',31,'imagen de odio','2025-06-08 14:21:17',17,'resuelto'),(8,'foro',21,'habla de contenido sexual','2025-06-08 14:24:48',17,'pendiente'),(9,'evento',10,'evento de situaciones raras','2025-07-02 17:33:48',2,'pendiente');
/*!40000 ALTER TABLE `reportes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `repositorios`
--

DROP TABLE IF EXISTS `repositorios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `repositorios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `visibilidad` enum('publico','privado') DEFAULT 'publico',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `repositorios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `repositorios`
--

LOCK TABLES `repositorios` WRITE;
/*!40000 ALTER TABLE `repositorios` DISABLE KEYS */;
INSERT INTO `repositorios` VALUES (10,1,'proyecto de grado','documentos del proyecto de grado','2025-06-05 09:23:32','publico');
/*!40000 ALTER TABLE `repositorios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `roles_name_uk` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguidores`
--

DROP TABLE IF EXISTS `seguidores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguidores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `seguidor_id` int NOT NULL,
  `seguido_id` int NOT NULL,
  `fecha_seguimiento` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seguidor_id` (`seguidor_id`,`seguido_id`),
  KEY `seguido_id` (`seguido_id`),
  CONSTRAINT `seguidores_ibfk_1` FOREIGN KEY (`seguidor_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `seguidores_ibfk_2` FOREIGN KEY (`seguido_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguidores`
--

LOCK TABLES `seguidores` WRITE;
/*!40000 ALTER TABLE `seguidores` DISABLE KEYS */;
INSERT INTO `seguidores` VALUES (6,2,4,'2024-11-30 20:39:49'),(9,2,1,'2024-12-01 09:49:01'),(12,1,4,'2024-12-05 10:59:50'),(18,1,10,'2024-12-05 15:32:47'),(19,1,2,'2025-01-11 19:21:03'),(21,4,1,'2025-02-24 21:51:16'),(22,1,11,'2025-05-20 15:49:25'),(23,1,12,'2025-05-20 15:49:35'),(25,1,15,'2025-05-20 15:49:51'),(26,1,16,'2025-05-20 15:50:07'),(27,1,17,'2025-06-08 21:01:37'),(28,11,1,'2025-06-08 21:04:43'),(29,11,15,'2025-06-08 21:04:50'),(30,11,17,'2025-06-08 21:04:55');
/*!40000 ALTER TABLE `seguidores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `Nombre` varchar(50) NOT NULL,
  `Apellido` varchar(50) NOT NULL,
  `Cedula` varchar(20) NOT NULL,
  `Fecha` date NOT NULL,
  `Genero` enum('Masculino','Femenino') NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Clave` varchar(255) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `presentacion` varchar(255) DEFAULT 'Usuario de Unex',
  `carrera` varchar(255) DEFAULT NULL,
  `tipo` enum('estudiante','profesor') NOT NULL,
  `rol` enum('usuario','admin') DEFAULT 'usuario',
  `estado` enum('activo','suspendido') DEFAULT 'activo',
  `session_token` varchar(64) DEFAULT NULL,
  `codigo_recuperacion` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `Cedula` (`Cedula`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Gabriel','Rivas','29667989','2002-12-27','Masculino','gaalex200@gmail.com','$2y$10$W34t7yIRzqX02mMjtBwy6O0U2kkVL0ZjkOLyT.KIQIcFOEHuoafam','uploads/1714841630633.jpg','amante de las empanadas','Ingenieria Informática','estudiante','usuario','activo',NULL,375665),(2,'Militza','Oviedo','12207279','1975-09-08','Femenino','miliempe08@gmail.com','$2y$10$cwaRZ7H9.iGxEttrXf0kwOYXOmjDrYRoYEjE/EkZfIWkqHzZGQXvK','uploads/militza.jpg','Usuario de Unex','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(4,'Alejandro','Rivas','31847956','2008-06-15','Masculino','alejo26@gmail.com','$2y$10$BddQKhgEvZdSAUHy51xXMuSX3sm9IgYlMkU7npnN8JjnLonybBBzC','uploads/alejandro.jpg','Usuario de Unex','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(10,'franchesco','paredes','31289514','2004-12-16','Masculino','franchesco23@gmail.com','$2y$10$RCT30ODnLRpR.96KVlfeJODOyOCgVdlkChsIC7qCCIaBV4aTs5kPS','uploads/1354305.jpeg','Futuro programador','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(11,'Karla','Villa','29840082','2001-11-23','Femenino','karlacaremonda@gmail.com','$2y$10$RCT30ODnLRpR.96KVlfeJODOyOCgVdlkChsIC7qCCIaBV4aTs5kPS','uploads/karla6.jpg','Creételo: Eres increíble. ?','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(12,'Elideth','Paredes','30845195','2004-12-16','Femenino','elideth@gmail.com','$2y$10$F/YROcJUgQrXh1q8iRL78uETK3BkbS22nWMGH.wM7SvBh5YayKPCm','uploads/elideth.jpg','Usuario de Unex','Medicina veterinaria','estudiante','usuario','activo',NULL,NULL),(14,'Eleanna','Azuaje','30911486','2003-05-15','Femenino','eleanna@gmail.com','$2y$10$F/keKr3uWWqv7Gi8sR8fvueue6wPfxVDywvIKnMvZ.VVlO.5IaxLO','uploads/default.jpg','Usuario de Unex','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(15,'Daniel','Olivar','29847625','2002-11-11','Masculino','daniel@gmail.com','$2y$10$AQs5nGXSCoAKxKJ7MfbwNOh5esNstIqxJKbvkvpJr1RHB3dIj/nea','uploads/olivar.jpg','Sacerdote de la unellez','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL),(16,'Neomar','Montilla','13486578','1993-02-16','Masculino','neomar@gmail.com','$2y$10$BR7YqvIFylCNBz/jggGa1.8pNpxgmwwZIzqDER8n6I1rQRWTWROrK','uploads/default.jpg','Usuario de Unex',NULL,'profesor','usuario','activo',NULL,NULL),(17,'Unellez','Barinas','0','1975-09-07','Masculino','UNELLEZVPDS@gmail.com','$2y$10$nNXB01x/sEHZ9GFuvUqZH.iweRoXxrz/WxcG9WYKf7nGQpJXllY1u','uploads/logounellez.png','Universidad Nacional Experimental Ezequiel Zamora \"UNELLEZ\"',NULL,'profesor','admin','activo',NULL,NULL),(19,'Adrian','Diaz','28678957','2001-06-12','Masculino','adrian@gmail.com','$2y$10$NBG.gLGxRo6VHGjEspsevuJjwno.pc91oN/9gIGPgmGRDkDTqFKeS','uploads/default.jpg','Usuario de Unex','Ingenieria Informática','estudiante','usuario','activo',NULL,NULL);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-07-03 10:18:17
