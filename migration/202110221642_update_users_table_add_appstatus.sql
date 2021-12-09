ALTER TABLE `p2p_user`.`users`
    ADD COLUMN `app_status` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0=app借款端未登入過, app借款端已登入過' AFTER `auth_otp`,
    ADD COLUMN `app_investor_status` TINYINT(4) NOT NULL DEFAULT '0'  COMMENT '0=app出借端未登入過, app出借端已登入過' AFTER `app_status`;
