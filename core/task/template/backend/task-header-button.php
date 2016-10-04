<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_load_dashboard_task_' . $task->id ); ?>" class="wpeo-task-open-dashboard"  title="<?php _e( 'More informations', 'task-manager'); ?>" ><i class="dashicons dashicons-admin-generic"></i></span>
<span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_reload_task_' . $task->id ); ?>" class="wpeo-reload-task"  title="<?php _e( 'Reload task', 'task-manager'); ?>" ><i class="dashicons dashicons-image-rotate"></i></span>
