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
	<span	class="action-attribute page-title-action action-attribute"
		data-action="create_audit_inpage"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_audit' ) ); ?>"
		data-id="<?php echo isset( $id ) && $id > 0 ? esc_attr( $id ) : 0 ?>">
		<?php echo esc_html( sprintf( __( 'Start audit', 'task-manager' ) ) ); ?>
	</span>
</span>
