window.task_manager.toggle = {};

window.task_manager.toggle.init = function() {
	window.task_manager.toggle.event();
};

window.task_manager.toggle.event = function() {
  jQuery( document ).on( 'click', 'toggle', window.task_manager.toggle.open );
  jQuery( document ).on( 'click', 'body', window.task_manager.toggle.close );
};

window.task_manager.toggle.open = function( event ) {
	var target = undefined;
  // Récupères la box de destination mis dans l'attribut du toggle
  if ( jQuery( this ).data( 'parent' ) ) {
	  target = jQuery( this ).closest( '.' + jQuery( this ).data( 'parent' ) ).find( "." + jQuery( this ).data( 'target' ) );
	}
	else {
		target = jQuery( "." + jQuery( this ).data( 'target' ) );
	}

	if ( target ) {
	  target.toggle();
	  event.stopPropagation();
	}
};

window.task_manager.toggle.close = function ( event ) {
	jQuery( '.toggle-content' ).hide();
}
