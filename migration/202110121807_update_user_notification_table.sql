ALTER TABLE `p2p_user`.`user_notification`
 ADD COLUMN `type` TINYINT NOT NULL DEFAULT 0 COMMENT '0:無行為\n1:開啟強制彈窗(parameter帶空)\n2.開啟URL(parameter帶URL)\n3.開啟target id(parameter帶target id,product id)\n4.開啟認證徵信(parameter帶alias,target id)' AFTER `status`,
 ADD COLUMN `data` VARCHAR(512) NOT NULL DEFAULT '' AFTER `type`;