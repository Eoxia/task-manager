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

		add_filter( 'tm_audit_list_customers', array( $this, 'callback_tm_audit_list_customers' ), 10, 1 );
	}

	public function hidden_dashboard_audit_task( $param ) {
		if ( is_admin() ) {
			$screen = get_current_screen();

			if( isset( $screen->id ) ){
				if ( 'toplevel_page_wpeomtm-dashboard' == $screen->id ) {
					$param['not_parent_type'] = array( 'wpeo-audit' );
				}
			}
		}

		return $param;
	}

	public function callback_tm_audit_list_customers(){

		$query = new \WP_Query(
			array(
				'post_type'   => 'wpshop_customers'
			)
		);

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'audit',
			'audit-page/metabox-list-customers',
			array(
				'data' => $query->posts
			)
		);

		$view = ob_get_clean();

		return $view;
	}
}

new Audit_Filter();
