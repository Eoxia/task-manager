<?php if( $time_elapsed > 0 || $time_estimated > 0  ) : ?>
  <td class="wpeo-tooltip-event"
    data-title="<?php echo esc_html( $time_elapsed ? $time_elapsed_readable : '0' ); ?><?php echo esc_html( $time_estimated ? '/' . $time_estimated_readable : '' ); ?>"
    aria-label="<?php echo esc_html( $time_elapsed ? $time_elapsed_readable : '0' ); ?><?php echo esc_html( $time_estimated ? '/' . $time_estimated_readable : '' ); ?>">

    <?php if( $time_elapsed > 0 && $time_estimated == 0 ): ?>
      <p class="tag-time" style="color : orange">
        <?php echo esc_html( $time_elapsed . esc_html( 'm (out)', 'task-manager' ) ); ?>
      </p>
    <?php else: ?>
      <p class="tag-time <?php echo esc_html( $time_percent > 100 ? 'time-excedeed' : '' ); ?>">
        <?php echo esc_html( $time_elapsed . ' /' . $time_estimated ); ?>
        <?php echo esc_html( $time_percent > 0 ? '(' . $time_percent . '%)' : '' ); ?>
      </p>
    <?php endif; ?>
	</td>
<?php else: ?>
  <td data-title="TimeElapsed">
    <p class="tag-time"><?= '-' ?></p>
  </td>
<?php endif; ?>
