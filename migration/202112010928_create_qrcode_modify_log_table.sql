CREATE TABLE `p2p_log`.`qrcode_modify_log` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `qrcode_id` BIGINT UNSIGNED NOT NULL,
   `status` TINYINT NULL,
   `settings` JSON NULL,
   `admin_id` INT(11) NOT NULL DEFAULT '0',
   `created_at` TIMESTAMP NOT NULL,
   `created_ip` VARCHAR(15) NOT NULL,
   PRIMARY KEY (`id`));
