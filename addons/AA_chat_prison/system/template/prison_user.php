<div class="sub_list_item console_data_logs inprison<?php echo $boom['user_id']; ?>">
    <div class="sub_list_cell_top hpad3">
        <div class="text_small console_log">
            <?php echo $boom['user_name']; ?>
        </div>
        <div class="sub_text text_xsmall vpad3 theme_color console_reason">
            IP : <b style="color:red;"><?php echo $boom['user_ip']; ?></b>
        </div>
    </div>
    <div onclick="removeUserFromPrison(<?php echo $boom['user_id']; ?>);" style="width: 25px;background: #0a0a0a;vertical-align: middle;font-size: 15px;border-radius: 5px;" class="console_date sub_text centered_element">
        <i class="fa fa-times error"></i>
    </div>
</div>