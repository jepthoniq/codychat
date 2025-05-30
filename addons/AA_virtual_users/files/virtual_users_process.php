<?php
function addVirtualUsersToRoom($room_id) {
    global $mysqli, $data;
    
    // جلب المستخدمين الوهميين النشطين في هذه الغرفة
    $virtual_users = $mysqli->query("SELECT * FROM boom_virtual_users 
                                   WHERE status = 1 AND room_id = '$room_id'");
    
    $users_array = array();
    while($user = $virtual_users->fetch_assoc()) {
        $users_array[] = array(
            'user_id' => 'v_' . $user['id'], // إضافة بادئة v_ للتمييز
            'user_name' => $user['username'],
            'user_rank' => 1, // رتبة عادية
            'user_sex' => rand(0, 1), // جنس عشوائي
            'user_avatar' => 'default_avatar.png',
            'user_status' => 1,
            'user_bot' => 1, // تعيين كروبوت
            'is_virtual' => true
        );
    }
    
    return $users_array;
}

// دمج المستخدمين الوهميين مع قائمة المستخدمين الحقيقيين
function mergeVirtualUsers($real_users, $room_id) {
    $virtual_users = addVirtualUsersToRoom($room_id);
    return array_merge($real_users, $virtual_users);
}
?>