<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<span>
	<span class="action-attribute button tm_audit_header_start_audit"
	id="tm_client_indicator_header_minus"
	data-action="start_new_audit"
	data-parent-id="<?php echo esc_attr( $post_id ); ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'start_new_audit' ) ); ?>">
		<?php echo esc_html( sprintf( __( 'Start audit', 'task-manager' ) ) ); ?>
	</span>

</span>
