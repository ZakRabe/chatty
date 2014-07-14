
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


CREATE TABLE IF NOT EXISTS `YiiSession` (
  `id` char(32) NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `activkey` varchar(128) NOT NULL DEFAULT '',
  `createtime` int(10) NOT NULL DEFAULT '0',
  `lastvisit` int(10) NOT NULL DEFAULT '0',
  `superuser` int(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`),
  KEY `superuser` (`superuser`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `admin_profiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lastname` varchar(50) NOT NULL DEFAULT '',
  `firstname` varchar(50) NOT NULL DEFAULT '',
  `birthday` date NOT NULL DEFAULT '0000-00-00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `admin_profiles_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `varname` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `field_type` varchar(50) NOT NULL,
  `field_size` int(3) NOT NULL DEFAULT '0',
  `field_size_min` int(3) NOT NULL DEFAULT '0',
  `required` int(1) NOT NULL DEFAULT '0',
  `match` varchar(255) NOT NULL DEFAULT '',
  `range` varchar(255) NOT NULL DEFAULT '',
  `error_message` varchar(255) NOT NULL DEFAULT '',
  `other_validator` varchar(5000) NOT NULL DEFAULT '',
  `default` varchar(255) NOT NULL DEFAULT '',
  `widget` varchar(255) NOT NULL DEFAULT '',
  `widgetparams` varchar(5000) NOT NULL DEFAULT '',
  `position` int(3) NOT NULL DEFAULT '0',
  `visible` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `varname` (`varname`,`widget`,`visible`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `cms_page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `content` text NOT NULL,
  `tags` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `lookup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `code` int(11) NOT NULL,
  `type` varchar(128) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `profile` text,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `dob` date NULL DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `subscribed` int(1) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `update_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `user_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL,
  `street1` varchar(250) NOT NULL DEFAULT '',
  `street2` varchar(250) NOT NULL DEFAULT '',
  `suburb` varchar(250) NOT NULL DEFAULT '',
  `city` varchar(250) NOT NULL DEFAULT '',
  `code` varchar(10) NOT NULL DEFAULT '',
  `country` varchar(250) NOT NULL DEFAULT '',
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `update_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `country` varchar(255) NOT NULL,
  `user_id` int(11) NULL,
  `email` varchar(255) NOT NULL,
  `subscribed` tinyint(1) NOT NULL DEFAULT '1',
  `update_time` int(11) NOT NULL,
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `cart` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sess_id` varchar(250) NOT NULL DEFAULT '',
  `user_id` int(10) NOT NULL DEFAULT '0',
  `created` int(20) NOT NULL DEFAULT '0',
  `modified` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `cart_product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL DEFAULT '0',
  `product_id` int(10) NOT NULL DEFAULT '0', 
  `price_id` int(10) NOT NULL DEFAULT '0',
  `quantity` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `cart_product_option` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cart_product_id` int(11) NOT NULL DEFAULT '0',
  `option_id` int(10) NOT NULL DEFAULT '0',
  `price_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) NOT NULL DEFAULT '0',
  `address_id` int(10) NOT NULL DEFAULT '0',
  `firstname` varchar(250) NOT NULL DEFAULT '',
  `lastname` varchar(250) NOT NULL DEFAULT '',
  `email` varchar(250) NOT NULL DEFAULT '',
  `organisation` varchar(250) NOT NULL DEFAULT '',
  `postal_street1` varchar(250) NOT NULL DEFAULT '',
  `postal_street2` varchar(250) NOT NULL DEFAULT '',
  `postal_suburb` varchar(250) NOT NULL DEFAULT '',
  `postal_city` varchar(250) NOT NULL DEFAULT '',
  `postal_code` varchar(10) NOT NULL DEFAULT '',
  `postal_country` varchar(250) NOT NULL DEFAULT '',
  `comments` longtext NOT NULL,
  `payment_method` varchar(100) NOT NULL DEFAULT '',
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending',
  `currency` varchar(100) NOT NULL DEFAULT '',
  `shipping`  decimal(18,4) NOT NULL DEFAULT '0.00',
  `total` decimal(18,4) NOT NULL DEFAULT '0.00',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `order_history` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `admin_id` int(10) NOT NULL DEFAULT '0',
  `payment_status` varchar(100) NOT NULL DEFAULT 'pending',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `order_product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) NOT NULL DEFAULT '0',
  `product_id` int(10) NOT NULL DEFAULT '0',
  `price_id` int(10) NOT NULL DEFAULT '0',
  `product_title` varchar(250) NOT NULL DEFAULT '',
  `shipping` decimal(18,4) NOT NULL DEFAULT '0.00',
  `options` decimal(18,4) NOT NULL DEFAULT '0.00',
  `price` decimal(18,4) NOT NULL DEFAULT '0.00',
  `total` decimal(18,4) NOT NULL DEFAULT '0.00',
  `special` int(1) NOT NULL DEFAULT '0',
  `quantity` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `order_product_option` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order_product_id` int(10) NOT NULL DEFAULT '0',
  `option_id` int(10) NOT NULL DEFAULT '0',
  `option_title` varchar(250) NOT NULL DEFAULT '',
  `option_group_title` varchar(250) NOT NULL DEFAULT '',
  `price` decimal(18,4) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



-- CREATE TABLE IF NOT EXISTS `order_transaction` 
-- TODO: need to find generic data between all merchant gateways!!



CREATE TABLE IF NOT EXISTS `product` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `code` varchar(250) NOT NULL DEFAULT '',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `description_detail` longtext NOT NULL,
  `specs` longtext NOT NULL,
  `weight` varchar(250) NOT NULL,
  `feature` tinyint(1) NOT NULL DEFAULT '0',
  `views` int(10) NOT NULL DEFAULT '0',
  `impressions` int(10) NOT NULL DEFAULT '0',
  `global_price` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `sort` int(10) NOT NULL DEFAULT '0',
  `modified` int(20) NOT NULL DEFAULT '0',
  `created` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `product_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) NOT NULL,
  `sort` int(10) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `image` varchar(250) NOT NULL DEFAULT '',
  `keywords` text NOT NULL,
  `description` text NOT NULL,
  `description_detail` longtext NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `modified` int(20) NOT NULL DEFAULT '0',
  `created` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `product_category_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `category_id` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `product_image` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL DEFAULT '0',
  `image` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) NOT NULL DEFAULT '',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `product_option` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group_id` int(10) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL DEFAULT '',
  `global_price` varchar(20) NOT NULL DEFAULT '',
  `global_code` varchar(20) NOT NULL DEFAULT '',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `product_option_group` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL DEFAULT '',
  `code` enum('prefix','suffix') NOT NULL DEFAULT 'suffix',
  `sort` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;



CREATE TABLE IF NOT EXISTS `product_option_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL DEFAULT '0',
  `option_id` int(10) NOT NULL DEFAULT '0',
  `price_id` int(10) NOT NULL DEFAULT '0',
  `setting_id` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;



CREATE TABLE IF NOT EXISTS `product_price` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `product_id` int(10) NOT NULL,
  `option_id` int(10) NOT NULL DEFAULT '0',
  `price` decimal(18,4) NOT NULL DEFAULT '0.00',
  `sale` tinyint(1) NOT NULL,
  `date` int(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;


CREATE TABLE IF NOT EXISTS `product_option_settings` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `status` tinyint(1) NOT NULL  DEFAULT '1',
  `group_status` tinyint(1) NOT NULL  DEFAULT '1',
  `use_custom_code` tinyint(1) NOT NULL  DEFAULT '0',
  `code` varchar(250) NOT NULL DEFAULT '',
  `use_option_global_price` tinyint(1) NOT NULL  DEFAULT '0',
  `deleted` tinyint(1) NOT NULL  DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `admin_profiles`
  ADD CONSTRAINT `FK_admin_profiles_admin` FOREIGN KEY (`user_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `email`
  ADD CONSTRAINT `FK_email_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `user_address`
  ADD CONSTRAINT `FK_user_address_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
#ALTER TABLE `cart`
#  ADD CONSTRAINT `FK_cart_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `cart_product`
  ADD CONSTRAINT `FK_cart_product_cart` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cart_product_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cart_product_product_price` FOREIGN KEY (`price_id`) REFERENCES `product_price` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `cart_product_option`
  ADD CONSTRAINT `FK_cart_product_option_cart_product` FOREIGN KEY (`cart_product_id`) REFERENCES `cart_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_cart_product_option_product_option` FOREIGN KEY (`option_id`) REFERENCES `product_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
  
ALTER TABLE `order`
  ADD CONSTRAINT `FK_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_order_user_address` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
 
ALTER TABLE `order_history`
  ADD CONSTRAINT `FK_order_history_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION, 
  ADD CONSTRAINT `FK_order_history_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE `order_product`
  ADD CONSTRAINT `FK_order_product_order` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_order_product_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_order_product_product_price` FOREIGN KEY (`price_id`) REFERENCES `product_price` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE `order_product_option`
  ADD CONSTRAINT `FK_order_product_option_order_product` FOREIGN KEY (`order_product_id`) REFERENCES `order_product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_order_product_option_product_option` FOREIGN KEY (`option_id`) REFERENCES `product_option` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE `product_category_link`
  ADD CONSTRAINT `FK_product_category_link_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_category_link_product_category` FOREIGN KEY (`category_id`) REFERENCES `product_category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;
 
ALTER TABLE `product_image`
  ADD CONSTRAINT `FK_product_image_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `product_option`
  ADD CONSTRAINT `FK_product_option_product_option_group` FOREIGN KEY (`group_id`) REFERENCES `product_option_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
ALTER TABLE `product_option_link`
  ADD CONSTRAINT `FK_product_option_link_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_option_link_product_option` FOREIGN KEY (`option_id`) REFERENCES `product_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_option_link_product_price` FOREIGN KEY (`price_id`) REFERENCES `product_price` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_option_link_product_option_settings` FOREIGN KEY (`setting_id`) REFERENCES `product_option_settings` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

ALTER TABLE `product_price`
  ADD CONSTRAINT `FK_product_price_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_product_price_product_option` FOREIGN KEY (`option_id`) REFERENCES `product_option` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;


INSERT INTO `admin` SET
  `username`      = 'admin',
  `password`      = 'e4a5b2413fb858535cf7a266e7f927eb',
  `email`         = 'team@reactordigital.com.au',
  `activkey`      = '0447d1752bb75b97932fe0b003c4daa6',
  `createtime`    = 1349761526,
  `lastvisit`     = 1373848633,
  `superuser`     = 1,
  `status`        = 1
