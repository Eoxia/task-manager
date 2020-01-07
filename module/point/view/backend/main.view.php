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

<a href="#"
   class="action-attribute wpeo-button button-primary button-radius-2"
   data-action="edit_point"
   data-content="Chocolat"
   data-parent_id="<?php echo esc_attr( $task_id ); ?>"
   data-nonce="<?php echo wp_create_nonce( 'edit_point' ); ?>">
	<span>Le vrai bouton "Nouvelle tâche"</span></a>

<div class="wpeo-table table-flex table-task">
	<div class="table-row table-header">
		<div class="table-cell table-25"></div>
		<div class="table-cell table-50"><i class="fas fa-check-circle"></i> Etat</div>
		<div class="table-cell table-300"><i class="fas fa-check-square"></i> Nom de la tâche</div>
		<div class="table-cell table-50"><i class="far fa-comment-dots"></i> Com</div>
		<div class="table-cell table-50"><i class="fas fa-hashtag"></i> ID</div>
		<div class="table-cell table-75"><i class="far fa-clock"></i> Temps</div>
		<div class="table-cell table-150"><i class="far fa-calendar-alt"></i> Date création</div>
		<div class="table-cell table-100"><i class="far fa-calendar-alt"></i> Date prévue</div>
		<div class="table-cell table-100">En attente de</div>
		<div class="table-cell table-100"><i class="fas fa-user"></i> Auteur tâche</div>
		<div class="table-cell table-150"><i class="fas fa-users"></i> Utilisateurs associée</div>
		<div class="table-cell table-50"></div>
	</div>

	<?php
	if ( ! empty( $points ) ) :
		foreach ( $points as $point ) :
			\eoxia\View_Util::exec( 'task-manager', 'point', 'backend/point', array(
				'point' => $point
			) );
		endforeach;
	else:
	endif;
	?>
</div>
