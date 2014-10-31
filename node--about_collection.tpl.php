<?php
/**
 * @file
 * Returns the HTML for a node.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728164
 */
?>
<article class="node-<?php print $node->nid; ?> <?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($title_prefix || $title_suffix || $display_submitted || $unpublished || !$page && $title): ?>
    <header>
      <?php print render($title_prefix); ?>
      <?php if (!$page && $title): ?>
        <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>

      <?php if ($display_submitted): ?>
        <p class="submitted">
          <?php print $user_picture; ?>
          <?php print $submitted; ?>
        </p>
      <?php endif; ?>

      <?php if ($unpublished): ?>
        <mark class="unpublished"><?php print t('Unpublished'); ?></mark>
      <?php endif; ?>
    </header>
  <?php endif; ?>

  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    print render($content['field_collection_image']);
  ?>

  <?php if (isset($inst_link)): ?>
    <div><a href="<?php print $inst_link; ?>"><?php print render($content['field_collection_logo']); ?></a></div>
  <?php endif; ?>

  <?php if (!isset($inst_link)): ?>
    <?php print render($content['field_collection_logo']); ?>
  <?php endif; ?>

  <?php print render($content['body']);?>

  <?php if (isset($return_link)): ?>
  <div class="about-collection-return-link">
    <a href="<?php print $return_link; ?>"><?php print t("View The Collection"); ?></a>
  </div>
  <?php endif; ?>
  <?php print render($content['links']); ?>
  <?php print render($content['comments']); ?>
</article>
