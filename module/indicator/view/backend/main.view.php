<?php
/**
 * La vue principale des indications.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="wrap wpeo-project-wrap page-indicator">
	<h2><?php esc_html_e( 'Indicator', 'task-manager' ); ?></h2>

	<form name="my_form" method="post">

		<input type="hidden" name="action" value="some-action">
		<?php wp_nonce_field( 'some-action-nonce' );

		/* Used to save closed meta boxes and their order */
		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false ); ?>

		<!-- Rest of admin page here -->

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				<div id="postbox-container-2" class="postbox-container">
					<?php
					do_meta_boxes( 'task-manager-indicator', 'normal', '' );
					?>
				</div>
				<!-- meta box containers here -->
		</div>
		</form>
		<!-- #post-body .metabox-holder goes here -->
	</div>

</div>
