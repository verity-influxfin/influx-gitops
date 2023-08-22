CREATE TABLE `p2p_user`.`user_certification_bak` (
    `id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `investor` TINYINT(4) NOT NULL DEFAULT 0,
    `certification_id` INT(11) NOT NULL,
    `content` TEXT NOT NULL,
    `remark` TEXT NOT NULL,
    `status` TINYINT(4) NOT NULL DEFAULT 0,
    `sub_status` TINYINT(4) NOT NULL DEFAULT 0,
    `sys_check` INT(11) NOT NULL DEFAULT 0,
    `certificate_status` TINYINT(4) NOT NULL DEFAULT 0,
    `expire_time` INT(11) NULL DEFAULT NULL,
    `created_at` INT(11) NOT NULL,
    `created_ip` VARCHAR(15) NOT NULL,
    `updated_at` INT(11) NOT NULL,
    `updated_ip` VARCHAR(15) NOT NULL,
    `can_resubmit_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    UNIQUE INDEX `id` (`id`)
)
COLLATE='utf8_general_ci'
;

