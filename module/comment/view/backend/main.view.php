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

<div class="wpeo-table table-flex table-task">
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
			?>
			<div class="table-row" data-id="<?php echo $comment->data['id']; ?>"
			     data-post-id="<?php echo esc_attr( $task_id ); ?>"
			     data-parent-id="<?php echo esc_attr( $point_id ); ?>"
			     data-nonce="<?php echo wp_create_nonce( 'edit_comment' ); ?>">
				<div class="table-cell">
					<div class="table-cell-container comment-title" contenteditable="true"><?php echo $comment->data['content']; ?></div>
				</div>

				<div class="table-cell">-</div>
				<div class="table-cell">26/11/2019 10h12</div>
				<div class="table-cell">30</div>
				<div class="table-cell"><span><i class="fas fa-ellipsis-v"></i></span></div>
			</div>
			<?php
		endforeach;
	else :
		?>

		<?php
	endif;
	?>

