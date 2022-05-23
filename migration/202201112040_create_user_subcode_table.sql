CREATE TABLE `p2p_user`.`user_subcode` (
   `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
   `alias` VARCHAR(70) NOT NULL DEFAULT '' COMMENT '暱稱別名',
   `registered_id` VARCHAR(25) NOT NULL DEFAULT '' COMMENT '登記subcode的身分證字號',
   `master_user_qrcode_id` VARCHAR(45) NOT NULL DEFAULT '',
   `user_qrcode_id` VARCHAR(45) NOT NULL DEFAULT '',
   `last_handle_time` TIMESTAMP NULL COMMENT '最近一次處理時間',
   `created_at` TIMESTAMP NOT NULL,
   `created_ip` VARCHAR(15) NOT NULL,
   `updated_at` TIMESTAMP NOT NULL,
   `updated_ip` VARCHAR(15) NOT NULL,
   PRIMARY KEY (`id`),
   INDEX `registered_idx` USING BTREE (`registered_id`),
   INDEX `master_qrcode_idx` USING BTREE (`master_user_qrcode_id`),
   INDEX `user_qrcode_idx` USING BTREE (`user_qrcode_id`),
   INDEX `status_idx` USING BTREE (`status`));
