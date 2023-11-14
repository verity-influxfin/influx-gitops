CREATE TABLE `p2p_user`.`user_certification_report` (
    `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,
    `user_id` BIGINT(11) UNSIGNED NOT NULL ,
    `target_id` BIGINT(11) UNSIGNED NOT NULL ,
    `content` JSON NULL DEFAULT NULL ,
    `admin_id` BIGINT(11) UNSIGNED NOT NULL ,
    `name` VARCHAR(45) NOT NULL ,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
    PRIMARY KEY (`id`),
    UNIQUE `user_id_target_id` (`user_id`, `target_id`),
    INDEX `user_id` (`user_id`),
    INDEX `target_id` (`target_id`)) COMMENT = '徵信報告';
