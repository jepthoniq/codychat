<?php
// تعطيل عرض الأخطاء لمنع إخراج نص غير JSON
ini_set('display_errors', 0);
error_reporting(0);

// ضمان أن الاستجابة هي JSON
header('Content-Type: application/json; charset=utf-8');

try {
    // تحميل ملف الإعدادات
    require_once('../../../system/config_addons.php');

    // التحقق من وجود user_id، user_coins، user_level، وصلاحية المستخدم
    if (!isset($_POST['user_id']) || !isset($_POST['user_coins']) || !isset($_POST['user_level']) || !boomAllow(10)) {
        echo json_encode(['status' => 'error', 'message' => 'غير مصرح أو بيانات مفقودة']);
        exit;
    }

    // تنظيف البيانات
    $userId = escape($_POST['user_id']);
    $coins = escape($_POST['user_coins']);
    $level = escape($_POST['user_level']);

    // التحقق من أن القيم عددية وصالحة
    if (!is_numeric($coins) || !is_numeric($level) || $coins < 0 || $level < 0) {
        echo json_encode(['status' => 'error', 'message' => 'البيانات غير صالحة']);
        exit;
    }

    // التحقق من وجود المستخدم
    $user = boomUserInfo($userId);
    if (empty($user)) {
        echo json_encode(['status' => 'error', 'message' => 'المستخدم غير موجود']);
        exit;
    }

    // تحديث عملات ومستوى المستخدم
    $mysqli->query("UPDATE boom_users SET user_coins = '$coins', user_level = '$level' WHERE user_id = '$userId'");

    if ($mysqli->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'تم حفظ التغييرات بنجاح']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'فشل في حفظ التغييرات']);
    }

    // إغلاق اتصال قاعدة البيانات
    $mysqli->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'خطأ في الخادم: ' . $e->getMessage()]);
}

// ضمان إنهاء التنفيذ
exit;
?>