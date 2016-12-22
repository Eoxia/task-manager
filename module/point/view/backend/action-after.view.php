<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span data-action="delete_point" data-point[id]="<?php echo $point->id; ?>" data-_wpnonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_point_' . $point->id ); ?>" class="wpeo-send-point-to-trash"  title="<?php _e( 'Move to trash', 'task-manager'); ?>" ><i class="dashicons dashicons-no-alt"></i></span>
