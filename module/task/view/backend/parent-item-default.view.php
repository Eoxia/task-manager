<ul class="wpeo-ul-parent">
  <li class="wpeo-task-parent">
    <span class="wpeo-task-link">
      <i class="fas fa-link"></i>
    </span>
    #<?php echo esc_html( $task->data['parent_id'] ) ?> -
    <a class="wpeo-tooltip-event"
    style="font-size: 18px"
    aria-label="<?php echo esc_attr( $task->data['parent']->displayed_post_title ); ?>"
    target="_blank" href="<?php echo admin_url( 'post.php?post=' . $task->data['parent_id'] . '&action=edit' ); ?>">
      <?php echo esc_html( $task->data['parent']->displayed_post_title ); ?>
    </a>
    <i>
      <?php $name_posttype = get_post_type_object( $task->data['parent']->post_type ); ?>
      <?php
      if( $name_posttype != "" ):
        echo esc_html( $name_posttype->label );
      else:
        echo esc_html( $task->data['parent']->post_type );
      endif; ?>
    </i>
  </li>
  <li style="float: right; margin-top: -28px; cursor : pointer">
    <span class="wpeo-task-link tm-task-delink-parent" data-id="<?php echo esc_html( $task->data[ 'id' ] ); ?>">
      <i class="fas fa-unlink"></i>
    </span>
  </li>
</ul>
