<?php
$load_addons = 'AA_virtual_users';
require_once('../../../system/config_addons.php');

// استرجاع قائمة المستخدمين الوهميين
$users = $mysqli->query("SELECT * FROM boom_virtual_users ORDER BY created_at DESC");
?>

<div class="modal_top">
    <div class="modal_top_empty">
        <div class="modal_title"><?php echo $lang['virtual_users']; ?></div>
        <div onclick="closeModal();" class="modal_close">
            <i class="fa fa-times"></i>
        </div>
    </div>
</div>

<div class="modal_content">
    <div class="pad20">
        <button onclick="addVirtualUser(<?php echo $data['custom2']; ?>);" class="reg_button theme_btn">
            <i class="fa fa-plus"></i> <?php echo $lang['add_virtual_user']; ?>
        </button>
        
        <div class="virtual_users_list">
            <?php
            if($users->num_rows > 0) {
                while($user = $users->fetch_assoc()) {
                    ?>
                    <div class="virtual_user_item">
                        <div class="virtual_user_info">
                            <span class="username"><?php echo $user['username']; ?></span>
                            <span class="room">الغرفة: <?php echo getRoomName($user['room_id']); ?></span>
                            <span class="last_active">آخر نشاط: <?php echo timeAgo($user['last_message']); ?></span>
                        </div>
                        <div class="virtual_user_actions">
                            <button onclick="removeVirtualUser(<?php echo $user['id']; ?>);" 
                                    class="small_button error_btn">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="no_results">لا يوجد مستخدمين وهميين حالياً</div>';
            }
            ?>
        </div>
    </div>
</div>

<style>
.virtual_users_list {
    margin-top: 20px;
}
.virtual_user_item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #eee;
}
.virtual_user_info {
    display: flex;
    flex-direction: column;
}
.virtual_user_info .username {
    font-weight: bold;
    font-size: 16px;
}
.virtual_user_info .room,
.virtual_user_info .last_active {
    font-size: 12px;
    color: #666;
}
.no_results {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>