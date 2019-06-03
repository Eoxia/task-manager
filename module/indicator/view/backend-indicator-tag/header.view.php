<span class="tm_client_indicator_update">
	<span class="action-attribute button"
	data-action="load_tags_stats"
	data-tag_id="<?php echo esc_attr( $tagid ); ?>"
	data-year="<?php echo esc_attr( $year - 1 ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_client' ) ); ?>">
		<span class="button-icon fa fa-minus" aria-hidden="true"></span>
	</span>

	<span class="action-attribute button"
	data-action="load_tags_stats"
	data-tag_id="<?php echo esc_attr( $tagid ); ?>"
	data-year="<?php echo esc_attr( $year ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_client' ) ); ?>">
		<span><?php echo esc_attr( $year ); ?></span>
	</span>

	<span class="action-attribute button"
	data-action="load_tags_stats"
	data-tag_id="<?php echo esc_attr( $tagid ); ?>"
	data-year="<?php echo esc_attr( $year + 1 ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_tag' ) ); ?>">
		<span class="button-icon fa fa-plus" aria-hidden="true"></span>
	</span>

		<span class="action-attribute button wpeo-tooltip-event"
		data-action="load_tags_stats"
		data-tag_id="<?php echo esc_attr( $tagid ); ?>"
		data-year="<?php echo esc_attr( $year ); ?>"
		data-order=""
		data-title="<?php esc_html_e( 'Order from Alphabetic', 'task-manager' ); ?>"
		aria-label="<?php esc_html_e( 'Order from Alphabetic', 'task-manager' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_tag' ) ); ?>"
		style="float:right; color : #3AF34D">
			<span class="button-icon fas fa-font" aria-hidden="true"></span>
		</span>

		<span class="action-attribute button wpeo-tooltip-event"
		data-action="load_tags_stats"
		data-tag_id="<?php echo esc_attr( $tagid ); ?>"
		data-year="<?php echo esc_attr( $year ); ?>"
		data-order="ASC"
		data-title="<?php esc_html_e( 'Order MIN to MAX', 'task-manager' ); ?>"
		aria-label="<?php esc_html_e( 'Order MIN to MAX', 'task-manager' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_tag' ) ); ?>"
		style="float:right; color: green;">
			<span class="button-icon fas fa-arrow-down" aria-hidden="true"></span>
		</span>
		<span class="action-attribute button wpeo-tooltip-event"
		data-action="load_tags_stats"
		data-tag_id="<?php echo esc_attr( $tagid ); ?>"
		data-year="<?php echo esc_attr( $year ); ?>"
		data-order="DESC"
		data-title="<?php esc_html_e( 'Order MAX to MIN', 'task-manager' ); ?>"
		aria-label="<?php esc_html_e( 'Order MAX to MIN', 'task-manager' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'update_indicator_tag' ) ); ?>"
		style="float:right; color: blue">
			<span class="button-icon fa fa-arrow-up" aria-hidden="true"></span>
		</span>
</span>
