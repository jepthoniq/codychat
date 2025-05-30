<?php
// تعطيل عرض الأخطاء لمنع إخراج نص غير JSON
ini_set('display_errors', 0);
error_reporting(0);

// ضمان أن الاستجابة هي JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // تحميل ملف الإعدادات
    require_once('../../../system/config_addons.php');

    // التحقق من وجود user_id وصلاحية المستخدم
    if (!isset($_POST['user_id']) || !boomAllow(11)) {
        echo json_encode(['status' => 'error', 'message' => 'غير مصرح أو معرف المستخدم مفقود']);
        exit;
    }

    // تنظيف معرف المستخدم
    $userId = escape($_POST['user_id']);

    // التحقق من وجود المستخدم
    $user = boomUserInfo($userId);
    if (empty($user)) {
        echo json_encode(['status' => 'error', 'message' => 'المستخدم غير موجود']);
        exit;
    }

    // تحديث حالة العضوية إلى بريميوم
    $mysqli->query("UPDATE boom_users SET user_prim = 1 WHERE user_id = '$userId'");

    if ($mysqli->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'تمت الترقية بنجاح']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'فشل في الترقية']);
    }

    // إغلاق اتصال قاعدة البيانات
    $mysqli->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'خطأ في الخادم: ' . $e->getMessage()]);
}

// ضمان إنهاء التنفيذ
exit;
?>