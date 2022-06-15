ALTER TABLE p2p_user.sale_goals ADD status tinyint(1) DEFAULT 1 NOT NULL COMMENT '是否顯示(0:否1:是)' AFTER `number`;
