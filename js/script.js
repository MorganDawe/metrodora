/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

(function ($, Drupal, window, document, undefined) {

$(window).load(
  function() {
    // Set focus of the Search Collections text box.
    $('#edit-islandora-simple-search-query').focus();
    if (Drupal.settings.metrotheme) {
      // Multiple check on object/property, because javascript.
      if (Drupal.settings.metrotheme.search_in_pid) {
        // The metrotheme.search_in_pid is set in the metro-theme/template.php
        // in the 'metro_theme_preprocess_node()' function.
        $('#edit-collection-select').val(Drupal.settings.metrotheme.search_in_pid);
      }
    }

    // Jquery mouse over/out functions handled by 'hover'.
    // Animation used in galleria description box.
    $(".galleria-info").hover(
      function() {
        $( ".galleria-info" ).animate({
          "max-height": "200px",
        },
        500
        );
      },
      function() {
        $( ".galleria-info" ).animate({
          "max-height": "30px",
        },
        500
        );
      }
    );
  }
);

})(jQuery, Drupal, this, this.document);
