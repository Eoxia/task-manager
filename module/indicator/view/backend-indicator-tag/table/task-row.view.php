<?php if( $time_elapsed > 0 || $time_estimated > 0 ): ?>
  <td class="wpeo-tooltip-event"
    data-title="<?php echo esc_html( $time_elapsed ? $time_elapsed_readable : '0' ); ?><?php echo esc_html( $time_estimated ? '/' . $time_estimated_readable : '' ); ?>"
    aria-label="<?php echo esc_html( $time_elapsed ? $time_elapsed_readable : '0' ); ?><?php echo esc_html( $time_estimated ? '/' . $time_estimated_readable : '' ); ?>">

    <?php if( $task_time_elapsed > 0 && $task_time_estimated == 0 ): ?>
      <p class="tag-time" style="color : #FF8C00">
        <?php echo esc_html( $task_time_elapsed . esc_html( 'm (out)', 'task-manager' ) ); ?>
      </p>
    <?php elseif( $task_time_elapsed == 0 && $task_time_estimated == 0 ): ?>
      <p class="tag-time">-</p>
    <?php else: ?>
      <p class="tag-time <?php echo esc_html( $task_percent > 100 ? 'time-excedeed' : '' ); ?>">
        <?php echo esc_html( $task_time_elapsed . ' /' . $task_time_estimated ) ?>
        <?php echo esc_html( $task_percent > 0 ? '(' . $task_percent . '%)' : '' ); ?>
      </p>
    <?php endif; ?>
  </td>
<?php else: ?>
  <td data-title="TimeElapsed">
    <p class="tag-time"><?= '-' ?></p>
  </td>
<?php endif; ?>
