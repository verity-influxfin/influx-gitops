ALTER TABLE `p2p_user`.`user_certification`
    ADD COLUMN `sub_status` TINYINT(4) NOT NULL DEFAULT '0' AFTER `status`;
