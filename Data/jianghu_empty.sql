-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2017 at 07:22 PM
-- Server version: 5.5.52-0ubuntu0.14.04.1-log
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jianghu`
--

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_admin`
--

CREATE TABLE IF NOT EXISTS `jianghu_admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL DEFAULT '""',
  `password` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='管理员表' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `jianghu_admin`
--

INSERT INTO `jianghu_admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'superadmin', '123', '123456'),
(2, 'Kingkun', '930314850@qq.com', '123456'),
(3, 'admin2', '', '123456'),
(4, 'admin3', '', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_admin_token`
--

CREATE TABLE IF NOT EXISTS `jianghu_admin_token` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `time` datetime NOT NULL,
  `state` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_business`
--

CREATE TABLE IF NOT EXISTS `jianghu_business` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `addr` varchar(100) NOT NULL COMMENT '地址',
  `latitude` varchar(30) NOT NULL COMMENT '纬度',
  `longtitude` varchar(30) NOT NULL COMMENT '经度',
  `discription` text NOT NULL,
  `state` tinyint(4) NOT NULL COMMENT '审核状态 0为已审核 1为未审核',
  `joindate` date NOT NULL COMMENT '加入江湖时间',
  `logourl` text NOT NULL,
  `logopath` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '推荐人的user_id',
  `star` tinyint(3) unsigned NOT NULL COMMENT '星级',
  `domain` varchar(30) NOT NULL,
  `website` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_business_comment`
--

CREATE TABLE IF NOT EXISTS `jianghu_business_comment` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `business_id` mediumint(9) NOT NULL COMMENT '商户id',
  `comment` text NOT NULL COMMENT '评论内容',
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户评价表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_collection`
--

CREATE TABLE IF NOT EXISTS `jianghu_collection` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `introduce_id` mediumint(8) unsigned NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收藏表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_comment`
--

CREATE TABLE IF NOT EXISTS `jianghu_comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `introduce_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `content` text NOT NULL COMMENT '评论内容',
  `owner_id` int(10) unsigned NOT NULL COMMENT '推荐所有者的id',
  `state` tinyint(3) unsigned NOT NULL COMMENT '已读则为零，未读则置1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_comment_comment`
--

CREATE TABLE IF NOT EXISTS `jianghu_comment_comment` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` mediumint(8) unsigned NOT NULL,
  `text` text NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论的品论' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_daily_num`
--

CREATE TABLE IF NOT EXISTS `jianghu_daily_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL COMMENT '日期',
  `joinnum` int(11) unsigned NOT NULL COMMENT '注册人数',
  `lognum` int(10) unsigned NOT NULL COMMENT '日登陆人数',
  `praisenum` int(10) unsigned NOT NULL COMMENT '每日点赞人数',
  `commentnum` int(10) unsigned NOT NULL COMMENT '评论人数',
  `keep` int(10) unsigned NOT NULL COMMENT '昨天注册今天登陆的人数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每日注册人数' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_domain`
--

CREATE TABLE IF NOT EXISTS `jianghu_domain` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `domain_pic` varchar(200) NOT NULL COMMENT '领域图片url',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_feedback`
--

CREATE TABLE IF NOT EXISTS `jianghu_feedback` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '是否已经处理，0为未处理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='反馈表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_forward`
--

CREATE TABLE IF NOT EXISTS `jianghu_forward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `introduce_id` int(10) unsigned NOT NULL,
  `original_id` int(10) unsigned NOT NULL COMMENT '原始推荐id',
  `original_id2` int(10) unsigned NOT NULL COMMENT '二度源user_id',
  `original_id3` int(10) unsigned NOT NULL COMMENT '三度源user_id',
  `original_id4` int(10) unsigned NOT NULL COMMENT '4度源user_id',
  `original_id5` int(11) unsigned NOT NULL,
  `original_id6` int(10) unsigned NOT NULL,
  `original_id7` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `owner_id` int(10) unsigned NOT NULL COMMENT '推荐所有者的id',
  `degree` tinyint(4) NOT NULL COMMENT '转发的时候该推荐的度数',
  `state` tinyint(3) unsigned NOT NULL COMMENT '是否阅读标记，未读则置零，已读则为一',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转采表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_friend`
--

CREATE TABLE IF NOT EXISTS `jianghu_friend` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_friend_request`
--

CREATE TABLE IF NOT EXISTS `jianghu_friend_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `friend_id` int(10) unsigned NOT NULL,
  `date` date NOT NULL,
  `state` tinyint(3) unsigned NOT NULL COMMENT '未读则为零，已读则为一',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='朋友请求表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_homepage`
--

CREATE TABLE IF NOT EXISTS `jianghu_homepage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(300) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_introduce`
--

CREATE TABLE IF NOT EXISTS `jianghu_introduce` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL COMMENT '文字内容',
  `degree` mediumint(11) NOT NULL COMMENT '度数',
  `time` datetime NOT NULL,
  `forwardnum` mediumint(10) unsigned NOT NULL COMMENT '转采量',
  `praisenum` mediumint(10) unsigned NOT NULL COMMENT '点赞量',
  `opposenum` mediumint(10) unsigned NOT NULL COMMENT '踩数',
  `collectnum` mediumint(10) unsigned NOT NULL COMMENT '收藏量',
  `isforward` int(10) unsigned DEFAULT NULL COMMENT '如果不是转载则为null，否则为转载的原始推荐的id',
  `commentnum` mediumint(10) unsigned NOT NULL,
  `forward_id` int(10) unsigned NOT NULL COMMENT '若该推荐是转载的，保存转载记录的id',
  `alldegree` tinyint(8) unsigned NOT NULL COMMENT '三度以上的度数总和，用于江湖返回推荐',
  `business_id` mediumint(8) unsigned NOT NULL COMMENT '商户id',
  `business_name` varchar(30) NOT NULL COMMENT '商户名字',
  `business_website` text NOT NULL,
  `domain` varchar(30) NOT NULL,
  `isban` tinyint(4) NOT NULL COMMENT '0是正常，1是已禁止',
  `business_latitude` varchar(50) NOT NULL,
  `business_longtitude` varchar(50) NOT NULL,
  `business_addr` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_introduce_domain`
--

CREATE TABLE IF NOT EXISTS `jianghu_introduce_domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `introduce_id` int(10) unsigned NOT NULL COMMENT '推荐id',
  `domain` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐_领域表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_introduce_images`
--

CREATE TABLE IF NOT EXISTS `jianghu_introduce_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `introduce_id` int(10) unsigned NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `imagepath` varchar(100) NOT NULL,
  `thumb_imageurl` varchar(100) NOT NULL,
  `thumb_imagepath` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐_图片表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_introduce_report`
--

CREATE TABLE IF NOT EXISTS `jianghu_introduce_report` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL COMMENT '举报者id',
  `introduce_id` mediumint(9) NOT NULL,
  `text` text NOT NULL,
  `time` datetime NOT NULL,
  `state` tinyint(4) NOT NULL COMMENT '0是未处理，1是已禁用，2是忽略',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_login_count`
--

CREATE TABLE IF NOT EXISTS `jianghu_login_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录统计表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_oppose`
--

CREATE TABLE IF NOT EXISTS `jianghu_oppose` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `introduce_id` mediumint(8) unsigned NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐踩表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_poster`
--

CREATE TABLE IF NOT EXISTS `jianghu_poster` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  `content_url` text NOT NULL,
  `praisenum` mediumint(8) unsigned NOT NULL COMMENT '点赞数',
  `readnum` mediumint(8) unsigned NOT NULL COMMENT '阅读数',
  `posterurl` varchar(100) NOT NULL COMMENT '海报地址',
  `posterpath` varchar(100) NOT NULL COMMENT '海报路径',
  `state` tinyint(3) unsigned NOT NULL COMMENT '0为运营中，1为到期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_poster_praise`
--

CREATE TABLE IF NOT EXISTS `jianghu_poster_praise` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `poster_id` mediumint(8) unsigned NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='海报点赞表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_praise`
--

CREATE TABLE IF NOT EXISTS `jianghu_praise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `introduce_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL COMMENT '点赞人的id',
  `time` datetime NOT NULL,
  `owner_id` int(10) unsigned NOT NULL COMMENT '推荐所有者的id',
  `state` tinyint(3) unsigned NOT NULL COMMENT '未读则为0，已读则为1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点赞表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_sort_praise`
--

CREATE TABLE IF NOT EXISTS `jianghu_sort_praise` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `sort_user_id` mediumint(8) unsigned NOT NULL COMMENT '被点赞人的id',
  `state` tinyint(4) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='口碑排行点赞表，每周更新一次' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_tag`
--

CREATE TABLE IF NOT EXISTS `jianghu_tag` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_test`
--

CREATE TABLE IF NOT EXISTS `jianghu_test` (
  `test` text NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='测试表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_token`
--

CREATE TABLE IF NOT EXISTS `jianghu_token` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `time` datetime NOT NULL COMMENT 'token生成时间',
  `state` tinyint(4) NOT NULL COMMENT 'token状态：1为过期，0为正常',
  `token` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='token表，用于用户登录验证' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_update`
--

CREATE TABLE IF NOT EXISTS `jianghu_update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(200) NOT NULL,
  `description` text NOT NULL,
  `time` datetime NOT NULL,
  `url` varchar(250) NOT NULL,
  `md5` varchar(100) NOT NULL,
  `filesize` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_user`
--

CREATE TABLE IF NOT EXISTS `jianghu_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `sex` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '性别：0为男，1为女',
  `phonenum` varchar(30) NOT NULL,
  `addr` text NOT NULL COMMENT '地址',
  `praisenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '口碑',
  `temp_praisenum` tinyint(3) unsigned NOT NULL COMMENT '临时存放来自江湖的点赞，当满10的时候praisenum就加一，这个就清零',
  `birthday` date NOT NULL,
  `description` text NOT NULL COMMENT '个人简介',
  `faceurl` varchar(100) NOT NULL COMMENT '头像url',
  `facepath` varchar(100) NOT NULL COMMENT '头像路径',
  `jointime` date NOT NULL COMMENT '注册时间',
  `email` varchar(20) NOT NULL,
  `isban` tinyint(4) NOT NULL DEFAULT '0' COMMENT '若用户被禁用则为1，否则为0',
  `allpraise` int(10) unsigned NOT NULL COMMENT '总点赞',
  `allforward` mediumint(8) unsigned NOT NULL,
  `alloppose` int(10) unsigned NOT NULL COMMENT '总踩量',
  `unreadnum` tinyint(3) unsigned NOT NULL COMMENT '新的未读推荐数',
  `qq_id` varchar(80) NOT NULL COMMENT 'qq平台id',
  `wechat_id` varchar(80) NOT NULL,
  `push_regid` varchar(200) DEFAULT NULL COMMENT '极光推送regid',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_user_domain`
--

CREATE TABLE IF NOT EXISTS `jianghu_user_domain` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL COMMENT '用户id',
  `domain` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='领域表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_user_report`
--

CREATE TABLE IF NOT EXISTS `jianghu_user_report` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(9) NOT NULL COMMENT '举报者id',
  `reported_id` mediumint(9) NOT NULL COMMENT '被举报者id',
  `text` text NOT NULL COMMENT '举报内容',
  `time` datetime NOT NULL,
  `state` tinyint(1) NOT NULL COMMENT '0是未处理，1是已处理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户举报表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `jianghu_weekend_reputation`
--

CREATE TABLE IF NOT EXISTS `jianghu_weekend_reputation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned NOT NULL,
  `reputationnum` smallint(5) unsigned NOT NULL COMMENT '口碑数',
  `date` date NOT NULL,
  `state` tinyint(4) NOT NULL COMMENT '0为正常，1为过期',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='周末用户口碑统计表，用于口碑排名' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
