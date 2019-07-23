<td class="wpeo-tooltip-event"
<?php if( $time_deadline > 0 && $time_estimated > 0 ) : ?>
  data-title="<?php echo esc_html( $time_deadline_readable . ' / ' . $time_estimated_readable )?>"
  aria-label="<?php echo esc_html( $time_deadline_readable . ' / ' . $time_estimated_readable )?>"
<?php else: ?>
  data-title="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
  aria-label="<?php esc_html_e( 'TimeElapsed /TimeEstimated', 'task-manager' ) ?>"
<?php endif; ?>>

<p class="tag-time <?php echo esc_html( $task_percent > 100 ? 'time-excedeed' : '' ); ?>">
  <?php echo esc_html( $task_time_deadline . ' /' . $task_time_estimated ) ?>
  <?php echo esc_html( $task_percent > 0 ? '(' . $task_percent . '%)' : '' ); ?>
</p>

  <?php else: ?>
    <td data-title="TimeElapsed">
      <p class="tag-time"><?= '-' ?></p>
  <?php endif; ?>

</td>
<?php else: ?>
<?php if( $task_time_elapsed ):?>
  <td class="wpeo-tooltip-event"
    data-title="<?php echo esc_html_e( 'Time out of deadline', 'task-manager' ) ?>"
    aria-label="<?php echo esc_html_e( 'Time out of deadline', 'task-manager' ) ?>" style="color : orange">
    <p class="tag-time">
      <?php echo esc_html( $task_time_deadline ); ?><?php echo esc_html_e( 'm (out)', 'task-manager' ) ?>
    </p>
  </td>
<?php else: ?>
  <td data-title="TimeElapsed">
    <p class="tag-time"><?= '-' ?></p>
  </td>
