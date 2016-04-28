<?php if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div id="wpeo-point-info">
  <h3><?php _e( 'Point informations', 'task-manager' ); ?></h3>
  <ul>
    <li>
      <?php $date_output_format = get_option( 'date_format' ) . ' à ' . get_option( 'time_format' ); ?>
      <div><?php _e( 'Create', 'task-manager' ); ?> : <?php echo mysql2date( $date_output_format, $element->date, true ); ?></div>
    </li>
    <!-- Temps du point / Time of the point -->
    <li>
      <span class="dashicons dashicons-clock"></span>
      <div><?php _e( 'Elapsed time', 'task-manager' ); ?> : <strong class="wpeo-point-elapsed-time"><?php echo $element->option['time_info']['elapsed']; ?></strong></div>
    </li>

    <!--  Nombre de commentaires sur le point / Number of comments on the point -->
    <li>
      <span class="dashicons dashicons-admin-comments"></span>
      <?php _e( 'Number of comments on the point', 'task-manager' ); ?> : <strong class="wpeo-point-list-point-time"><?php echo count( $list_time ); ?></strong>
    </li>
  </ul>
  <!-- Réference -->
  <ul>
    <li>
      <?php _e( 'Ref', 'task-manager' ); ?> : #<?php echo $element->id; ?>
    </li>
  </ul>
</div>
