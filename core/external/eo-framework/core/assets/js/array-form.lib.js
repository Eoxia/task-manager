/**
 * Action for make request AJAX.
 *
 * @since 1.0.0-easy
 * @version 1.0.0-easy
 */

if ( ! window.eoxiaJS.arrayForm ) {
	/**
	 * Declare the object arrayForm.
	 *
	 * @since 1.0.0-easy
	 * @version 1.0.0-easy
	 * @type {Object}
	 */
	window.eoxiaJS.arrayForm = {};

	window.eoxiaJS.arrayForm.init = function() {};

	window.eoxiaJS.arrayForm.event = function() {};

	window.eoxiaJS.arrayForm.getInput = function( parent ) {
		return parent.find( 'input, textarea, select' );
	};

	window.eoxiaJS.arrayForm.getInputValue = function( input ) {
		switch ( input.getAttribute( 'type' ) ) {
			case 'checkbox':
				return input.checked;
				break;
			case 'radio':
				return jQuery( 'input[name="' + jQuery( input ).attr( 'name' ) + '"]:checked' ).val();
				break;
			default:
				return input.value;
				break;
		}
	};
}
