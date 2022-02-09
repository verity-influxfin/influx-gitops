CREATE TABLE `p2p_user`.`ntu_press_conference` (
                                        `id` int(11) NOT NULL AUTO_INCREMENT,
                                        `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '使用者ID',
                                        `amount` int(11) NOT NULL DEFAULT '0' COMMENT '捐款金額',
                                        `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '狀態(0:未顯示,1:已顯示)',
                                        `weight` tinyint(4) NOT NULL DEFAULT '10' COMMENT '權重0-10(數字越小權重越高)',
                                        `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '建立時間',
                                        `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最後更新時間',
                                        `updated_admin_id` int(11) NOT NULL COMMENT '最後更新者ID',
                                        `data_source` tinyint(4) NOT NULL DEFAULT '0' COMMENT '資料來源(0:APP自動新增,1:後台人工新增)',
                                        PRIMARY KEY (`id`),
                                        KEY `ntu_press_conference_amount_IDX` (`amount`) USING BTREE,
                                        KEY `ntu_press_conference_user_id_IDX` (`user_id`) USING BTREE,
                                        KEY `ntu_press_conference_weight_IDX` (`weight`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='台大慈善記者會(捐款名單權重列表)';