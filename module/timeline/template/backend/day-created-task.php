<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<li>
	<div class="point-title">
		<strong><?php _e( 'Create the task #', 'wpeotimeline-i18n' ); echo $the_object->id; ?></strong>
	</div>
	<div class="point-content">
		<?php echo $the_object->title; ?>
	</div>
</li>