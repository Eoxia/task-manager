<?php

if ( !defined( 'ABSPATH' ) ) exit;

class user_controller_01 extends user_ctr_01 {
	public $list_user = array();

	protected $model_name 	= 'wpeo_user_mdl_01';
	protected $meta_key		= '_wpeo_user';
	protected $base = 'task/user';
	protected $version = '0.1';

	public function __construct() {
		parent::__construct();

		require_once( WPEO_USER_PATH . '/model/user.model.01.php' );

		add_action( 'admin_init', array( &$this, 'callback_admin_init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'callback_admin_enqueue_scripts' ) );

		// add_shortcode( 'wpeo_user', array( $this, 'callback_shortcode' ) );
		add_filter( 'task_avatar', array( $this, 'callback_task_avatar' ), 10, 4 );

		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_task_manager_dashboard_filter' ), 10, 2 );
		add_filter( 'task_manager_dashboard_search', array( $this, 'callback_task_manager_dashboard_search' ), 10, 2 );
		add_filter( 'task_footer', array( $this, 'callback_task_footer' ), 10, 2 );
		add_filter( 'task_window_footer_task_controller', array( $this, 'callback_task_footer' ), 12, 2 );
	}

	public function callback_admin_init() {
		/** On récupère toutes la liste des utilisateurs qui seront accessible grâce à cette global */
		$this->list_user = $this->index( array( 'role' => 'administrator' ) );
	}

	public function callback_admin_enqueue_scripts() {
		if( WPEO_TASKMANAGER_DEBUG ) {
			wp_enqueue_script( 'wpeo-task-user-backend-js', WPEO_USER_ASSETS_URL . '/js/backend.js', array( "jquery", "jquery-form", "jquery-ui-datepicker", "jquery-ui-sortable", 'jquery-ui-autocomplete', 'suggest' ), WPEO_TASKMANAGER_VERSION );
		}
	}

	public function callback_task_avatar( $string, $id, $size, $display_name ) {
		$user = $this->get_user_by_id( $id );
		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'user-gravatar' ) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_manager_dashboard_filter( $string ) {
		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'filter' ) );
		$string .= ob_get_clean();

		return $string;
	}

	public function callback_task_manager_dashboard_search( $string ) {
		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'choosen' ) );
		$string .= ob_get_clean();
		return $string;
	}

	public function callback_task_footer( $string, $element ) {
		/** On récupère le responsable de la tâche */
		$owner_id = ( !empty( $element ) && !empty( $element->option) && !empty( $element->option['user_info'] ) && !empty( $element->option['user_info']['owner_id'] ) ) ? $element->option['user_info']['owner_id']: 0;
		$owner_user = $this->get_user_by_id( $owner_id );
		$size = 50;

		ob_start();
		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'display', 'user' ) );
		$string .= ob_get_clean();

		return $string;
	}

	/**
	 * Récupères tous les utilisateurs et les affiches par rapport au template qui varie selon $args['display_type']
	 * Recovered all users and display it compared to the template which varies $args['display_type']
	 *
	 * @param array $args ( 'display_type' => string )
	 * @return void
	 */
	public function callback_shortcode( $args ) {
// 		$list_user = $this->index();

		require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'list' ) );
	}

	/**
	 * Display the list of user in post
	 *
	 * @param array $args
	 * @param $args['id'] Required
	 * @param $args['use_dashicons'] True or false
	 * @param $args['dashicons'] default : dashicons-plus
	 * @param $args['callback_js'] default : callback_user ( This callback is called when the action wpeo-update-users is done )
	 * @param $args['type'] default: post ( Where the post meta is saving in post, comment or whatever ? )
	 * @param $args['user_role'] default: administrator
	 * @example [wpeouser id="10" dashicons="dashicons-setting" callback_js="my_js_callback" ]
	 * @return string ( Template : backend/display-user )
	 */
	public function display_user_in_object( $object, $template = '' ) {
		/** On récupère le responsable de la tâche */
		$owner_id = ( !empty( $object ) && !empty( $object->option) && !empty( $object->option['user_info'] ) && !empty( $object->option['user_info']['owner_id'] ) ) ? $object->option['user_info']['owner_id']: 0;
		$owner_user = $this->get_user_by_id( $owner_id );

		if( $template == 'dashboard') {
			require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'display', 'user-window' ) );
		}
		else {
			require( wpeo_template_01::get_template_part( WPEO_USER_DIR, WPEO_USER_TEMPLATES_MAIN_DIR, 'backend', 'display', 'user' ) );
		}
	}

	public function get_user_by_id( $user_id ) {
		if ( !empty( $this->list_user ) ) {
			foreach ( $this->list_user as $user ) {
				if( $user->id == $user_id )
					return $user;
			}
		}

		return null;
	}

}

global $wp_project_user_controller;
$wp_project_user_controller = new user_controller_01();
