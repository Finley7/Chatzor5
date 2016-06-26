# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.49-0ubuntu0.14.04.1)
# Database: chatzor5
# Generation Time: 2016-06-26 12:02:48 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table activities
# ------------------------------------------------------------

CREATE TABLE `activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activity_user_id_uindex` (`user_id`),
  CONSTRAINT `activity_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table chats
# ------------------------------------------------------------

CREATE TABLE `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `message` varchar(150) NOT NULL,
  `created` datetime DEFAULT NULL,
  `whisper_to` int(11) DEFAULT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `chats_users_id_fk` (`user_id`),
  KEY `chats_whispers__fk` (`whisper_to`),
  CONSTRAINT `chats_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table mailkeys
# ------------------------------------------------------------

CREATE TABLE `mailkeys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `activated` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `mailkeys_token_uindex` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table permissions
# ------------------------------------------------------------

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_uindex` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;

INSERT INTO `permissions` (`id`, `name`, `description`)
VALUES
	(1,'pages_index','Can view dashboard'),
	(2,'ajax_chats_index','Can view chats'),
	(3,'ajax_chats_shout','Can shout'),
	(4,'ajax_chats_lastid','Fetch last shoutid'),
	(5,'ajax_chats_view','View shout'),
	(6,'ajax_activities_index','Activity'),
	(7,'pages_archive','Archive'),
	(8,'management_pages_index','Management'),
	(9,'management_chats_index','Chat Management'),
	(10,'management_chats_delete','Chat delete'),
	(11,'users_settings','Users settings'),
	(12,'pages_chat','New chat'),
	(13,'management_users_index','Users index'),
	(14,'management_users_add','Users add'),
	(15,'management_users_block','Users ban'),
	(16,'management_users_activate','Users activate'),
	(17,'management_users_edit','Users edit'),
	(18,'management_users_view','Users view'),
	(19,'management_users_search','Users search'),
	(20,'management_users_password','Users password'),
	(21,'management_users_avatarreset','Users avatar reset');

/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permissions_roles
# ------------------------------------------------------------

CREATE TABLE `permissions_roles` (
  `role_id` int(11) NOT NULL DEFAULT '0',
  `permission_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`role_id`,`permission_id`),
  KEY `const_permission` (`permission_id`),
  CONSTRAINT `const_permission` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  CONSTRAINT `const_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `permissions_roles` WRITE;
/*!40000 ALTER TABLE `permissions_roles` DISABLE KEYS */;

INSERT INTO `permissions_roles` (`role_id`, `permission_id`)
VALUES
	(1,1),
	(1,2),
	(1,3),
	(1,4),
	(1,5),
	(1,6),
	(1,7),
	(2,8),
	(2,9),
	(2,10),
	(1,11),
	(1,12),
	(2,13),
	(2,14),
	(2,15),
	(2,16),
	(2,17),
	(2,18),
	(2,19),
	(2,20),
	(2,21);

/*!40000 ALTER TABLE `permissions_roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles
# ------------------------------------------------------------

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` mediumtext,
  `banned` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;

INSERT INTO `roles` (`id`, `name`, `description`, `banned`)
VALUES
	(1,'Gebruiker','Regular user',0),
	(2,'Administrator','Administrator',0),
	(3,'Niet-geactiveerd','Not activated',0),
	(6,'Banned','Banned',1);

/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table roles_users
# ------------------------------------------------------------

CREATE TABLE `roles_users` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `role_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `const_roles` (`role_id`),
  CONSTRAINT `const_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `const_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table users
# ------------------------------------------------------------

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `primary_role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default-avatar.png',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_uindex` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
