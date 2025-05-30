<?php

/**
 * Codychat
 *
 * @package Codychat
 * @author www.boomcoding.com
 * @copyright 2020
 * @terms any use of this script without a legal license is prohibited
 * all the content of Codychat is the propriety of BoomCoding and Cannot be 
 * used for another project.
 */
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');

$check_action = getDelay();
$online_delay = time() - (86400 * 7);
$online_user = '';
$offline_user = '';
$onair_user = '';
$prim_users = '';
$online_count = 0;
$offline_count = 0;

$onair_count = 0;
$prim_count = 0;

if ($data['last_action'] < getDelay()) {
	$mysqli->query("UPDATE boom_users SET last_action = '" . time() . "' WHERE user_id = '{$data['user_id']}'");
}

$data_list = $mysqli->query("
	SELECT user_name, user_mobile, user_color, user_font, user_rank, user_dj, user_onair, user_join, user_tumb, user_status, user_sex, user_age, user_cover, country,
	fancy_name, name_smile, name_wing1, name_wing2, pic_shadow, user_level, photo_frame, another_prank, prim_plus, name_glow, sp_bg, sp_bg_width,
	user_id, user_mute, user_regmute, room_mute, last_action, user_bot, user_role, user_mood, country
	FROM `boom_users`
	WHERE `user_roomid` = {$data["user_roomid"]}  AND ((last_action > '$check_action') || (user_status = 4) ) AND user_banned = 0 AND user_status != 6 || user_bot = 1
	ORDER BY `user_rank` DESC, user_role DESC, `user_name` ASC 
");

if ($data['max_offcount'] > 0) {
	$offline_list = $mysqli->query("
		SELECT user_name, user_mobile, user_color, user_font, user_rank, user_dj, user_onair, user_join, user_tumb, user_status, user_sex, user_age, user_cover, country,
		fancy_name, name_smile, name_wing1, name_wing2, pic_shadow, user_level, photo_frame, another_prank,name_glow, sp_bg, sp_bg_width,
		user_id, user_mute, user_regmute, room_mute, last_action, user_bot, user_role, user_mood, country
		FROM `boom_users`
		WHERE `user_roomid` = {$data["user_roomid"]}  AND (last_action > '$online_delay' && (user_status != 4)) AND last_action < '$check_action' AND user_status != 6 AND  user_rank != 0 AND user_bot = 0
		ORDER BY last_action DESC LIMIT {$data['max_offcount']}
	");
}

mysqli_close($mysqli);

if ($data_list->num_rows > 0){
	while ($list = $data_list->fetch_assoc()){
		if($list['user_dj'] == 1 && $list['user_onair'] == 1){
			$onair_user .= excreateUserlist($list);
			$onair_count++;
		}
      	else if($list['user_rank'] == 12){
			$dev_user .= excreateUserlist($list, 1);
		}
      	else if($list['user_rank'] == 13){
			$fo_user .= createUserlist($list, 1);
		}
      	else if($list['user_rank'] == 11){
			$owner_user .= excreateUserlist($list, 1);
		}
		else if($list['user_rank'] == 9){
			$admin_user .= excreateUserlist($list, 1);
		}
		else if($list['user_rank'] == 10){
			$super_admin .= excreateUserlist($list, 1);
		}
		else if($list['user_rank'] == 8){
			$mod_user .= excreateUserlist($list, 1);
		}
      	else if($list['user_rank'] == 7){
			$rj_user .= excreateUserlist($list, 1);
		}
      	else if($list['user_rank'] == 3){
			$svip_user .= excreateUserlist($list, 1);
		}
      	else if($list['user_rank'] == 2){
			$vip_user .= excreateUserlist($list, 1);
		}
		else {
			$online_user .= excreateUserlist($list);
			$online_count++;
		}
	}
}
if($data['max_offcount'] > 0){
	if($offline_list->num_rows > 0){
		while($offlist = $offline_list->fetch_assoc()){
			$offline_user .= excreateUserlist($offlist);
		}
	}
}
?>

<div id="container_user">
	<?php if($onair_user != ''){ ?>
	<div class="user_count">
		<div style="color:white;text-shadow: 0 -1px 4px #FFF, 0 -2px 10px #ff0, 0 -10px 20px #ff8000, 0 -18px 40px #F00;font-variant: small-caps;border-radius: 0px; padding:5px;text-align:center;background-color: #000000;background: linear-gradient(245deg, #000000 0%, #FDFF96 100%), linear-gradient(245deg, #0038FF 0%, #000000 100%), radial-gradient(100% 225% at 100% 0%, #4200FF 0%, #001169 100%), linear-gradient(245deg, #000000 0%, #FFB800 100%), radial-gradient(115% 107% at 40% 100%, #EAF5FF 0%, #EAF5FF 40%, #A9C6DE calc(40% + 1px), #A9C6DE 70%, #247E6C calc(70% + 2px), #247E6C 85%, #E4C666 calc(85% + 2px), #E4C666 100%), linear-gradient(65deg, #083836 0%, #083836 40%, #66D37E calc(40% + 1px), #66D37E 60%, #C6E872 calc(60% + 1px), #C6E872 100%);background-blend-mode: overlay, screen, overlay, hard-light, overlay, normal;"><p><i title="Rj" class="fa fa-microphone" style="color:red;"></i> &nbsp;Live Onair<img src="https://chat.all4masti.com/emoticon/emot/music(1).gif" style="margin-top: -7px;display:inline-block;float:right;height:28px; width:70px;"></p>

    </div>
	</div>
	<div class="online_user"><?php echo $onair_user; ?></div>
	<?php } ?>
  	<?php if($dev_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background: rgb(14,25,14);
background: linear-gradient(163deg, rgba(14,25,14,1) 0%, #ff0202 51%, rgba(0,0,0,1) 100%); "><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > المبرمجين </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $dev_user ?></div>
		<?php } ?>
  <?php if($fo_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #7d00ff;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > Founder </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $fo_user ?></div>
		<?php } ?>
  <?php if($owner_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #262cf0;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > المالكين </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $owner_user ?></div>
		<?php } ?>

   
  <?php if($super_admin != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #f026ea;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > الإدارة </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $super_admin ?></div>
		<?php } ?>
  
    
  <?php if($admin_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #9026f0;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > الادمنية </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $admin_user ?></div>
		<?php } ?>
  <?php if($mod_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #26b8f0;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > المشرفين </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $mod_user ?></div>
		<?php } ?>
  <?php if($rj_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #f08d26;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > RJ </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $rj_user ?></div>
		<?php } ?>
  <?php if($svip_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #f03626;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > الشخصيات المهمة </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $svip_user ?></div>
		<?php } ?>
  <?php if($vip_user != ''){ ?>
			<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #32bd83;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > VIP </div></center></h3>
	</div>
			<div class="online_user"><?php  echo $vip_user ?></div>
		<?php } ?>
	<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #26b39f;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > المتصلين </div></center></h3>
	</div>
			<div class="online_user"><?php echo $online_user ?></div>
	</div>
	<?php if($offline_user != ''){ ?>
	<div class="user_count">
			<h3 style="color:white;font-weight: bold;border-radius: 0px;padding:2px;text-align:center;background-color: #990202;"><center><div style="background-image: url(https://cody.angelzchatroom.com/images/usrbg.gif);" > الغير متصلين </div></center></h3>
	</div>
	<div class="online_user"><?php echo $offline_user; ?></div>
	<?php } ?>
	<div class="clear"></div>
</div>