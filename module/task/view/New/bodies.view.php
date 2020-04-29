<?php
/**
 * Gère l'affichage d'une ligne dans le tableau.
 * Gères les trois types de ligne:
 * -Projet
 * -Task
 * -Comment
 *
 * @package   Task_Manager
 * @author    Jimmy Latour <jimmy@evarisk.com> && Nicolas Domenech <nicolas@eoxia.com>
 * @copyright 2020 Eoxia
 * @since     3.0.1
 */

namespace task_manager;

if ( ! empty( $contents['bodies'] ) ) :
	// Pour chaque ligne
	foreach ( $contents['bodies'] as $key => $data_def ) :
		$class = '';

		if ( isset( $_GET['notification'] ) ) :
			if ( isset( $_GET['comment_id'] ) && $data_def['type'] == 'comment' && $data_def['element_id'] == $_GET['comment_id'] ) :
				$class = 'row-focus';
			endif;

			if ( isset( $_GET['point_id'] ) && ! isset( $_GET['comment_id'] ) && $data_def['type'] == 'point' && $data_def['element_id'] == $_GET['point_id'] ) :
				$class = 'row-focus';
			endif;

			if ( isset( $_GET['task_id'] ) && ! isset( $_GET['point_id'] ) && $data_def['type'] == 'task' && $data_def['element_id'] == $_GET['task_id'] ) :
				$class = 'row-focus';
			endif;
		endif;

		?>
		<div class="table-row <?php echo esc_attr( $data_def['classes'] ) . ' ' . $class; ?>"
			<?php echo ! empty( $data_def['attrs'] ) ? implode( ' ', $data_def['attrs'] ) : ''; ?>>
			<?php
			// Pour chaque colonne.
			foreach ( $data_def['values'] as $order => $data ) : ?>
				<div data-key="<?php echo esc_attr( $data['key'] ); ?>"  class="table-cell <?php echo esc_attr( $data['classes'] ); ?>"
					style="<?php echo Follower_Class::g()->user_columns_def[ $data['key'] ]['displayed'] ? '' : 'display: none;'; ?>"
					<?php echo ! empty( $data['attrs'] ) ? implode( ' ', $data['attrs'] ) : ''; ?>>
					<?php
					\eoxia\View_Util::exec( 'task-manager', 'task', 'New/render/' . $data['type'] . '-' . $data['key'], array(
						'data_def' => $data_def,
						'data'     => $data,
						'key'      => $data['key'],
					) );
					?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		if ( get_user_meta( get_current_user_id(), '_tm_project_state', true ) == true ) :
			if ( $data_def['type'] == 'task' ) :
				Point_Class::g()->display( $data_def['element_id'], false, 0, false );
				//Point_Class::g()->display( $data_def['element_id'], false, 0, true );
			endif;
		endif;

//		if ( $data_def['type'] == 'point' ) :
//////			Task_Comment_Class::g()->display( $data_def['project_id'], $data_def['element_id'], false );
//////		endif;
	endforeach;
else :
	if ( $parent != null ) :
		?>
		<?php
			if ( $parent->data['type'] == 'wpeo-task' ) {
				$action = 'edit_point';
				$text = __( 'Aucune tâche pour le moment. Ajoutez-en une', 'task-manager' );

				?>
				<div class="table-row row-empty table-type-task" data-post-id="<?php echo $parent->data['id']; ?>">
				<p><?php echo $text; ?></p>
				<div class="wpeo-button button-main button-square-30 button-rounded action-attribute"
					data-parent-id="<?php echo $parent->data['id']; ?>"
					data-action="<?php echo esc_attr( $action ); ?>"
					data-content="<?php esc_html_e( 'New Task', 'task-manager' ); ?>"
					data-nonce="<?php echo esc_attr( wp_create_nonce( $action ) ); ?>"
					data-toggle="true">
					<i class="button-icon fas fa-plus-circle second-icon"></i>
				</div>
				<?php
			} else {
				$action = 'edit_comment';
				$text = __( 'Aucun commentaire pour le moment. Ajoutez-en un', 'task-manager' );
				?>
				<div class="table-row row-empty table-type-comment" data-parent-id="<?php echo $parent->data['id']; ?>" data-post-id="<?php echo $parent->data['post_id']; ?>">
				<p><?php echo $text; ?></p>
				<div class="wpeo-button button-main button-square-30 button-rounded action-attribute"
				     data-post-id="<?php echo $parent->data['post_id']; ?>"
				     data-parent-id="<?php echo $parent->data['id']; ?>"
				     data-action="<?php echo esc_attr( $action ); ?>"
				     data-content="<?php esc_html_e( 'New Comment', 'task-manager' ); ?>"
				     data-nonce="<?php echo esc_attr( wp_create_nonce( $action ) ); ?>"
					data-toggle="true">
					<i class="button-icon fas fa-plus-circle second-icon"></i>
				</div>
				<?php
			}

		?>
		</div>
		<?php
	endif;
endif;
