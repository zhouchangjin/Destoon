/*
SQLyog Community v12.5.0 (64 bit)
MySQL - 10.1.28-MariaDB : Database - mydestoon
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`mydestoon` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mydestoon`;

/*Table structure for table `destoon_exhibit_data` */

DROP TABLE IF EXISTS `destoon_exhibit_data`;

CREATE TABLE `destoon_exhibit_data` (
  `itemid` bigint(20) unsigned NOT NULL DEFAULT '0',
  `content` mediumtext NOT NULL,
  PRIMARY KEY (`itemid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='展会内容';

/*Data for the table `destoon_exhibit_data` */

insert  into `destoon_exhibit_data`(`itemid`,`content`) values 
(222,'<h2>活动须知</h2>\n    <div class=\"wr\">\n        主题：鱼儿亲子茶艺课之——“孝道”茶会<br/>人群：推荐5-12岁的喜爱中国传统文化的儿童报名参与<br/>席位：6人<br/>时间：上午10:00--12:00<br/>费用：280元/两人，包含一名家长和一个孩子同时参加<br/>提示：茶会请穿着素雅舒适的服装，请勿喷香水<br/>报名：扫描或长按识别下图二维码可进行咨询、报名及付款'),
(223,'<h2>附近热门活动（距离本活动3公里范围内）</h2>\n      \n  <ul class=\"gallery gallery-pic115\">\n      <li>\n        <div class=\"pic\">\n          <a tabindex=\"-1\" href=\"https://www.douban.com/event/30110940/\" title=\"学院与沙龙——法国国家造型艺术中心 巴黎国立高等美术学院珍藏展\">\n            <img src=\"https://img1.doubanio.com/pview/event_poster/large/public/48a7f7055b7cf57.jpg\" alt=\"\" />\n          </a>'),
(224,'<h2>我来问主办方 &nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;&nbsp;&middot;\n        <span class=\"pl\">\n          (<a href=\"qa/all\">全部0个</a>\n            · <a href=\"qa/mine\">提问</a>\n          )\n        </span>\n      </h2>\n        <div class=\"pl\">还没有人提问'),
(225,'<h2>附近热门活动（距离本活动3公里范围内）</h2>\n      \n  <ul class=\"gallery gallery-pic115\">\n      <li>\n        <div class=\"pic\">\n          <a tabindex=\"-1\" href=\"https://www.douban.com/event/29224056/\" title=\"【 休闲时间去哪儿】?跟老外来一场高逼格的英语聚会吧~\">\n            <img src=\"https://img1.doubanio.com/pview/event_poster/large/public/001086c9022ee2b.jpg\" alt=\"\" />\n          </a>'),
(226,'<h2>活动须知</h2>\n    <div class=\"wr\">\n        为了达到最好光线，我们一般会选择下午作为拍摄开始，地点我完全支持北京市的公园或者文艺气息而有趣的地方。<br/>地点：圆明园 清华大学 奥森 中山公园 景山公园 北京大学 香山植物园 朝阳公园等。 <br/>孩爸孩妈需要 自备孩子自己的衣物 当然 自己的衣物也请准备好');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
