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

})(jQuery);
