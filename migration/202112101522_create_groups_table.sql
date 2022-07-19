ALTER TABLE `p2p_admin`.`admins` ADD group_id INT DEFAULT 0 NOT NULL COMMENT '部門ID';
ALTER TABLE `p2p_admin`.`admins` ADD permission_status TINYINT DEFAULT 0 NOT NULL COMMENT '權限審查啟用否, 0: 否, 1: 是';

CREATE TABLE `p2p_admin`.`groups` (
                          `id` int(11) NOT NULL AUTO_INCREMENT,
                          `division` varchar(100) NOT NULL COMMENT '部門',
                          `department` varchar(100) NOT NULL COMMENT '組別',
                          `position` tinyint(4) NOT NULL COMMENT '角色名稱, 1: 執行長, 2: 主管, 3: 經辦',
                          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '創建時間',
                          `created_ip` varchar(15) NOT NULL COMMENT '創建者IP',
                          `created_admin_id` int(11) NOT NULL COMMENT '創建者ID',
                          `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '最後更新時間',
                          `updated_ip` varchar(15) NOT NULL COMMENT '最後更新者IP',
                          `updated_admin_id` int(11) NOT NULL COMMENT '最後更新者ID',
                          PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='部門';

CREATE TABLE `p2p_admin`.`group_permission` (
                                    `group_id` int(11) NOT NULL COMMENT '部門ID',
                                    `model_key` varchar(100) NOT NULL COMMENT '主模組',
                                    `submodel_key` varchar(100) NOT NULL COMMENT '子模組',
                                    `action_type` int(11) NOT NULL DEFAULT '0' COMMENT '權限, 0: 查詢, 1: 編輯, 2: 新增, 3: 刪除, pow(2)加總',
                                    PRIMARY KEY (`group_id`,`model_key`,`submodel_key`),
                                    KEY `group_permission_model_key_IDX` (`model_key`) USING BTREE,
                                    KEY `group_permission_submodel_key_IDX` (`submodel_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='部門權限';

CREATE TABLE `p2p_admin`.`admin_permission` (
                                    `admin_id` int(11) NOT NULL COMMENT '管理員ID',
                                    `model_key` varchar(100) NOT NULL COMMENT '主模組',
                                    `submodel_key` varchar(100) NOT NULL COMMENT '子模組',
                                    `action_type` int(11) NOT NULL DEFAULT '0' COMMENT '權限, 0: 查詢, 1: 編輯, 2: 新增, 3: 刪除, pow(2)加總',
                                    PRIMARY KEY (`admin_id`,`model_key`,`submodel_key`),
                                    KEY `admin_permission_model_key_IDX` (`model_key`) USING BTREE,
                                    KEY `admin_permission_submodel_key_IDX` (`submodel_key`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理員例外權限';
