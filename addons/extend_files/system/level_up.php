<?php
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');
?>
<div class="bpad30 hpad30">
	<div class="centered_element bpad15">
		<img class="large_icon" src="<?php echo $data['domain']; ?>/addons/extend_files/files/level-up-logo.png"/>
	</div>
	<div class="centered_element">
<p class="text_large bold">ترقية جديدة</p>
		<p class="text_small tpad10">تم ترقيتك الى رتبة <?php $newlevel = $data['user_level'] + 1; echo $newlevel; ?> مبروك حبيبي</p>
	</div>
	<div class="tpad20 centered_element">
		<button class="close_modal ok_btn reg_button">continue</button>
	</div>
</div>