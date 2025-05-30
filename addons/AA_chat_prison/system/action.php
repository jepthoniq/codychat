<?php
/*
 * @ https://EasyToYou.eu - IonCube v11 Decoder Online
 * @ PHP 7.2
 * @ Decoder version: 1.0.4
 * @ Release: 01/09/2021
 */

$load_addons = "AA_chat_prison";
require_once "../../../system/config_addons.php";
if (isset($_POST["set_addon_access"]) && isset($_POST["set_prison_mode"]) && isset($_POST["set_prison_room"]) && boomAllow($cody["can_manage_addons"])) {
    echo exdynmthisaddon1();
    exit;
}
if (isset($_POST["out_user"]) && boomAllow($data["addons_access"])) {
    echo exdynmthisaddon2();
    exit;
}
if (isset($_POST["go_to_prison"])) {
    echo exdynmthisaddon3();
    exit;
}
function exDynmThisAddon1()
{
    global $mysqli;
    global $data;
    global $lang;
    global $cody;
    $rank = escape($_POST["set_addon_access"]);
    $mode = escape($_POST["set_prison_mode"]);
    $room = escape($_POST["set_prison_room"]);
    $on = escape($_POST["apply_prison_for"]);
    $mysqli->query("UPDATE boom_addons SET addons_access = '" . $rank . "', custom1 = '" . $mode . "', custom2 = '" . $room . "', custom3 = '" . $on . "' WHERE addons = 'AA_chat_prison'");
    echo 1;
}
function exDynmThisAddon2()
{
    global $mysqli;
    global $data;
    global $lang;
    global $cody;
    $id = escape($_POST["out_user"]);
    $now = time();
    $mysqli->query("UPDATE boom_users SET user_roomid = '1', user_prison = '1', action_prison = '" . $now . "' WHERE user_id = '" . $id . "'");
    echo 1;
}
function exDynmThisAddon3()
{
    global $mysqli;
    global $data;
    global $lang;
    global $cody;
    $room = escape($data["custom2"]);
    $now = time();
    if (!boomAllow($data["custom3"]) && 0 < $data["custom1"] && !isStaff($data["user_rank"]) && $data["user_prison"] == 0) {
        $mysqli->query("UPDATE boom_users SET user_roomid = '" . $room . "', user_join = 0 WHERE user_id = '" . $data["user_id"] . "'");
        echo 1;
    }
    if (0 < $data["user_prison"] && 0 < $data["action_prison"]) {
        $mysqli->query("UPDATE boom_users SET action_prison = '0' WHERE user_id = '" . $data["user_id"] . "'");
        echo 2;
    }
}

?>