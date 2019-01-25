<?php
/**
 * Affiches l'historique des 'dates estimées'.
 * Affiches le formulaire pour ajouter une date estimé.
 *
 * @author Jimmy Latour <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="history-time-container">
	<?php
	\eoxia\View_Util::exec(
		'task-manager',
		'history-time',
		'backend/form',
		array(
			'history_time_schema' => $history_time_schema,
			'task_id'             => $task_id,
		)
	);
	?>

	<h2><?php esc_html_e( 'Event historic', 'task-manager' ); ?></h2>

	<ul class="history-time-list">
		<?php
		if ( ! empty( $history_times ) ) :
			foreach ( $history_times as $history_time ) :
				if ( ! empty( $history_time->data['id'] ) ) :
					\eoxia\View_Util::exec(
						'task-manager',
						'history-time',
						'backend/history-time',
						array(
							'history_time' => $history_time,
						)
					);
				endif;
			endforeach;
		else :
			?>
			<li><?php esc_html_e( 'No history time for now', 'task-manager' ); ?></li>
			<?php
		endif;
		?>
	</ul>
</div>
