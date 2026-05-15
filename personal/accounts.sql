-- phpMyAdmin SQL Dump
-- version 5.0.4deb2+deb11u1
-- https://www.phpmyadmin.net/
--
-- Хост: server.MYSQL18
-- Час створення: Трв 15 2026 р., 12:25
-- Версія сервера: 10.5.29-MariaDB-0+deb11u1
-- Версія PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `user43104`
--

-- --------------------------------------------------------

--
-- Структура таблиці `accounts`
--

CREATE TABLE `accounts` (
  `pID` int(11) NOT NULL,
  `Name` varchar(24) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `pKey` varchar(64) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT 'NULL',
  `pLevel` int(11) NOT NULL DEFAULT 100,
  `pJail` int(11) NOT NULL DEFAULT 0,
  `pvIp` varchar(17) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT 'no',
  `youtube` int(11) NOT NULL DEFAULT 0,
  `pIpReg` varchar(17) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL,
  `pDataReg` varchar(128) CHARACTER SET cp1250 COLLATE cp1250_bin NOT NULL,
  `pKeyip` varchar(5) NOT NULL DEFAULT '-',
  `pHP` float NOT NULL DEFAULT 100,
  `house` int(11) NOT NULL DEFAULT 0,
  `tempkey` int(11) NOT NULL DEFAULT 0,
  `bussiness` int(11) NOT NULL DEFAULT 0,
  `hotelid` int(11) NOT NULL DEFAULT 0,
  `airport` int(11) NOT NULL DEFAULT 0,
  `hotelroom` int(11) NOT NULL DEFAULT 0,
  `plane` int(11) NOT NULL DEFAULT -1,
  `pMats` int(11) NOT NULL DEFAULT 0,
  `pSex` int(11) NOT NULL,
  `pArrested` int(11) NOT NULL DEFAULT 0,
  `mute` int(11) NOT NULL DEFAULT 0,
  `pCrimes` int(11) NOT NULL DEFAULT 0,
  `pExp` int(11) NOT NULL DEFAULT 0,
  `pCash` int(11) NOT NULL DEFAULT 20000000,
  `pJailTime` int(11) NOT NULL DEFAULT 0,
  `pDrugs` int(11) NOT NULL DEFAULT 0,
  `pLeader` int(11) NOT NULL DEFAULT 0,
  `pMember` int(11) NOT NULL DEFAULT 0,
  `pRank` int(11) NOT NULL DEFAULT 0,
  `pJob` int(11) NOT NULL DEFAULT 0,
  `pModel` int(11) NOT NULL DEFAULT 0,
  `pPhone` int(11) NOT NULL DEFAULT 0,
  `licenses` varchar(64) NOT NULL DEFAULT '0,0,0,0',
  `pZakonp` int(11) NOT NULL DEFAULT 0,
  `pAddiction` int(11) NOT NULL DEFAULT 0,
  `pWarns` int(11) NOT NULL DEFAULT 0,
  `warntime` int(11) NOT NULL DEFAULT 0,
  `pFuel` int(11) NOT NULL DEFAULT 0,
  `pMarried` varchar(25) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '-',
  `pDrug` varchar(128) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '-',
  `pBank` int(11) NOT NULL DEFAULT 0,
  `pMobile` int(11) NOT NULL DEFAULT 0,
  `pSearch` int(11) NOT NULL DEFAULT 0,
  `pWeapons` varchar(64) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0',
  `pAmmos` varchar(64) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0',
  `pGunSkills` varchar(32) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '0, 0, 0, 0, 0, 0',
  `pOnline` varchar(128) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '0',
  `pEmail` varchar(64) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT 'no',
  `Skin` int(5) NOT NULL DEFAULT 1,
  `pPlayTime_one` int(11) NOT NULL DEFAULT 0,
  `pPlayTime_two` int(11) NOT NULL DEFAULT 0,
  `pAccusedof` varchar(50) NOT NULL DEFAULT 'Нет',
  `pVictim` varchar(24) NOT NULL DEFAULT 'Нет',
  `kill_capture` int(11) NOT NULL DEFAULT 0,
  `pHospital` int(3) NOT NULL DEFAULT 0,
  `pRod` int(11) NOT NULL DEFAULT 0,
  `pRopes` int(11) NOT NULL DEFAULT 0,
  `pWorms` int(11) NOT NULL DEFAULT 0,
  `pFish` float NOT NULL DEFAULT 0,
  `family` int(11) NOT NULL DEFAULT 0,
  `progress` int(11) NOT NULL DEFAULT 0,
  `spawn` int(3) NOT NULL DEFAULT 0,
  `salary` int(11) NOT NULL DEFAULT 0,
  `book` int(3) NOT NULL DEFAULT 0,
  `watch` int(3) NOT NULL DEFAULT 0,
  `phonenumber` varchar(256) NOT NULL DEFAULT '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0',
  `phonename` varchar(1024) NOT NULL DEFAULT ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет',
  `MedCard` int(4) NOT NULL DEFAULT 0,
  `MedHeal` int(11) NOT NULL DEFAULT 0,
  `Advert` int(11) NOT NULL DEFAULT 0,
  `ArmSkin` int(3) NOT NULL DEFAULT 0,
  `FracDuty` int(4) NOT NULL DEFAULT 0,
  `Settings` varchar(48) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1|1|0|0|0|0|0|0|0|1',
  `online_status` int(11) NOT NULL DEFAULT 0,
  `GoogleCode` varchar(50) DEFAULT NULL,
  `SecretCode` varchar(50) DEFAULT NULL,
  `bizz_work` int(11) NOT NULL DEFAULT 0,
  `bizz_cash` int(11) NOT NULL DEFAULT 0,
  `bizz_lcash` int(11) NOT NULL DEFAULT 0,
  `bizz_status` int(11) NOT NULL DEFAULT 0,
  `pGolos` int(11) NOT NULL DEFAULT 0,
  `pBlago` int(11) NOT NULL DEFAULT 0,
  `pVips` int(11) NOT NULL DEFAULT 0,
  `donatemoney` int(30) NOT NULL DEFAULT 0,
  `fwarn` int(11) NOT NULL DEFAULT 0,
  `pDisease_0` int(11) NOT NULL DEFAULT 0,
  `pDisease_1` int(11) NOT NULL DEFAULT 0,
  `pSatiety` int(11) NOT NULL DEFAULT 100,
  `pKills` int(11) NOT NULL DEFAULT 0,
  `pWinArea` int(11) NOT NULL DEFAULT 0,
  `pFamRank` int(11) NOT NULL DEFAULT 0,
  `pDSatiety` int(11) NOT NULL DEFAULT 0,
  `pDDisease` int(11) NOT NULL DEFAULT 0,
  `pBox` int(11) NOT NULL DEFAULT 0,
  `pSnow` float NOT NULL DEFAULT 0,
  `pMedKit` int(11) NOT NULL DEFAULT 0,
  `pMPromo` int(11) NOT NULL DEFAULT 0,
  `pAdmMSG` int(11) NOT NULL DEFAULT 0,
  `pAdmKL` int(11) NOT NULL DEFAULT 0,
  `pMask` int(11) NOT NULL DEFAULT 0,
  `pGoogle` int(11) NOT NULL DEFAULT 0,
  `homesell` int(11) NOT NULL DEFAULT 0,
  `bizzsell` int(11) NOT NULL DEFAULT 0,
  `hotelsell` int(11) NOT NULL DEFAULT 0,
  `airsell` int(11) NOT NULL DEFAULT 0,
  `roomsell` int(11) NOT NULL DEFAULT 0,
  `pVipadd` int(11) NOT NULL DEFAULT 0,
  `lotteryfree` int(11) NOT NULL DEFAULT 0,
  `GunLic` int(11) NOT NULL DEFAULT 0,
  `drunginv` int(11) NOT NULL DEFAULT 0,
  `pHelper` int(11) NOT NULL DEFAULT 0,
  `pAsk` int(11) NOT NULL DEFAULT 0,
  `pSlotItem` varchar(64) NOT NULL DEFAULT '0|0|0|0|0|0|0|0',
  `pSlotItem_Use` varchar(64) NOT NULL DEFAULT '1|1|1|1|1|1|1|1',
  `pInstr` int(5) NOT NULL DEFAULT 0,
  `fraction_date` varchar(64) NOT NULL DEFAULT '0/0/0',
  `pAskmute` int(11) NOT NULL DEFAULT 0,
  `pJemmy` int(11) NOT NULL DEFAULT 0,
  `EmailStatus` int(1) NOT NULL DEFAULT 2,
  `pFMute` int(4) NOT NULL DEFAULT 0,
  `theftSkill` int(3) NOT NULL DEFAULT 0,
  `theftExp` int(5) NOT NULL DEFAULT 0,
  `theftTime` int(11) NOT NULL DEFAULT 0,
  `theftHome` int(11) NOT NULL DEFAULT 0,
  `pDonateBank` int(1) NOT NULL DEFAULT 0,
  `pDonateBh` int(1) NOT NULL DEFAULT 0,
  `pVipTime` int(11) NOT NULL DEFAULT 0,
  `pVipName` int(11) NOT NULL DEFAULT 0,
  `pBoomBox` int(11) NOT NULL DEFAULT 0,
  `Akum` int(12) NOT NULL DEFAULT 0,
  `prefix` varchar(64) NOT NULL,
  `spawnData` varchar(64) NOT NULL DEFAULT '',
  `PlayerInfo[playerid` text NOT NULL COMMENT 'PlayerInfoplayerid'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COLLATE=cp1251_general_ci;

--
-- Дамп даних таблиці `accounts`
--

INSERT INTO `accounts` (`pID`, `Name`, `pKey`, `pLevel`, `pJail`, `pvIp`, `youtube`, `pIpReg`, `pDataReg`, `pKeyip`, `pHP`, `house`, `tempkey`, `bussiness`, `hotelid`, `airport`, `hotelroom`, `plane`, `pMats`, `pSex`, `pArrested`, `mute`, `pCrimes`, `pExp`, `pCash`, `pJailTime`, `pDrugs`, `pLeader`, `pMember`, `pRank`, `pJob`, `pModel`, `pPhone`, `licenses`, `pZakonp`, `pAddiction`, `pWarns`, `warntime`, `pFuel`, `pMarried`, `pDrug`, `pBank`, `pMobile`, `pSearch`, `pWeapons`, `pAmmos`, `pGunSkills`, `pOnline`, `pEmail`, `Skin`, `pPlayTime_one`, `pPlayTime_two`, `pAccusedof`, `pVictim`, `kill_capture`, `pHospital`, `pRod`, `pRopes`, `pWorms`, `pFish`, `family`, `progress`, `spawn`, `salary`, `book`, `watch`, `phonenumber`, `phonename`, `MedCard`, `MedHeal`, `Advert`, `ArmSkin`, `FracDuty`, `Settings`, `online_status`, `GoogleCode`, `SecretCode`, `bizz_work`, `bizz_cash`, `bizz_lcash`, `bizz_status`, `pGolos`, `pBlago`, `pVips`, `donatemoney`, `fwarn`, `pDisease_0`, `pDisease_1`, `pSatiety`, `pKills`, `pWinArea`, `pFamRank`, `pDSatiety`, `pDDisease`, `pBox`, `pSnow`, `pMedKit`, `pMPromo`, `pAdmMSG`, `pAdmKL`, `pMask`, `pGoogle`, `homesell`, `bizzsell`, `hotelsell`, `airsell`, `roomsell`, `pVipadd`, `lotteryfree`, `GunLic`, `drunginv`, `pHelper`, `pAsk`, `pSlotItem`, `pSlotItem_Use`, `pInstr`, `fraction_date`, `pAskmute`, `pJemmy`, `EmailStatus`, `pFMute`, `theftSkill`, `theftExp`, `theftTime`, `theftHome`, `pDonateBank`, `pDonateBh`, `pVipTime`, `pVipName`, `pBoomBox`, `Akum`, `prefix`, `spawnData`, `PlayerInfo[playerid`) VALUES
(10, 'Yarik_Samyrai', '5e3c8b269178ba2c4e413fccd1dca295', 1, 0, '93.170.66.71', 0, '93.170.66.71', '06/05/2026', '-', 100, 0, 0, 0, 0, 0, 0, -1, 0, 1, 0, 0, 0, 0, 2000, 0, 0, 0, 0, 0, 0, 0, 0, '0,0,0,0', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '06-05-2026', 'no', 78, 3, 2, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 99, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '50.00,50.00,50.00,0.00', ''),
(11, 'Artem_Furry', '98afde05ae218d49efe4372326997b58', 1, 0, '93.170.66.71', 0, '93.170.66.71', '06/05/2026', '-', 44, 0, 0, 0, 0, 0, 0, -1, 0, 2, 0, 0, 0, 0, 2000, 0, 0, 4, 4, 11, 0, 166, 0, '1,1,1,1', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '13-05-2026', 'no', 12, 34, 4, 'Нет', 'Нет', 0, 1, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 1, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 99, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '2026-05-06 11:37:29', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '0.00,0.00,0.00,0.00', ''),
(1, 'Klaus_Gurren', 'ea58bdba4507b20cef448e7c830f98aa', 15, 0, '146.120.175.183', 1, '146.120.175.220', '27/04/2023', '-', 101, 0, 0, 2, 0, 0, 0, -1, 0, 2, 0, 0, 0, 12, 2014329, 0, 0, 0, 0, 0, 0, 0, 0, '1,0,0,0', 8, 0, 0, 0, 0, '-', '-', 797360, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '03-02-2024', 'no', 49, 20, 163, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 0, 1, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 1, 0, 0, 0, 0, '1|1|0|0|0|0|1|0|0|1|48', 1001, NULL, NULL, 0, 0, 0, 0, 0, 666666, 0, 4400, 0, 0, 0, 100, 0, 0, 8, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 1, 0, 0, '18950|19006|0|18915|0|371|0|19045', '1|1|1|1|1|1|1|1', 0, '2023-05-10 21:38:04', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 'триста', '1500.22,691.76,10.82,185.76', ''),
(12, 'Klaus_Gurren', '5e3c8b269178ba2c4e413fccd1dca295', 1, 0, '93.170.66.71', 0, '93.170.66.71', '06/05/2026', '-', 100, 0, 0, 0, 0, 0, 0, -1, 0, 2, 0, 0, 0, 0, 2000, 0, 0, 0, 0, 0, 0, 0, 0, '0,0,0,0', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '06-05-2026', 'no', 13, 11, 3, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 96, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '2020.04,-2033.88,13.59,172.34', ''),
(13, 'Artem_Furryg', '5e3c8b269178ba2c4e413fccd1dca295', 1, 0, '93.170.66.71', 0, '93.170.66.71', '08/05/2026', '-', 100, 0, 0, 0, 0, 0, 0, -1, 0, 1, 0, 0, 0, 2, 1960, 0, 0, 0, 0, 0, 0, 0, 1, '0,0,0,0', 2, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '12-05-2026', 'a60840397@gmail.com', 134, 9, 44, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 1, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '56.65,137.25,2.43,251.45', ''),
(14, 'Mister_CreepTon', 'e46d54dc7e7b52067bf34306697094b0', 1, 0, '159.26.98.235', 0, '159.26.98.235', '09/05/2026', '-', 100, 0, 0, 0, 0, 0, 0, -1, 0, 1, 0, 0, 0, 0, 2000, 0, 0, 0, 0, 0, 0, 0, 0, '0,0,0,0', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '09-05-2026', 'no', 78, 0, 0, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 100, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '1979.65,-1784.25,22.80,1.54', ''),
(15, 'Artem_Furry64', '5e3c8b269178ba2c4e413fccd1dca295', 1, 0, '93.170.66.71', 0, '93.170.66.71', '12/05/2026', '-', 100, 0, 0, 0, 0, 0, 0, -1, 0, 1, 0, 0, 0, 0, 2000, 0, 0, 0, 0, 0, 0, 0, 0, '0,0,0,0', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '12-05-2026', 'no', 79, 21, 1, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 90, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '46.66,118.51,3.66,54.70', ''),
(16, 'Artem_Furry3r', '5e3c8b269178ba2c4e413fccd1dca295', 100, 0, '93.170.71.179', 0, '93.170.66.71', '13/05/2026', '-', 100, 683, 0, 0, 0, 0, 0, -1, 0, 1, 0, 0, 0, 0, 19475000, 0, 0, 0, 0, 0, 0, 0, 0, '0,0,0,0', 0, 0, 0, 0, 0, '-', '-', 0, 0, 0, '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0', '0, 0, 0, 0, 0, 0, ', '14-05-2026', 'no', 134, 20, 4, 'Нет', 'Нет', 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, '0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0|0', ' Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет|Нет', 0, 0, 0, 0, 0, '1|1|0|0|0|0|0|0|0|1', 1001, NULL, NULL, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 91, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '0|0|0|0|0|0|0|0', '1|1|1|1|1|1|1|1', 0, '0/0/0', 0, 0, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '2450.10,742.62,11.46,282.25', '');

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`pID`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `accounts`
--
ALTER TABLE `accounts`
  MODIFY `pID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
