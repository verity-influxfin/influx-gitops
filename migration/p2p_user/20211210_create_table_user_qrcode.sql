CREATE TABLE `p2p_user`.`block_list` (
  `id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '索引',
  `user_id` INT(11) NOT NULL COMMENT '用戶 ID',
  `status` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '狀態 (0: 黑名單不成立 1:黑名單成立)',
  `create_type` TINYINT(4) NOT NULL COMMENT '創建類型 (0:人工加入 1:系統加入)',

  `block_rules` JSON NOT NULL COMMENT '黑名單規則',
  `remark` VARCHAR(255) NULL COMMENT '備註',

  `expire_time` DATETIME NOT NULL COMMENT '期限時間',
  `block_time` DATETIME NOT NULL COMMENT '封鎖時間',

  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '資料建立時間',
  `created_by` INT(11) UNSIGNED NOT NULL COMMENT '建立人員 admin ID',
  `created_ip` VARCHAR(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '建立 IP',

  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '資料更新時間',
  `updated_by` INT(11) UNSIGNED NOT NULL COMMENT '更新人員 admin ID',
  `updated_ip` VARCHAR(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '更新 IP',

  PRIMARY KEY (`id`),
  INDEX `index_user_id_status_expire_time` (`user_id` ASC, `status` ASC, `expire_time` ASC),
  INDEX `index_status_user_id` (`status` ASC, `user_id` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用戶黑名單列表';