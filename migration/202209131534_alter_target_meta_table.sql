ALTER TABLE p2p_loan.target_meta ADD user_id int NOT NULL DEFAULT 0 COMMENT '使用者id' after target_id;
ALTER TABLE p2p_loan.target_meta DROP KEY target_id_2;
ALTER TABLE p2p_loan.target_meta ADD CONSTRAINT target_meta_UN UNIQUE KEY (target_id,user_id,meta_key);