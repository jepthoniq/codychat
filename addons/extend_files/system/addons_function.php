<?php
function loadAddonName($name)
{
	global $mysqli;
	$get_vip = $mysqli->query("SELECT * FROM boom_addons WHERE addons = '$name'");
	if ($get_vip->num_rows > 0) {
		return true;
	} else {
		return false;
	}
}
function exTemplate($getpage, $boom = '')
{
	global $data, $lang, $mysqli, $cody;
	$page = BOOM_PATH . '/addons/extend_files/' . $getpage . '.php';
	$structure = '';
	ob_start();
	require($page);
	$structure = ob_get_contents();
	ob_end_clean();
	return $structure;
}
function premiumDate($date)
{
	return date("Y-m-d", $date);
}
function premiumEndingDate($val)
{
	global $lang;
	if ($val == 0) {
		return 'Life time';
	} else {
		return '<i class="fa fa-clock-o error"></i> ' .  premiumDate($val);
	}
}
// user post chat
function exUserPostChats($content, $custom = array())
{
	global $mysqli, $data;
	$def = array(
		'hunter' => $data['user_id'],
		'room' => $data['user_roomid'],
		'color' => escape(myTextColor($data)),
		'type' => 'public__message',
		'rank' => 99,
		'snum' => '',
	);
	$c = array_merge($def, $data, $custom);
	$mysqli->query("INSERT INTO `boom_chat` (post_date, user_id, post_message, post_roomid, type, log_rank, snum, tcolor) VALUES ('" . time() . "', '{$c['hunter']}', '$content', '{$c['room']}', '{$c['type']}', '{$c['rank']}', '{$c['snum']}', '{$c['color']}')");
	$last_id = $mysqli->insert_id;
	chatAction($data['user_roomid']);
	if (boomAllow(1)) {
		$mysqli->query("UPDATE boom_users SET user_coins = user_coins + 1 WHERE user_id = '{$data['user_id']}'");
		userExpLevel();
		getMyGift($content);
	}
	if (!empty($c['snum'])) {
		$user_post = array(
			'post_id' => $last_id,
			'type' => $c['type'],
			'post_date' => time(),
			'tcolor' => $c['color'],
			'log_rank' => $c['rank'],
			'post_message' => $content,
		);
		$post = array_merge($c, $user_post);
		if (!empty($post)) {
			return exCreateLogs($data, $post);
		}
	}
}
// Levels system
function userExpLevel()
{
	global $mysqli, $data;
	$exp = $data['user_level'] == 0 ? 5 : $data['user_level'] * 50;
	$maxlevel = 100;
	$next_exp = $exp - 1;
	if ($data['user_exp'] < $next_exp && $data['user_level'] < 100) {
		$mysqli->query("UPDATE boom_users SET user_exp = user_exp + 1  WHERE user_id = '{$data['user_id']}'");
	} else if ($data['user_exp'] == $next_exp) {
		$newlev = $data['user_level'] + 1;
		$mysqli->query("UPDATE boom_users SET user_exp = 0  WHERE user_id = '{$data['user_id']}'");
		$mysqli->query("UPDATE boom_users SET user_level = user_level + 1  WHERE user_id = '{$data['user_id']}'");
		$msg = str_replace(array('@user@', '@level@'), array($data['user_name'], $newlev), '<a style="width: 15px;display: inline-block;" class="status_icon"><img style="width: 12px;height:12px;" src="default_images/status/online.svg"></a> 
		المستخدم <b style="color:red;"> [ @user@ ] </b> تم ترقيتة الى رتبة <b style="color:red;"> [ @level@ ] </b> من خلال نقاط الترفيع');
		systemPostChat($data['user_roomid'], $msg);
		echo exTemplate('system/level_up');
	} elseif ($data['user_level'] == $maxlevel) {
		return false;
	}
}
function setProfileColors($user)
{
	global $data;
	$color = $user['pro_color'];
	if (!empty($user['pro_color'])) {
		return 'class="' . $color . '"';
	}
}
function setProfileShadows($user)
{
	global $data;
	$shadow = $user['pro_shadow'];
	if (!empty($user['pro_shadow'])) {
		return 'class="' . $shadow . '"';
	}
}
function showUserSmile($smile)
{
	switch ($smile) {
		case 1:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 46px; height: 46px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3312" style="position: absolute; top: 0px; left: 0px; width: 238464px; height: 46px; animation: 6s steps(72) 0s infinite normal none running sm_3312; background: url(addons/chat_store/files/smiles/1.png) no-repeat;"></span></span>
';
		case 4:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 259200px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/2.png) no-repeat;"></span></span>
';
		case 6:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 48px; height: 48px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3456" style="position: absolute; top: 0px; left: 0px; width: 248832px; height: 48px; animation: 6s steps(72) 0s infinite normal none running sm_3456; background: url(addons/chat_store/files/smiles/3.png) no-repeat;"></span></span>
';
		case 7:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 34px; height: 34px; overflow: hidden; position: absolute; margin-top: -7.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_2448" style="position: absolute; top: 0px; left: 0px; width: 176256px; height: 34px; animation: 6s steps(72) 0s infinite normal none running sm_2448; background: url(addons/chat_store/files/smiles/4.png) no-repeat;"></span></span>
';
		case 8:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 259200px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/5.png) no-repeat;"></span></span>
';
		case 9:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 48px; height: 48px; overflow: hidden; position: absolute; margin-top: -10.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3456" style="position: absolute; top: 0px; left: 0px; width: 248832px; height: 48px; animation: 6s steps(72) 0s infinite normal none running sm_3456; background: url(addons/chat_store/files/smiles/6.png) no-repeat;"></span></span>
';
		case 10:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 44px; height: 44px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3168" style="position: absolute; top: 0px; left: 0px; width: 228096px; height: 44px; animation: 6s steps(72) 0s infinite normal none running sm_3168; background: url(addons/chat_store/files/smiles/7.png) no-repeat;"></span></span>
';
		case 11:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 36px; height: 36px; overflow: hidden; position: absolute; margin-top: -9.5px; margin-left: -2.5px;"><span data-animation="3s steps(36) 0s infinite normal none running sm_1296" style="position: absolute; top: 0px; left: 0px; width: 46656px; height: 36px; animation: 3s steps(36) 0s infinite normal none running sm_1296; background: url(addons/chat_store/files/smiles/8.png) no-repeat;"></span></span>
';
		case 12:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 34px; height: 34px; overflow: hidden; position: absolute; margin-top: -8.5px; margin-left: -2.5px;"><span data-animation="1.5s steps(18) 0s infinite normal none running sm_612" style="position: absolute; top: 0px; left: 0px; width: 11016px; height: 34px; animation: 1.5s steps(18) 0s infinite normal none running sm_612; background: url(addons/chat_store/files/smiles/9.png) no-repeat;"></span></span>
';
		case 13:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 44px; height: 44px; overflow: hidden; position: absolute; margin-top: -9.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3168" style="position: absolute; top: 0px; left: 0px; width: 228096px; height: 44px; animation: 6s steps(72) 0s infinite normal none running sm_3168; background: url(addons/chat_store/files/smiles/10.png) no-repeat;"></span></span>
';
		case 14:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 259200px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/11.png) no-repeat;"></span></span>
';
		case 15:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 259200px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/12.png) no-repeat;"></span></span>
';
		case 16:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 46px; height: 46px; overflow: hidden; position: absolute; margin-top: -12.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3312" style="position: absolute; top: 0px; left: 0px; width: 238464px; height: 46px; animation: 6s steps(72) 0s infinite normal none running sm_3312; background: url(addons/chat_store/files/smiles/13.png) no-repeat;"></span></span>
';
		case 17:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 259200px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/14.png) no-repeat;"></span></span>
';
		case 18:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 40px; height: 40px; overflow: hidden; position: absolute; margin-top: -9.5px; margin-left: -2.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_2880" style="position: absolute; top: 0px; left: 0px; width: 207360px; height: 40px; animation: 6s steps(72) 0s infinite normal none running sm_2880; background: url(addons/chat_store/files/smiles/15.png) no-repeat;"></span></span>
';
		case 19:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/16.png) no-repeat;"></span></span>
';
		case 20:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/17.png) no-repeat;"></span></span>
';
		case 21:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3456" style="position: absolute; top: 0px; left: 0px; width: 3456px; height: 48px; animation: 6s steps(72) 0s infinite normal none running sm_3456; background: url(addons/chat_store/files/smiles/18.png) no-repeat;"></span></span>
';
		case 22:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3456" style="position: absolute; top: 0px; left: 0px; width: 3456px; height: 48px; animation: 6s steps(72) 0s infinite normal none running sm_3456; background: url(addons/chat_store/files/smiles/19.png) no-repeat;"></span></span>
';
		case 23:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/20.png) no-repeat;"></span></span>
';
        case 24:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/21.png) no-repeat;"></span></span>
';
        case 25:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/22.webp) no-repeat;"></span></span>
';
        case 26:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/23.png) no-repeat;"></span></span>
';
        case 27:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/24.png) no-repeat;"></span></span>
';
        case 28:
			return '<span style="transform: scale(0.7, 0.7); display: inline-block; width: 50px; height: 50px; overflow: hidden; position: absolute; margin-top: -18.5px; margin-left: -3.5px;"><span data-animation="6s steps(72) 0s infinite normal none running sm_3600" style="position: absolute; top: 0px; left: 0px; width: 3600px; height: 50px; animation: 6s steps(72) 0s infinite normal none running sm_3600; background: url(addons/chat_store/files/smiles/25.png) no-repeat;"></span></span>
';
		default:
			return '';
	}
}
function exCreateLogs($data, $post, $ignore = '')
{
	$log_options = '';
	$report = 0;
	$delete = 0;
	$m = 0;
	$wing1 = '';
	$wing2 = '';
	$smile = '';
	$frame = '';
	$style = '';
	$levels = '';
	$newrank = '';
	$border = '';
	$clear_class = '';
	$date_class = '';
	$badge = '';
	$lang_dir = '';
	$frame_div = '';
	$padsmilemark = '';
	$glow = '';
	$pic_shadow = '';
	$sp_bg = '';
	$sp_bg_width = '';
	if (!empty($post['user_badge'])) {
		$date_class = 'ex_cdate';
		$clear_class = 'ex_cclear';
	} else {
		$date_class = 'cdate';
		$clear_class = 'cclear';
	}
	if (isIgnored($ignore, $post['user_id'])) {
		return false;
	}
	if (boomAllow($post['log_rank'])) {
		return false;
	}
	if (!empty($post['another_prank'])) {
		$exrank = exChatRanks($post, 'chat_rank');
	}
	if (empty($post['another_prank'])) {
		$exrank = chatRank($post);
	}
	if (canDeleteLog() || canDeleteRoomLog()) {
		$delete = 1;
		$m++;
	} else if (canReport() && !isSystem($post['user_id'])) {
		$report = 1;
		$m++;
	}
	if ($m > 0) {
		$log_options = '<div class="' . $clear_class . '" onclick="logMenu(this, ' . $post['post_id'] . ',' . $delete . ',' . $report . ');"><i class="fa fa-ellipsis-h"></i></div>';
	}

	if (loadAddonName('chat_store')) {
		if (!empty($post['sp_bg'])) {
			$sp_bg = 'class="' . $post['sp_bg'] . '"';
		}
		if (!empty($post['sp_bg_width'])) {
			$sp_bg_width = 'width: ' . $post['sp_bg_width'] . 'px;';
		}
		if (!empty($post['name_smile'])) {
			$smile = showUserSmile($post['name_smile']);
			$padsmilemark = 'padding-left: 35px;';
		}
		if (!empty($post['name_wing1']) && !empty($post['name_wing2'])) {
			$wing1 = '<img style="height:20px;vertical-align: middle;" src="addons/chat_store/files/wing/' . $post['name_wing1'] . '" />';
			$wing2 = '<img style="height:20px;vertical-align: middle;" src="addons/chat_store/files/wing/' . $post['name_wing2'] . '" />';
		}
		if (!empty($post['photo_frame'])) {
			$frame = '<img class="ch_fr_bg over4" src="addons/chat_store/files/frame/' . $post['photo_frame'] . '"/>';
			$levels = '';
			$mylevel = '<span title="level" style="font-size:8px;color:white;border-radius:2px;margin: 2px 0 0 0;text-align: center;" class="' . levelColors($post['user_level']) . '">' . $post['user_level'] . '</span>';
			$style = 'style="width:42px;"';
			$newrank = '<span style="display: inline-grid;width: 17px;padding: 0;margin: 0;text-align: center;position: relative;vertical-align: text-bottom;">' . $exrank . '' . $mylevel . '</span>';
			$border = 'nosex avsex';
			$frame_div = '<div ' . $style . ' class="chat_frame_avatar avtrig" onclick="avMenu(this,' . $post['user_id'] . ',\'' . $post['user_name'] . '\',' . $post['user_rank'] . ',' . $post['user_bot'] . ',\'' . $post['country'] . '\',\'' . $post['user_cover'] . '\',\'' . $post['user_age'] . '\',\'' . userGender($post['user_sex']) . '\');">
							<img class="chat_frame_avatar_inner2 under ch_fr_av cavatar avav ' . $border . ' ' . avGender($post['user_sex']) . ' ' . ownAvatar($post['user_id']) . '" src="' . myAvatar($post['user_tumb']) . '"/>
							' . $frame . '
							' . $levels . ' 
						 </div>';
		} else {
			$levels = '<span title="level" class="ex_levels ' . levelColors($post['user_level']) . '">' . $post['user_level'] . '</span>';
			$style = '';
			$newrank = '' . $exrank . '';
			$border = '' . borderLevelColors($post['user_level']) . '';
			$pic_shadow = '' . $post['pic_shadow'] . '';
			$frame_div = '<div ' . $style . ' class="avtrig chat_avatar" onclick="avMenu(this,' . $post['user_id'] . ',\'' . $post['user_name'] . '\',' . $post['user_rank'] . ',' . $post['user_bot'] . ',\'' . $post['country'] . '\',\'' . $post['user_cover'] . '\',\'' . $post['user_age'] . '\',\'' . userGender($post['user_sex']) . '\');">
							<img class="cavatar avav ' . $pic_shadow . ' ' . ownAvatar($post['user_id']) . ' ' . $border . '" src="' . myAvatar($post['user_tumb']) . '"/>
							' . $levels . ' 
						</div>';
		}
		if (!empty($post['user_badge'])) {
			$badge = '<div class="badge_class"><img src="addons/users_badge/files/badges/' . $post['user_badge'] . '" /></div>';
		}
	}
	if ($data['user_language'] != 'Arabic') {
		if (!empty($post['name_glow'])) {
			$glow = 'text-shadow: 0 0 6px ' . $post['name_glow'] . '';
		}
		$lang_dir = '<li id="log' . $post['post_id'] . '" data="' . $post['post_id'] . '" class="ch_logs ' . $post['type'] . '">
		' . $frame_div . '
		<div style="padding:10px 0 0 5px;" class="btable">
					<div style="display:block !important;" class="cname">
						<span style="float:left;" class="rtl_fright"> 
						' . $newrank . '<div style="' . $sp_bg_width . ' position: relative;display: inline-block;margin: 0 5px 0 0;" ' . $sp_bg . '><span style="padding-left:5px; ' . $glow . '" class=" username ' . myColorFont($post) . '">' . $wing2 . ' ' . (empty($post['fancy_name']) ? $post['user_name'] : $post['fancy_name']) . ' ' . $wing1 . ' ' . $smile . '</span></div> <p class="inline" style="margin:0 5px 0 -3px; ' . $padsmilemark . '"> : </p>
						</span> 
								<span style="float: left;padding: 5px 0 0 0;" class="chat_message inline ' . $post['tcolor'] . '"> ' . processChatsMsgs($post) . '</span>
								' . $badge . '
						</div>
						' . $log_options . '
			</div>
	</li>';
	}
	if ($data['user_language'] == 'Arabic') {
		if (!empty($post['name_glow'])) {
			$glow = 'style="text-shadow: 0 0 6px ' . $post['name_glow'] . '"';
		}
		$lang_dir =  '<li id="log' . $post['post_id'] . '" data="' . $post['post_id'] . '" class="ch_logs ' . $post['type'] . '">
		' . $frame_div . '
				<div class="my_text">
					<div class="btable">
							<div class="cname">' . $newrank . '<div style="' . $sp_bg_width . ' position: relative;display: inline-block;margin: 0 5px 0 0;" ' . $sp_bg . '><span ' . $glow . ' class="username ' . myColorFont($post) . '">' . $wing2 . ' ' . (empty($post['fancy_name']) ? $post['user_name'] : $post['fancy_name']) . ' ' . $wing1 . '</span></div>' . $smile . '</div>
							<div class="' . $date_class . '">' . chatDate($post['post_date']) . '</div>
							' . $log_options . '
					</div>
					<div class="chat_message ' . $post['tcolor'] . '">' . processChatsMsgs($post) . '</div>
					 ' . $badge . '
				</div>
			</li>';
	}
	if (loadAddonName('chat_store')) {
		return $lang_dir;
	} else {
		return '<li id="log' . $post['post_id'] . '" data="' . $post['post_id'] . '" class="ch_logs ' . $post['type'] . '">
		<div class="avtrig chat_avatar" onclick="avMenu(this,' . $post['user_id'] . ',\'' . $post['user_name'] . '\',' . $post['user_rank'] . ',' . $post['user_bot'] . ',\'' . $post['country'] . '\',\'' . $post['user_cover'] . '\',\'' . $post['user_age'] . '\',\'' . userGender($post['user_sex']) . '\');">
			<img class="cavatar avav ' . avGender($post['user_sex']) . ' ' . ownAvatar($post['user_id']) . '" src="' . myAvatar($post['user_tumb']) . '"/>
		</div>
		<div class="my_text">
			<div class="btable">
					<div class="cname">' . chatRank($post) . '<span class="username ' . myColorFont($post) . '">' . $post['user_name'] . '</span></div>
					<div class="cdate">' . chatDate($post['post_date']) . '</div>
					' . $log_options . '
			</div>
			<div class="chat_message ' . $post['tcolor'] . '">' . processChatsMsgs($post) . '</div>
		</div>
	</li>';
	}
}
function processChatsMsgs($post) {
	global $data;
	if (!empty($data['sp_bg'])) {
		$sp_bg = 'class="' . $data['sp_bg'] . '"';
	}
	if (!empty($data['sp_bg_width'])) {
		$sp_bg_width = 'width: ' . $data['sp_bg_width'] . 'px;';
	}
	if($post['user_id'] != $data['user_id'] && !preg_match('/http/',$post['post_message'])){
		if($data['prim_plus'] == 0){
			if(empty($data['fancy_name'])){
				$post['post_message'] = str_ireplace($data['user_name'], '<span class="my_notice">' . $data['user_name'] . '</span>', $post['post_message']);
			}
			else{
				$post['post_message'] = str_ireplace($data['fancy_name'], '<span class="my_notice">' . $data['fancy_name'] . '</span>', $post['post_message']);
			}
		}
		else{
			if(empty($data['fancy_name'])){
				$post['post_message'] = str_ireplace($data['user_name'], '<i class="fa fa-bullhorn error"></i> :<div class="my_notice">' . $data['user_name'] . '</span></div>', $post['post_message']);
			}
			else{
				$post['post_message'] = str_ireplace($data['fancy_name'], '<i class="fa fa-bullhorn error"></i> :<div class="my_notice">' . $data['fancy_name'] . '</span></div>', $post['post_message']);
			}
		}
	}
	return mb_convert_encoding(systemReplace($post['post_message']), 'UTF-8', 'auto');
}
function exJoinRoom()
{
	global $data, $cody;
	if (allowLogs() && isVisible($data) && $cody['join_room'] == 1) {
		$content = str_replace('%rank%', rankTitle($data['user_rank']), '<b style="font-size: 12px;
		color: #5300ab;
		background: rgb(255,255,255);
		background: linear-gradient(90deg, rgb(234 219 255) 35%, #c697ff 100%);
		border-radius: 15px 0;
		box-shadow: 0 2px 5px #afafaf;
		text-shadow: 0 1px white;
		padding: 2px 12px;
		border: 1px dashed white;"><img style="display: inline-block;vertical-align: sub;" class="icon_status" src="default_images/status/online.svg">المستخدم انضم الى الغرفة <span style="color:red;"> [ رتبة #%rank% ] </span></b>');
		userPostChat($content);
	}
}
function exJoinRoomPlus()
{
	global $data, $cody;
	if (allowLogs() && isVisible($data) && $cody['join_room'] == 1) {
		$content = str_replace('%rank%', rankTitle($data['user_rank']), '<b style="font-size: 14px;
		color: #f22a49;
		background-color: rgb(255 232 144);
		border-radius: 15px 0;
		box-shadow: 0 2px 5px #afafaf;
		text-shadow: 0 1px white;
		padding: 2px 12px;
		border: 1px dashed white;
		width: auto;
		background-image: url(https://i.imgur.com/eqGvZEC.gif);"><img style="display: inline-block;vertical-align: sub;" class="icon_status" src="default_images/status/online.svg"> <i class="fa fa-bullhorn"></i> المستخدم المميز انضم الى الغرفة<img style="height:30px;width:auto;vertical-align: middle;" src="addons/extend_files/files/icons/prim_plus.png"></b>');
		userPostChat($content);
	}
}

function exCreateUserlist($list)
{
	global $data, $lang;
	if (!isVisible($list)) {
		return false;
	}
	$icon = '';
	$muted = '';
	$mob = '';
	$stat = '';
	$mood = '';
	$wing1 = '';
	$wing2 = '';
	$smile = '';
	$frame = '';
	$border = '';
	$style = '';
	$mark = '';
	$prim_plus = '';
	$glow = '';
	$sp_bg = '';
	$sp_bg_width = '';
	$pic_shadow = '';
	$offline = 'offline';
	if (!empty($list['another_prank'])) {
		$rank_icon = exGetRankIcon($list, 'list_rank');
	} else {
		$rank_icon = getRankIcon($list, 'list_rank');
	}
	$mute_icon = getMutedIcon($list, 'list_mute');
	$mob_icon = getMobileIcon($list, 'list_mob');
	if ($rank_icon != '') {
		$icon = '<div class="user_item_icon icrank">' . $rank_icon . '<div style="font-size:8px;color:white;border-radius:2px;" class="' . levelColors($list['user_level']) . '">' . $list['user_level'] . '</div></div>';
	}
	if ($mute_icon != '') {
		$muted = '<div class="user_item_icon icmute">' . $mute_icon . '</div>';
	}
	if ($mob_icon != '') {
		$mob = '<div class="user_item_icon icmob">' . $mob_icon . '</div>';
	}
	if ($list['last_action'] > getDelay() || isBot($list)) {
		$offline = '';
		$stat = exGetStatus($list['user_status']);
	}
	if ($list['last_action'] > getRealDelay()) {
		$mark = '<img title="متواجد" class="list_online" src="default_images/status/online.svg">';
	}
	if (!empty($list['user_mood'])) {
		$mood = '<p class="text_xsmall bustate bellips">' . $list['user_mood'] . '</p>';
	}
	if (!empty($list['name_smile'])) {
		$smile = showUserSmile($list['name_smile']);
	}
	if (!empty($list['name_wing1']) && !empty($list['name_wing2'])) {
		$wing1 = '<img style="height:20px;vertical-align: middle;" src="addons/chat_store/files/wing/' . $list['name_wing1'] . '" />';
		$wing2 = '<img style="height:20px;vertical-align: middle;" src="addons/chat_store/files/wing/' . $list['name_wing2'] . '" />';
	}
	if (!empty($list['photo_frame'])) {
		$frame = '<img class="over2 ul_fr_bg" src="addons/chat_store/files/frame/' . $list['photo_frame'] . '"/>';
		$style = 'style="width:50px;"';
		$border = 'nosex avsex';
	} else {
		$style = '';
		$border = '' . borderLevelColors($list['user_level']) . '';
	}
	if ($list['prim_plus'] > 0) {
		$prim_plus = 'prim_plus_bg';
	}
	if (!empty($list['name_glow'])) {
		$glow = 'style="text-shadow: 0 0 6px ' . $list['name_glow'] . '"';
	}
	if (!empty($list['sp_bg'])) {
		$sp_bg = 'class="' . $list['sp_bg'] . '"';
	}
	if (!empty($list['sp_bg_width'])) {
		$sp_bg_width = 'width: ' . $list['sp_bg_width'] . 'px;';
	}
	$pic_shadow = '' . $list['pic_shadow'] . '';
	return '<div onclick="dropUser(this,' . $list['user_id'] . ',\'' . $list['user_name'] . '\',' . $list['user_rank'] . ',' . $list['user_bot'] . ',\'' . $list['country'] . '\',\'' . $list['user_cover'] . '\',\'' . $list['user_age'] . '\',\'' . userGender($list['user_sex']) . '\');" class="avtrig user_item ' . $offline . ' ' . $prim_plus . '">
				<div ' . $style . ' class="user_item_frame_avatar_new">
				<img class="user_item_frame_inner under ul_fr_av avav ' . $pic_shadow . ' ' . $border . ' ' . ownAvatar($list['user_id']) . '" src="' . myAvatar($list['user_tumb']) . '"/>
				' . $frame . ' ' . $mark . '
				</div>
				<div class="user_item_data"><div style="' . $sp_bg_width . ' position: relative;display: inline-block;" ' . $sp_bg . '><p ' . $glow . ' class="username ' . myColorFont($list) . '">' . $wing2 . ' ' . (empty($list['fancy_name']) ? $list['user_name'] : $list['fancy_name']) . ' ' . $wing1 . ' </div> ' . $smile . '</p>' . $mood . ' ' . $stat . '</div>
				' . $muted . $mob . $icon . '
			</div>';
}
function exGetStatus($status)
{
	switch ($status) {
		case 3:
			return '<p class="text_xsmall bustate bellips" style="color:red;"><img title="Busy" style="width: 14px;height: 14px;border-radius: 50%;background: #fff;padding: 2px;vertical-align:bottom;" src="default_images/status/busy.svg"> Busy</p>';
		case 2:
			return '<p class="text_xsmall bustate bellips" style="color:#b19612;"><img title="Away" style="width: 14px;height: 14px;border-radius: 50%;background: #fff;padding: 2px;vertical-align:bottom;" src="default_images/status/away.svg"> Away</p>';
		default:
			return '';
	}
}
function getRealDelay()
{
	return time() - 30;
}
function levelColors($lev)
{
	if ($lev < 10) {
		return 'under-10';
	} elseif ($lev >= 10 && $lev < 20) {
		return 'under-20';
	} elseif ($lev >= 20 && $lev < 40) {
		return 'under-40';
	} elseif ($lev >= 40 && $lev < 50) {
		return 'under-50';
	} elseif ($lev >= 50 && $lev < 60) {
		return 'under-60';
	} elseif ($lev >= 60 && $lev < 70) {
		return 'under-70';
	} elseif ($lev >= 70 && $lev < 80) {
		return 'under-80';
	} elseif ($lev >= 80 && $lev < 90) {
		return 'under-90';
	} elseif ($lev >= 90 && $lev < 100) {
		return 'under-100';
	} elseif ($lev >= 100) {
		return 'reach-100';
	} else {
		return '';
	}
}
function borderLevelColors($blev)
{
	if ($blev < 10) {
		return 'border-10';
	} elseif ($blev >= 10 && $blev < 20) {
		return 'border-20';
	} elseif ($blev >= 20 && $blev < 40) {
		return 'border-40';
	} elseif ($blev >= 40 && $blev < 50) {
		return 'border-50';
	} elseif ($blev >= 50 && $blev < 60) {
		return 'border-60';
	} elseif ($blev >= 60 && $blev < 70) {
		return 'border-70';
	} elseif ($blev >= 70 && $blev < 80) {
		return 'border-80';
	} elseif ($blev >= 80 && $blev < 90) {
		return 'border-90';
	} elseif ($blev >= 90 && $blev < 100) {
		return 'border-100';
	} elseif ($blev >= 100) {
		return 'reachborder-100';
	} else {
		return '';
	}
}
function sameAccountfingerprint($u)
{
	global $mysqli, $lang;
	$getsamef = $mysqli->query("SELECT user_name FROM boom_users WHERE parmakizi = '{$u['parmakizi']}' AND user_id != '{$u['user_id']}' AND user_bot = 0 ORDER BY user_id DESC LIMIT 50");
	$same = array();
	if ($getsamef->num_rows > 0) {
		while ($usame = $getsamef->fetch_assoc()) {
			array_push($same, $usame['user_name']);
		}
	} else {
		array_push($same, $lang['none']);
	}
	return listThisArray($same);
}
function canLike($user)
{
	global $mysqli, $data;
	$result = $mysqli->query("SELECT * FROM boom_AA_profile_like WHERE target = '{$user['user_id']}' AND hunter = '{$data['user_id']}'");
	if ($result->num_rows == 0) {
		return true;
	} else {
		return false;
	}
}
function canUnLike($user)
{
	global $mysqli, $data;
	$result = $mysqli->query("SELECT * FROM boom_AA_profile_like WHERE target = '{$user['user_id']}' AND hunter = '{$data['user_id']}'");
	if ($result->num_rows == 0) {
		return false;
	} else {
		return true;
	}
}
function getMyGift($content)
{
	global $mysqli, $data;
	$mycoins = $data['coins_gift_code'];
	$gift_text = $data['coins_gift_text'];
	$mygift = $data['user_giftcoins'];
	if ($content == $gift_text && $mygift != $gift_text) {
		$mysqli->query("UPDATE boom_users SET user_coins = user_coins + '$mycoins', user_giftcoins = '$gift_text' WHERE user_id = '{$data['user_id']}'");
		$res = str_replace('@coins@', $mycoins, 'I got a <b> @coins@ </b> Coins From Gift ');
		echo systemSpecial($res, 'seen', array('icon' => 'default.svg', 'title' => 'Gift System'));
	} elseif ($content == $gift_text && $mygift == $gift_text) {
		$res = 'You have used this code before or it has been changed by the administration';
		echo systemSpecial($res, 'seen', array('icon' => 'default.svg', 'title' => 'Gift System'));
	}
}
function exChatRanks($user, $type)
{
	global $lang;
	if (isBot($user)) {
		return curRanking($type, $lang['user_bot'], 'bot.svg');
	} else {
		switch ($user['user_rank']) {
			case 0:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['guest'] : $user['another_nrank']), (empty($user['another_prank']) ? 'guest.svg' : $user['another_prank']));
			case 1:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['user'] : $user['another_nrank']), (empty($user['another_prank']) ? 'user.svg' : $user['another_prank']));
			case 2:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['vip'] : $user['another_nrank']), (empty($user['another_prank']) ? 'vip.svg' : $user['another_prank']));
			case 8:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['mod'] : $user['another_nrank']), (empty($user['another_prank']) ? 'mod.svg' : $user['another_prank']));
			case 9:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['admin'] : $user['another_nrank']), (empty($user['another_prank']) ? 'admin.svg' : $user['another_prank']));
			case 10:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['super_admin'] : $user['another_nrank']), (empty($user['another_prank']) ? 'super.svg' : $user['another_prank']));
			case 11:
				return curRanking($type, (empty($user['another_nrank']) ? $lang['owner'] : $user['another_nrank']), (empty($user['another_prank']) ? 'owner.svg' : $user['another_prank']));
			default:
				return '';
		}
	}
}
function exProRanking($user, $type)
{
	global $lang;
	if (isBot($user)) {
		return proRank($type, $lang['user_bot'], 'bot.svg');
	} else {
		switch ($user['user_rank']) {
			case 0:
				return proRank($type, (empty($user['another_nrank']) ? $lang['guest'] : $user['another_nrank']), (empty($user['another_prank']) ? 'guest.svg' : $user['another_prank']));
			case 1:
				return proRank($type, (empty($user['another_nrank']) ? $lang['user'] : $user['another_nrank']), (empty($user['another_prank']) ? 'user.svg' : $user['another_prank']));
			case 2:
				return proRank($type, (empty($user['another_nrank']) ? $lang['vip'] : $user['another_nrank']), (empty($user['another_prank']) ? 'vip.svg' : $user['another_prank']));
			case 8:
				return proRank($type, (empty($user['another_nrank']) ? $lang['mod'] : $user['another_nrank']), (empty($user['another_prank']) ? 'mod.svg' : $user['another_prank']));
			case 9:
				return proRank($type, (empty($user['another_nrank']) ? $lang['admin'] : $user['another_nrank']), (empty($user['another_prank']) ? 'admin.svg' : $user['another_prank']));
			case 10:
				return proRank($type, (empty($user['another_nrank']) ? $lang['super_admin'] : $user['another_nrank']), (empty($user['another_prank']) ? 'super.svg' : $user['another_prank']));
			case 11:
				return proRank($type, (empty($user['another_nrank']) ? $lang['owner'] : $user['another_nrank']), (empty($user['another_prank']) ? 'owner.svg' : $user['another_prank']));
			default:
				return '';
		}
	}
}
function exGetRankIcon($list, $type)
{
	if (isBot($list)) {
		return botRank($type);
	} else if (haveRole($list['user_role']) && !isStaff($list['user_rank'])) {
		return roomRank($list['user_role'], $type);
	} else if (!empty($list['another_prank'])) {
		return exChatRanks($list, $type);
	} else {
		return systemRank($list['user_rank'], $type);
	}
}
function exProfileBg($user)
{
	global $data;
	$bg = '';
	if (!empty($user['pro_background'])) {
		$bg = 'style="background: url(addons/chat_store/system/upload/' . $user['pro_background'] . ') center bottom / cover;"';
	}
	return $bg;
}
