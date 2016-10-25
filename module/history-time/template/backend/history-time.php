<?php
/**
 * Display line history time.
 *
 * @package HistoryTime
 */

?>
<li class="list-element" data-id="<?php echo esc_attr( $history_time->id ); ?>">
	<ul>
		<li class="avatar"><?php echo get_avatar( $history_time->author_id, 20, 'blank' ); ?></li>
		<li class="author">
			<?php
			 	$user = $wp_project_user_controller->show( $history_time->author_id );
				echo esc_html( $user->displayname . ', ' );
				echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->date, true ) );
			?>
		</li>
		<li class="date">
			<span class="dashicons dashicons-calendar-alt"></span>
			<?php echo esc_html( mysql2date( get_option( 'date_format' ), $history_time->option['due_date'], true ) ); ?>
		</li>
		<li class="time">
			<span class="dashicons dashicons-clock"></span>
			<?php echo esc_html( $history_time->option['estimated_time'] ); ?>
		</li>
		<li class="delete">
			<span class="delete-history-time dashicons dashicons-no-alt" data-nonce="<?php echo esc_attr( wp_create_nonce( 'delete_history_time' ) ); ?>"></span>
		</li>
	</ul>
</li>
