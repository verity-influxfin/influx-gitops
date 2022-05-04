ALTER TABLE `p2p_log`.`request_log`
    ADD INDEX `user_url` (`user_id` ASC, `method` ASC, `url` ASC);
;
