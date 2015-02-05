<?php
/**
 * @file
 * Contains the theme's settings functions.
 */

/**
 * Implements hook_form_system_theme_settings_alter().
 */
function metro_theme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL) {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // Container fieldset.
  $form['footerbackground'] = array(
    '#type' => 'fieldset',
    '#title' => t('Footer Icon'),
  );

  $use_default_footer_icon = variable_get('use_default_footer', 1);
  $form['footerbackground']['default_footer'] = array(
    '#type' => 'checkbox',
    '#title' => t('Use the default footer icon.'),
    '#default_value' => $use_default_footer_icon,
  );

  // Default path for image.
  $bg_path = theme_get_setting('bg_path');
  if (file_uri_scheme($bg_path) == 'public' && !$use_default_footer_icon) {
    $bg_path = file_uri_target($bg_path);
  }
  else {
    // Use the default icon otherwise.
    $bg_path = file_uri_target(drupal_get_path('theme', 'metro_theme') . "/images/icons/METRO_M-Only-Logo_2014_250x250.jpg");
  }
  // Helpful text showing the file name, disabled to
  // avoid the user thinking it can be used for any purpose.
  $form['footerbackground']['bg_path'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to background image'),
    '#default_value' => $bg_path,
    '#disabled' => TRUE,
  );

  // Upload field.
  $form['footerbackground']['bg_upload'] = array(
    '#type' => 'file',
    '#title' => t('Upload background image'),
    '#description' => t('Upload a new image for the background.'),
  );

  // Container fieldset.
  $form['infolink'] = array(
    '#type' => 'fieldset',
    '#title' => t('More info about collection text'),
    '#description' => t('Configure the more info links that appears throughout this site.'),
  );

  // Setting to configure hyperlink text on the Browse collection page.
  $form['infolink']['moreinfobrowsecoltext'] = array(
    '#type' => 'textfield',
    '#title' => t('Browse collection page about collection link text'),
    '#description' => t('Configure the about collection hyperlink text, as it appears on the browse collection page'),
    '#default_value' => variable_get('moreinfobrowsecoltext', 'More info about collection'),
  );

  // Setting to configure the about collection link text,
  // on the collection page level.
  $form['infolink']['moreinfocolleveltext'] = array(
    '#type' => 'textfield',
    '#title' => t('Collection page about collection link text'),
    '#description' => t('Configure the about collection hyperlink text, as it appears on the collection page'),
    '#default_value' => variable_get('moreinfocolleveltext', 'More info about collection'),
  );

  // Attach custom submit handler to the form.
  $form['#submit'][] = 'metro_theme_settings_submit';

}

/**
 * Custom form submit handler to save Footer menu settings.
 *
 * @param array $form
 *   The forms indexed array.
 * @param unknown $form_state
 *   The form state, as an indexed array.
 */
function metro_theme_settings_submit($form, &$form_state) {
  $settings = array();
  // Get the previous value.
  $previous = 'public://' . $form['footerbackground']['bg_path']['#default_value'];
  variable_set('use_default_footer', $form_state['values']['default_footer']);
  variable_set('moreinfobrowsecoltext', $form_state['values']['moreinfobrowsecoltext']);
  variable_set('moreinfocolleveltext', $form_state['values']['moreinfocolleveltext']);
  $file = file_save_upload('bg_upload');
  if ($file) {
    $parts = pathinfo($file->filename);
    $destination = 'public://' . $parts['basename'];
    $file->status = FILE_STATUS_PERMANENT;
    if (file_copy($file, $destination, FILE_EXISTS_REPLACE)) {
      $_POST['bg_path'] = $form_state['values']['bg_path'] = $destination;
    }
  }
  else {
    // Avoid error when the form is submitted without specifying a new image.
    $_POST['bg_path'] = $form_state['values']['bg_path'] = $previous;
  }
}