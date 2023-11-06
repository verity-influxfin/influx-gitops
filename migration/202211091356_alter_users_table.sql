ALTER TABLE p2p_user.users ADD user_id varchar(128) NULL COMMENT '設定帳號(至少九碼大小寫英數)' AFTER id_number;

# 需要預設寫入嗎？