<?php
/**
 * Les actions relatives aux réglages de Task Manager.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
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
		add_action( 'wp_ajax_save_general_settings', array( $this, 'callback_save_general_settings' ) );
		add_action( 'wp_ajax_save_preferences', array( $this, 'callback_save_preferences' ) );

		add_action( 'display_setting_user_task_manager', array( $this, 'callback_display_setting_user_task_manager' ), 10, 2 );
		add_action( 'wp_ajax_paginate_setting_task_manager_page_user', array( $this, 'callback_paginate_setting_task_manager_page_user' ) );
	}

	/**
	 * La fonction de callback de l'action admin_menu de WordPress
	 *
	 * @since 1.5.0
	 */
	public function admin_menu() {
		add_options_page( 'Task Manager', 'Task Manager', 'manage_task_manager', 'task-manager-setting', array( $this, 'add_option_page' ) );
	}

	/**
	 * Appelle la vue main du module setting
	 *
	 * @since 1.5.0
	 */
	public function add_option_page() {
		$use_search_in_admin_bar = get_option( \eoxia\Config_Util::$init['task-manager']->setting->key_use_search_in_admin_bar, true );
		$advanced_settings       = get_option(
			\eoxia\Config_Util::$init['task-manager']->setting->key_advanced_settings,
			array(
				'advanced_display'  => false,
				'quick_point'       => false,
				'display_indicator' => false,
			)
		);

		\eoxia\View_Util::exec(
			'task-manager',
			'setting',
			'main',
			array(
				'use_search_in_admin_bar' => $use_search_in_admin_bar,
				'advanced_settings'       => $advanced_settings,
			)
		);
	}

	/**
	 * Rajoutes la capacité "manage_task_manager" à tous les utilisateurs ou $have_capability est à true.
	 *
	 * @since 1.5.0
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

		wp_send_json_success(
			array(
				'namespace'        => 'taskManager',
				'module'           => 'setting',
				'callback_success' => 'savedCapability',
			)
		);
	}

	/**
	 * Save general settings
	 *
	 * @since 1.6.0
	 */
	public function callback_save_general_settings() {
		check_ajax_referer( 'save_general_settings' );

		$display_search_bar = ( ! empty( $_POST['display_search_bar'] ) && 'true' === $_POST['display_search_bar'] ) ? true : false;

		update_option( \eoxia\Config_Util::$init['task-manager']->setting->key_use_search_in_admin_bar, $display_search_bar );

		wp_send_json_success();
	}

	/**
	 * Save general settings
	 *
	 * @since 1.6.0
	 */
	public function callback_save_preferences() {
		check_ajax_referer( 'save_preferences' );

		$advanced_settings = ! empty( $_POST['advanced_settings'] ) ? (array) $_POST['advanced_settings'] : array();

		if ( ! empty( $advanced_settings ) ) {
			foreach ( $advanced_settings as &$advanced_setting ) {
				$advanced_setting = ( ! empty( $advanced_setting ) && 'true' === $advanced_setting ) ? true : false;
			}
		}

		update_user_meta( \eoxia\Config_Util::$init['task-manager']->setting->key_advanced_settings, $advanced_settings );

		wp_send_json_success();
	}

	/**
	 * Méthode appelé par le champs de recherche dans la page "task-manager"
	 *
	 * @param  integer $id           L'ID de la société.
	 * @param  array   $list_user_id Le tableau des ID des évaluateurs trouvés par la recherche.
	 *
	 * @since 1.5.0
	 */
	public function callback_display_setting_user_task_manager( $id, $list_user_id ) {
		ob_start();
		Setting_Class::g()->display_user_list_capacity( $list_user_id );

		wp_send_json_success(
			array(
				'template' => ob_get_clean(),
			)
		);
	}

	/**
	 * Gestion de la pagination
	 *
	 * @since 1.5.0
	 */
	public function callback_paginate_setting_task_manager_page_user() {
		$current_page = isset( $_POST[ 'next_page' ] ) && $_POST[ 'next_page' ] != "" ? (int) $_POST[ 'next_page' ] : 1;
		Setting_Class::g()->display_user_list_capacity( array(), $current_page );
		wp_die();
	}
}

new Setting_Action();
