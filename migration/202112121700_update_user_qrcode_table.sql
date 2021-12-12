ALTER TABLE `p2p_user`.`user_qrcode`
    ADD COLUMN `contract_end_time` TIMESTAMP NOT NULL AFTER `end_time`;

SET SQL_SAFE_UPDATES=0;
UPDATE p2p_user.user_qrcode SET contract_end_time = end_time WHERE contract_end_time <= '1911-01-01 00:00:00';
SET SQL_SAFE_UPDATES=1;