<?php
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');

if (!isset($_POST['get_profile'], $_POST['cp'])) {
    die();
}
$id = escape($_POST['get_profile']);
$curpage = escape($_POST['cp']);
$user = boomUserInfo($id);
if (empty($user)) {
    echo 2;
    die();
}
$user['page'] = $curpage;
$pro_menu = boomTemplate('element/pro_menu', $user);
if (loadAddonName('chat_store')) {
    $pro_social = exTemplate('system/social_media', $user);
}
$room = roomInfo($user['user_roomid']);
?>
<div <?php echo setProfileShadows($user); ?>>
    <div data-id="<?php echo $user['user_id']; ?>" class="modal_wrap_top modal_top profile_background <?php echo coverClass($user); ?>" <?php echo getCover($user); ?>>
        <div class="brow">
            <div class="modal_top_menu">
                <div class="bcell_mid hpad15">
                </div>
                <?php if (loadAddonName('chat_store') && !empty($user['pro_phone']) || !empty($user['pro_wp']) || !empty($user['pro_fb']) || !empty($user['pro_insta']) || !empty($user['pro_tw'])) { ?>
                    <div id="prosocial" onclick="loadProSocial(<?php echo $user['user_id']; ?>, 'pro_social');" class="cover_text modal_top_item">
                        <i class="fa fa-ellipsis-v "></i>
                        <div id="pro_social" class="add_shadow fmenu">
                            <?php echo $pro_social; ?>
                        </div>
                    </div>
                <?php } ?>
                <?php if (canEditUser($user, 8)) { ?>
                    <div onclick="editUser(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
                        <i class="fa fa-edit"></i>
                    </div>
                <?php } ?>
                <?php if (canEditUser($user, 8, 1)) { ?>
                    <div onclick="getActions(<?php echo $user['user_id']; ?>);" class="cover_text modal_top_item">
                        <i class="fa fa-flash"></i>
                    </div>
                <?php } ?>
                <?php if (!mySelf($user['user_id']) && !empty($pro_menu)) { ?>
                    <div id="promenu" onclick="loadProMenu(<?php echo $user['user_id']; ?>, 'pro_menu');" class="cover_text modal_top_item">
                        <i class="fa fa-bars"></i>
                        <div id="pro_menu" class="add_shadow fmenu">
                            <?php echo $pro_menu; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="modal_top_menu_empty">
                </div>
                <div class="cancel_modal cover_text modal_top_item">
                    <i class="fa fa-times"></i>
                </div>
            </div>
        </div>
        <div class="brow">
            <div class="bcell_bottom profile_top">
                <div class="btable_auto">
                    <div id="proav" class="profile_avatar" data="<?php echo $user['user_tumb']; ?>">
                        <div <?php if (!empty($user['photo_frame'])) { ?> style="position: relative;bottom: 0px;left: -2px;" <?php } ?>>
                            <div class="dummy-child" style="display: inline-block;vertical-align: middle;"></div>
                            <img <?php if (!empty($user['photo_frame'])) { ?> style="border-radius: 50%;" <?php } ?> class="fancybox avatr_profile_frame avatar_profile" <?php echo profileAvatar($user['user_tumb']); ?> />
                            <?php if (!empty($user['photo_frame'])) { ?>
                                <img class="over3" src="addons/chat_store/files/frame/<?php echo $user['photo_frame']; ?>" />
                            <?php } ?>
                        </div>
                        <?php echo userActive($user, 'state_profile'); ?>
                    </div>
                    <div <?php if (!empty($user['photo_frame'])) { ?> style="padding: 0 25px;" <?php } ?> class="profile_tinfo cover_text">
                        <?php if (loadAddonName('chat_store') && $user['user_prim'] > 0 && $user['prim_plus'] == 0) { ?>
                            <div class="pdetails">
                                <p class="cover_text bellips pro_rank" style="color: orange;"><img style="height:30px;width:auto;" src="addons/chat_store/files/icons/prim.png" /></p>
                            </div>
                        <?php } ?>
                        <?php if (loadAddonName('chat_store') && $user['user_prim'] >= 0 && $user['prim_plus'] > 0) { ?>
                            <div class="pdetails">
                                <p class="cover_text bellips pro_rank" style="color: orange;"><img style="height:30px;width:auto;" src="addons/chat_store/files/icons/prim_plus.png" /></p>
                            </div>
                        <?php } ?>
                        <div class="pdetails">
                            <div class="pdetails_text pro_rank">
                                <?php echo exProRanking($user, 'pro_ranking'); ?>
                            </div>
                        </div>
                        <div class="pdetails">
                            <div <?php if ($user['sp_bg'] == 'sp_bg1' || $user['sp_bg'] == 'sp_bg2' || $user['sp_bg'] == 'sp_bg3' || $user['sp_bg'] == 'sp_bg4' || $user['sp_bg'] == 'sp_bg7') {
                                        echo 'style="width: 200px;height: 50px;padding: 13px 0 0 5px;"';
                                    } elseif ($user['sp_bg'] == 'sp_bg10' || $user['sp_bg'] == 'sp_bg9') {
                                        echo 'style="width: 200px;height: 50px;padding: 10px 0 0 5px;"';
                                    } else {
                                        echo 'style="width: 200px;height: 50px;padding: 19px 0 0 5px;"';
                                    }
                                    ?> class="pdetails_text pro_name pro_sp_bg1 <?php if ($user['sp_bg'] != '') {
                                                                            echo $user['sp_bg'];
                                                                        } ?>">
                                <?php echo (empty($user['fancy_name']) ? $user['user_name'] : $user['fancy_name']); ?>
                            </div>
                        </div>
                        <div class="pdetails">
                            <?php if (!empty($user['user_mood'])) { ?>
                                <div class="pdetails_text pro_mood bellips">
                                    <?php echo $user['user_mood']; ?>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="pdetails">
                            <div class="bcell">
                                <?php if (loadAddonName('AA_profile_like') && boomAllow(1)) { ?>
                                    <?php if (!isBot($user) && boomAllow(1) && canLike($user)) { ?>
                                        <a style="margin-top: 8px;" onclick="proLike(<?php echo $user['user_id']; ?>);" value="<?php echo $user['user_id']; ?>" title="Love it" class="btn btn-counter" data-count="<?php echo $user['AA_profile_like']; ?>"><span>&#x2764;</span> Like</a>
                                    <?php } ?>
                                    <?php if (!isBot($user) && boomAllow(1) && canUnLike($user)) { ?>
                                        <a style="margin-top: 8px;background: #f64136;color: white;" onclick="proUnLike(<?php echo $user['user_id']; ?>);" value="<?php echo $user['user_id']; ?>" title="Love it" class="btn btn-counter" data-count="<?php echo $user['AA_profile_like']; ?>"><span style="color:white;text-shadow:none;">&#x2764;</span> Like</a>
                                    <?php } ?>
                                <?php } ?>
                                <?php if (loadAddonName('rank_editor') && boomAllow(11)) { ?>
                                    <button onclick="getRankEditor(<?php echo $user['user_id']; ?>);" class="tiny_button ok_btn"><i class="fa fa-star"></i> Rank adjustment</button>
                                <?php } ?>
                                <?php if (loadAddonName('users_badge') && boomAllow(11)) { ?>
                                    <button onclick="getUsersBadge(<?php echo $user['user_id']; ?>);" class="tiny_button ok_btn"><i class="fa fa-cc"></i> nicknames</button>
                                <?php } ?>
                                <?php if (loadAddonName('users_vote') && boomAllow(11)) { ?>
                                    <button onclick="sendVote(<?php echo $user['user_id']; ?>);" class="tiny_button ok_btn"><i class="fa fa-vimeo"></i> User nomination</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (isRegmute($user) && !isMuted($user) && !isBanned($user)) { ?>
        <div class="im_muted profile_info_box theme_btn">
            <i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_regmuted']; ?>
        </div>
    <?php } ?>
    <?php if (isMuted($user) && !isBanned($user)) { ?>
        <div class="im_muted profile_info_box warn_btn">
            <i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_muted']; ?>
        </div>
    <?php } ?>
    <?php if (isBanned($user)) { ?>
        <div class="im_banned profile_info_box delete_btn">
            <i class="fa fa-exclamation-circle"></i> <?php echo $lang['user_banned']; ?>
        </div>
    <?php } ?>
    <div id="ex_menu" class="modal_menu <?php if (loadAddonName('chat_store')) {
                                            echo $user['pro_color'];
                                        } ?>">
        <ul>
            <li class="modal_menu_item modal_selected" data="mprofilemenu" data-z="profile_info"><?php echo $lang['about_me']; ?></li>
            <?php if (!isGuest($user) && !isBot($user)) { ?>
                <li class="modal_menu_item" data="mprofilemenu" onclick="lazyBoom('profile_friends');" data-z="profile_friends"><?php echo $lang['friends']; ?></li>
            <?php } ?>
            <?php if (!isBot($user)) { ?>
                <li class="modal_menu_item" data="mprofilemenu" data-z="prodetails"><?php echo $lang['main_info']; ?></li>
            <?php } ?>
            <?php if (boomAllow(11) && loadAddonName('chat_store')) { ?>
                <li class="modal_menu_item" data="mprofilemenu" data-z="developer">control</li>
            <?php } ?>
        </ul>
    </div>
    <div id="mprofilemenu" <?php if (loadAddonName('chat_store')) {
                                echo setProfileColors($user);
                            } ?> <?php echo exProfileBg($user); ?>>
        <div class="modal_zone pad25 tpad15" id="profile_info">
            <div class="clearbox">
                <?php if (!empty($user['fancy_name'])) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title">Primary Membership Name</div>
                        <div class="listing_text"><?php echo $user['user_name']; ?></div>
                    </div>
                <?php } ?>
                <?php if (loadAddonName('chat_store')) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title">Membership type</div>
                        <div class="listing_text">
                            <?php
                            $upgrade = '';
                            if (boomAllow(11)) {
                                $upgrade = '<span onclick="upgradeToPremium(' . $user['user_id'] . ');" style="color:#d20343;border: 1px solid;border-radius: 3px;padding: 0px 3px;"><i style="height:15px;" class="fa fa-arrow-circle-up error"></i> promotion</span>';
                            }
                            if ($user['user_prim'] > 0 && $user['prim_plus'] == 0) {
echo '<i style="height:15px;color:#2bb8ff;" class="fa fa-diamond"></i> PREMIUM MEMBER' . $upgrade . '';
                            } elseif ($user['user_prim'] == 0 && $user['prim_plus'] > 0 || $user['user_prim'] > 0 && $user['prim_plus'] > 0) {
echo ' Premium Plus Member ' . $upgrade . '';
                            } elseif ($user['user_prim'] == 0 && $user['prim_plus'] == 0) {
echo 'normal member' . $upgrade . '';
                            }
                            ?>
                        </div>
                    </div>
                    <?php if ($user['user_prim'] > 0) { ?>
                        <div class="listing_half_element info_pro">
<div class="listing_title">Premium Membership Expiry Date</div>
                            <div class="listing_text"><?php echo premiumEndingDate($user['prim_end']); ?></div>
                        </div>
                    <?php } ?>
                    <?php if ($user['sup_end'] > 0) { ?>
                        <div class="listing_half_element info_pro">
<div class="listing_title">Administrator membership expiry date</div>
                            <div class="listing_text"><?php echo premiumEndingDate($user['sup_end']); ?></div>
                        </div>
                    <?php } ?>
                    <div class="listing_half_element info_pro">
<div class="listing_title">User Coins</div>
                        <div class="listing_text"><img style="height:20px;width:auto;vertical-align: middle;" src="addons/extend_files/files/money.svg" /> <?php echo $user['user_coins']; ?></div>
                    </div>
                    <div class="listing_half_element info_pro">
<div class="listing_title">User Level</div>
                        <div class="listing_text"><img style="height:15px;width:auto;" src="addons/extend_files/files/energy.svg" /> <?php echo $user['user_level']; ?></div>
                    </div>
                    <div class="listing_half_element info_pro">
<div class="listing_title">Remaining for next level</div>
                        <div class="listing_text"><?php
                                                    $need_exp = $user['user_level'] == 0 ? 5 : $user['user_level'] * 50;
                                                    $result = $need_exp - $user['user_exp'];
                                                    echo '<img style="height:15px;width:auto;" src="addons/extend_files/files/energy.svg"/> ' . $result . ' ';
                                                    ?></div>
                    </div>
                <?php } ?>
                <?php if (boomAge($user['user_age'])) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['age']; ?></div>
                        <div class="listing_text"><?php echo getUserAge($user['user_age']); ?></div>
                    </div>
                <?php } ?>
                <?php if (boomSex($user['user_sex'])) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['gender']; ?></div>
                        <div class="listing_text"><?php echo getGender($user['user_sex']); ?></div>
                    </div>
                <?php } ?>
                <?php if (verified($user)) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['account_status']; ?></div>
                        <div class="listing_text"><?php echo $lang['verified']; ?></div>
                    </div>
                <?php } ?>
                <?php if (usercountry($user['country'])) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['country']; ?></div>
                        <div class="listing_text"><?php echo countryName($user['country']); ?></div>
                    </div>
                <?php } ?>
                <div class="listing_half_element info_pro">
                    <div class="listing_title"><?php echo $lang['join_chat']; ?></div>
                    <div class="listing_text"><?php echo longDate($user['user_join']); ?></div>
                </div>
                <?php if (userInRoom($user) && !empty($room)) { ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['user_room']; ?></div>
                        <div class="listing_text"><?php echo $room['room_name']; ?></div>
                    </div>
                <?php } ?>
                <?php if (loadAddonName('finger_print')) { ?>
                    <?php if (boomAllow(9)) { ?>
                        <div class="listing_half_element info_pro">
<div class="listing_title">User's Fingerprint</div>
                            <div class="listing_text"><?php
                                                        if ($user['parmakizi'] == '') {
echo '<i style="height:15px;" class="fa fa-ban error"></i> The user does not have a fingerprint ';
                                                        } else {
                                                            echo $user['parmakizi'];
                                                        }
                                                        ?></div>
                        </div>
                        <div class="listing_half_element info_pro">
<div class="listing_title">Other accounts with fingerprint</div>
                            <div class="listing_text"><?php echo sameAccountfingerprint($user); ?></div>
                        </div>
                    <?php } ?>
                <?php } ?>
            </div>
            <?php if ($user['user_about'] != '') { ?>
                <div>
                    <div class="listing_element info_pro">
                        <div class="listing_title"><?php echo $lang['about_me']; ?></div>
                        <div class="listing_text"><?php echo boomFormat($user['user_about']); ?></div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php if (!isBot($user)) { ?>
            <div class="hide_zone  pad25 tpad15 modal_zone" id="prodetails" <?php echo exProfileBg($user); ?>>
                <div class="clearbox">
                    <?php if (isVisible($user)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['last_seen']; ?></div>
                            <div class="listing_text"><?php echo longDateTime($user['last_action']); ?></div>
                        </div>
                    <?php } ?>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['language']; ?></div>
                        <div class="listing_text"><?php echo $user['user_language']; ?></div>
                    </div>
                    <div class="listing_half_element info_pro">
                        <div class="listing_title"><?php echo $lang['user_theme']; ?></div>
                        <div class="listing_text"><?php echo boomUserTheme($user); ?></div>
                    </div>
                    <?php if (canViewTimezone($user)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['user_timezone']; ?></div>
                            <div class="listing_text"><?php echo userTime($user); ?></div>
                        </div>
                    <?php } ?>
                    <?php if (canViewEmail($user)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['email']; ?></div>
                            <div class="listing_text"><?php echo $user['user_email']; ?></div>
                        </div>
                    <?php } ?>
                    <?php if (canViewIp($user)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['ip']; ?></div>
                            <div class="listing_text"><?php echo $user['user_ip']; ?></div>
                        </div>
                    <?php } ?>
                    <?php if (canViewId($user)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['user_id']; ?></div>
                            <div class="listing_text"><?php echo $user['user_id']; ?></div>
                        </div>
                    <?php } ?>
                    <?php if (canEditUser($user, 8, 1)) { ?>
                        <div class="listing_half_element info_pro">
                            <div class="listing_title"><?php echo $lang['other_account']; ?></div>
                            <div class="listing_text"><?php echo sameAccount($user); ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!isGuest($user) && !isBot($user)) { ?>
            <div class="hide_zone pad20 modal_zone" id="profile_friends" <?php echo exProfileBg($user); ?>>
                <?php echo findFriend($user); ?>
                <div class="clear"></div>
            </div>
        <?php } ?>
        <?php if (boomAllow(10)) { ?>
            <div class="hide_zone pad20 modal_zone" id="developer" <?php echo exProfileBg($user); ?>>
                <div class="form_split">
                    <div class="form_left">
                        <div class="setting_element ">
<p class="label">Modify a member's coins</p>
                            <input id="dev_usercoins" value="<?php echo $user['user_coins']; ?>" class="full_input" />
                        </div>
                    </div>
                    <div class="form_right">
                        <div class="setting_element ">
<p class="label">Modify a member's level</p>
                            <input id="dev_userlevel" value="<?php echo $user['user_level']; ?>" class="full_input" />
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <button type="button" onclick="saveDeveloperSetting(<?php echo $user['user_id']; ?>);" class="mod_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
                <div class="clear"></div>
            </div>
        <?php } ?>
    </div>
</div>
<?php if (!empty($user['pro_song']) && loadAddonName('chat_store')) { ?>
    <audio autoplay="true" loop="true" src="addons/chat_store/system/upload/<?php echo $user['pro_song']; ?>"></audio>
<?php } ?>
<?php if (!empty($user['pro_color']) && loadAddonName('chat_store')) { ?>
    <script>
        $('.listing_text').css('color', 'white');
    </script>
<?php } ?>
<?php if (!empty($user['pro_text_main'])) { ?>
    <script>
        var mainTextCol = '<?php echo $user['pro_text_main']; ?>';
        $('.listing_title').css('color', mainTextCol);
        $('#ex_menu').css('color', mainTextCol);
    </script>
<?php } ?>

<?php if (!empty($user['pro_text_sub'])) { ?>
    <script>
        var subTextCol = '<?php echo $user['pro_text_sub']; ?>';
        $('.listing_text').css('color', subTextCol);
    </script>
<?php } ?>

<?php if (!empty($user['pro_text_menu'])) { ?>
    <script>
        var menuTextCol = '<?php echo $user['pro_text_menu']; ?>';
        $('#ex_menu').css('background', menuTextCol);
    </script>
<?php } ?>

<?php if (!empty($user['pro_background'])) { ?>
    <script>
        $('.clearbox').css('background', '#00000054');
    </script>
<?php } ?>