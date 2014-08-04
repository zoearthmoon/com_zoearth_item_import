
-- 申請記錄
CREATE TABLE IF NOT EXISTS `#__loan_record` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '申請單號',
  `customerGuid` int(11) NOT NULL DEFAULT '0' COMMENT '申請人Guid',
  `guarantorGuid` int(11) NOT NULL DEFAULT '0' COMMENT '擔保人Guid',
  `realCustomerGuid` int(11) NOT NULL DEFAULT '0' COMMENT '實際貸款人Guid',
  `applyDate` date NOT NULL DEFAULT '0000-00-00' COMMENT '申請日',
  `loanDate` date NOT NULL DEFAULT '0000-00-00' COMMENT '實際貸款日',
  `loanAppropriationDate` date NOT NULL DEFAULT '0000-00-00' COMMENT '實際撥款日',
  `loanCost` int(11) NOT NULL DEFAULT '0' COMMENT '貸款金額',
  `loanInstallment` int(4) NOT NULL DEFAULT '0' COMMENT '貸款分期',
  `loanMonthPay` int(11) NOT NULL DEFAULT '0' COMMENT '月付款',
  `rate` double(10,4) NOT NULL DEFAULT '0.0000' COMMENT '利率',
  `sourceGuid` int(11) NOT NULL DEFAULT '0' COMMENT '進件商Guid',
  `bankGuid` int(11) NOT NULL DEFAULT '0' COMMENT '代理銀行Guid',
  `salesGuid` int(11) NOT NULL DEFAULT '0' COMMENT '業務Guid',
  `loanServiceItemGuid` int(11) NOT NULL DEFAULT '0' COMMENT '服務項目Guid',
  `memo` text NOT NULL COMMENT '備註',
  `note` text NOT NULL COMMENT '備註',
  `state` int(1) NOT NULL DEFAULT '0' COMMENT '狀態 1.開發中 2:審核中 3:通過 4:未通過 5.作廢',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='借貸客戶資料_申請記錄';

-- 服務記錄
CREATE TABLE IF NOT EXISTS `#__loan_service` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `customerGuid` int(11) NOT NULL DEFAULT '0' COMMENT '申請人Guid',
  `salesGuid` int(11) NOT NULL DEFAULT '0' COMMENT '業務Guid',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_服務記錄';

-- 服務項目
CREATE TABLE IF NOT EXISTS `#__loan_service_item` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名稱',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_服務項目';

-- 客戶記錄
CREATE TABLE IF NOT EXISTS `#__loan_customer` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名稱/客戶名稱',
  `keyId` varchar(50) NOT NULL DEFAULT '' COMMENT '統一編號/身分證字號',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '類型:1.公司 2.個人',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '電話',
  `tel2` varchar(50) NOT NULL DEFAULT '' COMMENT '電話2',
  `tel3` varchar(50) NOT NULL DEFAULT '' COMMENT '電話3',
  `addressCompany` varchar(255) NOT NULL DEFAULT '' COMMENT '公司住址',
  `addressHome` varchar(255) NOT NULL DEFAULT '' COMMENT '住家住址',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_客戶記錄';

-- 銀行
CREATE TABLE IF NOT EXISTS `#__loan_bank` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '銀行名稱',
  `code` varchar(10) NOT NULL DEFAULT '' COMMENT '銀行代號',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_銀行';

-- 進件商
CREATE TABLE IF NOT EXISTS `#__loan_source` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '進件商名稱',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_進件商';

-- 業務
CREATE TABLE IF NOT EXISTS `#__loan_sales` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名稱',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_業務';

-- 申請情況類別
CREATE TABLE IF NOT EXISTS `#__loan_situation` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '情況名稱',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_申請情況類別';

-- ***********************************************
-- *[ ZOEARTH 以下是預設系統都會共用的  ]  
-- ***********************************************

-- LOG
CREATE TABLE IF NOT EXISTS `#__loan_log` (
  `keyGuid` int(11) NOT NULL DEFAULT '0' COMMENT '主要ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '類型',
  `content` text NOT NULL COMMENT '內容',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  KEY `keyGuid` (`keyGuid`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='借貸客戶資料_LOG';

-- Zoe附檔資料
CREATE TABLE IF NOT EXISTS `#__zoe_file` (
  `guid` int(11) NOT NULL AUTO_INCREMENT,
  `keyGuid` int(11) NOT NULL DEFAULT '0' COMMENT '主要的guid',
  `option` varchar(50) NOT NULL DEFAULT '' COMMENT '元件名稱(權限相關)',
  `type` varchar(25) NOT NULL DEFAULT '' COMMENT '檔案類別(同一功能中不同的檔案上傳群組)',
  `filePath` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案存取路徑',
  `fileName` varchar(255) NOT NULL DEFAULT '' COMMENT '檔案名稱',
  `fileType` varchar(25) NOT NULL DEFAULT '' COMMENT '檔案類型',
  `fileSize` int(11) NOT NULL DEFAULT '0' COMMENT '檔案大小',
  `note` text NOT NULL COMMENT '備註',
  `active` int(1) NOT NULL DEFAULT '0' COMMENT '有效',
  `iuser` varchar(50) NOT NULL DEFAULT '',
  `idate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `uuser` varchar(50) NOT NULL DEFAULT '',
  `udate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`guid`),
  KEY `keyGuid` (`keyGuid`),
  KEY `option` (`option`),
  KEY `type` (`type`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Zoe附檔資料';

