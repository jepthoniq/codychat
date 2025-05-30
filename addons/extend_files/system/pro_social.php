<?php
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');

if(!isset($_POST['target'], $_POST['page'])){
	echo boomCode(0);
	die();
}
$id = escape($_POST['target']);
$user = userDetails($id);
if(empty($user)){
	echo boomCode(0);
	die();
}
$user['page'] = escape($_POST['page']);
$content = exTemplate('system/social_media', $user);
if(empty($content)){
	$content = boomTemplate('element/pro_menu_empty');
}
echo boomCode(1, array('data'=> $content));
?>