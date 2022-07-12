CREATE TABLE `target_meta` (
   `id` bigint(20) NOT NULL AUTO_INCREMENT,
   `target_id` int(11) NOT NULL,
   `meta_key` varchar(128) NOT NULL,
   `meta_value` text NOT NULL,
   `created_ip` varchar(15) NOT NULL,
   `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   `updated_ip` varchar(15) NOT NULL,
   `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
   PRIMARY KEY (`id`),
   UNIQUE KEY `target_id_2` (`target_id`,`meta_key`) USING BTREE,
   KEY `target_id` (`target_id`) USING BTREE,
   KEY `meta_key` (`meta_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;