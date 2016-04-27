<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<span class="dashicons dashicons-screenoptions" title="<?php _e( 'Drag and drop for set the order', 'task-manager' ); ?>"></span>

<!-- Checkbox pour compléter le point -->
<span>
  <input type="hidden" name="point[option][point_info][completed]" value="0" />
  <input tabindex="-1" type="checkbox" value="1" name="point[option][point_info][completed]" class="wpeo-done-point" <?php echo ( !empty( $point->option['point_info']['completed'] ) ) ? 'checked="checked"' : ''; ?> />
</span>
