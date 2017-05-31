<?php
/**
 * Gestion des avatars
 *
 * @since 1.3.4.0
 * @version 1.3.6.0
 * @package Task-Manager\avatar
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Gestion des avatars
 */
class Avatar_Class extends Singleton_Util {
	protected function construct() {}

	public function get_avatars( $param ) {
		$users = array();

		if ( ! empty( $param['ids'] ) ) {
			$users = User_Class::g()->get( array(
				'include' => $param['ids'],
			) );
		}

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user->avatar_url = get_avatar_url( $user->id, array(
					'size' => $param['size'],
					'default' => 'blank',
				) );
			}
		}

		return $users;
	}
}

Avatar_Class::g();
