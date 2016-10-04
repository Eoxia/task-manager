<?php

if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'timeline_controller_01' ) ) {
	class timeline_controller_01 {
		private $min_year = 2016;
		private $array_color = array( 'e15352', '46e58a', 'eaad4a' );

		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( &$this, 'callback_admin_enqueue_scripts' ), 1 );
			add_action( 'admin_menu', array( $this, 'callback_admin_menu' ) );

			add_action( 'show_user_profile', array( $this, 'callback_user_profile' ) );
			add_action( 'edit_user_profile', array( $this, 'callback_user_profile' ) );

			add_action( 'personal_options_update', array( $this, 'callback_options_update' ) );
			add_action( 'edit_user_profile_update', array( $this, 'callback_options_update' ) );
		}

		public function callback_admin_enqueue_scripts() {
			//wp_register_style( 'wpeo-timeline-css', WPEOMTM_TIMELINE_URL . '/asset/css/backend.css', '', WPEOMTM_TIMELINE_VERSION );
			//wp_enqueue_style( 'wpeo-timeline-css' );

			wp_enqueue_script( 'wpeo-timeline-js', WPEOMTM_TIMELINE_URL . '/asset/js/backend.js', array( "jquery" ), WPEOMTM_TIMELINE_VERSION );
		}

		public function callback_admin_menu() {
			add_submenu_page( 'wpeomtm-dashboard', __( 'Timeline', 'wpeotimeline-i18n' ), __( 'Timeline', 'wpeotimeline-i18n' ), 'manage_options', 'wpeo-project-timeline', array( &$this, 'callback_submenu_page' ) );
		}

		public function callback_user_profile( $user ) {
			require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'user', 'profile' ) );
		}

		public function callback_options_update( $user_id ) {
			if ( !current_user_can( 'edit_user', $user_id ) )
				return false;

			update_usermeta( $user_id, 'working_time', taskmanager\util\wpeo_util::convert_to_minut( $_POST['working_time'] ) );
		}

		public function callback_submenu_page() {
			global $wp_project_user_controller;
			$list_user = $wp_project_user_controller->list_user;

			$list_year = $this->generate_year();
			$current_month = date( 'n' );

			global $task_timeline;
			$user_id = get_current_user_id();

			require_once( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'timeline' ) );
		}

		/**
		 * Tant que $current_year est différent de $min_year, ajoutes un élément dans
		 * le tableau $list_year de $current_year - 1.
		 *
		 * @return array string
		 */
		public function generate_year() {
			$list_year = array( date( 'Y' ) );
			$current_year = $list_year[0];

			if( $current_year < $this->min_year ) {
				do {
					$current_year--;
					$list_year[] = $current_year;

				} while( $current_year != $this->min_year );
			}
			return $list_year;
		}

		public function render_month( $user_id, $year, $month ) {
			global $task_timeline;

			$ms_start = microtime( true );

			if( $month == date( 'm' ) ) {
				$current_day = date( 'd' );
			}
			else {
				$date = new DateTime( $year . '-' . ($month - 1) . '-01 00:00:00' );
				$date->modify( 'last day of this month' );
				$current_day = $date->format( 'd' );
			}

			// On met le mois en bon format
			if ( strlen( $month ) == 1 )
				$month = '0' . $month;

			$start_date = $year . '-' . $month . '-' . '01' . ' 00:00:00';
			$date = new DateTime( $start_date );
			$date->modify( 'last day of this month' );
			$last_day_in_the_month = $date->format( 'd' );
			$end_date = $year . '-' . $month . '-' . $last_day_in_the_month . ' 23:59:59';

			global $task_controller;
			$list_task = $task_controller->get_task_by_comment_user_id_and_date( $user_id, $start_date, $end_date );
			$list_task_created = $task_controller->get_task_created_by_user_id_and_date( $user_id, $start_date, $end_date );
			$number_task_created = count( $list_task_created );
			global $point_controller;
			$list_point_completed = $point_controller->get_completed_point_by_user_id_and_date( $user_id, $start_date, $end_date );
			$list_point_created = $point_controller->get_created_point_by_user_id_and_date( $user_id, $start_date, $end_date );

			// Calcul du temps travaillé
			$worked_time = 0;

			global $time_controller;
			$list_comment = $time_controller->get_list_comment_by_user_id_and_date( $user_id, $start_date, $end_date );
			$list_task_worked_time = array();
			if ( !empty( $list_comment ) ) {
				foreach( $list_comment as $comment ) {
					if( empty( $list_task_worked_time[$comment->post_id] ) )
						$list_task_worked_time[$comment->post_id] = 0;
					$list_task_worked_time[$comment->post_id] += $comment->option['time_info']['elapsed'];
					$worked_time += $comment->option['time_info']['elapsed'];
				}
			}

			$working_time = get_user_meta( $user_id, 'working_time', true );

			$ms_end = microtime( true );
			$ms = $ms_end - $ms_start;

			require( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'summary', 'month' ) );
		}

		public function render_day( $user_id, $year, $month, $day, $list_task_created, $list_point_created, $list_point_completed, $list_comment ) {
			global $task_controller;
			global $point_controller;
			global $time_controller;

			// On met le mois au bon format
			if ( strlen( $day ) == 1 )
				$day = '0' . $day;

			$start_date = $year . '-' . $month . '-' . $day . ' 00:00:00';
			$end_date = $year . '-' . $month . '-' . $day . ' 23:59:59';

			$list_message = array();

			if ( !empty( $list_task_created ) ) {
				foreach ( $list_task_created as $task ) {
					if( ( $task->date > $start_date ) && ( $task->date < $end_date ) ) {
						$list_message[mysql2date( 'H:i', $task->date )]['created-task'][] = $task;
					}
				}
			}

			if ( !empty( $list_point_created ) ) {
				foreach ( $list_point_created as $point ) {
					if( ( $point->date > $start_date ) && ( $point->date < $end_date ) ) {
						$list_message[mysql2date( 'H:i', $point->date )]['created-point'][] = $point;
					}
				}
			}

			if ( !empty( $list_point_completed ) ) {
				foreach ( $list_point_completed as $point ) {
					if( ( $point->completed_date > $start_date ) && ( $point->completed_date < $end_date ) ) {
						$list_message[mysql2date( 'H:i', $point->completed_date )]['completed-point'][] = $point;
					}
				}
			}

			if ( !empty( $list_comment ) ) {
				foreach ( $list_comment as $comment ) {
					if( ( $comment->date > $start_date ) && ( $comment->date < $end_date ) ) {
						$list_message[mysql2date( 'H:i', $comment->date )]['comment'][] = $comment;
					}
				}
			}

			krsort( $list_message );


			require( wpeo_template_01::get_template_part( WPEOMTM_TIMELINE_DIR, WPEOMTM_TIMELINE_TEMPLATES_MAIN_DIR, 'backend', 'day' ) );
		}
	}

	global $task_timeline;
	$task_timeline = new timeline_controller_01();
}
