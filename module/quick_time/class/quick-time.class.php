<?php
/**
 * Gestion des temps rapides.
 *
 * @author Jimmy Latour <jimmy.eoxia@gmail.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des temps rapides.
 */
class Quick_Time_Class extends \eoxia\Singleton_Util {

	/**
	 * Constructeur obligatoire pour Singleton_Util
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	protected function construct() {}

	/**
	 * Appel la vue principale de la metabox "temps rapides".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function display() {
		$quicktimes = $this->get_quick_time();

		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/main', array(
			'quicktimes' => $quicktimes,
		) );
	}

	/**
	 * Récupères les templates des temps rapides.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return array (Voir au dessus)
	 */
	public function get_quick_time() {
		$quick_times = get_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, true );

		if ( ! empty( $quick_times ) ) {
			foreach ( $quick_times as &$quick_time ) {
				$quick_time['displayed'] = array(
					'task'               => Task_Class::g()->get( array(
						'id' => $quick_time['task_id'],
					), true ),
					'point'              => Point_Class::g()->get( array(
						'id' => $quick_time['point_id'],
					), true ),
					'point_fake_content' => '',
				);

				$quick_time['displayed']['point_fake_content'] = '#' . $quick_time['displayed']['point']->id . ' ' . $quick_time['displayed']['point']->content;

				if ( strlen( $quick_time['displayed']['point']->content ) > 15 ) :
					$quick_time['displayed']['point_fake_content'] = substr( $quick_time['displayed']['point']->content, 0, 15 );
					$quick_time['displayed']['point_fake_content'] = '#' . $quick_time['displayed']['point']->id . ' ' . $quick_time['displayed']['point_fake_content'] . '...';
				endif;
			}
		}

		rsort( $quick_times );
		return $quick_times;
	}
}

Quick_Time_Class::g();
