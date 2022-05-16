ALTER TABLE `p2p_user`.`user_qrcode`\n    ADD COLUMN `subcode_flag` TINYINT NOT NULL DEFAULT '0' AFTER `status`,\n    ADD INDEX `subcode` (`subcode_flag` ASC);\n\n\n

