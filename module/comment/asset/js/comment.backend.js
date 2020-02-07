/**
 * Initialise l'objet "comment" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.comment = {};

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.comment.init = function() {
	window.eoxiaJS.taskManager.comment.event();
};

/**
 * Initialise tous les évènements liés au comment de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.comment.event = function() {
	jQuery( document ).on( 'keyup', '.comment div[contenteditable="true"], .comment input[name="time"]', window.eoxiaJS.taskManager.comment.triggerCreate );
	jQuery( document ).on( 'blur keyup paste keydown click', '.comments .comment .content', window.eoxiaJS.taskManager.comment.updateHiddenInput );
	jQuery( document ).on( 'click', '.point.edit', window.eoxiaJS.taskManager.comment.loadComments );

	jQuery( document ).on( 'click', '.wpeo-pagination.pagination-comment .pagination-element', window.eoxiaJS.taskManager.comment.paginationUpdateComments );

	jQuery( document ).on( 'change keyup input', '.table-type-comment div[contenteditable="true"]', window.eoxiaJS.taskManager.comment.autoCompleteWithFollowers );

	jQuery( document ).on( 'keydown', '.cell-content div[contenteditable="true"]', window.eoxiaJS.taskManager.comment.autoCompleteBlockEnter );
	jQuery( document ).on( 'click keyup', '.tm-auto-complete-user .dropdown-item', window.eoxiaJS.taskManager.comment.choseFollowerAdmin );

	jQuery( document ).on( 'click', '.comment-container .comment-action .tm_register_comment', window.eoxiaJS.taskManager.comment.editComment );
};

/**
 * Fermes les points.active ainsi que leurs commentaires
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.comment.closePoint = function( event ) {
	jQuery( '.point.active' ).removeClass( 'active' );
	if ( jQuery( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );
	}
};

/**
 * Stop propagation afin d'éviter la fermeture du point.
 *
 * @since 1.5.0
 * @version 1.5.0
 *
 * @return {void}
 */
window.eoxiaJS.taskManager.comment.preventClosePoint = function( event ) {
	event.stopPropagation();
};

window.eoxiaJS.taskManager.comment.triggerCreate = function( event ) {
	if ( event.ctrlKey && 13 === event.keyCode ) {
		jQuery( this ).closest( '.comment' ).find( '.comment-container .comment-action .tm_register_comment' ).trigger( "click" );
	}
};

/**
 * Met à jour le champ caché contenant le texte du comment écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.comment.updateHiddenInput = function( event ) {
	if ( 0 < jQuery( this ).text().length ) {
		if( event.type == "click" || event.type == "keyup" || event.type == "keydown" ){
			jQuery( this ).closest( '.comment' ).find( '.placeholder' ).addClass( 'hidden' );
			jQuery( this ).closest( '.comment' ).removeClass( 'add' ).addClass( 'edit' );
			window.eoxiaJS.taskManager.core.initSafeExit( true );
		}
	} else {
		jQuery( this ).closest( '.comment' ).find( '.placeholder' ).removeClass( 'hidden' );
		jQuery( this ).closest( '.comment' ).removeClass( 'edit' ).addClass( 'add' );
		window.eoxiaJS.taskManager.core.initSafeExit( false );
	}

	jQuery( this ).closest( '.comment' ).find( 'input[name="content"]' ).val( jQuery( this ).html() );
};

/**
 * Charges les commentaires au clic sur le content editable.
 *
 * @param  {MouseEvent} event L'évènement du clic
 * @return {void}
 *
 * @since 1.3.6.0
 * @version 1.3.6.0
 */
window.eoxiaJS.taskManager.comment.loadComments = function( event ) {
	var data = {};

	data.action = 'load_comments';
	data.task_id = jQuery( this ).closest( '.wpeo-project-task' ).data( 'id' );
	data.point_id = jQuery( this ).closest( '.point' ).data( 'id' );

	if ( ! jQuery( this ).closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );

		window.eoxiaJS.loader.display( jQuery( this ) );
		window.eoxiaJS.request.send( jQuery( this ), data );
	}
};

window.eoxiaJS.taskManager.comment.blurHideComments = function( event ){
	if ( jQuery( this ).closest( 'div.point' ).find( '.comments' ).is( ':visible' ) ) {
		jQuery( 'div.point .comments:visible' ).slideUp( 400, function() {
			window.eoxiaJS.refresh();
		} );
	}

}

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
window.eoxiaJS.taskManager.comment.loadedCommentsSuccess = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( 'div.point' ).find( '.comments' ).html( response.data.view );
	jQuery( triggeredElement ).closest( 'div.point' ).find( '.comments .comment-container .auto-complete-user' ).html( response.data.follower_view );

	triggeredElement.removeClass( 'loading' );
	triggeredElement.closest( 'div.point' ).find( '.comments' ).slideDown( 400, function() {
		window.eoxiaJS.refresh();
	} );
};

/**
 * Le callback en cas de réussite à la requête Ajax "edit_comment".
 * Met le contenu dans la div.comments.
 *
 * @since 1.0.0
 * @version 1.5.0
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 */
window.eoxiaJS.taskManager.comment.addedCommentSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.comment' ).find( 'div.content' ).html( '' );

	triggeredElement.closest( '.wpeo-project-task' ).find( '.wpeo-task-time-info .elapsed' ).text( response.data.time.task );
	triggeredElement.closest( '.comments' ).prev( '.form' ).find( '.wpeo-time-in-point' ).text( response.data.time.point );

	triggeredElement.closest( 'div.point' ).find( '.comments' ).html( response.data.view );
	jQuery( '.wpeo-project-task[data-id="' + response.data.comment.data.post_id + '"] .point[data-id="' + response.data.comment.data.parent_id + '"] .comment.new div.content' ).focus();
	jQuery( '.wpeo-project-task[data-id="' + response.data.comment.data.post_id + '"] .point[data-id="' + response.data.comment.data.parent_id + '"] .wpeo-point-summary .number-comments' ).html( response.data.point.data.count_comments );

	window.eoxiaJS.refresh();
	window.eoxiaJS.taskManager.core.initSafeExit( false );
};

/**
 * Le callback en cas de réussite à la requête Ajax "delete_comment".
 * Supprimes la ligne.
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.comment.deletedCommentSuccess = function( triggeredElement, response ) {
	triggeredElement.closest( '.table-row' ).fadeOut();

	const comment = response.data.comment;

	jQuery( '.table-type-project[data-id=' + comment.data.post_id + '] .project-time .elapsed' ).text( response.data.time.task );
	jQuery( '.table-type-task[data-id=' + comment.data.parent_id + '] .task-time .elapsed' ).text( response.data.time.point );
	jQuery( '.table-type-task[data-id=' + comment.data.parent_id + '] .number-comments' ).text( response.data.comment.data.point.data.count_comments );

};

/**
 * Le callback en cas de réussite à la requête Ajax "load_edit_view_comment".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.taskManager.comment.loadedEditViewComment = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.comment' ).replaceWith( response.data.view );
	jQuery( '.wpeo-project-task.mask' ).removeClass( 'mask' );
};

window.eoxiaJS.taskManager.comment.afterTriggerChangeDate = function( $input ) {
	$input.closest( '.group-date' ).find( 'input[name="value_changed"]' ).val( 1 );
	$input.closest( '.group-date' ).find( 'div' ).attr( 'aria-label', $input.val() );
	$input.closest( '.group-date' ).find( 'span' ).css( 'background', '#389af6' );
};

window.eoxiaJS.taskManager.comment.paginationUpdateComments = function( event ) {
	var data = {};

	var pagination_parent = jQuery( this ).parent();

	data.action   = 'pagination_update_commments';
	data.page     = pagination_parent.data( 'page' );
	data.point_id = pagination_parent.data( 'point-id' );
	data.next     = jQuery( this ).data( 'pagination' );

	window.eoxiaJS.loader.display( jQuery( this ).parent() );
	window.eoxiaJS.request.send( jQuery( this ), data );
}

window.eoxiaJS.taskManager.comment.position_actual = 0;
window.eoxiaJS.taskManager.comment.autoCompleteWithFollowers = function( event ){

	var div = jQuery( this );

	jQuery( '.tm-auto-complete-user .wpeo-dropdown ').attr( 'data-id', div.closest( '.table-row' ).data( 'id' ) );

	if( jQuery( this ).html() != "" ){
	 	var position =  window.eoxiaJS.taskManager.comment.caretPositionIndex( event );

		var fullcontent         = jQuery( this ).html().trim();
		var fullcontent_replace = fullcontent.replace(/<\/div>/g, "");
		fullcontent_replace     = fullcontent_replace.replace(/&nbsp;/g, ' ');
		fullcontent_replace     = fullcontent_replace.replace(/<br>/g, '');

		var fullcontent_array = fullcontent_replace.split('<div>');

	 	var mot_focus = window.eoxiaJS.taskManager.comment.getFocusWordContentEditableAutocomplete(fullcontent_array, position);
		var ashtag    = "#";

		if( mot_focus.substr( 0, 1 ) == "@" && ! ashtag.includes(mot_focus) ){
			window.eoxiaJS.taskManager.comment.position_actual = position;

			var list = jQuery( '.tm-auto-complete-user .wpeo-dropdown' );

			if ( event.keyCode == 13 ) {
				if ( list.find(".dropdown-active").find( '.content-text' ) && list.find(".dropdown-active" ).is(':visible') ) {
					jQuery( list.find(".dropdown-active") );

					id = list.find(".dropdown-active").attr('data-id');
					if ( id !== undefined ) {
						follower = list.find(".dropdown-active").find( '.content-text' ).html().trim() + "#" + id;
					} else {
						follower = list.find(".dropdown-active").find( '.content-text' ).html().trim();
					}

					var content = window.eoxiaJS.taskManager.comment.updateContentEditableAutocomplete( follower, fullcontent_array, position );

					window.eoxiaJS.taskManagerGlobal.quickTime.focusElementWhenPageLoad( jQuery( div ).html( content ) );

					jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).removeClass( 'dropdown-active' );
					// window.eoxiaJS.taskManager.comment.searchFollowerInContentEditable( jQuery( this ) );
				}
				return;
			}

			var pos = jQuery( this ).offset();

			jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).addClass( 'dropdown-active' ); // Affiche l'auto complete
			jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).css( {
				left: pos.left + 'px',
				top: (pos.top + 50) + 'px',
				position: 'absolute'
			} );


			var mot_focus = mot_focus.substr( 1 ); // Recupere le mot (sans le @)
			list.find("li:first").focus().addClass("active"); // Premier élement -> update de la couleur
			mot_focus = mot_focus.toLowerCase();

			var first_element = false;

			list.find( 'li' ).each( function(e){ // Pour chaque follower
				var element = jQuery( this ).find( '.content-text' ).html().trim();
				element = element.toLowerCase();

				if( element.includes(mot_focus) ){
					if( ! first_element ){
						first_element = true;
						jQuery( this ).addClass( 'dropdown-active' );
					}else{
						jQuery( this ).removeClass( 'dropdown-active' );
					}
					jQuery( this ).show();
				}else{
					jQuery( this ).removeClass( 'dropdown-active' );
					jQuery( this ).hide();
				}
			} );
		} else {
			jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).removeClass( 'dropdown-active' );
		}
	} else{
		jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).removeClass( 'dropdown-active' );
	}
}
window.eoxiaJS.taskManager.comment.getFocusWordContentEditableAutocomplete = function( fullcontent_array, position ) {
	var taille    = 0;
	var mot_focus = "";

	for (var i = 0; i < fullcontent_array.length; i++) {
		// 20 + 0 >= 20
	  if (fullcontent_array[i].length + taille >= position) {
			position -= taille;
			var mot   = fullcontent_array[i].substring(0, position);
			var mymot = mot.lastIndexOf(" ");
			mot_focus = mot.substring(mymot + 1);
			break;
	  } else {
	  	taille += fullcontent_array[i].length;
	  }
	}

	if( fullcontent_array[0] == "" ) {
		fullcontent_array.splice( 0, 1 );
	}

	return mot_focus;
};

window.eoxiaJS.taskManager.comment.updateContentEditableAutocomplete = function( follower = "", content_array = [], position = 0 ){
	var taille = 0;
	for( var i = 0; i < content_array.length; i++){
	  if( content_array[i].length + taille >= position){
	    position-= taille;
	    var positionelement  = content_array[i].substring(0, position);
	    var index = positionelement.lastIndexOf(" ");

	    var preventelement = positionelement.substring(0, index) == "" ? "" : positionelement.substring(0, index) + "&nbsp;";
	    var nextelement = content_array[i].substring( position );

	    content_array[i] = preventelement + "@" + follower + "&nbsp;" + nextelement;
	    break;
	  }else{
	    taille += content_array[i].length;
	  }
	}

 	var content = "";
	for( var i = 0; i < content_array.length; i++){
	  content += "<div>";
	  if ( content_array[i].length == "" ){
	    content += "<br>";
	  }else{
	    content += content_array[i];
	  }
	  content += "</div>";
	}

	return content;
}


window.eoxiaJS.taskManager.comment.autoCompleteBlockEnter = function( event ){
	if( event.keyCode == 13 ){
		var list = jQuery( '.tm-auto-complete-user .wpeo-dropdown' );
		if( list.is(':visible') && list.find(".dropdown-active" ).is(':visible')){
			return false;
		}
	}
}

window.eoxiaJS.taskManager.comment.choseFollowerAdmin = function( event ){

	// @todo: a optimiser
	var content_element = jQuery( '.table-row[data-id=' + jQuery( this ).closest( '.wpeo-dropdown' ).data('id') + '] .table-cell div[contenteditable="true"]' );

	position = window.eoxiaJS.taskManager.comment.position_actual;

	var fullcontent = jQuery( content_element ).html().trim();
	var fullcontent_replace = fullcontent.replace(/<\/div>/g, "");
	fullcontent_replace = fullcontent_replace.replace(/&nbsp;/g, ' ');
	fullcontent_replace = fullcontent_replace.replace(/<br>/g, '');

	var fullcontent_array = fullcontent_replace.split('<div>');

	var mot_focus = window.eoxiaJS.taskManager.comment.getFocusWordContentEditableAutocomplete(fullcontent_array, position);
	mot_focus.substr( 0, 1 );

	// content text dans l'élement
	if( jQuery( this ).find( '.content-text' ) ){
		id = jQuery( this ).attr('data-id');

		if ( id !== undefined ){
			follower = jQuery( this ).find( '.content-text' ).html().trim() + "#" + id;
		} else {
			follower = jQuery( this ).find( '.content-text' ).html().trim();
		}

		var content = window.eoxiaJS.taskManager.comment.updateContentEditableAutocomplete( follower, fullcontent_array, position );

		window.eoxiaJS.taskManagerGlobal.quickTime.focusElementWhenPageLoad( jQuery( content_element ).html( content ) );
		jQuery( '.tm-auto-complete-user .wpeo-dropdown' ).removeClass( 'dropdown-active' ); // Cache l'auto complete
	}
};

window.eoxiaJS.taskManager.comment.searchFollowerInContentEditable = function( element ){
	var content = jQuery( element ).html();
	var fullcontent_replace = content.replace(/<\/div>/g, "");
	fullcontent_replace = fullcontent_replace.replace(/&nbsp;/g, ' ');
	var fullcontent = fullcontent_replace.replace(/<br>/g, '');

	var ul_element = jQuery( '.tm-auto-complete-user .dropdown-content' );

	var list_notif = [];
	if( fullcontent.trim().includes('@everyone') ){ // Si everyone est tag, pas besoin de rechercher chaque personne
		list_notif.push( '-1' );
	} else {
		jQuery( ul_element.find( '.dropdown-item' ) ).each( function( index ) {
			var element_content = jQuery( this ).find( '.tm-user-data input[type="hidden"]' ).val().trim();
			var element_id = jQuery( this ).attr( 'data-id' );
			if( fullcontent.trim().includes(element_content.trim()) ){
				list_notif.push( element_id );
			}
		})
	}

	return list_notif;
}

window.eoxiaJS.taskManager.comment.editComment = function( event ){
	var data = {
		'mysql_date' : jQuery( this ).closest( '.comment-container' ).find( '.comment-meta .group-date .form-field-container .mysql-date' ).val(),
		'content' : jQuery( this ).closest( '.comment-container' ).find( '.comment-content-text input[type="hidden"]' ).val(),
		'post_id' : jQuery( this ).closest( '.comment' ).find( '[name=post_id]' ).val(),
		'parent_id' : jQuery( this ).closest( '.comment' ).find( '[name=parent_id]' ).val(),
		'comment_id' : jQuery( this ).closest( '.comment' ).find( '[name=comment_id]' ).val(),
		'time' : jQuery( this ).closest( '.comment-container' ).find( '.comment-meta input[name="time"]' ).val(),
		'parent' : 'comment',
		'action' : 'edit_comment'
	}

	var element = jQuery( this ).closest( '.comment-container' ).find( '.comment-content-text .content' );
	data.notif = window.eoxiaJS.taskManager.comment.searchFollowerInContentEditable( element );

	window.eoxiaJS.loader.display( jQuery( this ).closest( '.comment-container' ) );
	window.eoxiaJS.request.send( jQuery( this ), data );
}

window.eoxiaJS.taskManager.comment.caretPositionIndex = function( event ){
    const range = window.getSelection().getRangeAt(0);
    const { endContainer, endOffset } = range;

    // get contenteditableDiv from our endContainer node
    let contenteditableDiv;
    const contenteditableSelector = "div[contenteditable]";
    switch (endContainer.nodeType) {
      case Node.TEXT_NODE:
        contenteditableDiv = endContainer.parentElement.closest(contenteditableSelector);
        break;
      case Node.ELEMENT_NODE:
        contenteditableDiv = endContainer.closest(contenteditableSelector);
        break;
    }

    if (!contenteditableDiv) return '';

    const countBeforeEnd = countUntilEndContainer(contenteditableDiv, endContainer);
    if (countBeforeEnd.error ) return null;
    return countBeforeEnd.count + endOffset;

    function countUntilEndContainer(
    parent,
     endNode,
     countingState = {count: 0}
    ) {
      for (let node of parent.childNodes) {
        if (countingState.done) break;
        if (node === endNode) {
          countingState.done = true;
          return countingState;
        }
        if (node.nodeType === Node.TEXT_NODE) {
          countingState.count += node.length;
        } else if (node.nodeType === Node.ELEMENT_NODE) {
          countUntilEndContainer(node, endNode, countingState);
        } else {
          countingState.error = true;
        }
      }
      return countingState;
    }
  }
