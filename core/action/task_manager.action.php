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
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_before_admin_enqueue_scripts' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_enqueue_scripts' ), 11 );
		add_action( 'admin_print_scripts', array( $this, 'callback_admin_print_scripts' ) );
		add_action( 'wp_print_scripts', array( $this, 'callback_wp_print_scripts' ) );

		// add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );
	}

	/**
	 * Initialise les fichiers JS inclus dans WordPress (jQuery, wp.media et thickbox)
	 *
	 * @return void nothing
	 */
	public function callback_before_admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( in_array( $screen->id, config_util::$init['task-manager']->insert_scripts_pages, true ) ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-form' );
			wp_enqueue_script( 'jquery-ui-datepicker' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'jquery-ui-accordion' );
			wp_enqueue_script( 'jquery-ui-autocomplete' );
			wp_enqueue_media();
			add_thickbox();
		}
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin DigiRisk.
	 *
	 * @return void nothing
	 */
	public function callback_admin_enqueue_scripts() {
		$screen = get_current_screen();
		if ( in_array( $screen->id, config_util::$init['task-manager']->insert_scripts_pages, true ) ) {
			wp_register_style( 'task-manager-style', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/style.min.css', array(), config_util::$init['task-manager']->version );
			wp_enqueue_style( 'task-manager-style' );

			wp_enqueue_style( 'task-manager-datepicker', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/datepicker.min.css', array(), Config_Util::$init['task-manager']->version );
			wp_enqueue_style( 'task-manager-datetimepicker', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/jquery.datetimepicker.css', array(), Config_Util::$init['task-manager']->version );

			wp_enqueue_script( 'task-manager-masonry', PLUGIN_TASK_MANAGER_URL . 'core/asset/js/masonry.min.js', array(), Config_Util::$init['task-manager']->version );
			wp_enqueue_script( 'task-manager-script', PLUGIN_TASK_MANAGER_URL . 'core/asset/js/backend.min.js', array(), Config_Util::$init['task-manager']->version );
			wp_enqueue_script( 'task-manager-datetimepicker-script', PLUGIN_TASK_MANAGER_URL . 'core/asset/js/jquery.datetimepicker.full.js', array(), Config_Util::$init['task-manager']->version );
		}
	}

	public function callback_enqueue_scripts() {
		$pagename = get_query_var( 'pagename' );
		if ( in_array( $pagename, Config_Util::$init['task-manager']->insert_scripts_pages, true ) ) {
			wp_enqueue_style( 'task-manager-datepicker', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/datepicker.min.css', array(), Config_Util::$init['task-manager']->version );
		}

		wp_register_style( 'task-manager-frontend-style', PLUGIN_TASK_MANAGER_URL . 'core/asset/css/frontend.css', array(), Config_Util::$init['task-manager']->version );
		wp_enqueue_style( 'task-manager-frontend-style' );

		wp_enqueue_script( 'task-manager-frontend-script', PLUGIN_TASK_MANAGER_URL . 'core/asset/js/frontend.min.js', array(), Config_Util::$init['task-manager']->version, false );
	}

	/**
	 * Initialise en php le fichier permettant la traduction des variables string JavaScript.
	 *
	 * @return void nothing
	 */
	public function callback_admin_print_scripts() {
		$screen = get_current_screen();
		if ( in_array( $screen->id, config_util::$init['task-manager']->insert_scripts_pages, true ) ) {
			require( PLUGIN_TASK_MANAGER_PATH . '/core/asset/js/language.js.php' );
		}
	}

	/**
	 * Initialise le fichier MO
	 */
	public function callback_plugins_loaded() {
		$i18n_loaded = load_plugin_textdomain( 'task-manager', false, PLUGIN_TASK_MANAGER_DIR . '/core/asset/language/' );
	}

	public function callback_wp_print_scripts() {
		?>
		<script>var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";</script>
		<?php
	}

	/**
	 * Définition du menu dans l'administration de wordpress pour Digirisk / Define the menu for wordpress administration
	 */
	public function callback_admin_menu() {
		add_menu_page( __( 'Tâches', 'task-manager' ), __( 'Tâches', 'task-manager' ), 'publish_pages', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ), 'dashicons-layout' );
		add_submenu_page( 'wpeomtm-dashboard', __( 'Tâches', 'task-manager' ), __( 'Tâches', 'task-manager' ), 'publish_pages', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ) );
	}

}

new Task_Manager_Action();
