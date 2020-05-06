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
	jQuery( document ).on( 'blur', '.table-type-comment .comment-title', window.eoxiaJS.taskManager.newComment.editContent );
	jQuery( document ).on( 'blur', '.table-type-comment .comment-time', window.eoxiaJS.taskManager.newComment.editContent );
	jQuery( document ).on( 'blur', '.table-type-comment .group-date .date', window.eoxiaJS.taskManager.newComment.editContent );
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
	data.time       = element.closest( '.table-row' ).find( '.comment-time' ).text();
	data.content    = element.closest( '.table-row' ).find( '.comment-title' ).html();
	data.mysql_date = element.closest( '.table-row' ).find( '.mysql-date' ).val();

	data.notif = window.eoxiaJS.taskManager.comment.searchFollowerInContentEditable( element );


	window.eoxiaJS.loader.display( element.closest( 'div' ) );
	window.eoxiaJS.request.send( element, data );
};

window.eoxiaJS.taskManager.newComment.addedCommentSuccess = function( triggeredElement, response ) {
	if ( ! response.data.toggle ) {
		this.loadedCommentsSuccess( triggeredElement, response );

		jQuery( '.table-type-task[data-id=' + response.data.point.data.id + '] .wpeo-util-hidden' ).removeClass( 'wpeo-util-hidden' );
		jQuery( '.table-type-task[data-id=' + response.data.point.data.id + '] .fas.fa-angle-right' ).removeClass( 'fa-angle-right' ).addClass( 'fa-angle-down' );
	} else {
		var tmp = jQuery( response.data.view );
		tmp.css({display: 'none'});
		jQuery( '.table-type-task[data-id=' + response.data.point.data.id + ']' ).after( tmp );
		tmp.slideDown(400);

		window.eoxiaJS.taskManager.core.selectContentEditable( tmp.find( '.comment-title' ) );
	}

	triggeredElement.closest( '.row-empty' ).remove();

	const comment = response.data.comment;

	jQuery( '.table-type-project[data-id=' + comment.data.post_id + '] .project-time .elapsed' ).text( response.data.time.task );
	jQuery( '.table-type-task[data-id=' + comment.data.parent_id + '] .task-time .elapsed' ).text( response.data.time.point );
	jQuery( '.table-type-task[data-id=' + comment.data.parent_id + '] .number-comments' ).text( response.data.point.data.count_comments );

	window.eoxiaJS.taskManager.newTask.stickyAction();
};

window.eoxiaJS.taskManager.newComment.editedCommentSuccess = function( triggeredElement, response ) {
	const comment = response.data.comment;

	jQuery( '.table-type-project[data-id=' + comment.data.post_id + '] .project-time .elapsed' ).text( response.data.time.task );
	jQuery( '.table-type-task[data-id=' + comment.data.parent_id + '] .task-time .elapsed' ).text( response.data.time.point );
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
	var view = jQuery( response.data.view );
	view.css({display: 'none'});

	var row = triggeredElement.closest( '.table-row' );
	row.after(view);

	view.slideDown( 400 );

	triggeredElement.removeClass( 'loading' );

	if ( triggeredElement.hasClass( 'action-attribute' ) ) {
		triggeredElement.attr( 'data-toggle', true );
	}

	if ( 'addedCommentSuccess' == response.data.callback_success ) {
		window.eoxiaJS.taskManager.core.selectContentEditable( jQuery( '.table-type-comment[data-id=' + response.data.comment.data.id + '] .comment-title' ) );

	}

	window.eoxiaJS.taskManager.newTask.stickyAction();
};
