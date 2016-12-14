<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul class="wpeo-user-display-name">
  <?php echo apply_filters( 'task_avatar', '', $element->option['user_info']['owner_id'], 30, true ); ?>
</ul>
<span class="wpeo-task-owner-role"><?php _e( 'Owner of the task', 'task-manager' ); ?></span>

<ul id="wpeo-task-action">
  <li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_archive_task_' . $element->id ); ?>" class="wpeo-task-action-list wpeo-send-task-to-archive dashicons dashicons-archive"></span></li>
  <!--  <li><a href="<?php echo get_post_permalink( $element->id ); ?>" title="<?php _e( 'View the task', 'task-manager' ); ?>" target="_blank" class="wpeo-task-action-list"><span class="dashicons dashicons-visibility"></span></a></li> -->
  <li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_export_task_' . $element->id ); ?>" class="wpeo-task-action-list wpeo-export dashicons dashicons-download"></span></li>
  <!-- <li><span style="color: red;" data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_export_task_' . $element->id ); ?>" class="wpeo-task-action-list wpeo-export-comment dashicons dashicons-download"></span></li> -->
  <li><span data-nonce="<?php echo wp_create_nonce( 'wpeo_nonce_delete_task_' . $element->id ); ?>" class="wpeo-task-action-list wpeo-send-task-to-trash dashicons dashicons-trash" title="<?php _e( 'Move to trash', 'task-manager'); ?>" ></span></li>
</ul>
