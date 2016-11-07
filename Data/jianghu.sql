-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-11-07 04:09:18
-- 服务器版本： 10.1.16-MariaDB
-- PHP Version: 5.6.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jianghu`
--

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_admin`
--

CREATE TABLE `jianghu_admin` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员表';

--
-- 转存表中的数据 `jianghu_admin`
--

INSERT INTO `jianghu_admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'admin1', '123', '123456');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_business`
--

CREATE TABLE `jianghu_business` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(60) NOT NULL,
  `addr` varchar(100) NOT NULL COMMENT '地址',
  `latitude` varchar(100) NOT NULL COMMENT '纬度',
  `discription` text NOT NULL,
  `state` tinyint(4) NOT NULL COMMENT '审核状态 0为已审核 1为未审核',
  `joindate` date NOT NULL COMMENT '加入江湖时间',
  `logourl` text NOT NULL,
  `logopath` text NOT NULL,
  `phone` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '推荐人的user_id',
  `star` tinyint(3) UNSIGNED NOT NULL COMMENT '星级'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商户表';

--
-- 转存表中的数据 `jianghu_business`
--

INSERT INTO `jianghu_business` (`id`, `name`, `addr`, `latitude`, `discription`, `state`, `joindate`, `logourl`, `logopath`, `phone`, `user_id`, `star`) VALUES
(1, '章鱼小丸子', '贝岗', '', '好吃啊', 0, '0000-00-00', 'http://localhost/jianghu/Uploads/2016-11-03/581b5827bfc22.jpg', './Uploads/2016-11-03/581b5827bfc22.jpg', '1302063303', 1, 0),
(2, '章鱼小丸子', '贝岗', '192123', '好吃啊', 1, '2016-11-02', 'http://localhost/jianghu/Uploads/2016-11-02/5819f81c0e57d.jpg', './Uploads/2016-11-02/5819f81c0e57d.jpg', '1302063303', 1, 0),
(3, '宝鸡', '贝岗', '', '不好吃', 0, '0000-00-00', 'http://localhost/jianghu/Uploads/2016-11-04/581b600f13e03.jpg', './Uploads/2016-11-04/581b600f13e03.jpg', '13066331202', 0, 2),
(4, 'halo', 'lalal', '', '123456', 0, '0000-00-00', 'http://localhost/jianghu/Uploads/2016-11-04/581c9882e5e07.jpg', './Uploads/2016-11-04/581c9882e5e07.jpg', '321654', 0, 3),
(5, 'hhh', 'dddd', '', '', 0, '0000-00-00', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_comment`
--

CREATE TABLE `jianghu_comment` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `introduce_id` int(10) UNSIGNED NOT NULL,
  `time` datetime NOT NULL,
  `content` text NOT NULL COMMENT '评论内容'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='评论表';

--
-- 转存表中的数据 `jianghu_comment`
--

INSERT INTO `jianghu_comment` (`id`, `user_id`, `introduce_id`, `time`, `content`) VALUES
(1, 1, 8, '0000-00-00 00:00:00', ''),
(2, 1, 8, '2016-10-12 00:00:00', 'he评论huang的推荐'),
(3, 1, 8, '2016-10-12 20:59:29', 'he评论huang的推荐'),
(4, 2, 8, '2016-10-12 21:19:34', '评论huang的推荐'),
(5, 3, 8, '2016-10-12 21:20:26', '评论huang的推荐'),
(6, 3, 8, '2016-10-12 21:20:48', '评论huang的推荐'),
(7, 1, 64, '2016-10-24 15:55:06', '评论'),
(8, 1, 64, '2016-10-24 19:40:09', '评论'),
(9, 1, 64, '2016-10-30 12:28:03', '评论');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_daily_num`
--

CREATE TABLE `jianghu_daily_num` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL COMMENT '日期',
  `joinnum` int(11) UNSIGNED NOT NULL COMMENT '注册人数',
  `lognum` int(10) UNSIGNED NOT NULL COMMENT '日登陆人数',
  `praisenum` int(10) UNSIGNED NOT NULL COMMENT '每日点赞人数',
  `commentnum` int(10) UNSIGNED NOT NULL COMMENT '评论人数',
  `keep` int(10) UNSIGNED NOT NULL COMMENT '昨天注册今天登陆的人数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='每日注册人数';

--
-- 转存表中的数据 `jianghu_daily_num`
--

INSERT INTO `jianghu_daily_num` (`id`, `date`, `joinnum`, `lognum`, `praisenum`, `commentnum`, `keep`) VALUES
(1, '2016-10-11', 6, 3, 6, 0, 2),
(2, '2016-10-12', 12, 1, 3, 1, 4),
(3, '2016-10-13', 23, 7, 0, 0, 5),
(4, '2016-10-14', 5, 0, 0, 0, 0),
(5, '2016-10-15', 0, 3, 0, 0, 1),
(6, '2016-10-16', 5, 8, 0, 0, 0),
(7, '2016-10-21', 0, 1, 0, 0, 0),
(8, '2016-10-24', 0, 1, 2, 1, 0),
(9, '2016-10-25', 0, 3, 0, 0, 0),
(10, '2016-10-26', 0, 3, 1, 0, 0),
(11, '2016-10-30', 0, 1, 1, 0, 0),
(12, '2016-10-31', 0, 2, 2, 0, 0),
(13, '2016-11-01', 0, 4, 1, 0, 0),
(14, '2016-11-02', 0, 1, 0, 0, 0),
(15, '2016-11-06', 0, 1, 0, 0, 0),
(16, '2016-11-07', 0, 3, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_forward`
--

CREATE TABLE `jianghu_forward` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `introduce_id` int(10) UNSIGNED NOT NULL,
  `original_id` int(10) UNSIGNED DEFAULT NULL COMMENT '原始推荐id',
  `original_id2` int(10) UNSIGNED NOT NULL COMMENT '二度源user_id',
  `original_id3` int(10) UNSIGNED NOT NULL COMMENT '三度源user_id',
  `original_id4` int(10) UNSIGNED NOT NULL COMMENT '4度源user_id',
  `original_id5` int(11) UNSIGNED NOT NULL,
  `original_id6` int(10) UNSIGNED NOT NULL,
  `original_id7` int(10) UNSIGNED NOT NULL,
  `time` datetime NOT NULL,
  `owner_id` int(10) UNSIGNED NOT NULL COMMENT '推荐所有者的id',
  `degree` tinyint(4) NOT NULL COMMENT '转发的时候该推荐的度数'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='转采表';

--
-- 转存表中的数据 `jianghu_forward`
--

INSERT INTO `jianghu_forward` (`id`, `user_id`, `introduce_id`, `original_id`, `original_id2`, `original_id3`, `original_id4`, `original_id5`, `original_id6`, `original_id7`, `time`, `owner_id`, `degree`) VALUES
(1, 1, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-09-26 15:05:39', 0, 0),
(2, 2, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-09-26 15:06:52', 0, 0),
(3, 1, 9, NULL, 0, 0, 0, 0, 0, 0, '2016-09-27 19:44:50', 0, 0),
(4, 1, 9, NULL, 0, 0, 0, 0, 0, 0, '2016-09-27 19:46:28', 0, 0),
(5, 1, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-09-30 14:14:16', 0, 0),
(6, 1, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-09-30 14:19:20', 0, 0),
(7, 1, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-09-30 14:20:47', 4, 0),
(8, 1, 8, NULL, 0, 0, 0, 0, 0, 0, '2016-10-01 19:42:31', 2, 0),
(9, 1, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 15:48:05', 1, 1),
(10, 2, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 17:02:49', 1, 1),
(11, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 17:03:42', 1, 1),
(12, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 19:34:04', 1, 1),
(13, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 19:38:06', 1, 1),
(14, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 19:39:37', 1, 1),
(15, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 19:40:27', 1, 2),
(16, 2, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:04:20', 1, 2),
(17, 3, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:05:07', 1, 2),
(18, 7, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:06:22', 3, 2),
(19, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:06:56', 3, 2),
(20, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:09:59', 3, 2),
(21, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:10:18', 3, 2),
(22, 16, 26, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 20:11:25', 3, 2),
(23, 17, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 22:37:43', 17, 1),
(24, 18, 41, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 22:37:49', 18, 1),
(25, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 22:42:30', 17, 2),
(26, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 23:43:04', 17, 2),
(27, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-14 23:44:19', 17, 2),
(28, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 00:17:22', 17, 2),
(29, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 00:25:20', 17, 2),
(30, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 00:27:47', 17, 2),
(31, 18, 40, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 00:28:01', 17, 2),
(32, 19, 48, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 00:32:10', 18, 3),
(33, 17, 50, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 09:20:32', 17, 1),
(34, 17, 51, 51, 0, 0, 0, 0, 0, 0, '2016-10-15 09:29:36', 17, 1),
(35, 18, 51, NULL, 0, 0, 0, 0, 0, 0, '2016-10-15 09:33:44', 17, 3),
(36, 18, 51, 51, 0, 0, 0, 0, 0, 0, '2016-10-15 09:40:06', 17, 3),
(37, 17, 54, 54, 0, 0, 0, 0, 0, 0, '2016-10-15 09:41:39', 17, 1),
(38, 18, 54, 54, 0, 0, 0, 0, 0, 0, '2016-10-15 09:42:18', 17, 3),
(39, 18, 54, 54, 0, 0, 0, 0, 0, 0, '2016-10-15 09:49:22', 17, 2),
(40, 17, 57, 57, 0, 0, 0, 0, 0, 0, '2016-10-15 10:05:18', 17, 1),
(41, 18, 57, 57, 0, 0, 0, 0, 0, 0, '2016-10-15 10:06:21', 17, 3),
(42, 17, 59, 59, 0, 0, 0, 0, 0, 0, '2016-10-15 10:08:30', 17, 1),
(43, 18, 59, 59, 0, 0, 0, 0, 0, 0, '2016-10-15 10:09:08', 17, 2),
(44, 19, 60, 59, 0, 0, 0, 0, 0, 0, '2016-10-15 10:10:55', 18, 2),
(45, 20, 61, 59, 0, 0, 0, 0, 0, 0, '2016-10-15 10:12:02', 19, 3),
(46, 1, 63, 63, 0, 0, 0, 0, 0, 0, '2016-10-15 15:57:41', 1, 1),
(47, 1, 64, 64, 0, 0, 0, 0, 0, 0, '2016-10-24 14:28:14', 1, 1),
(48, 1, 64, 64, 0, 0, 0, 0, 0, 0, '2016-10-24 15:35:29', 1, 2),
(49, 1, 64, 64, 0, 0, 0, 0, 0, 0, '2016-10-25 20:29:09', 1, 2),
(50, 26, 64, 64, 0, 0, 0, 0, 0, 0, '2016-10-25 20:34:27', 1, 3),
(51, 17, 64, 64, 0, 0, 0, 0, 0, 0, '2016-10-25 20:36:55', 1, 3),
(52, 18, 69, 69, 0, 0, 0, 0, 0, 0, '2016-10-25 21:17:42', 18, 1),
(53, 19, 70, 70, 0, 0, 0, 0, 0, 0, '2016-10-25 21:17:59', 19, 1),
(54, 17, 70, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 20:49:52', 19, 2),
(55, 10, 71, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 20:55:00', 17, 3),
(56, 1, 72, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 21:29:23', 10, 4),
(57, 4, 72, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 21:33:30', 10, 4),
(58, 21, 74, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 21:54:29', 4, 3),
(59, 17, 75, 70, 0, 0, 0, 0, 0, 0, '2016-10-26 21:58:42', 21, 2),
(60, 1, 77, 77, 0, 0, 0, 0, 0, 0, '2016-10-30 23:35:46', 1, 1),
(61, 2, 77, 77, 2, 0, 0, 0, 0, 0, '2016-10-30 23:49:32', 1, 2),
(62, 17, 77, 77, 17, 0, 0, 0, 0, 0, '2016-10-30 23:54:50', 1, 2),
(63, 17, 78, 77, 0, 17, 0, 0, 0, 0, '2016-10-30 23:55:13', 2, 3),
(64, 19, 78, 77, 0, 19, 0, 0, 0, 0, '2016-10-31 00:06:47', 2, 3),
(65, 19, 78, 77, 2, 19, 0, 0, 0, 0, '2016-10-31 00:09:42', 2, 3),
(66, 25, 82, 77, 2, 19, 25, 0, 0, 0, '2016-10-31 00:10:48', 19, 4),
(67, 2, 83, 77, 2, 2, 0, 0, 0, 0, '2016-10-31 00:11:41', 25, 3),
(68, 2, 83, 77, 2, 0, 0, 0, 0, 0, '2016-10-31 00:20:25', 25, 2),
(69, 3, 85, 77, 2, 3, 0, 0, 0, 0, '2016-10-31 00:21:33', 2, 3),
(70, 3, 85, 77, 3, 0, 0, 0, 0, 0, '2016-10-31 00:22:29', 2, 2),
(71, 19, 86, 77, 17, 19, 0, 0, 0, 0, '2016-10-31 00:23:07', 3, 3),
(72, 19, 88, 77, 17, 19, 0, 0, 0, 0, '2016-10-31 00:25:03', 19, 3),
(73, 1, 90, 90, 0, 0, 0, 0, 0, 0, '2016-10-31 14:15:17', 1, 1),
(74, 2, 90, 90, 2, 0, 0, 0, 0, 0, '2016-10-31 14:19:46', 1, 2),
(75, 19, 91, 90, 2, 19, 0, 0, 0, 0, '2016-10-31 14:20:31', 2, 3),
(76, 26, 92, 90, 2, 19, 26, 0, 0, 0, '2016-10-31 14:21:14', 19, 4),
(77, 26, 93, 90, 2, 19, 26, 26, 0, 0, '2016-10-31 14:27:51', 26, 5),
(78, 25, 94, 90, 2, 19, 26, 26, 25, 0, '2016-10-31 15:06:29', 26, 6),
(79, 3, 95, 90, 3, 0, 0, 0, 0, 0, '2016-10-31 15:06:58', 25, 2),
(80, 12, 95, 90, 2, 19, 26, 26, 25, 12, '2016-10-31 16:02:59', 25, 7),
(81, 13, 95, 90, 2, 19, 26, 26, 25, 13, '2016-10-31 16:04:31', 25, 7),
(82, 14, 95, 90, 2, 19, 26, 26, 25, 14, '2016-10-31 16:06:33', 25, 7),
(83, 15, 95, 90, 2, 19, 26, 26, 25, 15, '2016-10-31 16:11:27', 25, 7),
(84, 17, 95, 90, 2, 19, 17, 0, 0, 0, '2016-10-31 16:12:52', 25, 4);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_friend`
--

CREATE TABLE `jianghu_friend` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `friend_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `jianghu_friend`
--

INSERT INTO `jianghu_friend` (`id`, `user_id`, `friend_id`) VALUES
(1, 1, 5),
(2, 1, 4),
(3, 1, 3),
(4, 1, 2),
(5, 6, 6),
(6, 1, 1),
(7, 2, 2),
(8, 3, 3),
(9, 4, 4),
(10, 5, 5),
(11, 1, 6),
(12, 7, 7),
(13, 1, 7),
(14, 8, 8),
(15, 9, 9),
(16, 10, 10),
(17, 11, 11),
(18, 12, 12),
(19, 13, 13),
(20, 14, 14),
(21, 15, 15),
(22, 16, 16),
(23, 17, 17),
(24, 18, 18),
(25, 19, 19),
(26, 20, 20),
(27, 21, 21),
(28, 17, 18),
(29, 17, 19),
(30, 17, 19),
(31, 19, 17),
(32, 3, 1),
(33, 0, 0),
(34, 0, 0),
(35, 2, 1),
(36, 0, 0),
(37, 0, 0),
(38, 0, 0),
(39, 0, 0),
(40, 0, 0),
(41, 0, 0),
(42, 18, 19),
(43, 19, 18),
(44, 22, 22),
(45, 23, 23),
(46, 24, 24),
(47, 25, 25),
(48, 26, 26),
(49, 10, 15),
(50, 15, 10),
(53, 17, 21),
(55, 21, 17),
(56, 2, 1),
(57, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_friend_request`
--

CREATE TABLE `jianghu_friend_request` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `friend_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='朋友请求表';

--
-- 转存表中的数据 `jianghu_friend_request`
--

INSERT INTO `jianghu_friend_request` (`id`, `user_id`, `friend_id`, `date`) VALUES
(2, 1, 2, '2016-11-06'),
(3, 3, 2, '2016-11-07'),
(4, 4, 2, '2016-11-07');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_introduce`
--

CREATE TABLE `jianghu_introduce` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `text` text NOT NULL COMMENT '文字内容',
  `degree` int(11) NOT NULL COMMENT '度数',
  `time` datetime NOT NULL,
  `forwardnum` int(10) UNSIGNED NOT NULL COMMENT '转采量',
  `praisenum` int(10) UNSIGNED NOT NULL COMMENT '点赞量',
  `opposenum` int(10) UNSIGNED NOT NULL COMMENT '踩数',
  `collectnum` int(10) UNSIGNED NOT NULL COMMENT '收藏量',
  `isforward` tinyint(3) UNSIGNED DEFAULT '0' COMMENT '如果不是转载则为0，否则为转载的原始推荐的id',
  `commentnum` int(10) UNSIGNED NOT NULL,
  `forward_id` int(10) UNSIGNED NOT NULL COMMENT '若该推荐是转载的，保存转载记录的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐表';

--
-- 转存表中的数据 `jianghu_introduce`
--

INSERT INTO `jianghu_introduce` (`id`, `user_id`, `text`, `degree`, `time`, `forwardnum`, `praisenum`, `opposenum`, `collectnum`, `isforward`, `commentnum`, `forward_id`) VALUES
(7, 1, '这是he的推荐', 0, '2016-09-26 15:00:15', 0, 2, 0, 0, 0, 0, 0),
(8, 4, '这是huang的推荐', 0, '2016-09-26 15:00:29', 6, 11, 0, 0, 0, 0, 0),
(9, 3, '这是wang的推荐', 0, '2016-09-26 15:00:40', 2, 0, 0, 0, 0, 0, 0),
(10, 5, '这是li的推荐', 0, '2016-09-26 15:00:51', 0, 0, 0, 0, 0, 0, 0),
(11, 2, '这是chen的推荐', 0, '2016-09-26 15:01:14', 0, 0, 0, 0, 0, 0, 0),
(12, 1, 'he转发huang的评论', 0, '2016-09-26 15:05:39', 0, 0, 0, 0, 8, 0, 0),
(13, 2, 'chen转发huang的评论', 0, '2016-09-26 15:06:52', 0, 0, 0, 0, 8, 0, 0),
(14, 1, 'he转发了wang的推荐', 0, '2016-09-27 19:44:50', 0, 0, 0, 0, 9, 0, 0),
(15, 1, 'he转发了wang的推荐', 0, '2016-09-27 19:46:28', 0, 0, 0, 0, 9, 0, 0),
(16, 1, '123', 0, '2016-09-30 14:14:16', 0, 0, 0, 0, 8, 0, 0),
(17, 1, '123', 0, '2016-09-30 14:19:20', 0, 0, 0, 0, 8, 0, 0),
(18, 1, '123', 0, '2016-09-30 14:20:47', 0, 0, 0, 0, 8, 0, 0),
(19, 3, '这是wang的推荐', 0, '2016-10-01 19:35:23', 0, 22, 0, 0, 0, 0, 0),
(21, 1, 'he的二次转发', 0, '2016-10-01 19:42:31', 0, 10, 0, 0, 8, 0, 0),
(22, 3, '这是wang的推荐', 0, '2016-10-01 22:08:43', 0, 0, 0, 0, 0, 0, 0),
(23, 8, '这是jiao的推荐', 0, '2016-10-03 19:16:48', 0, 0, 0, 0, 0, 0, 0),
(24, 1, '这是he的推荐', 0, '2016-10-03 19:20:20', 0, 0, 0, 0, 0, 0, 0),
(25, 1, '这是he的', 1, '2016-10-14 15:09:35', 0, 0, 0, 0, 0, 0, 0),
(26, 1, '这是he的', 1, '2016-10-14 15:48:05', 13, 0, 0, 0, 0, 0, 0),
(27, 2, '123', 1, '2016-10-14 17:02:49', 0, 0, 0, 0, 26, 0, 0),
(28, 16, '123', 1, '2016-10-14 17:03:42', 0, 0, 0, 0, 26, 0, 0),
(29, 16, '123', 1, '2016-10-14 19:34:04', 0, 0, 0, 0, 26, 0, 0),
(30, 16, '123', 1, '2016-10-14 19:38:06', 0, 0, 0, 0, 26, 0, 0),
(31, 16, '123', 1, '2016-10-14 19:39:37', 0, 0, 0, 0, 26, 0, 0),
(32, 16, '123', 2, '2016-10-14 19:40:27', 0, 0, 0, 0, 26, 0, 0),
(33, 2, '123', 2, '2016-10-14 20:04:20', 0, 0, 0, 0, 26, 0, 0),
(34, 3, '123', 2, '2016-10-14 20:05:07', 0, 0, 0, 0, 26, 0, 0),
(35, 7, '123', 2, '2016-10-14 20:06:22', 0, 0, 0, 0, 26, 0, 0),
(36, 16, '123', 2, '2016-10-14 20:06:56', 0, 0, 0, 0, 26, 0, 0),
(37, 16, '123', 2, '2016-10-14 20:09:59', 0, 0, 0, 0, 26, 0, 0),
(38, 16, '123', 2, '2016-10-14 20:10:18', 0, 0, 0, 0, 26, 0, 0),
(39, 16, '123', 2, '2016-10-14 20:11:25', 0, 0, 0, 0, 26, 0, 0),
(40, 17, '这是A的', 1, '2016-10-14 22:37:43', 7, 2, 0, 0, 0, 0, 0),
(41, 18, '这是B的', 1, '2016-10-14 22:37:49', 0, 0, 0, 0, 0, 0, 0),
(42, 18, '123', 2, '2016-10-14 22:42:30', 0, 0, 0, 0, 0, 0, 0),
(43, 18, '123', 2, '2016-10-14 23:43:04', 0, 0, 0, 0, NULL, 0, 0),
(44, 18, '123', 2, '2016-10-14 23:44:19', 0, 0, 0, 0, NULL, 0, 0),
(45, 18, '123', 2, '2016-10-15 00:17:22', 0, 0, 0, 0, NULL, 0, 0),
(46, 18, '123', 2, '2016-10-15 00:25:20', 0, 0, 0, 0, NULL, 0, 0),
(47, 18, '123', 2, '2016-10-15 00:27:47', 0, 0, 0, 0, NULL, 0, 0),
(48, 18, '123', 2, '2016-10-15 00:28:01', 1, 0, 0, 0, NULL, 0, 0),
(49, 19, '123', 3, '2016-10-15 00:32:10', 0, 0, 0, 0, NULL, 0, 0),
(50, 17, '这是A的', 1, '2016-10-15 09:20:32', 0, 0, 0, 0, 0, 0, 0),
(51, 17, '这是A的', 2, '2016-10-15 09:29:36', 2, 0, 0, 0, 0, 0, 0),
(52, 18, '123', 3, '2016-10-15 09:33:44', 0, 0, 0, 0, NULL, 0, 0),
(53, 18, '123', 3, '2016-10-15 09:40:06', 0, 0, 0, 0, 51, 0, 0),
(54, 17, '这是A的', 2, '2016-10-15 09:41:39', 2, 0, 0, 0, 0, 0, 0),
(55, 18, '123', 3, '2016-10-15 09:42:18', 0, 0, 0, 0, 54, 0, 0),
(56, 18, '123', 2, '2016-10-15 09:49:22', 0, 0, 0, 0, 54, 0, 0),
(57, 17, '这是A的', 2, '2016-10-15 10:05:17', 1, 0, 0, 0, 0, 0, 0),
(58, 18, '123', 3, '2016-10-15 10:06:21', 0, 0, 0, 0, 57, 0, 0),
(59, 17, '这是A的', 3, '2016-10-15 10:08:30', 1, 0, 0, 0, 0, 0, 0),
(60, 18, '123', 2, '2016-10-15 10:09:08', 1, 0, 0, 0, 59, 0, 0),
(61, 19, '123', 2, '2016-10-15 10:10:55', 1, 0, 0, 0, 59, 0, 0),
(62, 20, '123', 3, '2016-10-15 10:12:02', 0, 0, 0, 0, 59, 0, 0),
(63, 1, '123', 1, '2016-10-15 15:57:40', 0, 0, 0, 0, 0, 0, 0),
(64, 1, '这是he的内容', 2, '2016-10-24 14:28:14', 4, 1, 0, 0, 0, 2, 0),
(65, 1, 'he转发了he的推荐', 2, '2016-10-24 15:35:29', 0, 0, 0, 0, 64, 0, 0),
(66, 1, 'he转发了he的推荐', 2, '2016-10-25 20:29:09', 0, 0, 0, 0, 64, 0, 0),
(67, 26, 'M转发了he的二度推荐', 3, '2016-10-25 20:34:27', 0, 0, 0, 0, 64, 0, 0),
(68, 17, 'A转发了M的3度推荐', 3, '2016-10-25 20:36:55', 0, 0, 0, 0, 64, 0, 0),
(69, 18, '这是B的内容', 1, '2016-10-25 21:17:42', 0, 0, 0, 0, 0, 0, 0),
(70, 19, '这是C的内容', 1, '2016-10-25 21:17:59', 1, 0, 0, 0, 0, 0, 0),
(71, 17, '转载1', 2, '2016-10-26 20:49:52', 1, 0, 0, 0, 70, 0, 0),
(72, 10, '转载2', 3, '2016-10-26 20:55:00', 2, 0, 0, 0, 70, 0, 0),
(73, 1, '转载3', 4, '2016-10-26 21:29:23', 0, 0, 0, 0, 70, 0, 0),
(74, 4, '转载4', 4, '2016-10-26 21:33:30', 1, 0, 0, 0, 70, 0, 0),
(75, 21, '转载5', 3, '2016-10-26 21:54:29', 1, 1, 0, 0, 70, 0, 0),
(76, 17, '转载6', 2, '2016-10-26 21:58:42', 0, 8, 0, 0, 70, 0, 0),
(77, 1, '这是he的内容', 1, '2016-10-30 23:35:46', 2, 0, 0, 0, 0, 0, 0),
(78, 2, '1', 2, '2016-10-30 23:49:32', 3, 0, 0, 0, 77, 0, 61),
(79, 17, '1', 2, '2016-10-30 23:54:50', 0, 0, 0, 0, 77, 0, 62),
(80, 17, '1', 3, '2016-10-30 23:55:13', 0, 0, 0, 0, 77, 0, 63),
(81, 19, '1', 3, '2016-10-31 00:06:47', 0, 0, 0, 0, 77, 0, 64),
(82, 19, '1', 3, '2016-10-31 00:09:42', 1, 0, 0, 0, 77, 0, 65),
(83, 25, '1', 4, '2016-10-31 00:10:48', 2, 0, 0, 0, 77, 0, 66),
(84, 2, '1', 3, '2016-10-31 00:11:41', 0, 0, 0, 0, 77, 0, 67),
(85, 2, '1', 2, '2016-10-31 00:20:25', 2, 0, 0, 0, 77, 0, 68),
(86, 3, '1', 3, '2016-10-31 00:21:33', 1, 0, 0, 0, 77, 0, 69),
(87, 3, '1', 2, '2016-10-31 00:22:29', 0, 0, 0, 0, 77, 0, 70),
(88, 19, '1', 3, '2016-10-31 00:23:07', 1, 0, 0, 0, 77, 0, 71),
(89, 19, '1', 3, '2016-10-31 00:25:03', 0, 0, 0, 0, 77, 0, 72),
(90, 1, '这是he的内容', 1, '2016-10-31 14:15:17', 1, 6, 2, 0, 0, 0, 0),
(91, 2, '1', 2, '2016-10-31 14:19:46', 1, 0, 0, 0, 90, 0, 74),
(92, 19, '1', 3, '2016-10-31 14:20:31', 1, 0, 0, 0, 90, 0, 75),
(93, 26, '1', 4, '2016-10-31 14:21:14', 1, 0, 0, 0, 90, 0, 76),
(94, 26, '1', 5, '2016-10-31 14:27:51', 1, 0, 0, 0, 90, 0, 77),
(95, 25, '1', 6, '2016-10-31 15:06:29', 6, 0, 0, 0, 90, 0, 78),
(96, 3, '1', 2, '2016-10-31 15:06:58', 0, 0, 0, 0, 90, 0, 79),
(97, 12, '1', 7, '2016-10-31 16:02:59', 0, 0, 0, 0, 90, 0, 80),
(98, 13, '1', 7, '2016-10-31 16:04:31', 0, 0, 0, 0, 90, 0, 81),
(99, 14, '1', 7, '2016-10-31 16:06:33', 0, 0, 0, 0, 90, 0, 82),
(100, 15, '1', 7, '2016-10-31 16:11:27', 0, 0, 0, 0, 90, 0, 83),
(101, 17, '1', 4, '2016-10-31 16:12:52', 0, 13, 4, 0, 90, 0, 84);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_introduce_domain`
--

CREATE TABLE `jianghu_introduce_domain` (
  `id` int(10) UNSIGNED NOT NULL,
  `introduce_id` int(10) UNSIGNED NOT NULL COMMENT '推荐id',
  `name` varchar(20) NOT NULL COMMENT '领域名'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐_领域表';

--
-- 转存表中的数据 `jianghu_introduce_domain`
--

INSERT INTO `jianghu_introduce_domain` (`id`, `introduce_id`, `name`) VALUES
(1, 22, '音'),
(2, 23, '音乐'),
(3, 25, '音乐'),
(4, 26, '音乐'),
(5, 40, '音乐'),
(6, 41, '音乐'),
(7, 50, '音乐'),
(8, 51, '音乐'),
(9, 54, '音乐'),
(10, 57, '音乐'),
(11, 59, '音乐'),
(12, 63, ''),
(13, 64, '运动'),
(14, 69, '运动'),
(15, 70, '运动'),
(16, 77, '运动'),
(17, 90, '运动');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_introduce_images`
--

CREATE TABLE `jianghu_introduce_images` (
  `id` int(10) UNSIGNED NOT NULL,
  `introduce_id` int(10) UNSIGNED NOT NULL,
  `imageurl` varchar(100) NOT NULL,
  `imagepath` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='推荐_图片表';

--
-- 转存表中的数据 `jianghu_introduce_images`
--

INSERT INTO `jianghu_introduce_images` (`id`, `introduce_id`, `imageurl`, `imagepath`) VALUES
(13, 7, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c77f86249.jpg', './Uploads/2016-09-26/57e8c77f86249.jpg'),
(14, 7, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c77f8af71.png', './Uploads/2016-09-26/57e8c77f8af71.png'),
(15, 8, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c78d26d96.jpg', './Uploads/2016-09-26/57e8c78d26d96.jpg'),
(16, 8, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c78d2b3c4.png', './Uploads/2016-09-26/57e8c78d2b3c4.png'),
(17, 9, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c798a2baf.jpg', './Uploads/2016-09-26/57e8c798a2baf.jpg'),
(18, 9, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c798a745d.png', './Uploads/2016-09-26/57e8c798a745d.png'),
(19, 10, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c7a3246c5.jpg', './Uploads/2016-09-26/57e8c7a3246c5.jpg'),
(20, 10, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c7a328e4b.png', './Uploads/2016-09-26/57e8c7a328e4b.png'),
(21, 11, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c7bad16cc.jpg', './Uploads/2016-09-26/57e8c7bad16cc.jpg'),
(22, 11, 'http://localhost/jianghu/Uploads/2016-09-26/57e8c7bad5e17.png', './Uploads/2016-09-26/57e8c7bad5e17.png'),
(23, 19, 'http://localhost/jianghu/Uploads/2016-10-01/57ef9f7c0772e.jpg', './Uploads/2016-10-01/57ef9f7c0772e.jpg'),
(24, 19, 'http://localhost/jianghu/Uploads/2016-10-01/57ef9f7c09847.jpg', './Uploads/2016-10-01/57ef9f7c09847.jpg'),
(27, 22, 'http://localhost/jianghu/Uploads/2016-10-01/57efc36be69be.jpg', './Uploads/2016-10-01/57efc36be69be.jpg'),
(28, 22, 'http://localhost/jianghu/Uploads/2016-10-01/57efc36be9bf8.jpg', './Uploads/2016-10-01/57efc36be9bf8.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_login_count`
--

CREATE TABLE `jianghu_login_count` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户登录统计表';

--
-- 转存表中的数据 `jianghu_login_count`
--

INSERT INTO `jianghu_login_count` (`id`, `user_id`, `date`) VALUES
(1, 13, '2016-10-31 00:00:00'),
(2, 14, '2016-10-31 00:00:00'),
(3, 15, '2016-10-31 00:00:00'),
(4, 17, '2016-10-31 00:00:00'),
(5, 1, '2016-10-31 00:00:00'),
(6, 2, '2016-10-31 00:00:00'),
(7, 1, '2016-11-01 00:00:00'),
(8, 2, '2016-11-01 00:00:00'),
(9, 20, '2016-11-01 00:00:00'),
(10, 20, '2016-11-01 00:00:00'),
(11, 1, '2016-11-02 00:00:00'),
(12, 1, '2016-11-06 00:00:00'),
(13, 2, '2016-11-07 00:00:00'),
(14, 3, '2016-11-07 00:00:00'),
(15, 4, '2016-11-07 00:00:00');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_praise`
--

CREATE TABLE `jianghu_praise` (
  `id` int(10) UNSIGNED NOT NULL,
  `introduce_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '点赞人的id',
  `time` datetime NOT NULL,
  `owner_id` int(10) UNSIGNED NOT NULL COMMENT '推荐所有者的id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='点赞表';

--
-- 转存表中的数据 `jianghu_praise`
--

INSERT INTO `jianghu_praise` (`id`, `introduce_id`, `user_id`, `time`, `owner_id`) VALUES
(1, 8, 1, '2016-09-26 15:21:55', 0),
(2, 8, 1, '2016-09-27 19:03:48', 0),
(3, 8, 1, '2016-09-27 19:05:52', 0),
(4, 8, 1, '2016-09-27 19:06:33', 0),
(6, 8, 1, '2016-09-27 19:09:16', 0),
(7, 7, 3, '2016-09-28 22:55:00', 0),
(8, 7, 2, '2016-09-28 22:55:10', 0),
(9, 8, 2, '2016-09-29 09:19:02', 4),
(10, 8, 3, '2016-09-29 09:40:14', 4),
(11, 8, 1, '2016-09-29 13:00:59', 4),
(12, 8, 1, '2016-09-29 13:13:24', 4),
(13, 8, 1, '2016-09-29 13:17:04', 4),
(14, 8, 1, '2016-09-29 13:28:59', 4),
(15, 19, 11, '2016-10-11 20:16:35', 3),
(16, 19, 11, '2016-10-11 20:18:36', 3),
(17, 19, 11, '2016-10-11 20:20:13', 3),
(18, 19, 11, '2016-10-11 23:08:44', 3),
(19, 19, 11, '2016-10-11 23:10:27', 3),
(20, 19, 11, '2016-10-11 23:14:24', 3),
(21, 19, 11, '2016-10-11 23:18:06', 3),
(22, 19, 11, '2016-10-11 23:20:33', 3),
(23, 19, 11, '2016-10-11 23:26:02', 3),
(24, 19, 11, '2016-10-11 23:26:44', 3),
(25, 19, 11, '2016-10-11 23:28:23', 3),
(26, 19, 11, '2016-10-11 23:29:11', 3),
(27, 19, 11, '2016-10-11 23:30:34', 3),
(28, 19, 11, '2016-10-11 23:31:40', 3),
(29, 19, 11, '2016-10-11 23:32:20', 3),
(30, 19, 11, '2016-10-11 23:33:55', 3),
(31, 19, 11, '2016-10-11 23:34:31', 3),
(32, 19, 12, '2016-10-11 23:34:53', 3),
(33, 19, 12, '2016-10-11 23:36:42', 3),
(34, 19, 12, '2016-10-11 23:40:01', 3),
(35, 19, 13, '2016-10-11 23:45:32', 3),
(36, 19, 13, '2016-10-11 23:48:45', 3),
(37, 21, 13, '2016-10-12 00:08:10', 1),
(38, 21, 13, '2016-10-12 00:11:09', 1),
(39, 21, 13, '2016-10-12 00:14:21', 1),
(40, 21, 13, '2016-10-12 00:14:34', 1),
(41, 21, 14, '2016-10-12 00:15:32', 1),
(42, 21, 14, '2016-10-12 00:16:38', 1),
(43, 21, 1, '2016-10-12 00:19:18', 1),
(44, 21, 1, '2016-10-12 00:22:33', 1),
(45, 21, 1, '2016-10-12 00:23:06', 1),
(46, 21, 2016, '2016-10-12 00:28:12', 1),
(47, 21, 2, '2016-10-12 00:29:35', 1),
(48, 21, 2, '2016-10-12 00:29:44', 1),
(49, 40, 1, '2016-10-15 00:30:05', 17),
(50, 40, 1, '2016-10-15 09:27:24', 17),
(51, 64, 1, '2016-10-24 15:23:15', 1),
(52, 75, 1, '2016-10-26 23:31:57', 21),
(53, 76, 1, '2016-10-26 23:37:28', 17),
(54, 76, 1, '2016-10-26 23:50:49', 17),
(55, 76, 1, '2016-10-30 14:24:35', 17),
(56, 76, 1, '2016-10-30 14:26:03', 17),
(57, 76, 1, '2016-10-30 14:29:57', 17),
(58, 76, 1, '2016-10-31 22:08:22', 17),
(59, 76, 1, '2016-10-31 22:08:36', 17),
(60, 76, 2, '2016-10-31 22:08:47', 17),
(61, 101, 2, '2016-11-01 21:10:05', 17),
(62, 101, 2, '2016-11-01 21:20:15', 17),
(63, 101, 2, '2016-11-01 21:27:56', 17);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_tag`
--

CREATE TABLE `jianghu_tag` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='标签表';

--
-- 转存表中的数据 `jianghu_tag`
--

INSERT INTO `jianghu_tag` (`id`, `name`) VALUES
(2, 'music');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_token`
--

CREATE TABLE `jianghu_token` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
  `time` datetime NOT NULL COMMENT 'token生成时间',
  `state` tinyint(4) NOT NULL COMMENT 'token状态：1为过期，0为正常',
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='token表，用于用户登录验证';

--
-- 转存表中的数据 `jianghu_token`
--

INSERT INTO `jianghu_token` (`id`, `user_id`, `time`, `state`, `token`) VALUES
(1, 4, '2016-10-16 13:33:55', 0, 'c4ca4238a0b923820dcc509a6f75849b'),
(2, 4, '2016-10-16 13:36:13', 0, 'c4ca4238a0b923820dcc509a6f75849b'),
(3, 4, '2016-10-16 13:40:29', 0, 'c4ca4238a0b923820dcc509a6f75849b'),
(4, 4, '2016-10-16 13:43:28', 0, 'a87ff679a2f3e71d9181a67b7542122c'),
(5, 4, '2016-10-16 13:45:06', 0, '6523205cd074195bd9252252b0eab96e'),
(6, 4, '2016-10-16 13:58:29', 0, 'b7fea5e317d8336b1e458aa7dfafc7c7'),
(7, 25, '2016-10-16 23:05:35', 1, '65c9cf42415f8d1300e5ae8e60aa3b8b'),
(8, 25, '2016-10-16 23:13:52', 1, '25e00aec7fae6f93304b085f97b24417'),
(9, 25, '2016-10-21 15:46:49', 0, 'e42d4e872a937802b4665a4f33edfdc0'),
(10, 1, '2016-10-24 14:26:30', 0, '2ea7c6999b86afb0f34bc274e78377fe'),
(11, 26, '2016-10-25 20:32:46', 0, 'c363969593e6a5f5877f57ba39b6c6b8'),
(12, 17, '2016-10-25 20:35:40', 0, '153e5dd29b32336314b501add8c66c22'),
(13, 18, '2016-10-25 21:16:10', 0, 'efce7400634541412d644743d6ac25f6'),
(14, 10, '2016-10-26 20:52:31', 0, '4fc38308296c7e50a6ec639839324839'),
(15, 4, '2016-10-26 21:32:25', 0, 'ad7dce71ebc6555dbd24bb387211f6f0'),
(16, 21, '2016-10-26 21:49:18', 0, 'f0eac881e871f806c946703600ca3c5e'),
(17, 1, '2016-10-30 14:22:07', 0, '0ba4d1d0bc01d2a5b3abdbc36e512e12'),
(19, 20, '2016-11-01 21:38:21', 0, 'ebf55141712bde19cfeffcd95798a195');

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_unread_praise`
--

CREATE TABLE `jianghu_unread_praise` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `praise_id` int(10) UNSIGNED NOT NULL COMMENT '点赞id',
  `time` datetime NOT NULL COMMENT '消息时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='未读消息-点赞表';

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_user`
--

CREATE TABLE `jianghu_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `sex` tinyint(3) UNSIGNED NOT NULL DEFAULT '0' COMMENT '性别：0为男，1为女',
  `phonenum` varchar(30) DEFAULT NULL,
  `addr` text COMMENT '地址',
  `praisenum` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '口碑',
  `temp_praisenum` tinyint(3) UNSIGNED NOT NULL COMMENT '临时存放来自江湖的点赞，当满10的时候praisenum就加一，这个就清零',
  `birthday` date DEFAULT NULL,
  `description` text COMMENT '个人简介',
  `faceurl` varchar(100) DEFAULT NULL COMMENT '头像url',
  `facepath` varchar(100) DEFAULT NULL COMMENT '头像路径',
  `unreadnum` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '未读消息数',
  `jointime` date DEFAULT NULL COMMENT '注册时间',
  `email` varchar(20) DEFAULT NULL,
  `isban` tinyint(4) NOT NULL DEFAULT '0' COMMENT '若用户被禁用则为1，否则为0',
  `allpraise` int(10) UNSIGNED NOT NULL COMMENT '总点赞',
  `alloppose` int(10) UNSIGNED NOT NULL COMMENT '总踩量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `jianghu_user`
--

INSERT INTO `jianghu_user` (`id`, `username`, `password`, `sex`, `phonenum`, `addr`, `praisenum`, `temp_praisenum`, `birthday`, `description`, `faceurl`, `facepath`, `unreadnum`, `jointime`, `email`, `isban`, `allpraise`, `alloppose`) VALUES
(1, 'he', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '广州市', 148, 0, '0000-00-00', '', 'http://localhost/jianghu/Uploads/2016-09-26/57e8783210ebf.jpg', './Uploads/2016-09-26/57e8783210ebf.jpg', 39, NULL, NULL, 0, 3, 1),
(2, 'chen', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '广州市', 34, 0, '0000-00-00', '', 'http://localhost/jianghu/Uploads/2016-09-26/57e8bd94a0caf.jpg', './Uploads/2016-09-26/57e8bd94a0caf.jpg', 13, NULL, NULL, 0, 0, 0),
(3, 'wang', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '广州市', 28, 0, '0000-00-00', '', 'http://localhost/jianghu/Uploads/2016-09-26/57e8bd9ad534b.jpg', './Uploads/2016-09-26/57e8bd9ad534b.jpg', 28, NULL, NULL, 0, 0, 0),
(4, 'huang', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '广州市', 23, 0, '0000-00-00', '', 'http://localhost/jianghu/Uploads/2016-09-26/57e8bda1309a3.jpg', './Uploads/2016-09-26/57e8bda1309a3.jpg', 5, NULL, NULL, 0, 0, 0),
(5, 'li', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '广州市', 1, 0, '0000-00-00', '', 'http://localhost/jianghu/Uploads/2016-09-26/57e8bda72d51d.jpg', './Uploads/2016-09-26/57e8bda72d51d.jpg', 0, NULL, NULL, 0, 0, 0),
(6, 'hong', '202cb962ac59075b964b07152d234b70', 1, '', '', 0, 0, '0000-00-00', '', '', '', 0, NULL, NULL, 0, 0, 0),
(7, 'ming', 'd41d8cd98f00b204e9800998ecf8427e', 1, '', '', 0, 0, '0000-00-00', '', '', '', 0, NULL, NULL, 0, 0, 0),
(8, 'jiao', '202cb962ac59075b964b07152d234b70', 1, '', '', 0, 0, '0000-00-00', '', '', '', 0, NULL, NULL, 0, 0, 0),
(10, '1', '202cb962ac59075b964b07152d234b70', 0, '13202063304', '不说', 6, 0, NULL, NULL, NULL, NULL, 2, '2016-10-11', NULL, 0, 0, 0),
(11, '2', '202cb962ac59075b964b07152d234b70', 0, '13202063304', '不说', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(12, '3', '202cb962ac59075b964b07152d234b70', 0, '13202063304', '不说', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(13, '4', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(14, '5', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(15, '6', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(16, '7', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-11', NULL, 0, 0, 0),
(17, 'A', '202cb962ac59075b964b07152d234b70', 1, '', '', 38, 0, NULL, NULL, NULL, NULL, 24, '2016-10-14', NULL, 0, 3, 3),
(18, 'B', '202cb962ac59075b964b07152d234b70', 1, '', '', 6, 0, NULL, NULL, NULL, NULL, 3, '2016-10-14', NULL, 0, 0, 0),
(19, 'C', '202cb962ac59075b964b07152d234b70', 1, '', '', 29, 0, NULL, NULL, NULL, NULL, 5, '2016-10-14', NULL, 0, 0, 0),
(20, 'D', '202cb962ac59075b964b07152d234b70', 1, '', '', 1, 0, NULL, NULL, NULL, NULL, 0, '2016-10-14', NULL, 0, 1, 0),
(21, 'F', '202cb962ac59075b964b07152d234b70', 1, '', '', 4, 0, NULL, NULL, NULL, NULL, 2, '2016-10-14', NULL, 0, 0, 0),
(22, 'H', '202cb962ac59075b964b07152d234b70', 1, '13202063303', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-16', NULL, 0, 0, 0),
(23, 'J', '202cb962ac59075b964b07152d234b70', 1, '1320206', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-16', NULL, 0, 0, 0),
(24, 'K', '202cb962ac59075b964b07152d234b70', 1, '1320206', '', 0, 0, NULL, NULL, NULL, NULL, 0, '2016-10-16', NULL, 0, 0, 0),
(25, 'L', '202cb962ac59075b964b07152d234b70', 1, '1320206', '', 4, 0, NULL, NULL, NULL, NULL, 8, '2016-10-16', NULL, 0, 0, 0),
(26, 'M', '202cb962ac59075b964b07152d234b70', 1, '1320206', '', 24, 0, NULL, NULL, NULL, NULL, 2, '2016-10-16', NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `jianghu_user_domain`
--

CREATE TABLE `jianghu_user_domain` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='领域表';

--
-- 转存表中的数据 `jianghu_user_domain`
--

INSERT INTO `jianghu_user_domain` (`id`, `name`, `user_id`) VALUES
(3, 'music', 8),
(4, 'dancing', 8),
(5, 'sport', 8),
(6, 'music', 9),
(7, '', 9),
(8, '', 9),
(9, 'music', 10),
(10, '', 10),
(11, '', 10),
(12, 'music', 11),
(13, '', 11),
(14, '', 11),
(15, 'music', 12),
(16, '', 12),
(17, '', 12),
(18, '', 13),
(19, '', 13),
(20, '', 13),
(21, '', 14),
(22, '', 14),
(23, '', 14),
(24, '', 15),
(25, '', 15),
(26, '', 15),
(27, '', 16),
(28, '', 16),
(29, '', 16),
(30, '', 17),
(31, '', 17),
(32, '', 17),
(33, '', 18),
(34, '', 18),
(35, '', 18),
(36, '', 19),
(37, '', 19),
(38, '', 19),
(39, '', 20),
(40, '', 20),
(41, '', 20),
(42, '', 21),
(43, '', 21),
(44, '', 21),
(45, '音乐', 25),
(46, '音乐  ', 26),
(47, '体育', 26),
(48, '技术', 26);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jianghu_admin`
--
ALTER TABLE `jianghu_admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_business`
--
ALTER TABLE `jianghu_business`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_comment`
--
ALTER TABLE `jianghu_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_daily_num`
--
ALTER TABLE `jianghu_daily_num`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_forward`
--
ALTER TABLE `jianghu_forward`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_friend`
--
ALTER TABLE `jianghu_friend`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_friend_request`
--
ALTER TABLE `jianghu_friend_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_introduce`
--
ALTER TABLE `jianghu_introduce`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_introduce_domain`
--
ALTER TABLE `jianghu_introduce_domain`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_introduce_images`
--
ALTER TABLE `jianghu_introduce_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_login_count`
--
ALTER TABLE `jianghu_login_count`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_praise`
--
ALTER TABLE `jianghu_praise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_tag`
--
ALTER TABLE `jianghu_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_token`
--
ALTER TABLE `jianghu_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_unread_praise`
--
ALTER TABLE `jianghu_unread_praise`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_user`
--
ALTER TABLE `jianghu_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jianghu_user_domain`
--
ALTER TABLE `jianghu_user_domain`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `jianghu_admin`
--
ALTER TABLE `jianghu_admin`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `jianghu_business`
--
ALTER TABLE `jianghu_business`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `jianghu_comment`
--
ALTER TABLE `jianghu_comment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `jianghu_daily_num`
--
ALTER TABLE `jianghu_daily_num`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- 使用表AUTO_INCREMENT `jianghu_forward`
--
ALTER TABLE `jianghu_forward`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- 使用表AUTO_INCREMENT `jianghu_friend`
--
ALTER TABLE `jianghu_friend`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- 使用表AUTO_INCREMENT `jianghu_friend_request`
--
ALTER TABLE `jianghu_friend_request`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `jianghu_introduce`
--
ALTER TABLE `jianghu_introduce`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;
--
-- 使用表AUTO_INCREMENT `jianghu_introduce_domain`
--
ALTER TABLE `jianghu_introduce_domain`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `jianghu_introduce_images`
--
ALTER TABLE `jianghu_introduce_images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- 使用表AUTO_INCREMENT `jianghu_login_count`
--
ALTER TABLE `jianghu_login_count`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `jianghu_praise`
--
ALTER TABLE `jianghu_praise`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- 使用表AUTO_INCREMENT `jianghu_tag`
--
ALTER TABLE `jianghu_tag`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `jianghu_token`
--
ALTER TABLE `jianghu_token`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- 使用表AUTO_INCREMENT `jianghu_unread_praise`
--
ALTER TABLE `jianghu_unread_praise`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jianghu_user`
--
ALTER TABLE `jianghu_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- 使用表AUTO_INCREMENT `jianghu_user_domain`
--
ALTER TABLE `jianghu_user_domain`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
