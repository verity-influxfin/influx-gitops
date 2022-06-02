CREATE TABLE `p2p_log`.`qrcode_reward_log` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `amount` DECIMAL(12,3) NOT NULL,
    `ids` JSON NULL,
    `admin_id` INT(11) NOT NULL DEFAULT '0',
    `created_at` TIMESTAMP NOT NULL,
    `created_ip` VARCHAR(15) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx1` (`created_at` ASC, `admin_id` ASC));

ALTER TABLE `p2p_transaction`.`qrcode_reward`
    ADD COLUMN `notified_at` TIMESTAMP NULL AFTER `json_data` COMMENT '勞務報酬單通知時間';
