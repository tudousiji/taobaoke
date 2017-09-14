-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2017-09-14 09:15:08
-- 服务器版本： 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taobaoke`
--

-- --------------------------------------------------------

--
-- 表的结构 `goods_list`
--

CREATE TABLE `goods_list` (
  `id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `itemId` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `zkFinalPriceWap` double NOT NULL DEFAULT '0',
  `biz30Day` int(11) NOT NULL DEFAULT '0',
  `couponStartFee` int(11) NOT NULL DEFAULT '0',
  `clickUrl` varchar(600) DEFAULT NULL,
  `shareUrl` varchar(600) DEFAULT NULL,
  `pictUrl` varchar(600) DEFAULT NULL,
  `couponKey` varchar(600) DEFAULT NULL,
  `couponAmount` int(11) NOT NULL DEFAULT '0',
  `couponSendCount` int(11) NOT NULL DEFAULT '0',
  `couponTotalCount` int(11) NOT NULL DEFAULT '0',
  `couponEffectiveStartTime` int(11) NOT NULL,
  `couponEffectiveEndTime` int(11) NOT NULL,
  `provcity` varchar(30) NOT NULL,
  `nick` varchar(30) NOT NULL,
  `userType` tinyint(1) NOT NULL DEFAULT '0',
  `reason` json DEFAULT NULL,
  `commentList` json DEFAULT NULL,
  `askeverybodyList` json DEFAULT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `keywords`
--

CREATE TABLE `keywords` (
  `id` int(11) NOT NULL,
  `keyword` varchar(255) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `keywords`
--

INSERT INTO `keywords` (`id`, `keyword`, `update_time`) VALUES
(1, '手机', 1503649930),
(2, '手机2', 1503650527),
(3, '手机10', 1503892355),
(4, '电脑', 1504064102),
(5, '手表', 1504064215),
(6, '李宁', 1504064256),
(7, '安踏', 1504064289),
(8, '阿里', 1504064518),
(9, '菊花', 1504064747),
(10, '梅花', 1504064892),
(11, '123', 1505369432);

-- --------------------------------------------------------

--
-- 表的结构 `keywords_details`
--

CREATE TABLE `keywords_details` (
  `id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL,
  `json` json NOT NULL,
  `page` int(11) NOT NULL,
  `page_size` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `proxy_ip`
--

CREATE TABLE `proxy_ip` (
  `id` int(11) NOT NULL,
  `ip` int(11) NOT NULL,
  `port` int(11) NOT NULL,
  `http_type` tinyint(1) NOT NULL COMMENT '0是http,1是https',
  `status` tinyint(1) NOT NULL COMMENT '0是正确,1是待检查,2是错误没法用的',
  `log` text,
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='注意数据库ip在不同平台转换的问题';

--
-- 转存表中的数据 `proxy_ip`
--

INSERT INTO `proxy_ip` (`id`, `ip`, `port`, `http_type`, `status`, `log`, `update_time`) VALUES
(1, 342, 234, 1, 2, 'Could not resolve proxy: 342', 1504062155);

-- --------------------------------------------------------

--
-- 表的结构 `tbk_token`
--

CREATE TABLE `tbk_token` (
  `id` int(11) NOT NULL,
  `proxy_id` int(11) DEFAULT NULL,
  `tk` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0是正常,1是失效',
  `update_time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `tbk_token`
--

INSERT INTO `tbk_token` (`id`, `proxy_id`, `tk`, `status`, `update_time`) VALUES
(1, NULL, '_m_h5_tk=819c33227279aee8780dffeb585e1675_1505096128090;_m_h5_tk_enc=c0c09c3d5c60702c61b39bed0f586d61', 1, 1505096156),
(2, NULL, '_m_h5_tk=79dfd050f279117f78d6b16f871fde4d_1505099636729;_m_h5_tk_enc=35d6421a63fe70c9b998bc58280632d7', 1, 1505100283),
(3, NULL, '_m_h5_tk=0de5abecef1a4498dfcc9fe66d045f85_1505102923892;_m_h5_tk_enc=4330cffeadf4616cc1509bcd73afbc23', 1, 1505111303),
(4, NULL, '_m_h5_tk=b3f710f2572df26272e1e438664066d7_1505114663168;_m_h5_tk_enc=fdab8816e09fc28f1c3da791d32f7793', 1, 1505119531),
(5, NULL, '_m_h5_tk=23a79ee1037805db4f0f311530775871_1505122051201;_m_h5_tk_enc=9473bb0a1a87333e899c42f687871129', 1, 1505357418),
(6, NULL, '_m_h5_tk=8846724a441593c8829bc342fd25885c_1505360533306;_m_h5_tk_enc=004ce2f5cf64bb67c960e5ffac46e46e', 1, 1505367361),
(7, NULL, '_m_h5_tk=8f0a7498fc391661a99790d557dd7022_1505370836259;_m_h5_tk_enc=616dcc113a019a4e47b94ba9397342f9', 1, 1505380042),
(8, NULL, '_m_h5_tk=7c0e5ce32d5a499c8492beeb6f25edb2_1505383036515;_m_h5_tk_enc=630ef786de99f367658eaff02e4c4426', 0, 1505380042);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goods_list`
--
ALTER TABLE `goods_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword_id` (`keyword_id`),
  ADD KEY `itemId` (`itemId`);

--
-- Indexes for table `keywords`
--
ALTER TABLE `keywords`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword` (`keyword`),
  ADD KEY `time` (`update_time`),
  ADD KEY `keyword_2` (`keyword`);

--
-- Indexes for table `keywords_details`
--
ALTER TABLE `keywords_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyword_id` (`keyword_id`);

--
-- Indexes for table `proxy_ip`
--
ALTER TABLE `proxy_ip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbk_token`
--
ALTER TABLE `tbk_token`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proxy_id` (`proxy_id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `goods_list`
--
ALTER TABLE `goods_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `keywords`
--
ALTER TABLE `keywords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `keywords_details`
--
ALTER TABLE `keywords_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tbk_token`
--
ALTER TABLE `tbk_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
