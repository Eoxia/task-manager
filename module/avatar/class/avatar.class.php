<?php
/**
 * Gestion des avatars
 *
 * @since 1.3.4
 * @version 1.6.0
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des avatars
 */
class Avatar_Class extends \eoxia\Singleton_Util {

	/**
	 * Obligatoire pour Singleton_Util
	 *
	 * @since 1.3.4
	 * @version 1.6.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Récupères l'url de l'avatar
	 *
	 * @since 1.3.4
	 * @version 1.6.0
	 *
	 * @param  array $param (Voir plus haut).
	 * @return array
	 */
	public function get_avatars( $param ) {
		$users = array();

		if ( ! empty( $param['ids'] ) ) {
			$users = Follower_Class::g()->get(
				array(
					'include' => $param['ids'],
				)
			);
		}

		if ( ! empty( $users ) ) {
			foreach ( $users as $user ) {
				$user->data['avatar_url'] = get_avatar_url(
					$user->data['id'],
					array(
						'size'    => $param['size'],
						'default' => 'blank',
					)
				);
			}
		}

		return $users;
	}
}

Avatar_Class::g();
