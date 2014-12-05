<?php
/**
 * @file
 * Returns HTML for the sub header region.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728112
 */
?>
<?php if ($content): ?>

  <div class="<?php print $classes; ?>">
    <div id="pre_header_account_wrapper">
      <?php print $content; ?>
    <?php
      if ($user->uid > 0) {
        print "<form id='form-login' action=" . url('user/logout') . ">
         <input type='submit' value='Logout'>
       </form>";
      }
      else {
        print "<form id='form-login' action=" . url('user') . ">
         <input type='submit' value='Login'>
       </form>";
      }
    ?>
  </div>
  </div>
<?php endif; ?>