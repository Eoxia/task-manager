<?php
/**
 * Initialise les fichiers .config.json
 *
 * @package Evarisk\Plugin
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialise les scripts JS et CSS du Plugin
 * Ainsi que le fichier MO
 */
class Task_Manager_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 */
	public function __construct() {
		// Initialises ses actions que si nous sommes sur une des pages réglés dans le fichier task-manager.config.json dans la clé "insert_scripts_pages".
		$page = ( ! empty( $_REQUEST['page'] ) ) ? sanitize_text_field( $_REQUEST['page'] ) : '';

		if ( in_array( $page, config_util::$init['task-manager']->insert_scripts_pages, true ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ), 11 );
			add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts' ) );
		}

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 */
	public function callback_before_admin_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_media();
		add_thickbox();
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 */
	public function callback_admin_enqueue_scripts() {
		wp_register_style( 'task-manager-style', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/style.min.css', array(), config_util::$init['task-manager']->version );
		wp_enqueue_style( 'task-manager-style' );

		wp_enqueue_script( 'task-manager-script', PLUGIN_TASK_MANAGER_URL . 'core/asset/js/backend.min.js', array(), config_util::$init['task-manager']->version, false );
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 */
	public function callback_admin_print_scripts() {
		require( PLUGIN_TASK_MANAGER_PATH . '/core/asset/js/language.js.php' );
	}

	/**
	 * Initialise le fichier MO
	 */
	public function callback_plugins_loaded() {
		load_plugin_textdomain( 'task-manager', false, PLUGIN_DIGIRISK_DIR . '/core/asset/language/' );
	}

	/**
	 * Définition du menu dans l'administration de wordpress pour Digirisk / Define the menu for wordpress administration
	 */
	public function callback_admin_menu() {
		add_menu_page( __( 'Task management dashboard', 'task-manager' ), __( 'Tasks manager', 'task-manager' ), 'publish_pages', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ), 'dashicons-layout' );
	}

}

new Task_Manager_Action();
