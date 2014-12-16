<?php
/**
 * @file
 * Returns the HTML for the footer region.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728140
 */
?>
<?php if ($content): ?>
  <footer id="footer" class="<?php print $classes; ?>">
    <?php print $content; ?>
    <div class="user-login">
      <p>
      <?php
        if ($user->uid > 0) {
          print "<a href=". url('user/logout') .">Logout</a>";
        }
        else {
          print "<a href=" . url('user') . ">Login</a>";
        }
      ?>
      </p>
    </div>
  </footer>
<?php endif; ?>
