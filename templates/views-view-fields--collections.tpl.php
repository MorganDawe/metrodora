<?php

/**
 * @file
 * Default simple view template to all the fields as a row.
 *
 * - $view: The view in use.
 * - $fields: an array of $field objects. Each one contains:
 *   - $field->content: The output of the field.
 *   - $field->raw: The raw data for the field, if it exists. This is NOT output safe.
 *   - $field->class: The safe class id to use.
 *   - $field->handler: The Views field handler object controlling this field. Do not use
 *     var_export to dump this object, as it can't handle the recursion.
 *   - $field->inline: Whether or not the field should be inline.
 *   - $field->inline_html: either div or span based on the above flag.
 *   - $field->wrapper_prefix: A complete wrapper containing the inline_html to use.
 *   - $field->wrapper_suffix: The closing tag for the wrapper.
 *   - $field->separator: an optional separator that may appear before a field.
 *   - $field->label: The wrap label text to use.
 *   - $field->label_html: The full HTML of the label to use including
 *     configured element type.
 * - $row: The raw result object from the query, with all data it fetched.
 *
 * @ingroup views_templates
 */
?>

<div class="browse-all-collections-item-wrapper">
  <div class="browse-all-collections-image-wrapper">
  <?php print $fields['nothing']->content; ?>
  </div>
  <div class="browse-all-collections-content-wrapper">
    <div class="browse-all-collections-info-wrapper">
      <div class="browse-all-collections-info-wrapper-title-content">
      <span id="browse-all-collections-info-wrapper-title-label"><?php print t($fields['fgs_label_s']->label); ?>: </span>
      <?php print $fields['fgs_label_s']->content; ?>
      </div>
      <div class="browse-all-collections-info-description">
      <?php print $fields['dc.description']->label_html; ?>
      <?php print $fields['dc.description']->content; ?>
      </div>
    </div>
    <div class="browse-all-collections-links-wrapper">
      <div>
      <?php print $fields['nothing_1']->content; ?>
      </div>
      <div>
      <?php print $fields['nothing_2']->content; ?>
      </div>
    </div>
  </div>
</div>
