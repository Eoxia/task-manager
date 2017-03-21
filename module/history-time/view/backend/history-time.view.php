<?php
/**
 * Display line history time.
 *
 * @package HistoryTime
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

?>
<li class="list-element" data-id="<?php echo esc_attr( $history_time->id ); ?>">
	<ul>
		<li class="avatar"></li>
		<li class="author">
		 auteur
		</li>
		<li class="date">
			<span class="dashicons dashicons-calendar-alt"></span>
		</li>
		<li class="time">
			<span class="dashicons dashicons-clock"></span>
			<?php echo esc_html( $history_time->estimated_time ); ?>
		</li>
		<li class="delete">
			<span class="delete-history-time dashicons dashicons-no-alt" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_history_time' ) ); ?>"></span>
		</li>
	</ul>
</li>
