CREATE TABLE IF NOT EXISTS `p2p_user`.`user_version_update` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `app` INT NULL COMMENT '程式名稱-> 0:[APP_INVEST] 1:[APP_BORROW] 2:[APP_SELLER]',
  `platform` INT NULL COMMENT '平台-> 0:[ANDROID] 1:[IOS] 2:[PC]',
  `allow_version` VARCHAR(128) NOT NULL COMMENT '使用者能使用的版號',
  PRIMARY KEY (`id`)
  ) ENGINE = InnoDB DEFAULT CHARSET=utf8 COMMENT='使用者app版本控管';