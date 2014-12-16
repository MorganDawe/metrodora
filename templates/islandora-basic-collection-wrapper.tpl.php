<?php

/**
 * @file
 * islandora-basic-collection-wrapper.tpl.php
 *
 * @TODO: needs documentation about file and variables
 */
?>

<div class="islandora-basic-collection-wrapper">
  <?php if (!empty($dc_array['dc:description']['value'])): ?>
  <p><?php print $dc_array['dc:description']['value']; ?></p>
  <?php endif; ?>
  <?php if (isset($about_collection_link)): ?>
  <a class="about-collection" href ="<?php print $about_collection_link; ?>"><?php print t(variable_get('moreinfocolleveltext', "More info about collection")); ?></a>
  <?php endif; ?>
  <?php if (isset($about_collection_link) || !empty($dc_array['dc:description']['value'])): ?>
  <hr />
  <?php endif; ?>
  <div class="islandora-basic-collection clearfix">
    <span class="islandora-basic-collection-display-switch">
      <ul class="links inline">
        <?php foreach ($view_links as $link): ?>
          <li>
            <a <?php print drupal_attributes($link['attributes']) ?>><?php print filter_xss($link['title']) ?></a>
          </li>
        <?php endforeach ?>
      </ul>
    </span>
    <?php print $collection_pager; ?>
    <?php print $collection_content; ?>
    <?php print $collection_pager; ?>
  </div>
</div>
