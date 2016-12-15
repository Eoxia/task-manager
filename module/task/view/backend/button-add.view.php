<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<a href="#"
	class="action-attribute add-new-h2"
	data-action="create_task"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php esc_html_e( 'New task', 'task-manager' ); ?></a>
