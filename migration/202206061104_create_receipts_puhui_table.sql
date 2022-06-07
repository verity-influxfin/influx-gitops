CREATE TABLE `p2p_transaction`.`receipts_puhui` (
                            `id` bigint(20) NOT NULL AUTO_INCREMENT,
                            `entering_date` date DEFAULT NULL COMMENT '入帳日',
                            `user_id` int(11) NOT NULL DEFAULT '0',
                            `amount` int(11) NOT NULL,
                            `tax_amount` int(11) NOT NULL,
                            `tax_id` varchar(10) NOT NULL,
                            `order_no` varchar(20) NOT NULL,
                            `status` tinyint(4) DEFAULT '1',
                            `created_ip` varchar(15) NOT NULL,
                            `created_at` int(11) NOT NULL,
                            PRIMARY KEY (`id`),
                            KEY `user_id` (`user_id`),
                            KEY `entering_date` (`entering_date`),
                            KEY `tax_id` (`tax_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '普匯租賃發票紀錄';