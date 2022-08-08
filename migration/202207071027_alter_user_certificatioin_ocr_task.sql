ALTER TABLE `p2p_user`.`user_certification_ocr_task` DROP KEY user_certification_ocr_task_UN;
ALTER TABLE `p2p_user`.`user_certification_ocr_task` ADD `type` tinyint COMMENT 'OCR辨識類型' NOT NULL AFTER `id`;
ALTER TABLE `p2p_user`.`user_certification_ocr_task` ADD CONSTRAINT user_certification_ocr_task_UN UNIQUE KEY (`user_certification_id`,`type`);
