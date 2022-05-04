CREATE TABLE `p2p_user`.`user_qrcode_collaboration` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_qrcode_id` BIGINT UNSIGNED NOT NULL,
    `qrcode_collaborator_id` BIGINT UNSIGNED NOT NULL COMMENT '合作對象編號',
    `content` JSON NULL COMMENT '儲存內容',
    `loan_time` TIMESTAMP NOT NULL COMMENT '申貸時間',
    `created_at` TIMESTAMP NOT NULL,
    `created_ip` VARCHAR(15) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx1` (`user_qrcode_id` ASC, `qrcode_collaborator_id` ASC, `loan_time` ASC),
    INDEX `idx2` (`qrcode_collaborator_id` ASC, `loan_time` ASC));