<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<span data-nonce="<?php echo wp_create_nonce( 'ajax_load_dashboard_' . $task->id ); ?>" class="wpeo-task-open-dashboard"  title="<?php _e( 'More informations', 'task-manager'); ?>" ><i class="dashicons dashicons-admin-generic"></i></span>
