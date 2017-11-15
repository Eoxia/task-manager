<?php
/**
 * Les actions relatives aux réglages de Task Manager.
 *
 * @author Jimmy Latour <jimmy@evarisk.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Evarisk
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives aux réglages de Task Manager.
 */
class Setting_Action {

	/**
	 * Le constructeur
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_ajax_save_capability_task_manager', array( $this, 'callback_save_capability_task_manager' ) );

		add_action( 'display_setting_user_task_manager', array( $this, 'callback_display_setting_user_task_manager' ), 10, 2 );
		add_action( 'wp_ajax_paginate_setting_task_manager_page_user', array( $this, 'callback_paginate_setting_task_manager_page_user' ) );
	}

	/**
	 * La fonction de callback de l'action admin_menu de WordPress
	 *
	 * @return void
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function admin_menu() {
		add_options_page( 'Task Manager', 'Task Manager', 'manage_task_manager', 'task-manager-setting', array( $this, 'add_option_page' ) );
	}

	/**
	 * Appelle la vue main du module setting
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function add_option_page() {
		\eoxia\View_Util::exec( 'task-manager', 'setting', 'main' );
	}

	/**
	 * Rajoutes la capacité "manage_task_manager" à tous les utilisateurs ou $have_capability est à true.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_save_capability_task_manager() {
		check_ajax_referer( 'save_capability_task_manager' );

		if ( ! empty( $_POST['users'] ) ) {
			foreach ( $_POST['users'] as $user_id => $data ) {
				$user = new \WP_User( $user_id );

				if ( 'true' == $data['capability'] ) {
					$user->add_cap( 'manage_task_manager' );
				} else {
					$user->remove_cap( 'manage_task_manager' );
				}
			}
		}

		wp_send_json_success( array(
			'namespace' => 'taskManager',
			'module' => 'setting',
			'callback_success' => 'savedCapability',
		) );
	}

	/**
	 * Méthode appelé par le champs de recherche dans la page "task-manager"
	 *
	 * @param  integer $id           L'ID de la société.
	 * @param  array   $list_user_id Le tableau des ID des évaluateurs trouvés par la recherche.
	 * @return void
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	public function callback_display_setting_user_task_manager( $id, $list_user_id ) {
		ob_start();
		Setting_Class::g()->display_user_list_capacity( $list_user_id );

		wp_send_json_success( array(
			'template' => ob_get_clean(),
		) );
	}

	/**
	 * Gestion de la pagination
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function callback_paginate_setting_task_manager_page_user() {
		Setting_Class::g()->display_user_list_capacity();
		wp_die();
	}
}

new Setting_Action();
