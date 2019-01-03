<?php
/**
 * La vue principale de la page des clients WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package core
 * @subpackage view
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<a href="#"
class="action-attribute page-title-action"
data-action="create_task"
data-parent-id="<?php echo esc_attr( $parent_id ); ?>"
data-nonce="<?php echo esc_attr( wp_create_nonce( 'create_task' ) ); ?>"><?php echo esc_html( sprintf( __( 'New task', 'task-manager' ) ) ); ?></a>

