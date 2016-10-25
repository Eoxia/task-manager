<?php
/**
 * Display line history time.
 *
 * @package HistoryTime
 */

?>
<li data-id="<?php echo esc_attr( $history_time->id ); ?>">
	<?php echo get_avatar( $history_time->author_id, 20, 'blank' ); ?>
	<?php
	 	$user = $wp_project_user_controller->show( $history_time->author_id );
		echo esc_html( $user->displayname );
	?>,
	<?php echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->date, true ) ); ?>
	 	<span class="dashicons dashicons-calendar-alt"></span>
	<?php echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->option['due_date'], true ) ); ?>
	<span class="dashicons dashicons-clock"></span>
	<?php echo esc_html( $history_time->option['estimated_time'] ); ?>
	<span class="delete-history-time dashicons dashicons-dismiss" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_history_time' ) ); ?>"></span>
</li>
