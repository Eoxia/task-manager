<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Ajouter un point / Add a point -->
<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
  <?php wp_nonce_field( 'wpeo_nonce_create_point_' . $object_id ); ?>
  <?php echo apply_filters( 'create_point_additional_option', '' ); ?>
  <input type="hidden" name="point[post_id]" value="<?php echo $object_id; ?>" />
  <input type="hidden" name="action" value="<?php echo apply_filters( 'action_create_point', 'create_point' ); ?>" />
  <ul class="wpeo-task-point">
    <li class="wpeo-add-point wpeo-point-no-sortable">
      <ul>
        <li></li>
        <li class="wpeo-point-input">
		  <input type="hidden" name="point[content]" value="">
		  <div class="wpeo-point-new-contenteditable" contenteditable="true"></div>
		  <span class="wpeo-point-new-placeholder"><?php _e( 'Write your point here...', 'task-manager' ); ?></span>
        </li>
        <li>
          <div class="wpeo-point-new-btn" title="<?php _e( 'Add this point', 'task-manager' ); ?>">
            <i class="dashicons dashicons-plus-alt"></i>
          </div>
        </li>
      </ul>
    </li>
  </ul>
</form>
