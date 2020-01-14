<div class="table-cell-container">
	<div class="wpeo-tooltip-event" aria-label="Temps total : <?php echo $data['human_readable_elapsed']; ?>">
	<span class="elapsed"><?php echo esc_html( $data['value'] ); ?></span>
	<?php if ( isset( $data['value2'] ) ) : ?>
		<span class="separator">/</span>
		<span class="estimated"><?php echo esc_html( $data['value2'] ); ?></span>
	<?php endif; ?>
<!--	<span class="unit">min</span>-->
	</div>
</div>
