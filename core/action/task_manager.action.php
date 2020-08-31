<?php
/**
 * Les actions principales de l'application.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 0.1.0
 * @version 1.8.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;
use \eoxia\Custom_Menu_Handler as CMH;

defined( 'ABSPATH' ) || exit;

/**
 * Les actions principales de l'application.
 */
class Task_Manager_Action {

	/**
	 * Le constructeur ajoutes les actions WordPress suivantes:
	 * admin_enqueue_scripts (Pour appeller les scripts JS et CSS dans l'admin)
	 * admin_print_scripts (Pour appeler les scripts JS en bas du footer)
	 * plugins_loaded (Pour appeler le domaine de traduction)
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ), 11 );
		add_action( 'wp_enqueue_scripts', array( $this, 'callback_enqueue_scripts' ), 11 );
		add_action( 'wp_print_scripts', array( $this, 'callback_wp_print_scripts' ) );

		add_action( 'init', array( $this, 'callback_plugins_loaded' ) );
		add_action( 'admin_menu', array( $this, 'callback_admin_menu' ), 12 );

		add_action( 'wp_ajax_tm_have_patch_note', array( $this, 'have_patch_note' ) );
		add_action( 'wp_ajax_tm_close_change_log', array( $this, 'callback_close_change_log' ) );

		add_filter(
			'task_manager_get_tasks_args',
			function( $args ) {
				$archive = ( isset( $_REQUEST['tm_dashboard_archives_include'] ) && 1 == $_REQUEST['tm_dashboard_archives_include'] ) ? true : false;
				if ( $archive ) {
					$args['status'] .= ',"archive"';
				}

				return $args;
			}
		);
	}

	/**
	 * Initialise le fichier style.min.css et backend.min.js du plugin Task Manager.
	 *
	 * @since 0.1.0
	 * @version 1.5.0
	 *
	 * @return void nothing
	 */
	public function callback_admin_enqueue_scripts() {
		$screen = get_current_screen();
		wp_register_style( 'task-manager-global-style', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/global.css', array(), \eoxia\config_util::$init['task-manager']->version );
		wp_enqueue_style( 'task-manager-global-style' );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-form' );
		// wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-ui-autocomplete' );
		wp_enqueue_script( 'heartbeat' );
		wp_enqueue_media();
		add_thickbox();

		if ( ! empty( \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages ) ) {
			foreach ( \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages as $insert_script_page ) {
				if ( false !== strpos( $screen->id, $insert_script_page ) ) {
					wp_register_style( 'task-manager-style', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/style.min.css', array(), \eoxia\config_util::$init['task-manager']->version );
					wp_enqueue_style( 'task-manager-style' );

					// wp_enqueue_style( 'task-manager-datepicker', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/datepicker.min.css', array(), \eoxia\Config_Util::$init['task-manager']->version );
					// wp_enqueue_style( 'task-manager-datetimepicker', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/jquery.datetimepicker.css', array(), \eoxia\Config_Util::$init['task-manager']->version );

					wp_enqueue_style( 'task-manager-roboto-font', 'https://fonts.googleapis.com/css?family=Roboto+Slab' );

					wp_enqueue_script( 'task-manager-chart', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js' );

					wp_enqueue_script( 'task-manager-colcade', PLUGIN_TASK_MANAGER_URL . 'core/assets/js/colcade.js', array(), \eoxia\Config_Util::$init['task-manager']->version );
					wp_enqueue_script( 'task-manager-script', PLUGIN_TASK_MANAGER_URL . 'core/assets/js/backend.min.js', array(), \eoxia\Config_Util::$init['task-manager']->version );
					wp_localize_script(
						'task-manager-script',
						'taskManager',
						array(
							'updateManagerUrlPage'      => 'admin_page_' . \eoxia\Config_Util::$init['task-manager']->update_page_url,
							'updateManagerconfirmExit'  => __( 'Your data are being updated. If you confirm that you want to leave this page, your data could be corrupted', 'task-manager' ),
							'updateManagerloader'       => '<img src=' . admin_url( '/images/loading.gif' ) . ' />',
							// Translators: %s is the version number with strong markup.
							'updateManagerInProgress'   => sprintf( __( 'Update %s in progress', 'task-manager' ), '<strong>{{ versionNumber }}</strong>' ),
							// Translators: %s is the version number with strong markup.
							'updateManagerErrorOccured' => sprintf( __( 'An error occured. Please take a look at %s logs', 'task-manager' ), '<strong>{{ versionNumber }}</strong>' ),
							'data'                      => \eoxia\JSON_Util::g()->open_and_decode( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/data.json' ),
							'search'                    => \eoxia\JSON_Util::g()->open_and_decode( PLUGIN_TASK_MANAGER_PATH . 'core/assets/json/search.json' ),
						)
					);

					wp_localize_script(
						'task-manager-script',
						'indicatorString',
						array(
							'time_work'     => __( 'Time work', 'task-manager' ),
							'time_day'      => __( 'Time Day', 'task-manager' ),
							'minute'        => __( 'minute(s)', 'task-manager' ),
							'planning'      => __( 'Planning', 'task-manager' ),
							'date_error'    => __( 'Invalid date', 'task-manager' ),
							'person_error'  => __( 'Choose a user', 'task-manager' ),
							'nodata'        => __( 'No data, please configure your planning settings !', 'task-manager' ),
							'from'          => __( 'From', 'task-manager' ),
							'to'            => __( 'to', 'task-manager' ),
							'plan_week'     => __( 'Stats of the week', 'task-manager' ),
							'completed'     => __( 'Completed', 'task-manager' ),
							'uncompleted'   => __( 'Uncompleted', 'task-manager' ),
							'taskempty'     => __( 'No point', 'task-manager' ),
							'delink_parent' => __( 'Do you really want to delink this task from her parent ?', 'task-manager' ),
							'delink_audit'  => __( 'Do you really want to delink this audit from her client parent ?', 'task-manager' ),
							'resume_bar'    => __( 'Horizontal summary', 'task-manager' ),
							'resume_dog'    => __( 'Doghnut summary', 'task-manager' ),
							'delete_text'   => __( 'Do you want to delete your text ?', 'task-manager' ),
							'cat_head'      => __( 'Error Category', 'task-manager' ),
							'cat_body'      => __( 'This category doesn\'t exist : ', 'task-manager' ),
							'cat_question'  => __( 'What do you want to do ?', 'task-manager' ),
							'cat_nothing'   => __( 'Nothing', 'task-manager' ),
							'cat_create'    => __( 'Create it', 'task-manager' ),
						)
					);
					break;
				}
			}
		}

		wp_enqueue_script( 'task-manager-global-script', PLUGIN_TASK_MANAGER_URL . 'core/assets/js/global.min.js', array(), \eoxia\Config_Util::$init['task-manager']->version );
	}

	/**
	 * Enqueue scripts in frontend
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function callback_enqueue_scripts() {
		$pagename = get_query_var( 'pagename' );
		if ( in_array( $pagename, \eoxia\Config_Util::$init['task-manager']->insert_scripts_pages, true ) ) {
			// wp_enqueue_style( 'task-manager-datepicker', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/datepicker.min.css', array(), \eoxia\Config_Util::$init['task-manager']->version );
		}

		wp_register_style( 'task-manager-frontend-style', PLUGIN_TASK_MANAGER_URL . 'core/assets/css/frontend.css', array(), \eoxia\Config_Util::$init['task-manager']->version );
		wp_enqueue_style( 'task-manager-frontend-style' );

		wp_enqueue_script( 'task-manager-frontend-script', PLUGIN_TASK_MANAGER_URL . 'core/assets/js/frontend.min.js', array(), \eoxia\Config_Util::$init['task-manager']->version, false );

		wp_localize_script(
			'task-manager-frontend-script',
			'taskManagerFrontend',
			array(
				'wpeo_project_delete_comment_time' => __( 'Delete this comment ?', 'task-manager' ),
			)
		);
	}

	/**
	 * Initialise le fichier MO et les capacités
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function callback_plugins_loaded() {

		if( isset( \eoxia\Config_Util::$init['task-manager'] ) ){
			\eoxia\Config_Util::$init['task-manager']->associate_post_type[] = 'digi-workunit';
			\eoxia\Config_Util::$init['task-manager']->associate_post_type[] = 'digi-group';
			\eoxia\Config_Util::$init['task-manager']->associate_post_type[] = 'digi-society';
			\eoxia\Config_Util::$init['task-manager']->associate_post_type[] = 'digi-risk';
		}

		$i18n_loaded = load_plugin_textdomain( 'task-manager', false, PLUGIN_TASK_MANAGER_DIR . '/core/assets/language/' );

		/** Set capability to administrator by default */
		$administrator_role = get_role( 'administrator' );
		if ( ! $administrator_role->has_cap( 'manage_task_manager' ) ) {
			$administrator_role->add_cap( 'manage_task_manager' );
		}

		Task_Manager_Class::g()->init_default_data();
		Follower_Class::g()->init_default_data();


		add_action( 'load-post.php', array( $this, 'load_screen_option' ) );
	}

	/**
	 * Initialise ajaxurl.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function callback_wp_print_scripts() {
		?>
		<script>var ajaxurl = "<?php echo admin_url( 'admin-ajax.php' ); ?>";</script>
		<?php
	}

	/**
	 * Définition du menu "Task Manager" dans l'administration de WordPress.
	 *
	 * @since 1.0.0
	 * @version 1.5.0
	 */
	public function callback_admin_menu() {
		CMH::register_container( 'Task Manager', 'Task Manager', 'manage_task_manager', 'wpeomtm-dashboard', '', PLUGIN_TASK_MANAGER_URL . '/core/assets/images/icone-16-16-couleur.png', null );
		CMH::add_logo( 'wpeomtm-dashboard', PLUGIN_TASK_MANAGER_URL . '/core/assets/images/icone.png', admin_url( 'admin.php?page=wpeomtm-dashboard' ) );
		CMH::register_menu( 'wpeomtm-dashboard', __( 'Task Manager', 'task-manager' ), __( 'Task Manager', 'task-manager' ), 'manage_task_manager', 'tm-dashboard', array( Task_Manager_Class::g(), 'display_dashboard' ), PLUGIN_TASK_MANAGER_URL . 'core/assets/images/icone-16-16-couleur.png');
		CMH::register_menu( 'wpeomtm-dashboard', __( 'Projects', 'task-manager' ), __( 'Projects', 'task-manager' ), 'manage_task_manager', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ), 'fas fa-thumbtack', '' );
		if ( isset( \eoxia\Config_Util::$init['digirisk'] ) ) {
			CMH::register_others_menu( 'others', 'wpeomtm-dashboard', __( 'DigiRisk', 'digirisk' ), __( 'DigiRisk', 'digirisk' ), 'read', 'digirisk', array( \digi\Digirisk::g(), 'display' ),  PLUGIN_DIGIRISK_URL . '/core/assets/images/favicon2.png', 'bottom' );
		}
		// CMH::register_menu( 'wpeomtm-dashboard', __( 'My Tasks', 'task-manager' ), __( 'My Tasks', 'task-manager' ), 'read', 'tm-my-tasks', array( Task_Manager_Class::g(), 'display' ), 'fas fa-check-square', '' );
//		CMH::register_others_menu( 'others', 'digirisk-dashboard', __( 'DigiRisk', 'digirisk' ), __( 'DigiRisk', 'digirisk' ), 'read', 'digirisk', array( Digirisk::g(), 'display' ), PLUGIN_DIGIRISK_URL . '/core/assets/images/favicon_hd.png', 'bottom' );

		//add_menu_page( __( 'Task', 'task-manager' ), __( 'Task', 'task-manager' ), 'manage_task_manager', 'wpeomtm-dashboard', array( Task_Manager_Class::g(), 'display' ), PLUGIN_TASK_MANAGER_URL . 'core/assets/icon-16x16.png' );
//		add_meta_box( 'tm-dashboard-indicator-customer', __( 'Customer', 'task-manager' ), array( Indicator_Class::g(), 'callback_customer' ), 'wpeomtm-dashboard', 'normal' );
	}

	public static function load_screen_option(){
        add_filter( 'screen_settings', array( get_class(), 'add_field'), 10, 2 );
	}

	public static function add_field( $rv, $screen ) {
			$user_id = get_current_user_id();
 			$post_per_page = Task_Class::g()->get_task_per_page_for_this_user( $user_id );

			ob_start();

			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/screen_option/main',
				array(
					'value_task'  => $post_per_page
				)
			);

			$rv .= ob_get_clean();

      return $rv;
    }

	/**
	 * Lors de la fermeture de la notification de la popup.
	 * Met la metadonnée '_wptm_user_change_log' avec le numéro de version actuel à true.
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function have_patch_note() {
		$meta = get_user_meta( get_current_user_id(), '_wptm_user_change_loga', true );

		$result = Task_Manager_Class::g()->get_patch_note();

		if ( $result['status'] ) {
			$result['status'] = ( isset( $meta[ \eoxia\Config_Util::$init['task-manager']->version ] ) && $meta[ \eoxia\Config_Util::$init['task-manager']->version ] ) ? false : true;
		}

		ob_start();
		require PLUGIN_TASK_MANAGER_PATH . '/core/view/patch-note.view.php';
		wp_send_json_success( array(
			'status'  => $result['status'],
			'result'  => $result,
			'view'    => ob_get_clean(),
		) );
	}

	/**
	 * Lors de la fermeture de la notification de la popup.
	 * Met la metadonnée '_wpdigi_user_change_log' avec le numéro de version actuel à true.
	 *
	 * @since 6.0.0
	 */
	public function callback_close_change_log() {
		check_ajax_referer( 'close_change_log' );

		$version = ! empty( $_POST['version'] ) ? sanitize_text_field( wp_unslash( $_POST['version'] ) ) : ''; // WPCS: input var ok.

		if ( empty( $version ) ) {
			wp_send_json_error();
		}

		$meta = get_user_meta( get_current_user_id(), '_wptm_user_change_loga', true );

		if ( empty( $meta ) ) {
			$meta = array();
		}

		$meta[ $version ] = true;
		update_user_meta( get_current_user_id(), '_wptm_user_change_loga', $meta );

		wp_send_json_success();
	}
}

new Task_Manager_Action();
