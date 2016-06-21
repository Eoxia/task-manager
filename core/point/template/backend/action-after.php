<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span data-nonce="<?php echo wp_create_nonce( 'ajax_delete_point_' . $point->id ); ?>" class="wpeo-send-point-to-trash"  title="<?php _e( 'Move to trash', 'wpeopoint-i18n'); ?>" ><i class="dashicons dashicons-no-alt"></i></span>
