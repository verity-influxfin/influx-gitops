-- 新增欄位
ALTER TABLE `p2p_user`.`sale_dashboard` ADD `platform_type` TINYINT(1) DEFAULT 0 COMMENT '0: ga, 1: android, 2: ios' AFTER `created_at`;
ALTER TABLE `p2p_user`.`sale_dashboard` ADD `data_at` DATE NOT NULL COMMENT '資料日期' AFTER `id`;

-- 將 `created_at` 的資料同步到新欄位 `data_at`
UPDATE `p2p_user`.`sale_dashboard` SET `data_at` = `created_at` WHERE 1;

-- 調整欄位資訊
ALTER TABLE `p2p_user`.`sale_dashboard` CHANGE COLUMN `app_downloads` `amounts` INT(11) UNSIGNED DEFAULT 0 COMMENT '下載/流量數';
ALTER TABLE `p2p_user`.`sale_dashboard` CHANGE COLUMN `created_at` `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT '最後更新時間';

-- 移除無用欄位 official_sit_trends
ALTER TABLE `p2p_user`.`sale_dashboard` DROP COLUMN `official_site_trends`;
