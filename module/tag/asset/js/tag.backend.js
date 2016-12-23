/** File tag/asset/js/backend.js */

window.task_manager.tag = {
	tags_is_open: false,
	current_task_id: 0,
	archived_task: false
};

window.task_manager.tag.init = function() {
	window.task_manager.tag.event();
};

window.task_manager.tag.event = function() {
	// jQuery( document ).on( 'click', '.wpeo-tag-wrap-edit', function( e ) {
	// 	e.stopPropagation();
	// } );
	//
	// jQuery( document ).on( 'click', 'body', function( e ) {
	// 	if ( 0 != window.task_manager.tag.current_task_id ) {
	// 		window.task_manager.tag.edit_tag( e );
	// 	}
	// } );

	//
	// jQuery( document ).on( 'click', '.wpeo-tag-edit-tag-btn', window.task_manager.tag.edit_tag );
	// jQuery( document ).on( 'click', '.wpeo-tag-wrap-edit li:not(.wpeo-tag-edit-tag-btn)', window.task_manager.tag.select_tag );
	//
	//

	/** Chosen */
	/**
	 * 	@Todo
	 *	jQuery( '.wpeo-tag-filter' ).chosen( { no_results_text: wpeo_project_create_tag + " : ", } );
	 *	jQuery( '.wpeo-tag-filter' ).chosen().change( function( evt, params ) { window.task_manager.tag.change_filter( evt, params ); } );
	 *	jQuery( '.chosen-choices' ).prepend( '<span class="dashicons dashicons-search"></span>' );
	*/
	// jQuery( document ).on( 'click', '.wpeo-tag-search', window.task_manager.tag.change_filter );
	// jQuery( document ).on( 'click', '.wpeo-new-tag-search-btn', window.task_manager.tag.create_tag );
};

/**
 * Affiche la liste des tags pour affectation à un élément
 *
 * @param  {Object} element  The element clicked where to display tags.
 * @param  {Object} response The response to use to display tags
 */
window.task_manager.tag.load_tag_success = function( element, response ) {
	element.html( response.data.view );
};

window.task_manager.tag.load_archived_task = function( element, response ) {
	element.html( response.data.view );
};
