-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: barangay_records
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table "blotter"
--

DROP TABLE IF EXISTS "blotter";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "blotter" (
  "id" int(11) NOT NULL ,
  "incident_date" datetime NOT NULL,
  "description" text NOT NULL,
  "complainant" varchar(100) DEFAULT NULL,
  "respondent" varchar(100) DEFAULT NULL,
  "location" text DEFAULT NULL,
  "status" enum('Pending','Resolved','Dismissed') DEFAULT 'Pending',
  "resolution" text DEFAULT NULL,
  "created_at" timestamp NOT NULL DEFAULT current_timestamp(),
  "updated_at" timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY ("id")
) ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "blotter"
--

LOCK TABLES "blotter" WRITE;
/*!40000 ALTER TABLE "blotter" DISABLE KEYS */;
INSERT INTO "blotter" VALUES (1,'2026-05-22 13:30:00','ipatawag ka kay nangawat kag puthaw','charles laquio','jerome paig','buntod sa kanturatoy','Pending','','2026-05-19 05:30:32','2026-05-19 05:30:32');
/*!40000 ALTER TABLE "blotter" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "documents"
--

DROP TABLE IF EXISTS "documents";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "documents" (
  "id" int(11) NOT NULL ,
  "resident_id" int(11) NOT NULL,
  "document_type" varchar(100) NOT NULL,
  "issued_date" date NOT NULL,
  "expiry_date" date DEFAULT NULL,
  "status" enum('Active','Expired','Revoked') DEFAULT 'Active',
  "notes" text DEFAULT NULL,
  "created_at" timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY ("id"),
  KEY "resident_id" ("resident_id"),
  CONSTRAINT "documents_ibfk_1" FOREIGN KEY ("resident_id") REFERENCES "residents" ("id") ON DELETE CASCADE
) ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "documents"
--

LOCK TABLES "documents" WRITE;
/*!40000 ALTER TABLE "documents" DISABLE KEYS */;
INSERT INTO "documents" VALUES (1,2,'pdf','2005-04-15','2026-12-06','Active','ipatawag kang kapitan','2026-05-19 05:27:10');
/*!40000 ALTER TABLE "documents" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "residents"
--

DROP TABLE IF EXISTS "residents";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "residents" (
  "id" int(11) NOT NULL ,
  "first_name" varchar(50) NOT NULL,
  "last_name" varchar(50) NOT NULL,
  "middle_name" varchar(50) DEFAULT NULL,
  "address" text NOT NULL,
  "birthdate" date NOT NULL,
  "contact_number" varchar(15) DEFAULT NULL,
  "email" varchar(100) DEFAULT NULL,
  "gender" enum('Male','Female','Other') DEFAULT NULL,
  "civil_status" enum('Single','Married','Widowed','Divorced') DEFAULT NULL,
  "occupation" varchar(100) DEFAULT NULL,
  "created_at" timestamp NOT NULL DEFAULT current_timestamp(),
  "updated_at" timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY ("id")
) ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "residents"
--

LOCK TABLES "residents" WRITE;
/*!40000 ALTER TABLE "residents" DISABLE KEYS */;
INSERT INTO "residents" VALUES (1,'Christian','Salazar','Schenider','banonong dapitan city','2002-11-12','091286175023','lauvthegreatman@gmail.com','Male','Single','tambay lang','2026-04-28 02:28:28','2026-04-28 02:28:28'),(2,'jerome','paig','','dipolog','1999-12-12','8080','charles@gmail.com','Male','Single','','2026-05-19 05:25:35','2026-05-19 05:25:35');
/*!40000 ALTER TABLE "residents" ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table "users"
--

DROP TABLE IF EXISTS "users";
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE "users" (
  "id" int(11) NOT NULL ,
  "username" varchar(50) NOT NULL,
  "password" varchar(255) NOT NULL,
  "role" enum('admin','staff') DEFAULT 'staff',
  "created_at" timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY ("id"),
  UNIQUE KEY "username" ("username")
) ;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table "users"
--

LOCK TABLES "users" WRITE;
/*!40000 ALTER TABLE "users" DISABLE KEYS */;
INSERT INTO "users" VALUES (1,'admin','$2y$10$C9XTTub4qYMCcxqgjIkSheZaluZ8M.ZQYK7VwGhY/jeSjRCTGg1pm','admin','2026-04-16 08:36:31');
/*!40000 ALTER TABLE "users" ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-23 14:51:44

