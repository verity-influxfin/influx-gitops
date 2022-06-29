CREATE TABLE p2p_user.user_certification_ocr_task
(
    id                    int         NOT NULL AUTO_INCREMENT,
    user_certification_id int         NOT NULL COMMENT '徵信項id',
    task_id               varchar(50) NOT NULL COMMENT 'OCR任務id(API回傳)',
    created_at            TIMESTAMP   NOT NULL,
    created_ip            varchar(15) NOT NULL,
    updated_at            TIMESTAMP   NOT NULL,
    updated_ip            varchar(15) NOT NULL,
    CONSTRAINT user_certification_ocr_task_PK PRIMARY KEY (id)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_general_ci;
CREATE INDEX user_certification_ocr_task_task_id_IDX USING BTREE ON p2p_user.user_certification_ocr_task (user_certification_id);

CREATE TABLE p2p_log.certification_ocr_log
(
    id                    int AUTO_INCREMENT NOT NULL,
    task_path             varchar(100)       NOT NULL,
    user_certification_id int                NOT NULL COMMENT '徵信項id',
    status_code           smallint(6)        NOT NULL,
    response              LONGTEXT           NOT NULL,
    created_ip            varchar(15)        NOT NULL,
    created_at            TIMESTAMP          NOT NULL,
    CONSTRAINT certification_ocr_log_PK PRIMARY KEY (id)
)
    ENGINE = InnoDB
    DEFAULT CHARSET = utf8
    COLLATE = utf8_general_ci;
CREATE INDEX certification_ocr_log_cert_id_IDX USING BTREE ON p2p_log.certification_ocr_log (user_certification_id);
