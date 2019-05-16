<?php
/**
 * Gestion des temps rapides.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.6.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
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
		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/main' );
	}

	/**
	 * Affiches le bouton "réglage".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function display_setting_button() {
		\eoxia\View_Util::exec( 'task-manager', 'quick_time', 'backend/button-setting' );
	}

	/**
	 * Affiches la liste des "temps rapides".
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return void
	 */
	public function display_list( $createnewline = false, $editline = false ) {
		$quicktimes = $this->get_quicktimes();

		//echo '<pre>'; print_r( $quicktimes ); echo '</pre>'; exit;

		$comment_schema = Task_Comment_Class::g()->get(
			array(
				'schema' => true,
			),
			true
		);

		\eoxia\View_Util::exec(
			'task-manager',
			'quick_time',
			'backend/list',
			array(
				'quicktimes'     => $quicktimes,
				'comment_schema' => $comment_schema,
				'createnewline'  => $createnewline,
				'editline'    => $editline
			)
		);
	}

	/**
	 * Récupères les templates des temps rapides.
	 *
	 * @since 1.6.0
	 * @version 1.6.0
	 *
	 * @return array (Voir au dessus)
	 */
	public function get_quicktimes() {

		$quicktimes = get_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, true );

		if ( ! empty( $quicktimes ) ) {
			foreach ( $quicktimes as $key => $quicktime ) {
				if( $quicktime != '' ){
					$quicktimes[ $key ] = quicktime_format_data( $quicktime );

				}
			}
			// @comment sort( $quicktimes );
		}

		//echo '<pre>'; print_r( $quicktimes ); echo '</pre>';
		return $quicktimes;
	}

	public function update_quicktimes( $quicktimes, $key, $content ){
		$commentsfromdb = $this->get_quicktimes();

		$commentsfromdb[ $key ][ 'content' ] = $content;

		$quicktimes = update_user_meta( get_current_user_id(), \eoxia\Config_Util::$init['task-manager']->quick_time->meta_quick_time, $commentsfromdb );

	}

	public function display_this_task_and_point( $index ){

		$quicktimes = self::get_quicktimes();

		if( ! array_key_exists( $index, $quicktimes ) || $index == -1 || $quicktimes[ $index ] == '' ){
			\eoxia\View_Util::exec(
				'task-manager',
				'quick_time',
				'backend/quicktimemode/error-view',
				array(
					'index' => $index + 1
				)
			);
			return;
		}

		$content = $quicktimes[ $index ][ 'content' ]; // Peut etre vide ""

		$param = array(
			'task_id' => $quicktimes[ $index ][ 'task_id' ],
			'point_id' => $quicktimes[ $index ][ 'point_id' ],
		);



		$task = Task_Class::g()->get_tasks( $param );


		\eoxia\View_Util::exec(
			'task-manager',
			'quick_time',
			'backend/quicktimemode/task-quicktime',
			array(
				'task'     => $task[0],
				'point_id' => $quicktimes[ $index ][ 'point_id' ],
				'content'  => $content
			)
		);
	}

	public function this_point_is_a_quicktime( $task_id, $point_id ){

		if( $task_id != 0 && $point_id != 0 ){
			$quicktimes = self::get_quicktimes();
			if( ! empty ( $quicktimes ) ){

				foreach ($quicktimes as $key => $value) {
					if( ! empty( $value ) ){
						if( $value[ 'task_id' ] == $task_id ){
							return $key;
						}
					}
				}
			}

		}
		return -1;
	}
}

Quick_Time_Class::g();
