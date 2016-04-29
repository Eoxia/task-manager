<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<!-- Ajouter un point / Add a point -->
<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST">
  <?php wp_nonce_field( 'wpeo_nonce_create_point_' . $object_id ); ?>
  <input type="hidden" name="point[post_id]" value="<?php echo $object_id; ?>" />
  <ul class="wpeo-task-point">
    <li class="wpeo-add-point wpeo-point-no-sortable">
      <ul>
        <li></li>
        <li class="wpeo-point-input">
          <textarea name="point[content]" placeholder="<?php _e( 'Write your point here...', 'wpeopoint-i18n' ); ?>"></textarea>
        </li>
        <li>
          <div class="wpeo-task-add-new-point" title="<?php _e( 'Add this point', 'wpeopoint-i18n' ); ?>">
            <i class="dashicons dashicons-plus-alt"></i>
          </div>
        </li>
      </ul>
    </li>
  </ul>
</form>