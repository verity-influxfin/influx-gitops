CREATE TABLE `p2p_user`.`charity_anonymous` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT '' COMMENT '姓名|公司抬頭',
  `number` varchar(10) DEFAULT '' COMMENT '身份證字號|公司統編',
  `amount` int(11) NOT NULL COMMENT '捐款金額',
  `phone` varchar(15) DEFAULT '',
  `email` varchar(50) DEFAULT '',
  `address` varchar(255) DEFAULT '',
  `upload` tinyint(4) DEFAULT 0 COMMENT '捐款收據代上傳國稅局 0:否, 1:是',
  `receipt` tinyint(4) DEFAULT 0 COMMENT '索取紙本收據 0:否, 1:是',
  `source` tinyint(4) DEFAULT 0 COMMENT '捐款來源 1:官網 2:借款app 3:投資app',
  `created_at` timestamp NOT NULL,
  `created_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name_number_amount`(`name`, `number`, `amount`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `p2p_transaction`.`anonymous_donate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `last5` varchar(5) COMMENT '帳號末五碼',
  `amount` int(11) NOT NULL COMMENT '轉入金額',
  `charity_institution_alias` varchar(45) DEFAULT '' COMMENT '慈善機構別名',
  `charity_anonymous_id` int(11) DEFAULT 0 COMMENT '成功對應的匿名捐款紀錄',
  `created_at` timestamp NOT NULL,
  `created_ip` varchar(15) NOT NULL,
  `updated_at` timestamp NOT NULL,
  `updated_ip` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `charity_anonymous_id` (`charity_anonymous_id`),
  KEY `amount_last5` (`amount`, `last5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;