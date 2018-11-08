<?php
/**
 * Classe gérant le support.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.2.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Classe gérant le support.
 */
class Support_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 */
	protected function construct() {}

	/**
	 * Renvoies le nombre de demande
	 *
	 * @since 1.2.0
	 * @version 1.2.0
	 *
	 * @return integer Le nombre de demande
	 */
	public function get_number_ask() {
		$ids = get_option( \eoxia\Config_Util::$init['task-manager']->key_customer_ask, array() );

		$count = 0;

		if ( ! empty( $ids ) ) {
			foreach ( $ids as $task_id => $points ) {
				if ( ! empty( $points ) ) {
					foreach ( $points as $point_id => $id ) {
						$count += count( $id );
					}
				}
			}
		}

		return $count;
	}
}

new Support_Class();