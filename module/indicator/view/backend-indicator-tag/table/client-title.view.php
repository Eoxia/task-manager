<td class="wpeo-tooltip-event"
data-title="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $time_elapsed_readable . ' /' . $time_estimated_readable ?>"
aria-label="<?php echo esc_html__( 'Total : ', 'task-manager' ) . $time_elapsed_readable . ' /' . $time_estimated_readable ?>">

  <p class="tag-title">
    <i class="fas fa-caret-down" style="display : none"></i>
    <i class="fas fa-caret-right"></i>
    <?php if( $key_indicator != 0 && $key_indicator > 0 ): ?>
      <a style="color: inherit; text-decoration: none;"	target="_blank" href="<?php echo admin_url( 'post.php?post=' . $key_indicator . '&action=edit' ); ?>">
      <strong><?php echo esc_html( $name ) ?></strong>
      (#<?php echo esc_html( $key_indicator ); ?>)</a>
    <?php else: ?>
      <?php echo esc_html( $name ) ?>
    <?php endif; ?>
  </p>
  <p class="tag-time <?php echo esc_html( $time_percent > 100 ? 'time-excedeed' : '' ); ?>">
    <?php echo esc_html( $time_elapsed . '/' . $time_estimated . ' (' . $time_percent . '%)' ); ?></p>
</td>
