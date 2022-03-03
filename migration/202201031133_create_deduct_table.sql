CREATE TABLE `deduct` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `transaction_id` int(11) NOT NULL COMMENT '內帳交易ID',
                          `user_id` int(11) NOT NULL COMMENT '使用者ID',
                          `amount` int(11) DEFAULT '0' COMMENT '金額',
                          `reason` text NOT NULL COMMENT '事由',
                          `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '處理狀態, 1:應付, 2:已付, 3:註銷',
                          `cancel_reason` text COMMENT '註銷(status=3)原因',
                          `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '創建時間',
                          `created_ip` varchar(15) NOT NULL COMMENT '創建者IP',
                          `created_admin_id` int(11) NOT NULL COMMENT '創建者ID',
                          `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最後更新時間',
                          `updated_ip` varchar(15) NOT NULL COMMENT '最後更新者IP',
                          `updated_admin_id` int(11) NOT NULL COMMENT '最後更新者ID',
                          PRIMARY KEY (`id`),
                          KEY `deduct_user_id_IDX` (`user_id`) USING BTREE,
                          KEY `deduct_created_at_IDX` (`created_at`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='法催扣款'