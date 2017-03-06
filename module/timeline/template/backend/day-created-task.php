<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<div class="point-title">
		<strong>
			<?php
			esc_html_e( 'Create the task #', 'wpeotimeline-i18n' );
			echo esc_html( $the_object->id );

			if ( ! empty( $the_object->parent ) ) :
				esc_html_e( ' on the task parent #', 'wpeotimeline-i18n' );
				echo esc_html( $the_object->parent->id . ' ' . $the_object->parent->title );
			endif;
			?>
		</strong>
	</div>
	<div class="point-content">
		<?php echo $the_object->title; ?>
	</div>
</li>
