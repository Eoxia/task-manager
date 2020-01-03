<?php
/**
 * La vue principale des commentaires dans le backend.
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
   data-action="edit_comment"
   data-post-id="<?php echo esc_attr( $task_id ); ?>"
   data-parent-id="<?php echo esc_attr( $point_id ); ?>"
   data-content="Nouveau commentaire"
   data-nonce="<?php echo wp_create_nonce( 'edit_comment' ); ?>">
	<span>Le vrai bouton "Nouveau commentaire"</span></a>

<div class="wpeo-table table-flex table-comments">
	<div class="table-row table-header">
		<div class="table-cell">Commentaire</div>
		<div class="table-cell">Auteur</div>
		<div class="table-cell">Date création</div>
		<div class="table-cell">Temps</div>
		<div class="table-cell"></div>
	</div>

	<?php
	if ( ! empty( $comments ) ) :
		foreach ( $comments as $comment ) :
			\eoxia\View_Util::exec( 'task-manager', 'comment', 'backend/comment', array(
				'comment'  => $comment,
				'task_id'  => $task_id,
				'point_id' => $point_id,
			) );
		endforeach;
	else :
		?>

		<?php
	endif;
	?>

