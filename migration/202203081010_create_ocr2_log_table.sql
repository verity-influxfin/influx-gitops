CREATE TABLE `p2p_log`.`ocr2_log` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `router` varchar(255) NOT NULL COMMENT '呼叫的 api',
  `http_status` smallint(6) DEFAULT 0,
  `secret_key` varchar(16)  DEFAULT '' COMMENT '隨機產生的金鑰',
  `session_id` varchar(32)  DEFAULT '' COMMENT '跟 OCR 註冊的 session',
  `user_id` int(11) NOT NULL DEFAULT 0,
  `cer_id` int(11) NOT NULL DEFAULT 0,
  `response` text NOT NULL,
  `created_ip` varchar(15) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='新版 OCR api log';
