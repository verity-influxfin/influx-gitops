CREATE TABLE `p2p_user`.`user_qrcode_apply` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_qrcode_id` INT UNSIGNED NOT NULL,
    `status` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '0: 待制定合約, 1:審核成功, 2:審核失敗, 3: 已送出審核',
    `contract_format_id` INT NOT NULL,
    `contract_content` JSON NOT NULL,
    `created_at` TIMESTAMP NOT NULL,
    `created_ip` VARCHAR(15) NOT NULL,
    `updated_at` TIMESTAMP NOT NULL,
    `updated_ip` VARCHAR(15) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `idx1` (`user_qrcode_id` ASC, `status` ASC));

CREATE TABLE `p2p_log`.`qrcode_apply_review_log` (
     `id` BIGINT NOT NULL AUTO_INCREMENT,
     `admin_id` INT NOT NULL,
     `qrcode_apply_id` BIGINT NOT NULL,
     `status` TINYINT NOT NULL COMMENT '1:成功 2: 失敗',
     `created_at` TIMESTAMP NOT NULL,
     `created_ip` VARCHAR(15) NOT NULL,
     `updated_at` TIMESTAMP NOT NULL,
     `updated_ip` VARCHAR(15) NOT NULL,
     PRIMARY KEY (`id`),
     INDEX `idx1` (`qrcode_apply_id` ASC, `admin_id` ASC, `status` ASC));

INSERT INTO `p2p_user`.`qrcode_setting` (`id`, `alias`, `description`, `status`, `start_time`, `end_time`, `settings`, `length`, `prefix`, `created_at`, `created_ip`, `created_admin_id`, `updated_at`, `updated_ip`, `updated_admin_id`) VALUES (NULL, 'appointed', '特約方案', '1', '2021-11-29 00:00:00', '0000-00-00 00:00:00', '{\"reward\": {\"product\": {\"student\": {\"percent\": 1, \"product_id\": [1, 2]}, \"salary_man\": {\"percent\": 1, \"product_id\": [3, 4]}}, \"full_member\": {\"amount\": 20}}}', '8', '', '2021-11-29 18:20:00', '', '78', '2021-10-20 18:20:00', '', '0');
