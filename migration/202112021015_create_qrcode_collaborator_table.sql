CREATE TABLE `p2p_user`.`qrcode_collaborator` (
   `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
   `collaborator` VARCHAR(100) NOT NULL COMMENT '合作對象名稱',
   `type` INT UNSIGNED NOT NULL COMMENT '1: 合作個人產品, 2: 合作企業產品',
   `status` TINYINT NOT NULL DEFAULT 0 COMMENT '0: 禁用, 1:啟用',
   `created_at` TIMESTAMP NOT NULL,
   `created_ip` VARCHAR(15) NOT NULL,
   `created_admin_id` INT(11) NOT NULL DEFAULT '0',
   `updated_at` TIMESTAMP NOT NULL,
   `updated_ip` VARCHAR(15) NOT NULL,
   `updated_admin_id` INT(11) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`));
