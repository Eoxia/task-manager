/**
 * Initialise l'objet "comment" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.parseContent = {};
window.eoxiaJS.taskManager.parseContent.currentInput;
window.eoxiaJS.taskManager.parseContent.currentSuggest;
window.eoxiaJS.taskManager.parseContent.parseContent;
window.eoxiaJS.taskManager.parseContent.startPos;
window.eoxiaJS.taskManager.parseContent.endPos;

/**
 * La méthode obligatoire pour la biblotèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.0.0
 */
window.eoxiaJS.taskManager.parseContent.init = function() {
	window.eoxiaJS.taskManager.parseContent.event();

	wp.heartbeat.enqueue( 'refresh-index' );
};

/**
 * Initialise tous les évènements liés au comment de Task Manager.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.5.0
 */
window.eoxiaJS.taskManager.parseContent.event = function() {
	jQuery( document ).on( 'blur keyup paste keydown click', '.comments .comment .content, .wpeo-point-new-contenteditable', window.eoxiaJS.taskManager.parseContent.parse );
	jQuery( document ).on( 'keydown', window.eoxiaJS.taskManager.parseContent.navigate );
	jQuery( document ).on( 'click', '.suggest li', window.eoxiaJS.taskManager.parseContent.selectSuggested );

	jQuery( document ).on( 'heartbeat-tick.refresh-index', function( event, data ) {
		taskManager.data = data.task_manager_data;
	} );
};

window.eoxiaJS.taskManager.parseContent.navigate = function( event ) {
	if ( window.eoxiaJS.taskManager.parseContent.currentSuggest ) {
		if ( window.eoxiaJS.taskManager.parseContent.currentSuggest.hasClass( 'dropdown-active' ) ) {
			switch (event.keyCode) {
				case 40: // BAS
					var next = window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li.focus' ).next();

					if (next.length == 0 ) {
						next = window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li:first' );
					}

					window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li.focus' ).removeClass( 'focus' );
					next.addClass( 'focus' );

					event.preventDefault();
				break;
				case 38: // HAUT
					var prev = window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li.focus' ).prev();

					if (prev.length == 0 ) {
						prev = window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li:last' );
					}

					window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li.focus' ).removeClass( 'focus' );
					prev.addClass( 'focus' );

					event.preventDefault();
				break;
				case 13:
				case 9:
					window.eoxiaJS.taskManager.parseContent.selectSuggested();
					event.preventDefault();
				break;
			}
		}
	}
}

window.eoxiaJS.taskManager.parseContent.selectSuggested = function() {
	var text = window.eoxiaJS.taskManager.parseContent.currentInput.text();
	var startText = text.substr( 0, window.eoxiaJS.taskManager.parseContent.startPos );
	var endText = undefined;
	if ( window.eoxiaJS.taskManager.parseContent.endPos > -1 ) {
		endText = text.substr( window.eoxiaJS.taskManager.parseContent.startPos + window.eoxiaJS.taskManager.parseContent.parseContent.length );
	}
	var replacedText = window.eoxiaJS.taskManager.parseContent.parseContent;

	replacedText = replacedText.replace( replacedText, window.eoxiaJS.taskManager.parseContent.currentSuggest.find( 'li.focus' ).data( 'value' ) );

	if ( endText ) {
		text = startText + replacedText + endText;
	} else {
		text = startText + replacedText;
	}

	var lengthText = startText + replacedText;

	window.eoxiaJS.taskManager.parseContent.currentInput.text( text );

	var el = window.eoxiaJS.taskManager.parseContent.currentInput[0];
	var range = document.createRange();
	var sel = window.getSelection();
	sel.collapse(el.firstChild, lengthText.length);

	window.eoxiaJS.taskManager.parseContent.currentSuggest.removeClass( 'dropdown-active' );
}

/**
 * Met à jour le champ caché contenant le texte du comment écris dans la div "contenteditable".
 *
 * @param  {MouseEvent} event L'évènement de la souris lors de l'action.
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.6.0
 */
window.eoxiaJS.taskManager.parseContent.parse = function( event ) {
	if ( 'keyup' == event.type && (event.which <= 90 && event.which >= 48 ) || event.which == 32 || event.which == 37 || event.which == 39 || ( event.which >= 96 && event.which <= 105 ) || event.which == 8 ) {
		window.eoxiaJS.taskManager.parseContent.currentInput = jQuery( this );
		var x = getSelectionCoords().x;
		var position = jQuery( this ).offset();

		var foundHashtag = false;
		var endPos = getCaretPosition(jQuery( this )[0]);
		var startPos = -1;
		var stringToParse = '';
		var currentText = jQuery( this ).text();

		// Cherche fin de la chaine de caractère

		var i = endPos;
		while ( ( i - 1 ) >= 0 ) {
			if ( currentText[ i - 1 ].match(/\s/g)) {
				foundHashtag = false;
				startPos = -1;
				break;
			} else if ( currentText[ i - 1 ] != '#' ) {
				i--;
			} else {
				foundHashtag = true;
				startPos = i;
				break;
			}
		}

		i = startPos;

		if ( i < - 1 ) {
			i = 0;
		}

		while (true) {
			if ( currentText[i] ) {
				if ( currentText[i].match(/\s/g)) {
					endPos = i;
					break;
				}
			} else {
				endPos = -1;
				break;
			}
			i++;
		}

		if ( foundHashtag ) {
			if ( endPos != - 1 && startPos != -1 ) {
				stringToParse = currentText.substr( startPos, endPos - startPos );
			} else if (endPos != -1 && startPos == -1) {
				stringToParse = currentText.substr(0, endPos);
			} else if( endPos == -1 && startPos != -1) {
				stringToParse = currentText.substr(startPos);
			} else {
				stringToParse = currentText;
			}
		}

		window.eoxiaJS.taskManager.parseContent.startPos = startPos;
		window.eoxiaJS.taskManager.parseContent.endPos = endPos;

		window.eoxiaJS.taskManager.parseContent.currentSuggest = jQuery( this ).closest( 'li' ).find( '.suggest' );

		if ( foundHashtag ) {
			window.eoxiaJS.taskManager.parseContent.parseContent = stringToParse;
			window.eoxiaJS.taskManager.parseContent.currentSuggest.addClass( 'dropdown-active' );
			window.eoxiaJS.taskManager.parseContent.currentSuggest.css( {
				'left': (x - position.left) + 'px',
				'top': 20,
			} );
		} else {
			window.eoxiaJS.taskManager.parseContent.currentSuggest.removeClass( 'dropdown-active' );
		}

		var list = {};

		var output = '';


		if ( stringToParse ) {
			list = window.eoxiaJS.taskManager.getResultsFromParsedContent( list, stringToParse, true );
			list = window.eoxiaJS.taskManager.getResultsFromParsedContent( list, stringToParse );
		} else {
			for (var key in taskManager.data.last) {

				var content  = taskManager.data.last[key].content;
				var type     = taskManager.data.last[key].type;
				var id       = taskManager.data.last[key].id;
				var id_index = taskManager.data.last[key].id_index;

				list[id_index] = {
					id: id,
					content: content,
					type: type
				};
			}
		}

		var first = true;

		for (var key in list ) {
			var type_value = '';

			if ( list[key].type == 'point' ) {
				type_value = 'P';
			} else if( list[key].type == 'comment' ) {
				type_value = 'C';
			} else {
				type_value = 'T';
			}

			if ( first ) {
				output += '<li class="dropdown-item navigation focus" data-value="' + key + '" data-type="' + list[key].type + '" data-id="' + list[key].id + '"><span class="id">#' + key + '</span>' + list[key].content + '</li>';
				first = false;
			} else {
				output += '<li class="dropdown-item navigation" data-value="' + key + '" data-type="' + list[key].type + '" data-id="' + list[key].id + '"><span class="id">#' + key + '</span>' + list[key].content + '</li>';
			}
		}

		if ( 0 === Object.keys( list ).length ) {
				window.eoxiaJS.taskManager.parseContent.currentSuggest.removeClass( 'dropdown-active' );
		}

		window.eoxiaJS.taskManager.parseContent.currentSuggest.find( '.dropdown-content' ).html( output );
	}
};

window.eoxiaJS.taskManager.getResultsFromParsedContent = function( list, stringToParse, priorityID ) {
	if ( 5 <= Object.keys( list ).length ) {
		return list;
	}

	for (var key in taskManager.data.list) {
		if ( 5 <= Object.keys( list ).length ) {
			break;
		}

		var content = taskManager.data.list[key].content;
		var type    = taskManager.data.list[key].type;
		var id      = taskManager.data.list[key].id;

		if ( priorityID && stringToParse == id ) {
			list[key] = {
				id: id,
				content: content,
				type: type
			};
		} else if( !priorityID && ( stringToParse == key || content.indexOf( stringToParse ) != - 1 ) ) {
			list[key] = {
				id: id,
				content: content,
				type: type
			};
		}
	}

	return list;
};

function getSelectionCoords() {
	var sel = document.selection, range, rect;
	var x = 0, y = 0;
	if ( sel ) {
		if ( sel.type != "Control" ) {
			range = sel.createRange();
			range.collapse( true );
			x = range.boundingLeft;
			y = range.boundingTop;
		}
	} else if ( window.getSelection ) {
		sel = window.getSelection();
		if ( sel.rangeCount ) {
			range = sel.getRangeAt( 0 ).cloneRange();
			if ( range.getClientRects ) {
				range.collapse( true );
				if ( range.getClientRects().length > 0 ) {
					rect = range.getClientRects()[0];
					x = rect.left;
					y = rect.top;
				}
			}

			if ( x == 0 && y == 0 ) {

				var span = document.createElement( "span" );
				if ( span.getClientRects ) {
					span.appendChild( document.createTextNode( "\u200b" ) );
					range.insertNode( span );
					rect = span.getClientRects()[0];
					x = rect.left;
					y = rect.top;
					var spanParent = span.parentNode;
					spanParent.removeChild( span );

					spanParent.normalize();
				}
			}
		}
	}

	return { x: x, y: y };
}

function setCursorPosition( obj, pos ) {

	if(obj != null) {
		if(obj.createTextRange) {
			var range = obj.createTextRange();
			range.move('character', pos);
			range.select();
		} else {
			if(obj.selectionStart) {
				obj.focus();
				obj.setSelectionRange(pos, pos);
			} else {
				obj.focus();
			}
		}
	}
}

function getCaretPosition( editableDiv ) {
	var caretPos = 0,
	sel, range;
	if ( window.getSelection ) {
		sel = window.getSelection();
		if ( sel.rangeCount ) {
			range = sel.getRangeAt( 0 );
			if ( range.commonAncestorContainer.parentNode == editableDiv ) {
				caretPos = range.endOffset;
			}
		}
	} else if ( document.selection && document.selection.createRange ) {
		range = document.selection.createRange();
		if ( range.parentElement() == editableDiv ) {
			var tempEl = document.createElement( "span" );
			editableDiv.insertBefore( tempEl, editableDiv.firstChild );
			var tempRange = range.duplicate();
			tempRange.moveToElementText( tempEl );
			tempRange.setEndPoint( "EndToEnd", range );
			caretPos = tempRange.text.length;
		}
	}
	return caretPos;
}
