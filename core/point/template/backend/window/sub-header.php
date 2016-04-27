<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<?php echo apply_filters( 'window_point_owner', '' ); ?>

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
