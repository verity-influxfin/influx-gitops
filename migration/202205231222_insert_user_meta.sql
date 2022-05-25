INSERT INTO `p2p_user`.`user_meta` (`user_id`,`meta_key`,`meta_value`,`created_ip`,`updated_ip`,`created_at`,`updated_at`)
SELECT
    `uc`.`user_id`,
    IF(`uc`.`investor`=1,'email_investor','email_borrower'),
    json_extract(`uc`.`content`,'$.email'),
    `uc`.`created_ip`,
    `uc`.`updated_ip`,
    `uc`.`created_at`,
    `uc`.`updated_at`
FROM `p2p_user`.`user_certification` `uc`
WHERE `uc`.`id` IN (SELECT MAX(`uc2`.`id`) FROM `p2p_user`.`user_certification` `uc2` WHERE `uc2`.`status`=1 AND `uc2`.`certification_id`=6 GROUP BY `uc2`.`user_id`,`uc2`.`investor`)