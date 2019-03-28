<?php
/**
 * Les filtres relatives aux audits.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux audits.
 */
class Audit_Filter {

	/**
	 * Constructeur
	 *
	 * @since 1.9.0
	 * @version 1.9.0
	 */
	public function __construct() {
		add_filter( 'task_manager_get_tasks_args', array( $this, 'hidden_dashboard_audit_task' ), 10, 1 );
	}

	public function hidden_dashboard_audit_task( $param ) {
		$screen = get_current_screen();

		if ( 'toplevel_page_wpeomtm-dashboard' == $screen->id ) {
			$param['not_parent_type'] = array( 'wpeo-audit' );
		}

		return $param;
	}

}

new Audit_Filter();
