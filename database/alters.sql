ALTER TABLE `admin_users` ADD `seller_type` SMALLINT NOT NULL DEFAULT '0' AFTER `superuser`;
ALTER TABLE `shop_currency` ADD `order_default` BOOLEAN NOT NULL DEFAULT FALSE AFTER `company_id`;
ALTER TABLE `shop_product_like` ADD `likes` INT NULL AFTER `rate`;
ALTER TABLE `shop_product_like` CHANGE `likes` `likes` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `shop_product_like` ADD `userName` VARCHAR(250) NULL
UPDATE `admin_menu` SET `active` = '0' WHERE `admin_menu`.`id` = 63 and uri = 'documents'
UPDATE `admin_menu` SET `active` = '1' WHERE `admin_menu`.`id` = 62 and uri is null 
ALTER TABLE `banner` ADD `path` VARCHAR(250) NULL AFTER `url`;
ALTER TABLE `company_contact` ADD `path` VARCHAR(255) NULL AFTER `image`;
ALTER TABLE `companies` ADD `logo` VARCHAR(250) NULL AFTER `iban`;
ALTER TABLE `companies` ADD `path` VARCHAR(250) NULL AFTER `logo`;
----
CREATE TABLE `coupon_order` (
  `id` int(11) NOT NULL,
  `coupon_name` varchar(250) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `discount_value` decimal(18,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf32;

ALTER TABLE `coupon_order` ADD PRIMARY KEY(`id`);
ALTER TABLE `coupon_order` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `shop_shoppingcart` ADD `user_id` INT NOT NULL AFTER `content`, ADD `company_id` INT NOT NULL AFTER `user_id`;

ALTER TABLE `shop_shoppingcart` CHANGE `created_at` `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `product_price_list` ADD `status` TINYINT(4) NOT NULL DEFAULT '0' AFTER `price`;
INSERT INTO `admin_menu` (`id`, `parent_id`, `order`, `title`, `icon`, `uri`, `permission`, `active`, `created_at`, `updated_at`) VALUES (NULL, '15', '48', 'PriceList', 'fa fa-cubes', 'product_price_list', NULL, '1', '2018-09-19 15:51:31', '2019-03-23 10:28:54');
CREATE TABLE `order_addresses` (
  `id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `f_name` varchar(120) DEFAULT NULL,
  `l_name` varchar(250) DEFAULT NULL,
  `order_id` int(11) NOT NULL,
  `city` varchar(200) DEFAULT NULL,
  `city_id` int(11) NULL,
  `company_id` int(11) NULL,
  `region` varchar(200) DEFAULT NULL,
  `street` varchar(200) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
   `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
 
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
ALTER TABLE `order_addresses` ADD PRIMARY KEY(`id`);
ALTER TABLE `order_addresses` CHANGE `id` `id` BIGINT(20) NOT NULL AUTO_INCREMENT;
ALTER TABLE `config_global` ADD `path` VARCHAR(255) NULL AFTER `template`;


ALTER TABLE `product_price_list` ADD `uof_group` INT NULL AFTER `uof_id`;
ALTER TABLE `order_addresses` CHANGE `order_id` `order_id` INT(11) NULL;

ALTER TABLE `order_addresses` ADD `is_shipping_addr` TINYINT NOT NULL AFTER `order_id`;
ALTER TABLE `order_addresses` ADD `phone` VARCHAR(20)  NULL AFTER `region`, ADD `email` VARCHAR(250)  NULL AFTER `phone`;
ALTER TABLE `order_addresses` CHANGE `is_shipping_addr` `type` INT(4) NOT NULL;
ALTER TABLE `order_addresses` CHANGE `is_shipping_addr` `type` ENUM('BILLING','SHIPPING','','') NOT NULL;

--
ALTER TABLE `shop_discount_user` ADD `company_id` INT NULL AFTER `user_id`;


--


ALTER TABLE `shipping_standard` CHANGE `fee` `fee` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `shipping_standard` CHANGE `shipping_free` `shipping_free` INT(11) NOT NULL DEFAULT '0';

---
CREATE TABLE install_items ( `id` INT NOT NULL , `company_id` INT NOT NULL , `fee` INT NOT NULL DEFAULT '0' , `condition_fee` INT NOT NULL DEFAULT '0' , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NULL ) ENGINE = InnoDB;
ALTER TABLE `install_items` ADD PRIMARY KEY(`id`);
INSERT INTO `install_items` (`id`, `company_id`, `fee`, `condition_fee`, `created_at`, `updated_at`) VALUES (NULL, '1', '1', '1', current_timestamp(), NULL);
ALTER TABLE `shop_order` CHANGE `address1` `address1` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `shop_order` CHANGE `phone` `phone` CHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `shop_order` ADD `address_id` INT NOT NULL AFTER `email`;
ALTER TABLE `shop_order_detail` CHANGE `status` `status` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `shop_order` ADD `s_address_id` INT NOT NULL AFTER `address2`;
ALTER TABLE `order_to_address` ADD `updated_at` TIMESTAMP NULL AFTER `bill_address_id`, ADD `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `updated_at`;
ALTER TABLE `order_to_address` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `coupon_order` ADD `company_id` INT NULL DEFAULT NULL AFTER `order_id`;
--//
ALTER TABLE `champion_details` DROP `horse_name`;
ALTER TABLE `champion_details_desc` ADD `horse_name` VARCHAR(250) NULL AFTER `champion_detail_id`;
ALTER TABLE `champion_details_desc` ADD `horse_name` VARCHAR(250) NULL AFTER `champion_detail_id`;
ALTER TABLE `champion_details_desc` CHANGE `description` `description` VARCHAR(255) CHARACTER SET utf32 COLLATE utf32_general_ci NULL;
ALTER TABLE `champion_details_desc` ADD `company_id` INT NULL AFTER `champion_detail_id`;
ALTER TABLE `champion_details_desc` CHANGE `id` `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `champion_details` ADD `path` VARCHAR(250) NULL AFTER `company_id`;
//---

CREATE TABLE `magazine_desc` ( `id` INT NOT NULL AUTO_INCREMENT , `title` VARCHAR(250) NOT NULL , `notes` VARCHAR(250) NOT NULL , `magazine_id` INT NOT NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
ALTER TABLE `magazine_desc` ADD `lang_id` INT NOT NULL AFTER `notes`;
CREATE TABLE `activitylist_desc` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `activity_id` INT NOT NULL , `type` VARCHAR(250) NOT NULL , `notes` VARCHAR(250) NULL , `lang_id` INT NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
CREATE TABLE `magazine_topic_desc` ( `id` INT NOT NULL , `content` TEXT NOT NULL , `title` VARCHAR(250) NOT NULL , `lang_id` INT NOT NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL ) ENGINE = InnoDB;

ALTER TABLE `magazine_topic_desc` ADD `magazine_topic_id` INT NOT NULL AFTER `title`, ADD `company_id` INT NOT NULL AFTER `magazine_topic_id`, ADD `magazine_id` INT NOT NULL AFTER `company_id`;
ALTER TABLE `magazine_topic_desc` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);

--//
ALTER TABLE `champoines` CHANGE `type` `type` ENUM('CHAMPION','CLASS') CHARACTER SET utf32 COLLATE utf32_general_ci NOT NULL;
ALTER TABLE `activitylist` CHANGE `cat_type` `cat_type` ENUM('COMPANY','PHOTOGRAPHER','FAMOUS','STALL') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
CREATE TABLE `transaction` ( `id` INT NOT NULL , `order_id` INT NOT NULL , `user_id` INT NOT NULL , `amount` DECIMAL(10,2) NOT NULL DEFAULT '0' , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL ) ENGINE = InnoDB;
ALTER TABLE `shop_order` ADD `type` ENUM('NORMAL','RESERVE','','') NOT NULL DEFAULT 'NORMAL' AFTER `address_id`;
ALTER TABLE `shop_order` CHANGE `country` `country` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `shop_order` ADD `company_name` VARCHAR(200) NULL AFTER `type`;


--//
ALTER TABLE `champion_details` ADD `date` DATE NULL AFTER `image`;
ALTER TABLE `champion_details` CHANGE `update_at` `updated_at` TIMESTAMP NULL DEFAULT NULL;



--//
ALTER TABLE `magazine_topic` ADD `date` DATE NULL AFTER `image`;
ALTER TABLE `cities` ADD `country_id` INT NULL AFTER `name`;
CREATE TABLE `countries` ( `id` INT NOT NULL , `name` INT NOT NULL , `created_at` TIMESTAMP NULL , `updated_at` TIMESTAMP NULL ) ENGINE = InnoDB;
ALTER TABLE `countries` CHANGE `name` `name` VARCHAR(100) NOT NULL;
ALTER TABLE `countries` ADD PRIMARY KEY(`id`);
ALTER TABLE `countries` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
INSERT INTO `countries` (`id`, `name`, `created_at`, `updated_at`) VALUES (NULL, 'الكويت', '2020-09-28 23:53:19', '2020-09-28 23:53:19');
delete from cities;
INSERT INTO `cities` (`id`, `name`, `country_id`) VALUES (NULL, 'العاصمة', '1');
INSERT INTO `cities` (`id`, `name`, `country_id`) VALUES (NULL, 'حولي', '1');
INSERT INTO `cities` (`id`, `name`, `country_id`) VALUES (NULL, 'الجهراء', '1');
ALTER TABLE `company_contact` ADD `city_id` INT NULL AFTER `path`;
ALTER TABLE `company_contact` ADD `country` VARCHAR(250) NULL AFTER `city`;
//--
ALTER TABLE `shop_category_description` ADD `created_at` TIMESTAMP NULL AFTER `description`, ADD `updated_at` TIMESTAMP NULL AFTER `created_at`;
UPDATE `admin_menu` SET `title` = 'magazines Blogs' WHERE `admin_menu`.`id` = 113;


ALTER TABLE `champion_details` ADD `birth_date` DATE NULL AFTER `grade`;
ALTER TABLE `champion_details_desc` ADD `color` VARCHAR(250) NULL AFTER `horse_name`;
ALTER TABLE `champion_details_desc` ADD `instructor` VARCHAR(250) NULL AFTER `horse_name`;
ALTER TABLE `champion_details_desc` ADD `owner` VARCHAR(250) NULL AFTER `instructor`;
ALTER TABLE `champion_details_desc` ADD `type` VARCHAR(250) NULL AFTER `owner`;
ALTER TABLE `champion_details_desc` ADD `video` VARCHAR(250) NULL AFTER `updated_at`, ADD `pedigree` VARCHAR(250) NULL AFTER `video`;
ALTER TABLE `champoines_desc` ADD `country` VARCHAR(250) NULL AFTER `name`;
ALTER TABLE `companies` ADD `country` VARCHAR(250) NULL AFTER `logo`;
ALTER TABLE `banner` ADD `title` VARCHAR(250) NULL AFTER `image`;
--
ALTER TABLE `champion_details_desc` ADD `horse_father` VARCHAR(250) NULL AFTER `horse_name`;





