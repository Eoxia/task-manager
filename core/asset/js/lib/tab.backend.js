window.task_manager.tab = {};

window.task_manager.tab.init = function() {
	window.task_manager.tab.event();
};

window.task_manager.tab.event = function() {
  jQuery( document ).on( 'click', '.wp-digi-global-sheet-tab li, .tab', window.task_manager.tab.load );
};

window.task_manager.tab.load = function( event ) {
  event.preventDefault();
  var a = jQuery( this );

  jQuery( ".wp-digi-global-sheet-tab li.active" ).removeClass( "active" );
  a.addClass( "active" );

  jQuery( ".wp-digi-content" ).addClass( "wp-digi-bloc-loading" );

  var data = {
    action:           "load_tab_content",
    _wpnonce:         a.data( 'nonce' ),
    tab_to_display:   a.data( "action" ),
    element_id :      a.closest( '.wp-digi-sheet' ).data( 'id' ),
  };

  jQuery.post( window.ajaxurl, data, function( response ) {
    jQuery( ".wp-digi-content" ).replaceWith( response.data.template );

		window.task_manager.tab.call_tab_changed();
  } );
};

window.task_manager.tab.call_tab_changed = function() {
	for ( var key in window.task_manager ) {
		if (window.task_manager[key].tab_changed) {
			window.task_manager[key].tab_changed();
		}
	}
}
