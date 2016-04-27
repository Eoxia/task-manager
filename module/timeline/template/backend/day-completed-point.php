<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<div class="point-title">
		<strong><?php _e( 'Completed the point #', 'wpeotimeline-i18n' ); echo $the_object->id; ?></strong>
		<span><i class="dashicons dashicons-clock"></i><?php echo $the_object->option['time_info']['elapsed']; ?> min</span>
	</div>
	<div class="point-content">
		<?php echo $the_object->content; ?>
	</div>
</li>