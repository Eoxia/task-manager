<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_point_' . $point->id ); ?>" class="wpeo-send-point-to-trash"  title="<?php _e( 'Move to trash', 'task-manager'); ?>" ><i class="dashicons dashicons-no-alt"></i></span>
