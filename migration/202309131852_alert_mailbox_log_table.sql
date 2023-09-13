alter table mailbox_log
    modify filename varchar(100) default '' not null comment 'mailbox檔案名稱';
