<?php if (!empty($boom['pro_phone'])) { ?>
    <div class="fmenu_item">
        <div class="fmenu_icon">
            <i style="color:#d80303;" class="fa fa-phone"></i>
        </div>
        <div class="fmenu_text">
            <?php echo $boom['pro_phone']; ?>
        </div>
    </div>
<?php } ?>
<?php if (!empty($boom['pro_wp'])) { ?>
    <div class="fmenu_item">
        <div class="fmenu_icon">
            <i class="fa fa-whatsapp success"></i>
        </div>
        <div class="fmenu_text">
            <?php echo $boom['pro_wp']; ?>
        </div>
    </div>
<?php } ?>
<?php if (!empty($boom['pro_fb'])) { ?>
    <div class="fmenu_item">
        <div class="fmenu_icon">
            <i style="color:#030ad8;" class="fa fa-facebook-square"></i>
        </div>
        <div class="fmenu_text">
            <?php echo $boom['pro_fb']; ?>
        </div>
    </div>
<?php } ?>
<?php if (!empty($boom['pro_insta'])) { ?>
    <div class="fmenu_item">
        <div class="fmenu_icon">
            <i style="color:#fd0098;" class="fa fa-instagram"></i>
        </div>
        <div class="fmenu_text">
            <?php echo $boom['pro_insta']; ?>
        </div>
    </div>
<?php } ?>
<?php if (!empty($boom['pro_tw'])) { ?>
    <div class="fmenu_item">
        <div class="fmenu_icon">
            <i style="color:#00c3fd;" class="fa fa-twitter-square"></i>
        </div>
        <div class="fmenu_text">
            <?php echo $boom['pro_tw']; ?>
        </div>
    </div>
<?php } ?>
<script data-cfasync="false">
const aioColors = document.querySelectorAll('.fmenu_item .fmenu_text');

aioColors.forEach(color => {
  color.addEventListener('click', () => {
    const selection = window.getSelection();
    const range = document.createRange();
    range.selectNodeContents(color);
    selection.removeAllRanges();
    selection.addRange(range);

    try {
      document.execCommand('copy');
      selection.removeAllRanges();

      const original = color.textContent;
      color.textContent = 'Copied!';
      callSaved('تم النسخ', 1);
      color.classList.add('success');

      setTimeout(() => {
        color.textContent = original;
        color.classList.remove('success');
      }, 1200);
    } catch(e) {
      const errorMsg = document.querySelector('.error-msg');
      errorMsg.classList.add('show');

      setTimeout(() => {
        errorMsg.classList.remove('show');
      }, 1200);
    }
  });
});
</script>