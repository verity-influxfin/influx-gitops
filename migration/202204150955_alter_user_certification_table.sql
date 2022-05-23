ALTER TABLE `p2p_user`.`user_certification` ADD `certificate_status` TINYINT DEFAULT 0 NOT NULL COMMENT '是否已送出審核(0:否1:是)' AFTER `sys_check`;
