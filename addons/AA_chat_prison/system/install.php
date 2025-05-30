<?php
if(!defined('BOOM')){
	die();
}
if(boomAllow(10)){
	$ad = array(
	'name' => 'AA_chat_prison',
	'access'=> 10,
	'custom1' => 0,
	'custom2' => 1,
	'custom3' => 0
	);
}
$mysqli->query("ALTER TABLE boom_users ADD user_prison INT(1) NOT NULL DEFAULT '0'");
$mysqli->query("ALTER TABLE boom_users ADD action_prison INT(1) NOT NULL DEFAULT '0'");
?>