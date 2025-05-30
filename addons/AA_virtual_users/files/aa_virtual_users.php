<?php
if (boomAllow($addons['addons_access'])) {
?>
<script type="text/javascript">
// المتغيرات العامة
let virtualUsers = [];
let messageInterval;
let defaultRoomId = <?php echo (int)$addons['custom2']; ?>;
let currentActiveRoom = defaultRoomId;
let isInitialized = false;

$(document).ready(function() {
    // إضافة زر في القائمة
    appLeftMenu('users', 'المستخدمين الوهميين', 'openVirtualUsers();');
    
    // بدء النظام بعد تحميل الصفحة
    initializeVirtualSystem();
});

// تهيئة النظام
function initializeVirtualSystem() {
    // انتظار حتى يتم تحميل النظام
    if (typeof boomSystem === 'undefined') {
        setTimeout(initializeVirtualSystem, 1000);
        return;
    }
    
    console.log('بدء تهيئة نظام المستخدمين الوهميين');
    
    // تحديد الغرفة الحالية
    if(typeof boom_default !== 'undefined' && boom_default.room > 0) {
        currentActiveRoom = boom_default.room;
    }
    
    // إضافة المستمعات للأحداث
    $(document).on('room_changed', function(e, room_id) {
        currentActiveRoom = room_id;
        updateVirtualUsers();
    });
    
    // بدء تشغيل المستخدمين الوهميين
    loadVirtualUsers();
}

// تحميل المستخدمين الوهميين
function loadVirtualUsers() {
    // إيقاف أي فواصل زمنية سابقة
    if(messageInterval) {
        clearInterval(messageInterval);
    }
    
    $.ajax({
        url: 'addons/AA_virtual_users/system/action.php',
        type: 'POST',
        data: {
            get_active_users: 1,
            room_id: currentActiveRoom,
            token: utk
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.status == 1 && data.users) {
                    virtualUsers = data.users;
                    // إضافة المستخدمين للغرفة
                    joinVirtualUsersToRoom();
                }
            } catch(e) {
                console.error('خطأ في تحميل المستخدمين الوهميين:', e);
            }
        }
    });
    
    // بدء دورة إرسال الرسائل والتحديث
    messageInterval = setInterval(function() {
        if(currentActiveRoom > 0) {
            updateVirtualUsers();
            sendVirtualMessages();
        }
    }, <?php echo (int)$addons['custom3'] * 1000; ?>);
}

// إضافة المستخدمين الوهميين للغرفة
function joinVirtualUsersToRoom() {
    if(!virtualUsers.length) return;
    
    console.log('إضافة المستخدمين الوهميين للغرفة:', currentActiveRoom);
    
    // إضافة كل مستخدم للغرفة
    virtualUsers.forEach(function(user) {
        var userId = 'v_' + user.id;
        
        // التحقق من وجود المستخدم
        if($('#user_' + userId).length === 0) {
            // إنشاء بيانات المستخدم
            var userData = {
                id: userId,
                username: user.username,
                avatar: 'default_avatar.png',
                room_id: currentActiveRoom,
                user_status: 1,
                user_role: 'virtual',
                join_msg: 1
            };
            
            // إرسال رسالة الانضمام للغرفة
            $.ajax({
                url: 'addons/AA_virtual_users/system/action.php',
                type: 'POST',
                data: {
                    join_room: 1,
                    user_id: user.id,
                    room_id: currentActiveRoom,
                    token: utk
                },
                success: function(response) {
                    try {
                        var data = JSON.parse(response);
                        if(data.status == 1) {
                            // إضافة المستخدم لقائمة المستخدمين
                            var userElement = `
                                <div id="user_${userId}" class="user_element virtual_user" data-user="${userId}" data-status="1">
                                    <div class="user_wrap">
                                        <div class="user_avatar">
                                            <img src="default_avatar.png" class="avatar_img" alt="${user.username}">
                                        </div>
                                        <div class="user_info">
                                            <div class="user_name">${user.username}</div>
                                            <div class="user_status"><i class="fa fa-circle"></i></div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            
                            $('.users_list').append(userElement);
                            
                            // تحديث عداد المستخدمين
                            updateUsersCount();
                        }
                    } catch(e) {
                        console.error('خطأ في إضافة المستخدم للغرفة:', e);
                    }
                }
            });
        }
    });
}

// تحديث قائمة المستخدمين الوهميين
function updateVirtualUsers() {
    $.ajax({
        url: 'addons/AA_virtual_users/system/action.php',
        type: 'POST',
        data: {
            get_active_users: 1,
            room_id: currentActiveRoom,
            token: utk
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.status == 1 && data.users) {
                    virtualUsers = data.users;
                }
            } catch(e) {
                console.error('خطأ في تحديث المستخدمين الوهميين:', e);
            }
        }
    });
}

// تحديث عداد المستخدمين
function updateUsersCount() {
    var roomCount = $('.room_count');
    if(roomCount.length > 0) {
        var count = $('.user_element').length;
        roomCount.text(count);
    }
}

// إرسال رسائل من المستخدمين الوهميين
function sendVirtualMessages() {
    if(!virtualUsers.length) return;
    
    $.ajax({
        url: 'addons/AA_virtual_users/system/action.php',
        type: 'POST',
        data: {
            send_messages: 1,
            room_id: currentActiveRoom,
            token: utk
        },
        success: function(response) {
            try {
                var data = JSON.parse(response);
                if(data.status == 1 && data.messages) {
                    data.messages.forEach(function(msg) {
                        createVirtualMessage(msg);
                    });
                }
            } catch(e) {
                console.error('خطأ في إرسال الرسائل:', e);
            }
        }
    });
}

// إنشاء رسالة في نافذة الدردشة
function createVirtualMessage(msg) {
    var chatMessages = $('.chat_messages');
    if(chatMessages.length === 0) return;
    
    var messageElement = `
        <div class="chat_message virtual_message" data-id="v_${msg.id}">
            <div class="message_user">
                <img src="default_avatar.png" class="avatar_img">
                <span class="user_name">${msg.username}</span>
            </div>
            <div class="message_content">
                <div class="message_text">${msg.message}</div>
                <div class="message_time">${msg.time}</div>
            </div>
        </div>
    `;
    
    chatMessages.append(messageElement);
    chatMessages.scrollTop(chatMessages[0].scrollHeight);
}

// فتح نافذة إدارة المستخدمين الوهميين
function openVirtualUsers() {
    $.post('addons/AA_virtual_users/system/manage_users.php', {
        token: utk
    }, function(response) {
        if(response) {
            overModal(response);
        }
    });
}
</script>

<style>
.virtual_user {
    opacity: 0.9;
}
.virtual_user .user_wrap {
    background: rgba(0, 0, 0, 0.02);
    border: 1px solid rgba(0, 0, 0, 0.05);
}
.virtual_user .user_status i {
    color: #4CAF50;
}
.virtual_message {
    background-color: rgba(0, 0, 0, 0.01);
}
.virtual_message .message_content {
    border-right: 2px solid #4CAF50;
}
.virtual_message .user_name {
    color: #4CAF50;
}
.virtual_message .message_time {
    opacity: 0.7;
}
.virtual_user:hover {
    opacity: 1;
}
.virtual_user .user_info {
    display: flex;
    flex-direction: column;
    margin-left: 10px;
}
.virtual_message .message_user {
    display: flex;
    align-items: center;
    margin-bottom: 5px;
}
.virtual_message .message_user img {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    margin-right: 8px;
}
</style>
<?php 
}
?>