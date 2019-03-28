<?php
/**
 * Gestion des filtres de la page indicateur.
 *
 * @author    Eoxia <dev@eoxia.com>
 * @copyright (c) 2006-2018 Eoxia <dev@eoxia.com>.
 *
 * @license   GPL2 <http://www.gnu.org/licenses/gpl-2.0.html>
 *
 * @package   Task_Manager\Classes
 *
 * @since     1.7.1
 */

namespace task_manager;

defined( 'ABSPATH' ) || exit;

/**
 * Indicator Filter class.
 */
class Indicator_Filter {

	/**
	 * Le constructeur
	 *
	 * @since 1.7.1
	 */
	public function __construct() {
		add_filter( 'tm_filter_activity', array( $this, 'callback_tm_filter_activity' ), 10, 4 );
	}

	/**
	 * Filtre l'activité
	 *
	 * @param  [type]  $content              [description].
	 * @param  integer $selected_user_id     [description].
	 * @param  integer $selected_customer_id [description].
	 * @return $content                      [la vue].
	 */
	public function callback_tm_filter_activity( $content, $selected_user_id = 0, $selected_customer_id = 0, $page = '' ) {
		if ( class_exists( '\wps_customer_ctr' ) ) {
			$customer_ctr = new \wps_customer_ctr();

			$users = Follower_Class::g()->get(
				array(
					'role' => 'administrator',
				)
			);

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'indicator',
				'backend/filter-daily-activity',
				array(
					'customer_ctr'         => $customer_ctr,
					'users'                => $users,
					'selected_user_id'     => $selected_user_id,
					'selected_customer_id' => $selected_customer_id,
					'page'                 => $page
				)
			);
			$content = ob_get_clean();
		}

		return $content;
	}
}

new Indicator_Filter();
