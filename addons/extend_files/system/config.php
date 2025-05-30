<?php
$load_addons = 'extend_files';
require_once('../../../system/config_addons.php');
?>
<?php echo elementTitle($data['addons'], 'loadLob(\'admin/setting_addons.php\');'); ?>
<div class="page_full">
   <div>
      <div class="tab_menu">
         <ul>
            <li class="tab_menu_item tab_selected" data="korsy" data-z="korsy_setting"><i class="fa fa-cogs"></i> <?php echo $lang['settings']; ?></li>
            <li class="tab_menu_item" data="korsy" data-z="pro_settings"><i class="fa fa-user"></i> Profile personly</li>
         </ul>
      </div>
   </div>
   <div class="page_element">
      <div id="korsy" class="tpad15">
         <div id="korsy_setting" class="tab_zone">
            <div class="im_banned profile_info_box delete_btn">
<i class="fa fa-exclamation-circle"></i> Important notice: You should pay attention to changing the following options, and in case you have installed the "Chat Store" extension, you will need to activate all the following options to "Yes" so as not to face problems While using the premium features or while using the store and the options associated with it, the options for coins and others.
            </div>
            <div class="setting_element ">
               <p class="label"><?php echo $lang['limit_feature']; ?></p>
               <select id="set_addon_access">
                  <?php echo listRank($data['addons_access'], 1); ?>
               </select>
<p style="color:red;" class="sub_text sub_label">Alert: put this option on "visitor" so that all visitors to your chat can see the features and add-ons and use them without problems, unless you want to exclude a certain rank from these features</p>
            </div>
            <div class="setting_element ">
<p class="label">Allow profile.php to be altered</p>
               <select id="set_allow_profile">
                  <option <?php echo selCurrent($data['custom1'], 0); ?> value="0">No</option>
                  <option <?php echo selCurrent($data['custom1'], 1); ?> value="1">Yes</option>
               </select>
<p style="color:red;" class="sub_text sub_label">Caution: You must choose "Yes" in case you have installed Add Chat Store to show you the developed profile</p>
            </div>
            <div class="setting_element ">
<p class="label">Allow chat_process.php file to be swapped</p>
               <select id="set_allow_chprocess">
                  <option <?php echo selCurrent($data['custom2'], 0); ?> value="0">No</option>
                  <option <?php echo selCurrent($data['custom2'], 1); ?> value="1">Yes</option>
               </select>
<p style="color:red;" class="sub_text sub_label">Caution: You must choose "Yes" if you use the Coins and Levels system in order for the system to function properly</p>
            </div>
            <div class="setting_element ">
<p class="label">Allow file alteration edit_profile.php</p>
               <select id="set_allow_editpro">
                  <option <?php echo selCurrent($data['custom3'], 0); ?> value="0">No</option>
                  <option <?php echo selCurrent($data['custom3'], 1); ?> value="1">Yes</option>
               </select>
<p style="color:red;" class="sub_text sub_label">Caution: You must choose "Yes" in the case of using the premium features in the chat to be able to see the features and use them without problems</p>
            </div>
            <div class="setting_element ">
<p class="label">Allow user_list.php file to be altered</p>
               <select id="set_allow_userlist">
                  <option <?php echo selCurrent($data['custom4'], 0); ?> value="0">No</option>
                  <option <?php echo selCurrent($data['custom4'], 1); ?> value="1">Yes</option>
               </select>
<p style="color:red;" class="sub_text sub_label">Caution: This option is very important to enable, use and see the premium features in the contacts list</p>
            </div>
            <div class="setting_element ">
<p class="label">Allow chat_log.php file to be swapped</p>
               <select id="set_allow_chlog">
                  <option <?php echo selCurrent($data['custom5'], 0); ?> value="0">No</option>
                  <option <?php echo selCurrent($data['custom5'], 1); ?> value="1">Yes</option>
               </select>
<p style="color:red;" class="sub_text sub_label">Caution: This option is very important to enable, use, and view premium features on public text</p>
            </div>
            <button onclick="saveSettings();" type="button" class="clear_top reg_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
         </div>
      </div>
      <div id="korsy" class="tpad15">
         <div id="pro_settings" class="tab_zone hide_zone">
            <div class="setting_element ">
<p class="label">Using the new profile look</p>
               <select id="set_profile_display">
                  <option <?php echo selCurrent($data['profile_display'], 1); ?> value="1">No</option>
                  <option <?php echo selCurrent($data['profile_display'], 2); ?> value="2">Yes</option>
               </select>
            </div>
            <button onclick="saveProSettings();" type="button" class="clear_top reg_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
         </div>
      </div>
      <div class="config_section">
         <script data-cfasync="false" type="text/javascript">
            saveSettings = function() {
               $.post('addons/extend_files/system/action.php', {
                  set_addon_access: $('#set_addon_access').val(),
                  set_allow_profile: $('#set_allow_profile').val(),
                  set_allow_chprocess: $('#set_allow_chprocess').val(),
                  set_allow_editpro: $('#set_allow_editpro').val(),
                  set_allow_userlist: $('#set_allow_userlist').val(),
                  set_allow_chlog: $('#set_allow_chlog').val(),
                  token: utk,
               }, function(response) {
                  if (response == 1) {
callSaved('New settings saved', 1);
                  } else {
                     callSaved(system.error, 3);
                  }
               });
            }
            saveProSettings = function() {
               $.post('addons/extend_files/system/action.php', {
                  set_profile_display: $('#set_profile_display').val(),
                  token: utk,
               }, function(response) {
                  if (response == 1) {
callSaved('New settings saved', 1);
                  } else {
                     callSaved(system.error, 3);
                  }
               });
            }
         </script>
      </div>
   </div>
</div>