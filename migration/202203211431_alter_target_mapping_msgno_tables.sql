ALTER TABLE `p2p_skbank`.`loan_target_mapping_msg_no`
    ADD COLUMN `bank` TINYINT UNSIGNED NOT NULL COMMENT '1: 新光銀行, 2:凱基銀行' AFTER `remark`;
SET SQL_SAFE_UPDATES=0;
UPDATE `p2p_skbank`.`loan_target_mapping_msg_no` SET `bank` = '1';
SET SQL_SAFE_UPDATES=1;
