<?php

namespace task_manager;

if ( ! empty( $contents['bodies'] ) ) :
	foreach ( $contents['bodies'] as $key => $data_def ) :
		?>
		<div class="table-row <?php echo esc_attr( $data_def['classes'] ); ?>"
			<?php echo ! empty( $data_def['attrs'] ) ? implode( ' ', $data_def['attrs'] ) : ''; ?>>

			<?php
			foreach ( $data_def['values'] as $order => $data ) :
				?>
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
			<?php
			endforeach;
			?>
		</div>
	<?php

	endforeach;
else :
	if ( $parent != null ) :
		?>
		<?php
			if ( $parent->data['type'] == 'wpeo-task' ) {
				$action = 'edit_point';
				$text = __( 'No tasks yet. Add a new one', 'task-manager' );

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
				$text = __( 'No tasks yet. Add a new one', 'task-manager' );
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
