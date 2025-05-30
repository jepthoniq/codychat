function upgradeToPremium(userId) {
    $.ajax({
        url: 'addons/extend_files/system/upgrade_script.php',
        type: 'POST',
        data: {
            user_id: userId
        },
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message);
                    location.reload();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('خطأ في معالجة الاستجابة: ' + response + '\nالخطأ: ' + e.message);
            }
        },
        error: function(xhr, status, error) {
            alert('خطأ في الطلب: ' + status + ' - ' + error);
        }
    });
}

function saveDeveloperSetting(userId) {
    var coins = $('#dev_usercoins').val();
    var level = $('#dev_userlevel').val();
    
    $.ajax({
        url: 'addons/extend_files/system/save_developer.php',
        type: 'POST',
        data: {
            user_id: userId,
            user_coins: coins,
            user_level: level
        },
        success: function(response) {
            try {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    alert(result.message);
                    location.reload();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                alert('خطأ في معالجة الاستجابة: ' + response + '\nالخطأ: ' + e.message);
            }
        },
        error: function(xhr, status, error) {
            alert('خطأ في الطلب: ' + status + ' - ' + error);
        }
    });
}