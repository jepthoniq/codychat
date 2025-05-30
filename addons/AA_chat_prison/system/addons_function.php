<?php
function getUsersInPrison(){
    global $data, $mysqli;
    $users = '';
    $sql = $mysqli->query("SELECT * FROM boom_users WHERE user_prison = 0 AND user_rank < 8 AND user_bot = 0 AND user_roomid = '{$data['custom2']}'");
    if($sql->num_rows > 0){
        while($fetch = $sql->fetch_assoc()){
            $users .= boomTemplate('/../addons/AA_chat_prison/system/template/prison_user', $fetch);
        }
    }
    else {
        $users = emptyZone('No users in prison.');
    }
    return $users;
}
?>