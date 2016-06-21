<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<a href="<?php echo esc_attr( $url ); ?>" data-nonce="<?php echo wp_create_nonce( 'ajax_create_task' ); ?>" class="wpeo-project-new-task add-new-h2"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>
