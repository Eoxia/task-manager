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

	<div class="column-task">
		<?php
		if ( ! empty( $points ) ) :
			foreach ( $points as $point ) :
				?>
				<div class="table-row">
					<div class="table-cell">
						<i class="fas fa-angle-right"></i>
					</div>

					<div class="table-cell">
						<input type="checkbox" />
					</div>

					<div class="table-cell">
						<span><?php echo esc_html( $point->data['content'] ); ?></span>
					</div>

					<div class="table-cell">
						<span>2</span>
					</div>

					<div class="table-cell">
						<span><?php echo esc_html( $point->data['id'] ); ?></span>
					</div>

					<div class="table-cell">
						<span>30</span>
					</div>

					<div class="table-cell">
						<span>20/11/2019 10h15</span>
					</div>

					<div class="table-cell">
						<span>-</span>
					</div>

					<div class="table-cell">
						<span>-</span>
					</div>

					<div class="table-cell">
						<span>-</span>
					</div>

					<div class="table-cell">
						<span>-</span>
					</div>

					<div class="table-cell">
						<span><i class="fas fa-ellipsis-v"></i></span>
					</div>
				</div>
				<div class="column-extend hidden">
					Lalala
				</div>
				<?php
			endforeach;
		else:
		endif;
		?>
	</div>
</div>
