CREATE TABLE `behavion`.`user_behavior` (
    `id` BIGINT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL DEFAULT 0,
    `identity` TINYINT NOT NULL DEFAULT 0 COMMENT '1: borrower, 2:investor',
    `device_id` VARCHAR(255) NOT NULL,
    `action` VARCHAR(45) NOT NULL,
    `type` TINYINT NOT NULL DEFAULT 0 COMMENT '1: website, 2:app',
    `data1` VARCHAR(100) NOT NULL,
    `data2` VARCHAR(100) NOT NULL,
    `json_data` JSON NOT NULL,
    `created_at` TIMESTAMP NOT NULL,
    `created_ip` VARCHAR(15) NOT NULL,
    PRIMARY KEY (`id`, `created_at`),
    INDEX `idx1` (`user_id` ASC, `action` ASC, `type` ASC),
    INDEX `idx2` (`action` ASC, `data1` ASC),
    INDEX `idx3` (`action` ASC, `data2` ASC))
    PARTITION BY RANGE( UNIX_TIMESTAMP(created_at))(
    PARTITION from_2022_or_less VALUES LESS THAN (UNIX_TIMESTAMP('2022-01-01 00:00:00')),
    PARTITION from_2023 VALUES LESS THAN (UNIX_TIMESTAMP('2023-01-01 00:00:00')),
    PARTITION from_2024 VALUES LESS THAN (UNIX_TIMESTAMP('2024-01-01 00:00:00')),
    PARTITION from_2025 VALUES LESS THAN (UNIX_TIMESTAMP('2025-01-01 00:00:00')),
    PARTITION from_2026 VALUES LESS THAN (UNIX_TIMESTAMP('2026-01-01 00:00:00')),
    PARTITION from_2027_and_up VALUES LESS THAN MAXVALUE);
