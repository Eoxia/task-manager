<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<li>
	<div class="point-title">
		<strong>
			<?php
			esc_html_e( 'Add the comment #', 'wpeotimeline-i18n' );
			echo esc_html( $the_object->id ) . ' ';
			esc_html_e( 'on the point #', 'wpeotimeline-i18n' );
			echo esc_html( $the_object->parent->id . ' ' . $the_object->parent->content );
			?>
		</strong>
		<span><i class="dashicons dashicons-clock"></i><?php echo esc_attr( $the_object->option['time_info']['elapsed'] ); ?> min</span>
	</div>
	<div class="point-content">
		<span></span>
		<p><?php echo $the_object->content; ?></p>
	</div>
</li>
