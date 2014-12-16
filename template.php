<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * A QUICK OVERVIEW OF DRUPAL THEMING
 *
 *   The default HTML for all of Drupal's markup is specified by its modules.
 *   For example, the comment.module provides the default HTML markup and CSS
 *   styling that is wrapped around each comment. Fortunately, each piece of
 *   markup can optionally be overridden by the theme.
 *
 *   Drupal deals with each chunk of content using a "theme hook". The raw
 *   content is placed in PHP variables and passed through the theme hook, which
 *   can either be a template file (which you should already be familiary with)
 *   or a theme function. For example, the "comment" theme hook is implemented
 *   with a comment.tpl.php template file, but the "breadcrumb" theme hooks is
 *   implemented with a theme_breadcrumb() theme function. Regardless if the
 *   theme hook uses a template file or theme function, the template or function
 *   does the same kind of work; it takes the PHP variables passed to it and
 *   wraps the raw content with the desired HTML markup.
 *
 *   Most theme hooks are implemented with template files. Theme hooks that use
 *   theme functions do so for performance reasons - theme_field() is faster
 *   than a field.tpl.php - or for legacy reasons - theme_breadcrumb() has "been
 *   that way forever."
 *
 *   The variables used by theme functions or template files come from a handful
 *   of sources:
 *   - the contents of other theme hooks that have already been rendered into
 *     HTML. For example, the HTML from theme_breadcrumb() is put into the
 *     $breadcrumb variable of the page.tpl.php template file.
 *   - raw data provided directly by a module (often pulled from a database)
 *   - a "render element" provided directly by a module. A render element is a
 *     nested PHP array which contains both content and meta data with hints on
 *     how the content should be rendered. If a variable in a template file is a
 *     render element, it needs to be rendered with the render() function and
 *     then printed using:
 *       <?php print render($variable); ?>
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. With this file you can do three things:
 *   - Modify any theme hooks variables or add your own variables, using
 *     preprocess or process functions.
 *   - Override any theme function. That is, replace a module's default theme
 *     function with one you write.
 *   - Call hook_*_alter() functions which allow you to alter various parts of
 *     Drupal's internals, including the render elements in forms. The most
 *     useful of which include hook_form_alter(), hook_form_FORM_ID_alter(),
 *     and hook_page_alter(). See api.drupal.org for more information about
 *     _alter functions.
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   If a theme hook uses a theme function, Drupal will use the default theme
 *   function unless your theme overrides it. To override a theme function, you
 *   have to first find the theme function that generates the output. (The
 *   api.drupal.org website is a good place to find which file contains which
 *   function.) Then you can copy the original function in its entirety and
 *   paste it in this template.php file, changing the prefix from theme_ to
 *   metro_theme_. For example:
 *
 *     original, found in modules/field/field.module: theme_field()
 *     theme override, found in template.php: metro_theme_field()
 *
 *   where metro_theme is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_field() function.
 *
 *   Note that base themes can also override theme functions. And those
 *   overrides will be used by sub-themes unless the sub-theme chooses to
 *   override again.
 *
 *   Zen core only overrides one theme function. If you wish to override it, you
 *   should first look at how Zen core implements this function:
 *     theme_breadcrumbs()      in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called theme hook suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node--forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and theme hook suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440 and http://drupal.org/node/1089656
 */

/**
 * Override or insert variables into the about_collection content type.
 *
 * Add an additional theme_hook_suggestion for the 'about_collection'
 * content type and construct the return link used on that page. This link
 * is added into the variables array as 'return_link'.
 *
 * @param array $variables
 *   An array of variables to pass to the theme template.
 */
function metro_theme_preprocess_node(&$variables) {
  // Handle additional processing for the 'about_collection' content type.
  if (isset($variables['node']) && $variables['node']->type === "about_collection") {
    $variables['theme_hook_suggestions'][] = "node__" . $variables['node']->type;
    if (isset($variables['field_collection_pid']['und']['0']['value'])) {
      $pid = $variables['field_collection_pid']['und']['0']['value'];
      $variables['return_link'] = url("islandora/object/$pid");
      drupal_add_js(
        array(
          'metrotheme' => array(
            'search_in_pid' => $pid,
          ),
        ),
      'setting'
      );
    }
    if (isset($variables['field_institutions_website']['und']['0']['value'])) {
      $variables['inst_link'] = $variables['field_institutions_website']['und']['0']['value'];
    }
  }
}

/**
 * Override or insert variables into the template.
 *
 * Construct the "About '{COLLECTION_LABEL}'" url and
 * add it into the variables array as 'about_collection_link'.
 * This will be rendered on every page that represents a collection
 * and has a relevent 'about_collection' content type
 * created with the same pid.
 *
 * @param array $variables
 *   An array of variables to pass to the theme template.
 */
function metro_theme_preprocess_islandora_basic_collection_wrapper(&$variables) {
  $object = menu_get_object('islandora_object', 2);
  if (isset($object) && in_array("islandora:collectionCModel", $object->models)) {
    $results = metro_theme_find_about_page_by_pid($object->id);
    if (isset($results['node'])) {
      $nodes = node_load_multiple(array_keys($results['node']));
      $node = reset($nodes);
      $node_id = $node->nid;
      $variables['about_collection_link'] = url("node/$node_id");
    }
  }
}

/**
 * Implements hook_view_fields().
 *
 * Preform preprocessing to set the formatted link of the
 * related collections about collection page. Used in
 * 'Browse Collections' view.
 */
function metro_theme_preprocess_views_view_fields(&$vars) {
  $view = $vars['view'];
  if ($view->name === 'collections') {

    // Define vars for use in the view template and further preprocessing.
    $vars['label_field'] = variable_get('islandora_solr_object_label_field', 'fgs_label_s');
    $vars['thumb_field'] = METRODORA_THEME_VIEW_IMAGE_FIELD;
    $vars['description_field'] = METRODORA_THEME_VIEW_DESCRIPTION_FIELD;
    $vars['collection_link_field'] = METRODORA_THEME_VIEW_COLLECTION_LINK_FIELD;
    $vars['about_collection_link_field'] = METRODORA_THEME_VIEW_ABOUT_COLLECTION_LINK_FIELD;

    // Get the pid, used to query the node table to find
    // its realted 'about collections' page.
    $pid = $view->result[$view->row_index]->PID;
    $results = metro_theme_find_about_page_by_pid($pid);

    // Construct the about collection page link, if it exists.
    if (isset($results['node'])) {
      $nodes = node_load_multiple(array_keys($results['node']));
      $node = reset($nodes);
      $node_id = $node->nid;
      $formatted_url = url("node/$node_id");
      $link_text = variable_get('moreinfobrowsecoltext', "More info about the collection");
      $vars['fields'][$vars['about_collection_link_field']]->content
        = '<span class="field-content"><a href="' . $formatted_url . '">' . t($link_text) . '</a></span>';
    }
    else {
      // Empty this field if the about collection page does not exist.
      $vars['fields'][$vars['about_collection_link_field']]->content = "";
    }
  }
}

/**
 * Implements hook_block_view_alter().
 *
 * Use a metro-theme setting to alter the
 * footer, as default or user defined.
 */
function metro_theme_block_view_alter(&$data, $block) {
  switch ($block->delta) {
    case 'menu-footer-menu':
      $keys = array_keys($data['content']);
      $path = theme_get_setting('bg_path');
      if (variable_get('use_default_footer')) {
        $path = drupal_get_path('theme', 'metro_theme') . "/images/icon/METRO_M-Only-Logo_2014_250x250.jpg";
      }
      $file_url = file_create_url($path);
      $data['content'][$keys[2]]['#attributes']['style']
        = array("background-image: url($file_url);background-repeat: no-repeat;background-position: center;");
      break;

  }
}

/**
 * Find the about collection node for a given pid.
 *
 * @param string $pid
 *   The islandora object PID.
 *
 * @return array
 *   An array of results, returned from the EntityFieldQuery.
 */
function metro_theme_find_about_page_by_pid($pid) {
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'about_collection')
    ->propertyCondition('status', 1)
    ->fieldCondition('field_collection_pid', 'value', $pid);
  $results = $query->execute();
  return $results;
}

/**
 * Implements hook_form_alter().
 */
function metro_theme_form_islandora_solr_simple_search_form_alter(&$form, &$form_state, $form_id) {
  $link = array(
    '#markup' => l(t("Advanced Search"), "advanced-search", array('attributes' => array('class' => array('adv_search')))),
  );
  $form['simple']['advanced_link'] = $link;
}

/**
 * Replace instances of 'Islandora Repository' with 'All Collections'.
 *
 * @param array $breadcrumb
 *   An indexed array of breadcrumbs.
 */
function metro_theme_breadcrumb($breadcrumb) {
  if (isset($breadcrumb['breadcrumb']) && count($breadcrumb['breadcrumb']) > 0) {
    foreach ($breadcrumb['breadcrumb'] as $key => $value) {
      if (strpos($value, 'Islandora Repository') !== FALSE) {
        $breadcrumb['breadcrumb'][$key] = "<a href='/islandora'>" . t("All Collections") . "</a>";
      }
    }
    return implode(" › ", $breadcrumb['breadcrumb']);
  }
}


/**
 * Implements hook_preprocess_html().
 */
function metro_theme_preprocess_html(&$variables) {
  drupal_add_css('http://openfontlibrary.org/face/linear-regular', array('group' => CSS_THEME, 'preprocess' => FALSE));
  drupal_add_css('http://openfontlibrary.org/face/open-baskerville', array('group' => CSS_THEME, 'preprocess' => FALSE));
  drupal_add_css('http://openfontlibrary.org/face/news-cycle', array('group' => CSS_THEME, 'preprocess' => FALSE));
}
