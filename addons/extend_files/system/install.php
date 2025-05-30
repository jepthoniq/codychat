<?php
if(!defined('BOOM')){
	die();
}
if(boomAllow(10)){
	$ad = array(
	'name' => 'extend_files',
	'access'=> 0,
	'custom1'=> 1,
	'custom2'=> 1,
	'custom3'=> 1,
	'custom4'=> 1,
	'custom5'=> 1,
	);
}

// boom_users sql.
$mysqli->query("ALTER TABLE `boom_users` ADD user_coins int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD user_exp int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD user_level int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD user_prim int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD prim_end int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD prim_plus int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD prim_plus_end int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD sup_end int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD fancy_name VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_song VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_color VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_shadow VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pic_shadow VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD name_smile int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD name_wing1 VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD name_wing2 VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_fb VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_insta VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_tw VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_wp VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_phone VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD photo_frame VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD user_giftcoins VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_text_main VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_text_sub VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_text_menu VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD pro_background VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD private_profile VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD sp_bg VARCHAR(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD sp_bg_width int(11) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE `boom_users` ADD another_nrank varchar(50) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD another_prank varchar(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD name_glow varchar(100) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_users` ADD user_badge varchar(100) NOT NULL DEFAULT ''");

// boom_setting sql.
$mysqli->query("ALTER TABLE `boom_setting` ADD allow_sendcoins int(11) NOT NULL DEFAULT '1'");
$mysqli->query("ALTER TABLE `boom_setting` ADD allow_takecoins int(11) NOT NULL DEFAULT '8'");
$mysqli->query("ALTER TABLE `boom_setting` ADD coins_gift_text VARCHAR(500) NOT NULL DEFAULT ''");
$mysqli->query("ALTER TABLE `boom_setting` ADD coins_gift_code int(11) NOT NULL DEFAULT '1'");

// store sql.
$mysqli->query("CREATE TABLE IF NOT EXISTS `boom_store` (`store_id` int(11) NOT NULL PRIMARY KEY,`store_name` varchar(100) NOT NULL,`store_info` varchar(500) NOT NULL,`store_price` int(11) NOT NULL,`store_level` int(11) NOT NULL,`store_rank` int(11) NOT NULL DEFAULT '0',`store_time` INT(11) NOT NULL DEFAULT '0')");
$mysqli->query("INSERT INTO `boom_store` (`store_id`, `store_name`, `store_info`, `store_price`, `store_level`, `store_rank`, `store_time`) VALUES
(1, 'VIP', 'Type something here', 15000, 30, 2, 1),
(2, 'Moderator', 'Type something here', 70000, 50, 8, 1),
(3, 'Admin', 'Type something here', 100000, 70, 9, 1),
(4, 'Premium 7 days', 'Type something here', 49999, 50, 1, 0),
(5, 'Premium 15 days', 'Type something here', 59999, 70, 2, 0),
(6, 'Premium 30 days', 'Type something here', 79999, 80, 3, 0)");
?>