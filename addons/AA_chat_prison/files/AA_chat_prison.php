<?php if (boomAllow($addons['addons_access'])) { ?>
	<script data-cfasync="false" type="text/javascript">
		$(document).ready(function() {
			appLeftMenu('ban', 'Prison', 'openChatPrison();');
		});
		openChatPrison = function() {
			$.post('addons/AA_chat_prison/system/users_prison.php', {
				token: utk,
			}, function(response) {
				if (response == 0) {
					callSaved(system.error, 3);
				} else {
					overModal(response);
				}
			});
		}
		removeUserFromPrison = function(id) {
			$.ajax({
				url: "addons/AA_chat_prison/system/action.php",
				type: "post",
				cache: false,
				dataType: 'json',
				data: {
					out_user: id,
					token: utk
				},
				success: function(response) {
					if (response == 1) {
						callSaved(system.saved, 1);
					} else {
						callSaved(system.error, 3);
					}
				},
			});
		}
	</script>
<?php } ?>
<?php
if (!boomAllow($addons['custom3']) && $addons['custom1'] > 0 && !isStaff($data['user_rank'])) {
?>
	<script data-cfasync="false" type="text/javascript">
		$(document).ready(function() {
			setInterval(goToPrison, speed);
			goToPrison();
		});
		goToPrison = function() {
			$.ajax({
				url: "addons/AA_chat_prison/system/action.php",
				type: "post",
				cache: false,
				dataType: 'json',
				data: {
					go_to_prison: 1,
					token: utk
				},
				success: function(response) {
					if (response == 1) {
						<?php if ($data['user_roomid'] != $addons['custom2']) { ?>
							location.reload();
						<?php } ?>
						$('#rooms_option').remove();
					} else if(response == 2){
						location.reload();
						location.reload();
					}
				},
			});
		}
	</script>
<?php } ?>