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

<span class="wrap">
	<span	id="tm_audit_header_start_audit"
		class="action-attribute page-title-action tm_audit_header_start_audit"
		data-action="start_new_audit"
		data-parent-id="<?php echo esc_attr( isset( $post_id ) ? $post_id : 0 ); ?>"
		data-page="<?php echo esc_attr( isset( $post_id ) ? 'audit-createnew' : 'audit-page/metabox-audit-edit' ); ?>"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'start_new_audit' ) ); ?>">
		<?php echo esc_html( sprintf( __( 'Start audit', 'task-manager' ) ) ); ?>

	</span>
</span>
