/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50723
Source Host           : localhost:3306
Source Database       : chat

Target Server Type    : MYSQL
Target Server Version : 50723
File Encoding         : 65001

Date: 2018-11-21 21:19:20
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES ('1', 'tom', '4297f44b13955235245b2497399d7a93', '2018-11-16 16:56:39', '2018-11-16 16:56:39');
INSERT INTO `users` VALUES ('2', 'jack', '4297f44b13955235245b2497399d7a93', '2018-11-16 16:56:42', '2018-11-16 16:56:42');
INSERT INTO `users` VALUES ('3', 'aaa', '4297f44b13955235245b2497399d7a93', '2018-11-16 16:56:45', '2018-11-16 16:56:45');
INSERT INTO `users` VALUES ('4', 'ccc', '4297f44b13955235245b2497399d7a93', '2018-11-16 16:56:48', '2018-11-16 16:56:48');
