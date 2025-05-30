<?php
$load_addons = 'AA_virtual_users';
require_once('../../../system/config_addons.php');
?>

<div class="page_full">
    <div class="page_element">
        <h3 class="centered_element"><?php echo $lang['virtual_users_settings']; ?></h3>
        
        <div class="setting_element">
            <p class="label"><?php echo $lang['virtual_users_count']; ?></p>
            <select id="virtual_users_count">
                <?php
                $counts = array(5, 10, 15, 20, 25, 30);
                foreach($counts as $count) {
                    $selected = ($count == $data['custom1']) ? 'selected' : '';
                    echo "<option value='{$count}' {$selected}>{$count}</option>";
                }
                ?>
            </select>
        </div>
        
        <div class="setting_element">
            <p class="label"><?php echo $lang['virtual_users_room']; ?></p>
            <select id="virtual_users_room">
                <?php echo roomSelect($data['custom2']); ?>
            </select>
        </div>
        
        <div class="setting_element">
            <p class="label"><?php echo $lang['message_interval']; ?></p>
            <input type="number" id="message_interval" value="<?php echo $data['custom3']; ?>" 
                   min="60" max="3600" step="60">
            <small class="help-text">من 60 إلى 3600 ثانية</small>
        </div>
        
        <button onclick="saveVirtualSettings();" type="button" class="reg_button theme_btn">
            <i class="fa fa-save"></i> <?php echo $lang['save']; ?>
        </button>
        
        <!-- إضافة عنصر لعرض رسائل الخطأ -->
        <div id="settings_message" class="settings-message" style="display: none;"></div>
    </div>
</div>

<style>
.settings-message {
    margin-top: 10px;
    padding: 10px;
    border-radius: 4px;
}
.settings-message.success {
    background-color: #dff0d8;
    color: #3c763d;
    border: 1px solid #d6e9c6;
}
.settings-message.error {
    background-color: #f2dede;
    color: #a94442;
    border: 1px solid #ebccd1;
}
</style>

<script type="text/javascript">
function saveVirtualSettings() {
    // إظهار مؤشر التحميل
    var btn = $('button[onclick="saveVirtualSettings();"]');
    var originalText = btn.html();
    btn.html('<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');
    btn.prop('disabled', true);
    
    $.ajax({
        url: 'addons/AA_virtual_users/system/action.php',
        type: 'POST',
        dataType: 'json', // تحديد نوع البيانات المتوقع
        data: {
            save_settings: 1,
            count: $('#virtual_users_count').val(),
            room: $('#virtual_users_room').val(),
            interval: $('#message_interval').val(),
            token: utk
        },
        success: function(response) {
            try {
                if(response.status == 1) {
                    showMessage('success', response.message);
                    callSaved(system.saved, 1);
                } else {
                    showMessage('error', response.message);
                    callSaved(system.error, 3);
                }
            } catch(e) {
                showMessage('error', 'حدث خطأ في معالجة الرد من السيرفر');
                callSaved(system.error, 3);
            }
        },
        error: function(xhr, status, error) {
            showMessage('error', 'حدث خطأ في الاتصال: ' + error);
            callSaved(system.error, 3);
        },
        complete: function() {
            // إعادة الزر إلى حالته الأصلية
            btn.html(originalText);
            btn.prop('disabled', false);
        }
    });
}

function showMessage(type, message) {
    var msgElement = $('#settings_message');
    msgElement.removeClass('success error').addClass(type);
    msgElement.html(message);
    msgElement.fadeIn();
    
    // إخفاء الرسالة بعد 3 ثواني
    setTimeout(function() {
        msgElement.fadeOut();
    }, 3000);
}
</script>