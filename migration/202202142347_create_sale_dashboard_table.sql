CREATE TABLE `p2p_user`.`sale_dashboard` (
     `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
     `created_at` TIMESTAMP NOT NULL,
     `app_downloads` INT(11) UNSIGNED NOT NULL,
     `official_site_trends` INT(11) UNSIGNED NOT NULL,
     PRIMARY KEY (`id`));
