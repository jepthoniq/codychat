<?php
if (boomAllow(10)) {
    // boom_users sql.
    $mysqli->query("ALTER TABLE `boom_users` DROP user_exp");
    $mysqli->query("ALTER TABLE `boom_users` DROP user_prim");
    $mysqli->query("ALTER TABLE `boom_users` DROP prim_end");
    $mysqli->query("ALTER TABLE `boom_users` DROP prim_plus");
    $mysqli->query("ALTER TABLE `boom_users` DROP prim_plus_end");
    $mysqli->query("ALTER TABLE `boom_users` DROP sup_end");
    $mysqli->query("ALTER TABLE `boom_users` DROP fancy_name");
    $mysqli->query("ALTER TABLE `boom_users` DROP name_glow");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_song");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_color");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_shadow");
    $mysqli->query("ALTER TABLE `boom_users` DROP pic_shadow");
    // $mysqli->query("ALTER TABLE `boom_users` DROP name_smile");
    $mysqli->query("ALTER TABLE `boom_users` DROP name_wing1");
    $mysqli->query("ALTER TABLE `boom_users` DROP name_wing2");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_fb");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_insta");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_tw");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_wp");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_phone");
    // $mysqli->query("ALTER TABLE `boom_users` DROP photo_frame");
    $mysqli->query("ALTER TABLE `boom_users` DROP user_giftcoins");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_text_main");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_text_sub");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_text_menu");
    $mysqli->query("ALTER TABLE `boom_users` DROP pro_background");
    $mysqli->query("ALTER TABLE `boom_users` DROP private_profile");
    $mysqli->query("ALTER TABLE `boom_users` DROP sp_bg");
    $mysqli->query("ALTER TABLE `boom_users` DROP sp_bg_width");
    $mysqli->query("ALTER TABLE `boom_users` DROP another_nrank");
    $mysqli->query("ALTER TABLE `boom_users` DROP another_prank");
    $mysqli->query("ALTER TABLE `boom_users` DROP user_badge");

    // boom_setting sql.
    $mysqli->query("ALTER TABLE `boom_setting` DROP allow_sendcoins");
    $mysqli->query("ALTER TABLE `boom_setting` DROP allow_takecoins");
    $mysqli->query("ALTER TABLE `boom_setting` DROP coins_gift_text");
    $mysqli->query("ALTER TABLE `boom_setting` DROP coins_gift_code");

    // store sql.
    $mysqli->query("DROP TABLE `boom_store`");
}
