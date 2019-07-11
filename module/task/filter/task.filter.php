<?php
/**
 * Les filtres relatives aux tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.6.0
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les filtres relatives aux tâches.
 */
class Task_Filter {

	/**
	 * Constructeur
	 *
	 * @since 1.0.0
	 * @version 1.6.0
	 */
	public function __construct() {
		add_filter( 'task_manager_dashboard_title', array( $this, 'callback_dashboard_title' ) );
		add_filter( 'tm_task_header_summary', array( $this, 'callback_task_header_summary' ), 10, 2 );
		add_filter( 'task_manager_dashboard_filter', array( $this, 'callback_dashboard_filter' ), 12 );
		add_filter( 'task', array( $this, 'callback_dashboard_content' ), 10, 2 );

		add_filter( 'task_header_action', array( $this, 'callback_task_header_action' ), 10, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_elapsed' ), 11, 2 );
		add_filter( 'task_header_information', array( $this, 'callback_task_header_information_button' ), 20, 2 );

		add_filter( 'tm_task_footer', array( $this, 'callback_tm_task_footer' ), 10, 2 );
		add_filter( 'tm_task_client_archive', array( $this, 'callback_tm_task_client_archive' ), 10, 2 );

		add_filter( 'wps_third_party_metaboxes', function( $metaboxes ) {
			$metaboxes['wps-third-party-tasks'] = array(
				'callback' => 'metabox_tasks',
			);

			$metaboxes['wps-third-party-indicator'] = array(
				'callback' => 'metabox_indicator',
			);

			$metaboxes['wps-third-party-activity'] = array(
				'callback' => 'metabox_activity',
			);

			return $metaboxes;
		}, 10, 1 );
	}
	/**
	 * Tableau titre et description
	 *
	 * @param  [type] $string [description].
	 * @return [type] $string [description]
	 */
	public function callback_dashboard_title( $string ) {
		$url = wp_nonce_url( add_query_arg( array( 'action' => 'create_task' ), admin_url( 'admin-post.php' ) ), 'wpeo_nonce_create_task' );

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/button-add',
			array(
				'url' => $url,
			)
		);
		$string .= ob_get_clean();

		return $string;
	}

	/**
	 * Tache header résumé
	 *
	 * @param  [type] $output [Affichage].
	 * @param  [type] $task   [Tache].
	 * @return [type] $output [Affichage]
	 */
	public function callback_task_header_summary( $output, $task ) {
		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( $user->data['_tm_advanced_display'] ) {
			// Construction de l'affichage du temps passé.
			$task_time_info                = $task->data['time_info']['elapsed'];
			$task_time_info_human_readable = \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['time_info']['elapsed'] );

			// Construction de l'affichage du temps prévu.
			if ( ! empty( $task->data['last_history_time']->data['estimated_time'] ) ) {
				$task_time_info                .= ' / ' . $task->data['last_history_time']->data['estimated_time'];
				$task_time_info_human_readable .= ' / ' . \eoxia\Date_Util::g()->convert_to_custom_hours( $task->data['last_history_time']->data['estimated_time'] );
			}

			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/task-header-summary',
				array(
					'task'                          => $task,
					'task_time_info'                => $task_time_info,
					'task_time_info_human_readable' => $task_time_info_human_readable,
				)
			);
			$output .= ob_get_clean();
		}

		return $output;
	}

	/**
	 * Tableau Filtre
	 *
	 * @param  [type] $string [description].
	 * @return [type] $string [description]
	 */
	public function callback_dashboard_filter( $string ) {
		ob_start();
		\eoxia\View_Util::exec( 'task-manager', 'task', 'backend/filter-tab' );
		$string .= ob_get_clean();

		return $string;
	}

	/**
	 * Contenu du dashboard
	 *
	 * @param  [type] $string      [description].
	 * @param  [type] $post_parent [description].
	 * @return [type] $string      [description]
	 */
	public function callback_dashboard_content( $string, $post_parent ) {
		if ( 0 == $post_parent ) {
			$list_task = Task_Class::g()->get(
				array(
					'post_parent' => 0,
					'meta_query'  => array(
						array(
							'key'     => 'wpeo_task',
							'value'   => '{"user_info":{"owner_id":' . get_current_user_id(),
							'compare' => 'like',
						),
					),
				)
			);
		} else {
			$list_task = Task_Class::g()->get( array( 'post_parent' => $post_parent ) );
		}

		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/list-task',
			array(
				'list_task' => $list_task,
			)
		);
		$string .= ob_get_clean();

		return $string;
	}

	/**
	 * Tache action header
	 *
	 * @param  [type] $string [description].
	 * @param  [type] $task   [description].
	 * @return [type] $string [description]
	 */
	public function callback_task_header_action( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/task-header-button',
			array(
				'task' => $task,
			)
		);
		$string .= ob_get_clean();
		return $string;
	}

	/**
	 * Tache headers information
	 *
	 * @param  [type] $string [description].
	 * @param  [type] $task   [description].
	 * @return [type] $string
	 */
	public function callback_task_header_information_elapsed( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/time-elapsed',
			array(
				'task' => $task,
			)
		);
		$string .= ob_get_clean();
		return $string;
	}

	/**
	 * Header information du bouton
	 *
	 * @param  [type] $string [description].
	 * @param  [type] $task   [description].
	 * @return [type] $string [description]
	 */
	public function callback_task_header_information_button( $string, $task ) {
		ob_start();
		\eoxia\View_Util::exec(
			'task-manager',
			'task',
			'backend/information-button',
			array(
				'task' => $task,
			)
		);
		$string .= ob_get_clean();
		return $string;
	}

	/**
	 * Tache footer
	 *
	 * @param  [type] $output [view].
	 * @param  [type] $task   [description].
	 * @return [type] $output [view]
	 */
	public function callback_tm_task_footer( $output, $task ) {
		$user = Follower_Class::g()->get( array( 'id' => get_current_user_id() ), true );

		if ( $user->data['_tm_advanced_display'] ) {
			ob_start();
			\eoxia\View_Util::exec(
				'task-manager',
				'task',
				'backend/linked-post-type',
				array(
					'task' => $task,
				)
			);
			$output .= ob_get_clean();
		}else{
			// echo '<pre>'; print_r( $task->data[ 'parent_id' ] ); echo '</pre>';
		}

		return $output;
	}

	public function callback_tm_task_client_archive( $output, $task_statut ){
		$screen = get_current_screen();

		if( $screen->id == 'wpshop_customers' && $task_statut == 'archive' ){
			return 'none';
		}else{
			return 'block';
		}
	}


}

new Task_Filter();
