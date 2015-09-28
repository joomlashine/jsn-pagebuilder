CREATE TABLE IF NOT EXISTS `#__jsn_pagebuilder_config` (
	`name` varchar( 255 ) NOT NULL ,
	`value` text NOT NULL ,
	UNIQUE KEY `name` ( `name` )
)  CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__jsn_pagebuilder_content_custom_css` (
  `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `content` BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
  `css_key` VARCHAR(255) NULL,
  `css_value` LONGTEXT NULL,
  PRIMARY KEY (`id`)
  )  CHARACTER SET `utf8`;

CREATE TABLE IF NOT EXISTS `#__jsn_pagebuilder_messages` (
	`msg_id` int(11) NOT NULL AUTO_INCREMENT,
	`msg_screen` varchar(150) DEFAULT NULL,
	`published` tinyint(1) DEFAULT '1',
	`ordering` int(11) DEFAULT '0',
	PRIMARY KEY (`msg_id`),
	UNIQUE KEY `message` (`msg_screen`,`ordering`)
)  CHARACTER SET `utf8`;
