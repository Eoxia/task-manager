<div>
	<a class="wpeo-button button-main button-radius-2 button-size-small wpeo-modal-event load_more_result"
	   data-action="load_modal_create_shortcut"
	   data-title="<?php esc_html_e( 'Create shortcut', 'task-manager' ); ?>"
	   data-term="<?php echo ! empty( $term ) ? esc_attr( $term ) : ''; ?>"
	   data-task-id="<?php echo ! empty( $task_id ) ? esc_attr( $task_id ) : ''; ?>"
	   data-point-id="<?php echo ! empty( $point_id ) ? esc_attr( $point_id ) : ''; ?>"
	   data-user-id="<?php echo ! empty( $data['user_id'] ) ? esc_attr( $data['user_id'] ) : ''; ?>"
	   data-categories-id="<?php echo ! empty( $data['categories_id'] ) ? esc_attr( $data['categories_id'] ) : ''; ?>"
	   data-post-parent="<?php echo ! empty( $data['post_parent_id'] ) ? esc_attr( $data['post_parent_id'] ) : ''; ?>"
	   data-target="wpeo-modal"><i class="button-icon fas fa-plus"></i>
	</a>
</div>
