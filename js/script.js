/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - http://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($, Drupal, window, document, undefined) {

$(window).load(
  function() {
    if ($("#edit-islandora-simple-search-query").val() == "") {
      $("#edit-islandora-simple-search-query").val("Search this repository");
    }
    $("#edit-islandora-simple-search-query").focus(function() {
      $(this).val("");
    });
    // Set the 'SEARCH IN' combo box from islandora collection search
    // when viewing an 'about_collection' page to the correct default value.
    if (Drupal.settings.metrotheme) {
      // Multiple check on object/property, because javascript.
      if (Drupal.settings.metrotheme.search_in_pid) {
        // The metrotheme.search_in_pid is set in the metro-theme/template.php
        // in the 'metro_theme_preprocess_node()' function.
        $('#edit-collection-select').val(Drupal.settings.metrotheme.search_in_pid);
      }
    }
    $("#print_btn").parent().parent().hide();
  }
);

})(jQuery, Drupal, this, this.document);
