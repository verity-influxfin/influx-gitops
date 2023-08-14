CREATE TABLE `p2p_log`.`mailbox_log`
(
    id          bigint auto_increment primary key,
    filename    varchar(30) default '' not null comment 'mailbox檔案名稱',
    mail_from    varchar(30) default '' not null comment '寄件者',
    mail_title    varchar(30) default '' not null comment '郵件標題',
    remark      varchar(100)  default ''not null comment '備註',
    actions      text not null comment '檔案處理的動作',
    created_ip  varchar(15)              not null,
    created_at  int                      not null
);
