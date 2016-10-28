jQuery( document ).ready( function() {
	wpeo_timeline.init();
});



/** It's a Namespace. Use this for no conflict */
var wpeo_timeline = {
	init: function() {
		wpeo_timeline.event();
		wpeo_timeline.add_class_blocks();
		wpeo_timeline.add_animation_blocks();
	},

	event: function() {
		jQuery( document ).on( 'change', '.wpeo-project-timeline .wpeo-header-bar select', function( event ) { wpeo_timeline.change_user_filter( event, jQuery( this ) ); } );
	},

	change_user_filter: function( event, element ) {
		if ( jQuery( element ).val() != undefined && jQuery( element ).val() != '' ) {
			var data = {
				action: 'load_timeline_user',
				user_id: jQuery( element ).val(),
			};

			jQuery.eoajax( ajaxurl, data, function() {
				jQuery( '.wpeo-project-timeline .wpeo-timeline-content' ).replaceWith( this.template );
				wpeo_timeline.add_class_blocks();
				wpeo_timeline.add_animation_blocks();
			} );
		}
	},

	add_class_blocks: function() {
		var blocks = jQuery('.timeline-block.day');
		jQuery.each( blocks, function() {
			/* Solution temporaire pour classer les blocks */
			if( jQuery( this )[0].offsetLeft <= ( jQuery( '.wpeo-timeline-content' ).width() / 2 ) ) {
				jQuery( this ).addClass( 'left-block' );
			} else {
				jQuery( this ).addClass( 'right-block' );
			}
		});
	},

	add_animation_blocks: function() {
		var blocks = jQuery('.timeline-block.is-hidden');
		/* Rend visible les blocs présents sur la page au chargement */
		blocks.each(function() {
			if( jQuery(this).offset().top <= jQuery(window).scrollTop() + jQuery(window).height()*0.75 ) {
				jQuery(this).removeClass('is-hidden');
			}
		});

		var blocks = jQuery('.timeline-block.is-hidden');
		/* Anime l'élément lorsque il est dans la viewport */
		jQuery(window).on('scroll', function(){
			blocks.each(function() {
				if( jQuery(this).offset().top <= jQuery(window).scrollTop() + jQuery(window).height()*0.75 ) {
					jQuery(this).removeClass('is-hidden');

					if( jQuery(this).hasClass('month') ) {
						jQuery(this).addClass('bounceIn');
					}
					else if( jQuery(this).hasClass('left-block') ) {
						jQuery(this).addClass('bounceInLeft');
					} else {
						jQuery(this).addClass('bounceInRight');
					}
				}
			});
		});
	}


};
