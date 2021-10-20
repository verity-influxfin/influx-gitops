CREATE TABLE `p2p_user`.`edm_event` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `description` VARCHAR(200) NOT NULL DEFAULT '',
  `title` TEXT NOT NULL,
  `content` TEXT NOT NULL,
  `html` LONGTEXT NOT NULL,
  `minute_freq` INT NOT NULL COMMENT '每幾分鐘觸發一次',
  `status` SMALLINT NOT NULL DEFAULT 1,
  `creator_admin_id` INT NOT NULL DEFAULT 0,
  `editor_admin_id` INT NOT NULL DEFAULT 0,
  `attachment_data` TEXT NOT NULL,
  `triggered_at` TIMESTAMP NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_ip` VARCHAR(15) NOT NULL,
  `updated_ip` VARCHAR(15) NOT NULL,
  PRIMARY KEY (`id`));

CREATE TABLE `p2p_user`.`edm_event_log` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `edm_event_id` INT UNSIGNED NOT NULL,
  `user_id` BIGINT UNSIGNED NOT NULL,
  `investor` TINYINT NOT NULL,
  `type` SMALLINT NOT NULL DEFAULT 0 COMMENT '發送方式(0: Email)',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `memo` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  INDEX `idx1` (`user_id` ASC, `type` ASC, `created_at` ASC, `edm_event_id` ASC),
  INDEX `idx2` (`edm_event_id` ASC, `user_id` ASC));

INSERT INTO `p2p_user`.`edm_event` (`id`, `description`, `title`, `content`, `html`, `minute_freq`, `status`, `creator_admin_id`, `editor_admin_id`, `attachment_data`, `triggered_at`, `created_at`, `updated_at`, `created_ip`, `updated_ip`) VALUES ('1', '王道導流(平台上班族還款中/已結案申貸戶)', '【普匯前5%優質VIP限定快閃】專屬您的擴大額度專案！', '<div style=\'font-size: 30px; font-weight:bold;\'>▍優質客戶專屬優惠<br/>經由AI大數據智能篩選，您符合普匯前5%優質用戶【限定快閃專案】資格。<br/>小額貸款不夠嗎？我們為您獻上特別專案。<br/><br/>▍快閃專案<br/>普匯推薦王道銀行 額度大升級！<br/>→額度提高超過10倍，最高可申請350萬，首期利率0.07%，開辦費再減免！只要1999元。<br/><br/>▍一支手機完成貸款<br/>一如既往，待在家即可完成貸款。<br/>→簡單三步驟：線上申請、上傳資料、審核撥款，資金就到手。<br/><br/>▍名額有限，活動只到2021.11.30<br/><br/><span style=\'color: #808080; \'>了解更多<a href=\'https://www.influxfin.com/obank\' target=\'_blank\'>「普匯x王道 優惠方案」</a><br/>#一支手機完成貸款<br/>#金融科技首度攜手合作數位銀行<br/></span></div>', '', '60', '1', '78', '0', '{\"EDM_image\": \"event/EDM01.png\",\"EDM_href\": \"https://www.influxfin.com/obank\"}', NULL, '2021-10-06 05:57:48', '2021-10-07 19:41:01', '', '114.34.172.44');
INSERT INTO `p2p_user`.`edm_event` (`id`, `description`, `title`, `content`, `html`, `minute_freq`, `status`, `creator_admin_id`, `editor_admin_id`, `attachment_data`, `created_at`, `updated_at`, `created_ip`, `updated_ip`) VALUES ('2', '王道導流(平台上班族未核准之申貸戶)', '抗疫新選擇！立省NT5801元懶人包大公開', '<div style=\'font-size: 30px; font-weight:bold;\'>▍疫情加碼不囉嗦<br/>提供抗疫有感方案！額度最高350萬，利率首期0.07%。<br/>開辦費立刻減免NT5801元。<br/><br/>▍快閃專案<br/>普匯搭配王道銀行，陪你渡過難關，共同迎接後疫情時代！<br/>→最高可申請350萬，首期利率0.07%，開辦費原價NT7800元，現在只要NT1999元！<br/><br/>▍一支手機完成貸款<br/>一如既往，待在家即可完成貸款。<br/>→簡單三步驟：線上申請、上傳資料、審核撥款，資金就到手。<br/><br/>▍名額有限，活動只到2021.11.30<br/><br/><span style=\'color: #808080; \'>了解更多<a href=\'https://www.influxfin.com/obank\' target=\'_blank\'>「普匯x王道 優惠方案」</a><br/>#一支手機完成貸款<br/>#金融科技首度攜手合作數位銀行<br/></span></div>', '', '60', '1', '78', '0', '{\"EDM_image\": \"event/EDM02.png\",\"EDM_href\": \"https://www.influxfin.com/obank\"}', '2021-10-06 05:57:48', '2021-10-07 11:46:32', '', '114.34.172.44');