<?php
$load_addons = 'AA_chat_prison';
require_once('../../../system/config_addons.php');
?>
<h1 class="centered_element error tpad10" style="background: #f3f3f3;padding-bottom: 5px;box-shadow: 0 5px 10px #6262624f;"><?php echo $lang['chat_prison']; ?></h1>
<div class="ulist_container">
    <?php echo getUsersInPrison(); ?>
</div>