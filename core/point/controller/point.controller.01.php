<?php
/**
 * Gestion des points
 *
 * @since 1.3.4.0
 * @version 1.3.4.0
 * @package Task-Manager\point
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( !class_exists( 'point_controller_01' ) ) {

	/**
	 * Gestion des points
	 */
	class Point_Controller_01 extends comment_ctr_01 {

		/**
		 * Le nom du modèle
		 *
		 * @var string
		 */
		protected $model_name 	= 'point_model_01';

		/**
		 * La clé principale du modèle
		 *
		 * @var string
		 */
		protected $meta_key		= 'wpeo_point';

		/**
		 * La route pour la rest API
		 *
		 * @var string
		 */
		protected $base = 'point';

		/**
		 * La version pour la rest API
		 *
		 * @var string
		 */
		protected $version = '0.1';

		/**
		 * Constructeur qui inclus le modèle des points et également des les scripts
		 * JS et CSS nécessaire pour le fonctionnement des points
		 *
		 * @return void
		 */
		public function __construct() {
			parent::__construct();

			include_once( WPEO_POINT_PATH . '/model/point.model.01.php' );

			add_filter( 'task_content', array( $this, 'callback_task_content' ), 10, 2 );
			add_filter( 'task_export', array( $this, 'callback_task_export' ), 10, 2 );
			add_filter( 'task_points_mail', array( $this, 'callback_task_points_mail' ), 10, 2 );

			add_filter( 'point_action_before', array( $this, 'callback_point_action_before' ), 10, 2 );
			add_filter( 'point_action_after', array( $this, 'callback_point_action_after' ), 10, 2 );

			add_filter( 'point_list_add', array( $this, 'callback_point_list_add' ), 10, 2 );

			/** Window */
			add_filter( 'task_window_sub_header_point_controller', array( $this, 'callback_task_window_sub_header' ), 10, 2 );
			add_filter( 'task_window_action_point_controller', array( $this, 'callback_task_window_action' ), 10, 2 );
			add_filter( 'task_window_footer_point_controller', array( $this, 'callback_task_window_footer' ), 10, 2 );
		}

		/**
		 * Récupères la liste de tous les points d'une tâche
		 *
		 * @param  string        $string Le contenu du filtre.
		 * @param  Task_Model_01 $task   Les données de la tâche.
		 * @return string         Le contenu du filtre modifié
		 *
		 * @since 1.3.4.0
		 * @version 1.3.4.0
		 */
		public function callback_task_content( $string, $task ) {
			$list_point_completed = array();
			$list_point_uncompleted = array();

			if ( ! empty( $task->option['task_info']['order_point_id'] ) ) {
				$list_point = $this->index( $task->id, array( 'orderby' => 'comment__in', 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
				$list_point_completed = array_filter( $list_point, function( $point ) {
					return true === $point->option['point_info']['completed'];
				} );

				$list_point_uncompleted = array_filter( $list_point, function( $point ) {
					return false === $point->option['point_info']['completed'];
				} );
			}

			ob_start();
			$this->render_point( $task->id, $list_point_completed, $list_point_uncompleted );
			$string .= ob_get_clean();

			return $string;
		}

		public function callback_task_export( $string, $task ) {
			$list_point_completed = array();
			$list_point_uncompleted = array();

			if ( !empty( $task->option['task_info']['order_point_id'] ) ) {
				$list_point = $this->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
				$list_point_completed = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === true; } );
				$list_point_uncompleted = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === false; } );
			}

			$string .= __( 'Uncompleted', 'task-manager' ) . "\r\n";

			if ( !empty( $list_point_uncompleted ) ) {
				foreach ( $list_point_uncompleted as $point ) {
					$string .= '     ' . $point->id . ' - ' . $point->content . "\r\n";
				}
			}

			$string .= __( 'Completed', 'task-manager' ) . "\r\n";

			if ( !empty( $list_point_completed ) ) {
				foreach ( $list_point_completed as $point ) {
					$string .= '     ' . $point->id . ' - ' . $point->content . "\r\n";
				}
			}

			return $string;
		}

		public function callback_task_points_mail( $string, $task ) {
			if ( !empty( $task->option['task_info']['order_point_id'] ) ) {
				$list_point = $this->index( $task->id, array( 'comment__in' => $task->option['task_info']['order_point_id'], 'status' => -34070 ) );
				$list_point_completed = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === true; } );
				$list_point_uncompleted = array_filter( $list_point, function( $point ) { return $point->option['point_info']['completed'] === false; } );
			}

			$string .= '<h3>Uncompleted</h3>';
			if ( ! empty( $list_point_uncompleted ) ) :
				$string .= '<ul>';
			  foreach ( $list_point_uncompleted as $element ) :
					$string .= '<li>#' . $element->id . ' - ' . $element->content . '</li>';
			  endforeach;
				$string .= '</ul>';
			endif;

			return $string;
		}

		public function get_created_point_by_user_id_and_date( $user_id, $start_date, $end_date ) {
			if ( empty( $user_id ) || empty( $start_date ) || empty( $end_date ) )
				return null;

			global $wpdb;

			$query =
			"SELECT DISTINCT point.comment_ID
			FROM {$wpdb->comments} as point
			WHERE	point.comment_parent = 0 AND
					point.user_id = %d AND
					point.comment_date BETWEEN %s AND %s";

			$list_comment = $wpdb->get_results( $wpdb->prepare( $query, array( $user_id, $start_date, $end_date ) ) );

			$list_point = array();

			if ( !empty( $list_comment ) ) {
				foreach ( $list_comment as $comment ) {
					$list_point[] = $this->show( $comment->comment_ID );
				}
			}

			return $list_point;
		}

		/**
		 * RÃ©cupÃ¨res tous les points ou l'utilisateur $user_id Ã  commentÃ©.
		 *
		 * @param int $user_id L'id de l'utilisateur
		 * @return NULL|Array point_model
		 */
		public function get_list_point_by_comment_user_id( $user_id ) {
			if ( empty( $user_id ) )
				return null;

			global $wpdb;

			$query =
			"SELECT DISTINCT point.comment_ID
			FROM {$wpdb->comments} as point
			JOIN {$wpdb->comments} as comment
			ON point.comment_ID=comment.comment_parent
			WHERE	comment.comment_parent != 0 AND
					comment.user_id = %d";

			$list_comment = $wpdb->get_results( $wpdb->prepare( $query, array( $user_id ) ) );

			$list_point = array();

			if ( !empty( $list_comment ) ) {
				foreach ( $list_comment as $comment ) {
					$list_point[] = $this->show( $comment->comment_ID );
				}
			}

			return $list_point;
		}

		/**
		 * RÃ©cupÃ¨res tous les point ou l'utilisateur $user_id Ã  commentÃ© et ou les
		 * $start_date et $end_date corresponde.
		 *
		 * @param int $user_id L'id de l'utilisateur
		 * @param string $start_date La date minimum
		 * @param string $end_date La date maximum
		 * @return NULL|Array point_model
		 */
		public function get_list_point_by_comment_user_id_and_date( $user_id, $start_date, $end_date ) {
			if ( empty( $user_id ) || empty( $start_date ) || empty( $end_date ) )
				return null;

			global $wpdb;

			$query =
			"SELECT DISTINCT point.comment_ID
			FROM {$wpdb->comments} as point
			JOIN {$wpdb->comments} as comment
			ON point.comment_ID=comment.comment_parent
			WHERE	comment.comment_parent != 0 AND
					comment.user_id = %d AND
					comment.comment_date BETWEEN %s AND %s";

			$list_comment = $wpdb->get_results( $wpdb->prepare( $query, array( $user_id, $start_date, $end_date ) ) );

			$list_point = array();

			if ( !empty( $list_comment ) ) {
				foreach ( $list_comment as $comment ) {
					$list_point[] = $this->show( $comment->comment_ID );
				}
			}

			return $list_point;
		}

		public function get_completed_point_by_user_id_and_date( $user_id, $start_date, $end_date ) {
			if ( empty( $user_id ) || empty( $start_date ) || empty( $end_date ) )
				return null;

			$list_point = $this->index( 0, array( 'post_type' => 'wpeo-task', 'status' => '-34070' ) );
			$list_point_completed = array();

			if ( !empty( $list_point ) ) {
				foreach ( $list_point as $point ) {
					if ( !empty( $point->option['time_info']['completed_point'][$user_id] ) ) {
						foreach( $point->option['time_info']['completed_point'][$user_id] as $date_completed ) {

							if( ( $date_completed > $start_date ) && ( $date_completed < $end_date ) ) {
								$point->completed_date = $date_completed;
								$list_point_completed[] = $point;
							}

							break;
						}
					}
				}
			}

			return $list_point_completed;
		}

		public static function get_point_name_by_id( $point_id ) {
			if( empty( $point_id ) )
				return __( 'Point not found', 'task-manager' );

			global $point_controller;
			$point = $point_controller->show( $point_id );

			if( empty( $point ) )
				return __( 'Point not found', 'task-manager' );

			return $point->content;
		}

		public function callback_point_list_add( $string, $object_id ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'point-add' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_point_action_before( $string, $point ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'action-before' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_point_action_after( $string, $point ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'action-after' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function increase_time( $point_id, $time_elapsed ) {
			$point = $this->show( $point_id );
			$point->option['time_info']['elapsed'] += $time_elapsed;
			$this->update($point);

			global $task_controller;
			$task = $task_controller->update_time( $point->post_id );

			return $task;
		}

		public function decrease_time( $point_id, $elapsed_time = 0 ) {
			$point = $this->show( $point_id );

			if ( $elapsed_time == 0 )
				$point->option['time_info']['elapsed'] = $elapsed_time;
			else
				$point->option['time_info']['elapsed'] -= $elapsed_time;

			$this->update( $point );

			global $task_controller;
			$task = $task_controller->update_time( $point->post_id );

			return $task;
		}

		public function send_comment_to( $old_task_id, $task_id, $point_id ) {
			global $time_controller;
			$list_time = $time_controller->index( $old_task_id, array( 'parent' => $point_id, 'status' => -34070 ) );

			if ( !empty( $list_time ) ) {
				foreach ( $list_time as $time ) {
					$time->post_id = $task_id;
					$time_controller->update( $time, false );
				}
			}
		}

		/**
		 * Appelle la vue pour faire le rendu.
		 *
		 * @param  integer $object_id              L'ID de la tâche.
		 * @param  array   $list_point_completed   Le tableau des points complétés.
		 * @param  array   $list_point_uncompleted Le tableau des points incompléts.
		 * @return void
		 */
		public static function render_point( $object_id, $list_point_completed, $list_point_uncompleted ) {
			$disabled_filter = apply_filters( 'point_disabled', '' );
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend', 'list', 'point' ) );
		}

		public function callback_task_window_sub_header( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'sub-header' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_information( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'information' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_action( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'action' ) );
			$string .= ob_get_clean();
			return $string;
		}

		public function callback_task_window_footer( $string, $element ) {
			ob_start();
			require( wpeo_template_01::get_template_part( WPEO_POINT_DIR, WPEO_POINT_TEMPLATES_MAIN_DIR, 'backend/window', 'footer' ) );
			$string .= ob_get_clean();
			return $string;
		}
	}

	global $point_controller;
	$point_controller = new point_controller_01();

}
