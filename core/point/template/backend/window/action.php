<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="wpeo-point-option" class="wpeo-display-option">
  <ul class="wpeo-send-element">
    <li>
      <i class="dashicons dashicons-migrate"></i><?php _e( 'Send the point to task <strong>#</strong>', 'task-manager' ); ?>
    </li>
    <li>
      <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="POST">
         <!-- For admin-ajax -->
        <?php wp_nonce_field( 'wpeo_nonce_send_to_task_' . $element->id ); ?>
        <div>
          <input type="text" placeholder="162" class="wpeo-task-auto-complete" data-type="wpeo-task" />
          <input type="hidden" name="element_id" value="" />
        </div>
        <input type="hidden" name="current_task_id" value="<?php echo $element->post_id; ?>" />
        <input type="hidden" name="point_id" value="<?php echo $element->id; ?>" />
        <input type="button" class="wpeo-send-point-to-task" value="<?php _e( 'Send', 'task-manager' ); ?>" />
      </form>
    </li>
  </ul>
</div>
