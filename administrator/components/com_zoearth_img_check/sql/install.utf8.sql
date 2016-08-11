
-- 20160811 zoearth 紀錄對應檔案位置
CREATE TABLE IF NOT EXISTS `#__zoearth_img_flow_files` (
  `fileName` varchar(255) NOT NULL,
  `fileTime` datetime NOT NULL,
  `fileUrl` varchar(255) NOT NULL,
  PRIMARY KEY (`fileName`,`fileTime`),
  KEY `fileUrl` (`fileUrl`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='Zoearth檔案FTP同步資料';