(function($) {

  // we create a copy of the WP inline edit post function
  var $wp_inline_edit = inlineEditPost.edit;

  // and then we overwrite the function with our own code
  inlineEditPost.edit = function( id ) {

    // "call" the original WP edit function
    // we don't want to leave WordPress hanging
    $wp_inline_edit.apply( this, arguments );

    var $post_id = 0;
    if ( typeof( id ) == 'object' ) {
      $post_id = parseInt(this.getId(id));
    }

    if ( $post_id > 0 ) {
      var $edit_row = $( '#edit-' + $post_id );
      var $exclude = $( '#search-exclude-' + $post_id).data("search_exclude");
      $edit_row.find( 'input[name="sep[exclude]"]' ).prop('checked', $exclude);
    }
  };

  $('#bulk_edit').live( 'click', function() {
    // define the bulk edit row
    var $bulk_row = $( '#bulk-edit' );

    // get the selected post ids that are being edited
    var $post_ids = new Array();
    $bulk_row.find('#bulk-titles').children().each( function() {
      $post_ids.push( $( this ).attr( 'id' ).replace( /^(ttle)/i, '' ) );
    });

    // get the search exclude value
    var $exclude = $bulk_row.find('select[name="sep[exclude]"]').val();

    var nonce = $bulk_row.find('input[name="_wpnonce_search_exclude_bulk_edit"]').val();
    var referer = $bulk_row.find('input[name="_wp_http_referer"]').val();

    // save the data
    $.ajax({
      url: ajaxurl,
      type: 'POST',
      cache: false,
      async: false, // Fixes bulk editing in FF, see https://wordpress.org/support/topic/bulk-search-exclude-doesnt-work
      data: {
        action: 'search_exclude_save_bulk_edit',
        post_ids: $post_ids,
        sep_exclude: $exclude,
        _wpnonce_search_exclude_bulk_edit: nonce,
        _wp_http_referer: referer
      }
    });
  });

})(jQuery);
