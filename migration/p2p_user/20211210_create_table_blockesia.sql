CREATE TABLE `p2p_user`.`blockesia` (
  `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '索引',

  `user_id` INT(11) NOT NULL COMMENT '用戶 ID',
  `item` VARCHAR(255) NULL COMMENT '項目',
  `data_source` VARCHAR(255) NULL COMMENT '資料來源',
  `category` VARCHAR(255) NULL COMMENT '歸類',
  `content` VARCHAR(255) NULL COMMENT '內容',
  `risk` VARCHAR(255) NULL COMMENT '風險',
  `resolution` VARCHAR(255) NULL COMMENT '解決方式',

  `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '狀態 (0: 停用 1:啟用)',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '資料建立時間',
  `created_by` INT(11) UNSIGNED NOT NULL COMMENT '建立人員 admin ID',
  `created_ip` VARCHAR(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '建立 IP',

  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '資料更新時間',
  `updated_by` INT(11) UNSIGNED NOT NULL COMMENT '更新人員 admin ID',
  `updated_ip` VARCHAR(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '更新 IP',

  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用戶人工反詐欺設定';