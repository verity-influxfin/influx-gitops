CREATE TABLE `p2p_log`.`qrcode_import_collaboration_log` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `qrcode_collaboration_id` INT NOT NULL,
   `count` INT NOT NULL,
   `content` JSON NULL,
   `admin_id` INT(11) NOT NULL DEFAULT '0',
   `created_at` TIMESTAMP NOT NULL,
   `created_ip` VARCHAR(15) NOT NULL,
   PRIMARY KEY (`id`));
