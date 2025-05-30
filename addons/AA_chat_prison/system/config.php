<?php
$load_addons = 'AA_chat_prison';
require_once('../../../system/config_addons.php');
?>
<?php echo elementTitle($data['addons'], 'loadLob(\'admin/setting_addons.php\');'); ?>
<div class="page_full">
   <div>
      <div class="tab_menu">
         <ul>
            <li class="tab_menu_item tab_selected" data="korsy" data-z="korsy_setting"><i class="fa fa-cogs"></i> <?php echo $lang['settings']; ?></li>
         </ul>
      </div>
   </div>
   <div class="page_element">
      <div id="korsy" class="tpad15">
         <div id="korsy_setting" class="tab_zone">
            <div class="setting_element ">
               <p class="label"><?php echo $lang['limit_feature']; ?></p>
               <select id="set_addon_access">
                  <?php echo listRank($data['addons_access'], 1); ?>
               </select>
            </div>
            <div class="setting_element ">
               <p class="label"><?php echo $lang['prison_mode']; ?></p>
               <select id="set_prison_mode">
                  <option <?php echo selCurrent($data['custom1'], 0); ?> value="0">Off</option>
                  <option <?php echo selCurrent($data['custom1'], 1); ?> value="1">On</option>
               </select>
            </div>
            <div class="setting_element ">
               <p class="label"><?php echo $lang['apply_prison']; ?></p>
               <select id="apply_prison_for">
                  <?php echo listRank($data['custom3']); ?>
               </select>
            </div>
            <div class="setting_element ">
               <p class="label"><?php echo $lang['prison_room']; ?></p>
               <select id="set_prison_room">
                  <?php echo roomSelect($data['custom2']); ?>
               </select>
            </div>
            <button onclick="saveSettings();" type="button" class="clear_top reg_button theme_btn"><i class="fa fa-floppy-o"></i> <?php echo $lang['save']; ?></button>
         </div>
      </div>
      <div class="config_section">
         <script data-cfasync="false" type="text/javascript">
            saveSettings = function() {
               $.post('addons/AA_chat_prison/system/action.php', {
                  set_addon_access: $('#set_addon_access').val(),
                  set_prison_mode: $('#set_prison_mode').val(),
                  set_prison_room: $('#set_prison_room').val(),
                  apply_prison_for: $('#apply_prison_for').val(),
                  token: utk,
               }, function(response) {
                  if (response == 1) {
                     callSaved(system.saved, 1);
                  } else {
                     callSaved(system.error, 3);
                  }
               });
            }
         </script>
      </div>
   </div>
</div>