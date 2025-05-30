<?php
if(!defined('BOOM')){
    die();
}
if(boomAllow(10)){
    $ad = array(
        'name' => 'AA_virtual_users',
        'access'=> 10,
        'custom1' => 5,    // عدد المستخدمين الافتراضي
        'custom2' => 1,    // معرف الغرفة الافتراضية
        'custom3' => 300   // فترة إرسال الرسائل (بالثواني)
    );
}

// إنشاء جدول المستخدمين الوهميين
$mysqli->query("CREATE TABLE IF NOT EXISTS boom_virtual_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    room_id INT NOT NULL DEFAULT 1,
    avatar VARCHAR(255),
    last_message TIMESTAMP,
    status INT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// إنشاء جدول رسائل المستخدمين الوهميين
$mysqli->query("CREATE TABLE IF NOT EXISTS boom_virtual_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    virtual_user_id INT,
    message TEXT,
    room_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (virtual_user_id) REFERENCES boom_virtual_users(id) ON DELETE CASCADE
)");
?>