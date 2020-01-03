/**
 * Initialise l'objet "task" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.newComment = {};

window.eoxiaJS.taskManager.newComment.init = function() {
	window.eoxiaJS.taskManager.newComment.event();
};

window.eoxiaJS.taskManager.newComment.event = function() {
	jQuery( '.tm-wrap' ).on( 'blur', '.table-column .column-extend .comment-title', window.eoxiaJS.taskManager.newComment.editContent );
};

window.eoxiaJS.taskManager.newComment.editContent = function() {
	var data = {};
	var element;

	if ( ! element ) {
		element = jQuery( this );
	}

	data.action     = 'edit_comment';
	data._wpnonce   = element.closest( '.table-row' ).data( 'nonce' );
	data.comment_id = element.closest( '.table-row' ).data( 'id' );
	data.post_id    = element.closest( '.table-row' ).data( 'post-id' );
	data.parent_id  = element.closest( '.table-row' ).data( 'parent-id' );
	data.content    = element.text();

	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );

};

window.eoxiaJS.taskManager.newComment.addedCommentSuccess = function( triggeredElement, response ) {
	var tmp = jQuery( response.data.view );
	tmp.css({display: 'none'});
	jQuery( '.table-task .column-extend .table-comments .table-header' ).after( tmp );
	tmp.slideDown(400);
};

/**
 * Le callback en cas de réussite à la requête Ajax "load_comments".
 * Met le contenu dans la div.comments.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.newComment.loadedCommentsSuccess = function( triggeredElement, response ) {
	var taskColumn = triggeredElement.closest( '.table-column' );

	taskColumn.find( '.column-extend' ).html( response.data.view );

	triggeredElement.removeClass( 'loading' );
	taskColumn.find( '.column-extend' ).slideDown( 400, function() {
	} );
};
