<?php
if(!defined('BOOM')){
    die();
}
if(boomAllow(10)){
    // حذف جداول الإضافة
    $mysqli->query("DROP TABLE IF EXISTS boom_virtual_messages");
    $mysqli->query("DROP TABLE IF EXISTS boom_virtual_users");
}
?>