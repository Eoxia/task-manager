<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<div class="point-title">
		<strong>
			<?php
			esc_html_e( 'Completed the point #', 'task-manager' );
			echo esc_html( $the_object->id );
			esc_html_e( ' on the task #', 'task-manager' );
			echo esc_html( $the_object->parent->id . ' ' . $the_object->parent->title );
			?>
		</strong>
	</div>
	<div class="point-content">
		<?php echo $the_object->content; ?>
	</div>
</li>
