<?php
$load_addons = "AA_virtual_users";
require_once "../../../system/config_addons.php";

// قائمة الأسماء العربية
$arabic_names = array(
    'أحمد', 'محمد', 'علي', 'عمر', 'يوسف', 'حسن', 'حسين', 
    'فاطمة', 'عائشة', 'مريم', 'زينب', 'نور', 'ليلى', 'سارة',
    'خالد', 'عبدالله', 'ابراهيم', 'ريم', 'رنا', 'سلمى',
    'جمال', 'كريم', 'منى', 'هدى', 'رامي', 'سمير', 'لينا',
    'ياسر', 'هاني', 'رانيا', 'طارق', 'نادر', 'سامي', 'ناصر',
    'هند', 'دينا', 'سعاد', 'نجلاء', 'أمير', 'باسم', 'حازم'
);

// قائمة الرسائل العربية
$arabic_messages = array(
    'السلام عليكم ورحمة الله وبركاته',
    'كيف حالكم جميعاً؟',
    'يوم سعيد للجميع',
    'مرحباً بكم',
    'صباح الخير',
    'مساء الخير',
    'ما الأخبار؟',
    'كل عام وأنتم بخير',
    'أتمنى لكم يوماً طيباً',
    'أهلاً وسهلاً بالجميع',
    'جزاكم الله خيراً',
    'بارك الله فيكم',
    'أسعد الله مساءكم',
    'صباح النور والسرور',
    'تحياتي للجميع',
    'ما شاء الله',
    'الحمد لله',
    'إن شاء الله',
    'طاب يومكم',
    'أسعد الله صباحكم'
);

// معالجة حفظ الإعدادات
if(isset($_POST['save_settings']) && boomAllow($data['addons_access'])) {
    try {
        $count = (int)escape($_POST['count']);
        $room = (int)escape($_POST['room']);
        $interval = (int)escape($_POST['interval']);
        
        // التحقق من صحة القيم
        if($count < 1 || $count > 30) {
            throw new Exception('عدد المستخدمين يجب أن يكون بين 1 و 30');
        }
        if($interval < 60 || $interval > 3600) {
            throw new Exception('الفاصل الزمني يجب أن يكون بين 60 و 3600 ثانية');
        }
        
        // تحديث الإعدادات
        $query = $mysqli->query("UPDATE boom_addons SET 
            custom1 = '{$count}',
            custom2 = '{$room}',
            custom3 = '{$interval}'
            WHERE addons = 'AA_virtual_users'");
            
        if($query) {
            echo json_encode([
                'status' => 1,
                'message' => 'تم حفظ الإعدادات بنجاح'
            ]);
        } else {
            throw new Exception('حدث خطأ أثناء حفظ الإعدادات');
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => 'خطأ: ' . $e->getMessage()
        ]);
    }
    exit;
}

// إضافة مستخدم وهمي جديد
if(isset($_POST['add_virtual_user']) && boomAllow($data['addons_access'])) {
    try {
        $name = $arabic_names[array_rand($arabic_names)];
        $room = (int)escape($_POST['room_id']);
        
        // التحقق من عدم وجود اسم مكرر
        $check = $mysqli->query("SELECT id FROM boom_virtual_users WHERE username = '$name'");
        if($check->num_rows > 0) {
            $name .= rand(1, 99); // إضافة رقم عشوائي إذا كان الاسم موجوداً
        }
        
        $query = $mysqli->query("INSERT INTO boom_virtual_users (username, room_id, status, last_message) 
                               VALUES ('$name', '$room', 1, NOW())");
        
        if($query) {
            echo json_encode([
                'status' => 1,
                'message' => 'تم إضافة المستخدم بنجاح',
                'user' => [
                    'id' => $mysqli->insert_id,
                    'username' => $name,
                    'room_id' => $room
                ]
            ]);
        } else {
            throw new Exception('حدث خطأ أثناء إضافة المستخدم');
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// حذف مستخدم وهمي
if(isset($_POST['remove_virtual_user']) && boomAllow($data['addons_access'])) {
    try {
        $id = (int)escape($_POST['user_id']);
        
        // حذف المستخدم ورسائله
        $mysqli->query("DELETE FROM boom_virtual_messages WHERE virtual_user_id = '$id'");
        $query = $mysqli->query("DELETE FROM boom_virtual_users WHERE id = '$id'");
        
        if($query) {
            echo json_encode([
                'status' => 1,
                'message' => 'تم حذف المستخدم بنجاح'
            ]);
        } else {
            throw new Exception('حدث خطأ أثناء حذف المستخدم');
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// جلب المستخدمين النشطين
if(isset($_POST['get_active_users'])) {
    try {
        $room_id = isset($_POST['room_id']) ? (int)escape($_POST['room_id']) : (int)$data['custom2'];
        
        $users = $mysqli->query("SELECT id, username, room_id, status 
                               FROM boom_virtual_users 
                               WHERE status = 1 AND room_id = '$room_id'
                               ORDER BY last_message DESC");
        
        $active_users = array();
        while($user = $users->fetch_assoc()) {
            $active_users[] = array(
                'id' => $user['id'],
                'username' => $user['username'],
                'room_id' => $user['room_id'],
                'type' => 'virtual'
            );
        }
        
        echo json_encode([
            'status' => 1,
            'users' => $active_users
        ]);
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// إرسال رسائل من المستخدمين الوهميين
if(isset($_POST['send_messages'])) {
    try {
        $room_id = isset($_POST['room_id']) ? (int)escape($_POST['room_id']) : (int)$data['custom2'];
        
        // اختيار مستخدم عشوائي نشط في الغرفة
        $user_query = $mysqli->query("SELECT id, username FROM boom_virtual_users 
                                    WHERE status = 1 AND room_id = '$room_id'
                                    ORDER BY RAND() LIMIT 1");
        
        if($user = $user_query->fetch_assoc()) {
            $message = $arabic_messages[array_rand($arabic_messages)];
            $user_id = (int)$user['id'];
            
            // إضافة الرسالة
            $mysqli->query("INSERT INTO boom_virtual_messages (virtual_user_id, message, room_id) 
                          VALUES ('$user_id', '$message', '$room_id')");
            
            // تحديث وقت آخر رسالة
            $mysqli->query("UPDATE boom_virtual_users 
                          SET last_message = NOW() 
                          WHERE id = '$user_id'");
            
            echo json_encode([
                'status' => 1,
                'messages' => [[
                    'id' => $mysqli->insert_id,
                    'username' => $user['username'],
                    'message' => $message,
                    'time' => date('H:i'),
                    'user_id' => $user_id,
                    'room_id' => $room_id
                ]]
            ]);
        } else {
            echo json_encode([
                'status' => 0,
                'message' => 'لا يوجد مستخدمين نشطين في هذه الغرفة'
            ]);
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// تحديث حالة المستخدم
if(isset($_POST['update_status']) && boomAllow($data['addons_access'])) {
    try {
        $id = (int)escape($_POST['user_id']);
        $status = (int)escape($_POST['status']);
        
        $query = $mysqli->query("UPDATE boom_virtual_users SET status = '$status' WHERE id = '$id'");
        
        if($query) {
            echo json_encode([
                'status' => 1,
                'message' => 'تم تحديث الحالة بنجاح'
            ]);
        } else {
            throw new Exception('حدث خطأ أثناء تحديث الحالة');
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}

// تحديث غرفة المستخدم
if(isset($_POST['update_room']) && boomAllow($data['addons_access'])) {
    try {
        $id = (int)escape($_POST['user_id']);
        $room_id = (int)escape($_POST['room_id']);
        
        $query = $mysqli->query("UPDATE boom_virtual_users SET room_id = '$room_id' WHERE id = '$id'");
        
        if($query) {
            echo json_encode([
                'status' => 1,
                'message' => 'تم تحديث الغرفة بنجاح'
            ]);
        } else {
            throw new Exception('حدث خطأ أثناء تحديث الغرفة');
        }
    } catch(Exception $e) {
        echo json_encode([
            'status' => 0,
            'message' => $e->getMessage()
        ]);
    }
    exit;
}
?>