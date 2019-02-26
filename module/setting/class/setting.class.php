<?php
/**
 * Classe gérant les configurations de Task Manager.
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
 * Classe gérant les configurations de Task Manager.
 *
 * @return void
 */
class Setting_Class extends \eoxia\Singleton_Util {

	/**
	 * La limite des utilisateurs de la page "task-manager-setting"
	 *
	 * @var integer
	 */
	private $limit_user = 10;

	/**
	 * Le constructeur
	 *
	 * @return void
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 */
	protected function construct() {}

	/**
	 * Récupère le role "subscriber" et appel la vue "capability/has-cap".
	 *
	 * @since 1.5.0
	 * @version 1.5.0
	 *
	 * @return void
	 */
	public function display_role_has_cap() {
		$role_subscriber = get_role( 'subscriber' );

		\eoxia\View_Util::exec(
			'task-manager',
			'setting',
			'capability/has-cap',
			array(
				'role_subscriber' => $role_subscriber,
			)
		);
	}

	/**
	 * Récupères la liste des utilisateurs pour les afficher dans la vue "capability/list".
	 *
	 * @since 1.5.0
	 * @version 1.6.0
	 *
	 * @param array $list_user_id La liste des utilisateurs à afficher. Peut être vide pour récupérer tous les utilisateurs.
	 *
	 * @return void
	 */
	public function display_user_list_capacity( $list_user_id = array() ) {
		$current_page = ! empty( $_POST['next_page'] ) ? (int) $_POST['next_page'] : 1;
		$args_user    = array(
			'exclude' => array( 1 ),
			'offset'  => ( $current_page - 1 ) * $this->limit_user,
			'number'  => $this->limit_user,
		);

		if ( ! empty( $list_user_id ) ) {
			$args_user['include'] = $list_user_id;
		}

		$users = \eoxia\User_Class::g()->get( $args_user );

		unset( $args_user['offset'] );
		unset( $args_user['number'] );
		unset( $args_user['include'] );
		$args_user['fields'] = array( 'ID' );

		$count_user  = count( \eoxia\User_Class::g()->get( $args_user ) );
		$number_page = ceil( $count_user / $this->limit_user );

		$role_subscriber      = get_role( 'subscriber' );
		$has_capacity_in_role = ! empty( $role_subscriber->capabilities['task_manager'] ) ? true : false;

		if ( ! empty( $users ) ) {
			foreach ( $users as &$user ) {
				$user->wordpress_user = new \WP_User( $user->data['id'] );
			}
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'setting',
			'capability/list',
			array(
				'users'                => $users,
				'has_capacity_in_role' => $has_capacity_in_role,
				'number_page'          => $number_page,
				'count_user'           => $count_user,
				'current_page'         => $current_page,
			)
		);
	}

	public function get_user_settings_indicator_client(){

		$data = get_user_meta( get_current_user_id(), 'indicator_client_settings', true ); // on récupere les settings de l'utilisateur
		if( ! isset( $data ) || empty( $data ) ){
			$data = array();
		}

		return $data;
	}

	public function display_user_settings_indicator_client(){

		$data = $this->get_user_settings_indicator_client();

		ob_start();

		\eoxia\View_Util::exec(
			'task-manager',
			'setting',
			'indicatorclient/table-callline'
		);

		\eoxia\View_Util::exec(
			'task-manager',
			'setting',
			'indicatorclient/table-newline'
		);

		$view = ob_get_clean();

		return $view;
	}


	public function update_settings_user_indicatorclient( $color, $value ){

		$data = $this->get_user_settings_indicator_client();
		$element = array(
			'from_number' => $value,
			'to_number' => 0,
			'value_color' => $color
		);

		array_push( $data, $element );

		$data_tried = $this->update_properly_settings_user_indicator_client( $data );

		update_user_meta( get_current_user_id(), 'indicator_client_settings', $data_tried );

		return $this->display_user_settings_indicator_client( $data_tried );
	}

	public function delete_user_settings_indicator_client( $key ){
		$data = $this->get_user_settings_indicator_client();

		if( isset( $data[ $key ] ) ){
			unset( $data[$key] );
		}

		$data_tried = $this->update_properly_settings_user_indicator_client( $data );

		update_user_meta( get_current_user_id(), 'indicator_client_settings', $data_tried );

		return $this->display_user_settings_indicator_client();
	}

	public function update_properly_settings_user_indicator_client( $data ){

		$data_tried = Follower_Class::g()->array_sort( $data, 'from_number', SORT_DESC );

		foreach ($data_tried as $key => $value) {
			if( $key == 0 ){
				$data_tried[ $key ][ 'to_number' ] = '+++';
				$data_tried[ $key ][ 'topnumber' ] = true;
			}else{
				$data_tried[ $key ][ 'to_number' ] = $data_tried[ $key - 1 ][ 'from_number' ];
				$data_tried[ $key ][ 'topnumber' ] = false;
			}

			if( $key == count( $data_tried ) - 1 ){
				$data_tried[ $key ][ 'lownumber' ] = true;
			}else{
				$data_tried[ $key ][ 'lownumber' ] = false;
			}

		}


		return $data_tried;
	}
}

Setting_Class::g();
