<?php
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');
if(isset($_POST['set_private_profile'])){
    $pri_profile = escape($_POST['set_private_profile']);
    if(!boomAllow($data['addons_access'])){
        die();
    }
    $mysqli->query("UPDATE boom_users SET private_profile = '$pri_profile' WHERE user_id = '{$data['user_id']}'");
	echo 1;
}
?>