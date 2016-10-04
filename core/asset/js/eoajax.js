jQuery( document ).ready( function() {
	jQuery.eoajax = function( url, data, callback ) {
		if ( typeof callback == 'function' ) {
			jQuery.post( url, data, function( response ) { 
				if( response && !response.success ) {
					//alert( 'Success: ' + response.success );
				}
				else {
					callback.call( response.data );
				}
			} );
	    }
	}
	
	/** Si ajaxForm est activé */
	if( jQuery.fn.ajaxSubmit ) {
		jQuery.eoAjaxSubmit = function( form, data, callback ) {
			if ( typeof callback == 'function' ) {
				form.ajaxSubmit( {
					'data': data,
					'success': function( response ) { 
						if( response && !response.success ) {
							//alert( 'Success: ' + response.success );
						}
						else {
							callback.call( response.data );
						}
					}
				} );
		    }
			
			return this;
		}
	}
	
	if( jQuery.fn.ajaxForm ) {
		jQuery.eoAjaxForm = function( form, data, callback ) {
			if ( typeof callback == 'function' ) {
				form.ajaxForm( {
					'data': data,
					'beforeSubmit': function( arra, $form, options ) {
						return false;
					},
					'success': function( response ) { 
						if( response && !response.success ) {
							//alert( 'Success: ' + response.success );
						}
						else {
							callback.call( response.data );
						}
					}
				} );
		    }
			
			return this;
		}
	}
} );