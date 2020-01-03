<?php
/**
 * La vue principale des points dans le backend.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wpeo-table table-flex table-task">
	<div class="table-row table-header">
		<div class="table-cell"></div>
		<div class="table-cell">Etat</div>
		<div class="table-cell">Nom de la tâche</div>
		<div class="table-cell">Com</div>
		<div class="table-cell"># ID</div>
		<div class="table-cell">Temps</div>
		<div class="table-cell">Date création</div>
		<div class="table-cell">Date prévue</div>
		<div class="table-cell">En attente de</div>
		<div class="table-cell">Auteur tâche</div>
		<div class="table-cell">Utilisateurs associée</div>
		<div class="table-cell"></div>
	</div>

	<?php
	if ( ! empty( $points ) ) :
		foreach ( $points as $point ) :
			?>
			<div class="task-column <?php echo $point->data['completed'] ? 'task-completed' : ''; ?>"
				data-parent-id="<?php echo esc_attr( $point->data['post_id'] ); ?>"
				data-id="<?php echo esc_attr( $point->data['id'] ); ?>"
				data-nonce="<?php echo wp_create_nonce( 'edit_point' ); ?>">

				<div class="table-row">
					<div class="table-cell">
						<div class="table-cell-container">
							<i class="task-toggle-comment fas fa-angle-right"></i>
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							<input class="task-complete-point" type="checkbox" />
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container task-title" contenteditable="true">
							<?php echo esc_html( $point->data['content'] ); ?>
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							2
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							<?php echo esc_html( $point->data['id'] ); ?>
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							30
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							20/11/2019 10h15
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							-
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							-
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							-
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							-
						</div>
					</div>

					<div class="table-cell">
						<div class="table-cell-container">
							<i class="fas fa-ellipsis-v"></i>
						</div>
					</div>
				</div>
				<div class="column-extend hidden">
					Lalala
				</div>
			</div>
			<?php
		endforeach;
	else:
	endif;
	?>
</div>
