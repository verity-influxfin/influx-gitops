ALTER TABLE `p2p_log`.`user_bankaccount_log` ADD `user_bankaccount_id` int NOT NULL COMMENT '金融驗證ID' AFTER `id`;
ALTER TABLE `p2p_log`.`user_bankaccount_log` ADD `status` int AFTER `user_id`;
ALTER TABLE `p2p_log`.`user_bankaccount_log` ADD `verify_at` int AFTER `verify`;
