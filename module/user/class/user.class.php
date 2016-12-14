<?php

namespace task_manager;

if ( !defined( 'ABSPATH' ) ) exit;

class Task_Manager_User_Class extends User_Class {
	public $list_user = array();

	protected $model_name 	= 'task_manager\Task_Manager_User_Model';
	protected $meta_key		= '_wpeo_user';
	protected $base = 'task/user';
	protected $version = '0.1';

	protected function construct() {}

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

Task_Manager_User_Class::g();
