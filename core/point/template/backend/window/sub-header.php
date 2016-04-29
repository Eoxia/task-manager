<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<ul>
  <?php echo apply_filters( 'task_avatar', '', $element->author_id, 26, true ); ?>
  <li class="wpeo-task-owner-role"><?php _e( 'Owner of the point', 'task-manager' ); ?></span>
</ul>

<ul id="wpeo-task-action">
  <li><input type="checkbox" disabled <?php echo !empty( $element->option['point_info']['completed'] ) ? 'checked="checked"': ''; ?> /></li>
  <li><?php
    if( !empty( $element->option['point_info']['completed'] ) ) :
      _e( 'Point completed', 'task-manager' );
    else:
      _e( 'Point uncompleted', 'task-manager' );
    endif;
  ?></li>
</ul>
