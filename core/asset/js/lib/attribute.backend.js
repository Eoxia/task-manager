jQuery.fn.get_data = function( cb ) {
  this.each( function() {
    var data = {};
	var i, localName;

    for ( i = 0; i <  jQuery( this )[0].attributes.length; i++ ) {
      localName = jQuery( this )[0].attributes[i].localName;
      if (  'data' == localName.substr( 0, 4 ) ||
            'action' == localName ) {

        localName = localName.substr( 5 );

        localName = ( 'nonce' == localName ) ? '_wpnonce' : localName;
        localName = localName.replace( '-', '_' );
        data[localName] =  jQuery( this )[0].attributes[i].value;
      }
    }

    cb( data );
  } );
};
