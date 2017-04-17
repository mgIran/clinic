/*
Navicat MySQL Data Transfer

Source Server         : local
Source Server Version : 50616
Source Host           : localhost:3306
Source Database       : ym_tablo_db

Target Server Type    : MYSQL
Target Server Version : 50616
File Encoding         : 65001

Date: 2016-02-05 22:57:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ym_page_categories
-- ----------------------------
DROP TABLE IF EXISTS `ym_page_categories`;
CREATE TABLE `ym_page_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL COMMENT 'عنوان',
  `slug` varchar(255) DEFAULT NULL COMMENT 'آدرس',
  `multiple` tinyint(1) unsigned DEFAULT '1' COMMENT 'چند صحفه ای',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ym_page_categories
-- ----------------------------
INSERT INTO `ym_page_categories` VALUES ('1', 'قوانین', 'rules', '1');
INSERT INTO `ym_page_categories` VALUES ('2', 'راهنما', 'guide', '1');
INSERT INTO `ym_page_categories` VALUES ('3', 'آزاد', 'free', '1');
INSERT INTO `ym_page_categories` VALUES ('4', 'صفحات اصلی', 'base', '1');

-- ----------------------------
-- Table structure for ym_pages
-- ----------------------------
DROP TABLE IF EXISTS `ym_pages`;
CREATE TABLE `ym_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'عنوان',
  `summary` text COMMENT 'متن',
  `category_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `ym_pages_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `ym_page_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ym_pages
-- ----------------------------
INSERT INTO `ym_pages` VALUES ('2', 'درباره ما', '', '4');
INSERT INTO `ym_pages` VALUES ('3', 'تماس با ما', null, '4');
INSERT INTO `ym_pages` VALUES ('5', 'قوانین و مقررات', null, '1');
