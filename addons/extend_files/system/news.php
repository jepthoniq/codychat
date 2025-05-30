<?php
$frame = '';
$frame_class = '';
$img_frame_class = '';
$smile = '';
$wing1 = '';
$wing2 = '';
$sp_bg = '';
$sp_bg_width = '';
$pad = '';
if(!empty($boom['photo_frame'])){
	$frame = '<img class="over5 ul_fr_news_bg" src="addons/extend_files/files/frame/' . $boom['photo_frame'] . '"/>';
	$frame_class = 'post_avatar_frame';
	$img_frame_class = 'class="under ul_fr_news avsex nosex"';
}
if(empty($boom['photo_frame'])){
	$frame = '';
	$frame_class = 'post_avatar'; 
	$img_frame_class = '';
}
if (!empty($boom['name_smile'])) {
	$smile = showUserSmile($boom['name_smile']);
}
if (!empty($boom['name_wing1']) && !empty($boom['name_wing2'])) {
	$wing1 = '<img style="height:20px;vertical-align: middle;" src="addons/extend_files/files/wing/' . $boom['name_wing1'] . '" />';
	$wing2 = '<img style="height:20px;vertical-align: middle;" src="addons/extend_files/files/wing/' . $boom['name_wing2'] . '" />';
}
if (!empty($boom['sp_bg'])) {
	$sp_bg = '' . $boom['sp_bg'] . '';
}
if (!empty($boom['sp_bg_width'])) {
	$sp_bg_width = 'width: '. $boom['sp_bg_width'] .'px;';
}
if (!empty($boom['sp_bg'])) {
	if($boom['sp_bg'] == 'sp_bg9' || $boom['sp_bg'] == 'sp_bg10'){
		$pad = 'padding: 8px 0 0 5px';
	}
	elseif($boom['sp_bg'] == 'sp_bg2' || $boom['sp_bg'] == 'sp_bg1' || $boom['sp_bg'] == 'sp_bg3'){
		$pad = 'padding: 3px 0 0 5px';
	}
	else{
		$pad = 'padding: 17px 0 0 5px';
	}
}
?>
<div id="boom_news<?php echo $boom['id']; ?>" data="<?php echo $boom['id']; ?>" class="news_box post_element">
	<div class="post_title">
		<div class=" <?php echo $frame_class; ?> get_info" data="<?php echo $boom['user_id']; ?>">
			<img <?php echo $img_frame_class; ?> src="<?php echo myAvatar($boom['user_tumb']); ?>"/>
			<?php echo $frame; ?>
		</div>
		<div style="<?php echo $sp_bg_width; ?><?php echo $pad; ?>" class="bcell_mid hpad5 maxflow post_info <?php echo $sp_bg; ?>">
			<p class="username text_small <?php echo myColor($boom); ?> <?php echo $boom['user_font']; ?>"><?php echo $wing2; ?> <?php echo (empty($boom['fancy_name']) ? $boom['user_name'] : $boom['fancy_name']); ?> <?php echo $wing1; ?> <?php echo $smile; ?></p>
			<p class="text_xsmall date"><?php echo displayDate($boom['news_date']); ?></p>
		</div>
		<div onclick="openPostOptions(this);" class="post_edit bcell_mid_center">
			<i class="fa fa-ellipsis-h"></i>
			<div class="post_menu fmenu">
				<div onclick="viewNewsLikes(<?php echo $boom['id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['view_likes']; ?>
				</div>
				<?php if(canDeleteNews($boom) || mySelf($boom['news_poster'])){ ?>
				<div onclick="openDeletePost('news', <?php echo $boom['id']; ?>);" class="fmenu_item fmenut">
					<?php echo $lang['delete']; ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="post_content">
		<?php echo boomPostIt($boom, $boom['news_message']); ?>
		<?php echo boomPostFile($boom['news_file']); ?>
	</div>
	<div class="post_control btauto">
		<div class="bcell_mid like_container newslike<?php echo $boom['id']; ?>">
			<?php echo getLikes($boom['id'], $boom['liked'], 'news'); ?>
		</div>
		<div data="0" class="bcell_mid comment_count bcauto load_comment <?php if($boom['reply_count'] < 1){ echo 'hidden'; } ?>" onclick="loadNewsComment(this, <?php echo $boom['id']; ?>);">
			<span id="nrepcount<?php echo $boom['id']; ?>"><?php echo $boom['reply_count']; ?></span> <img class="comment_icon" src="<?php echo $data['domain']; ?>/default_images/icons/comment.svg"/>
		</div>
	</div>
	<?php if(!muted() && boomAllow($cody['can_reply_news'])){ ?>
	<div class="add_comment_zone cmb<?php echo $boom['id']; ?>">
		<div class="tpad10 reply_post">
			<input onkeydown="newsReply(event, <?php echo $boom['id']; ?>, this);" maxlength="500" placeholder="<?php echo $lang['comment_here']; ?>" class="add_comment full_input">
		</div>
	</div>
	<?php } ?>
	<div class="tpad10 ncmtboxwrap<?php echo $boom['id']; ?>">
		<div class="ncmtbox ncmtbox<?php echo $boom['id']; ?>">
		</div>
		<div class="nmorebox nmorebox<?php echo $boom['id']; ?>">
		</div>
		<div class="clear"></div>
	</div>
</div>