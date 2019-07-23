<?php
/**
 * Gestion des audits.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.9.0
 * @version 1.9.0
 * @copyright 2019 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Gestion des audits.
 */
class Audit_Class extends \eoxia\Post_Class {

	protected $model_name = '\task_manager\Audit_Model';

	/**
	 * Le post type
	 *
	 * @var string
	 */
	protected $type = 'wpeo-audit';

	/**
	 * La clé principale du modèle
	 *
	 * @var string
	 */
	protected $meta_key = 'wpeo_audit';

	/**
	 * La route pour accéder à l'objet dans la rest API
	 *
	 * @var string
	 */
	protected $base = 'audit';

	/**
	 * La version de l'objet
	 *
	 * @var string
	 */
	protected $version = '0.1';

	/**
	 * La taxonomy lié à ce post type.
	 *
	 * @var string
	 */
	protected $attached_taxonomy_type = '';

	public function callback_render_indicator( $post = array(), $parent_id = 0, $showedit = false ){

		if( ! empty( $post ) ){
			$parent_id = $post->ID;
		}

		if( ! $parent_id ){
			return 0;
		}

		$audits = $this->audit_task_link( array( 'post_parent' => $parent_id ) );

		foreach( $audits as $key => $audit ){
			if( $audit->data[ 'parent_id' ] ){
				$query = new \WP_Query(
					array(
						'p' => $audit->data[ 'parent_id' ],
						'post_type'   => 'wpshop_customers',
					)
				);
				$audit->data[ 'parent_title' ] = $query->post->post_title;
			}
		}

	 	\eoxia\View_Util::exec(
			'task-manager',
			'audit',
			'metabox-main',
			array(
				'parent_id' => $parent_id,
				'audits' => $audits,
				'showedit' => $showedit
			)
		);

		$this->audit_create_indicator_javascript( $audits );
	}

	public function audit_task_link( $args = array() ){
		$audits = Audit_Class::g()->get( $args );

		foreach( $audits as $key_audit => $audit ){
			$task_link = array();
			$total_count_completed_points = 0;
			$total_count_uncompleted_points = 0;

			$tasks = Task_Class::g()->get( array( 'post_parent' => $audit->data[ 'id' ] ) );

			foreach( $tasks as $key => $task ){

				if( ! empty( $task->data[ 'parent_id' ] ) ){
					if( $task->data[ 'parent_id' ] == $audit->data[ 'id' ] ){

						$task_link[ $task->data[ 'id' ] ] = array(
							'task_id' => $task->data[ 'id' ],
							'count_completed_points' => $task->data[ 'count_completed_points' ],
							'count_uncompleted_points' => $task->data[ 'count_uncompleted_points' ],
							'percent_uncompleted_points' => $this->audit_client_calcul_percent_uncompletedpoints( $task->data[ 'count_completed_points' ], $task->data[ 'count_uncompleted_points' ]),
							'title' => $task->data[ 'title' ]
						);

						$total_count_completed_points += $task->data[ 'count_completed_points' ];
						$total_count_uncompleted_points += $task->data[ 'count_uncompleted_points' ];

					}
				}
			}

			$audits[ $key_audit ]->data[ 'tasklink' ] = $task_link;
			$percent_audit = $this->audit_client_calcul_percent_uncompletedpoints( $total_count_completed_points, $total_count_uncompleted_points );

			if( $percent_audit < 1 ){
				$percent_audit = 1;
			}

			$audits[ $key_audit ]->data[ 'info' ] = array(
				'count_completed_points' => $total_count_completed_points,
				'count_uncompleted_points' => $total_count_uncompleted_points,
				'percent_uncompleted_points' => $percent_audit,
				'color' => $this->audit_client_color_percent( $percent_audit )
			);
		}

		return $audits;
	}

	public function audit_create_indicator_javascript( $audits ){
		// Foreach pas beau
		if( ! empty( $audits ) ){
			foreach( $audits as $key => $value ){
				if( ! empty( $value->data[ 'tasklink' ] ) ){
					foreach( $value->data[ 'tasklink' ] as $key_ => $value_ ){
						echo '<script>window.eoxiaJS.taskManager.audit.generateAuditIndicator(' . $value_[ 'task_id' ] . ',' . $value_[ 'count_completed_points' ] . ',' . $value_[ 'count_uncompleted_points' ] . ',' . $value->data[ 'id' ] . ',"' . $value_[ 'title' ] . '")</script>';
					}
				}
			}
		}
	}

	public function audit_client_calcul_percent_uncompletedpoints( $completed_points, $uncompleted_points ){
		$percent = 0;

		if( $completed_points != 0 &&  $uncompleted_points == 0 ){ // Tous les points sont complétés
			$percent = 100;
		}else if( $completed_points == 0 &&  $uncompleted_points != 0 ){ // Tous les points sont incomplets
			$percent = 0;
		}else if( $completed_points != 0 &&  $uncompleted_points != 0 ){
			$total = $completed_points + $uncompleted_points;
			if( $total > 0 ){
				$percent = intval( $completed_points / $total * 100 );
			}
		}else{
			$percent = 0;
		}

		if( $percent < 0 ){
			$percent = 0;
		}

		return $percent;
	}

	public function audit_client_color_percent( $percent ){
		$color = "";

		if( $percent < 25 ){
			$color = '#DF0000';
		}else if( $percent < 75 ){
			$color = '#FFCC40';
		}else{
			$color = '#40FFB7';
		}

		return $color;
	}

	public function audit_client_return_task_link( $audit_id ){

		if( $audit_id ){
			$view = do_shortcode( '[task post_parent="' . $audit_id . '" ]' );
			if( $view ){
				echo $view;
			}else{

			}
		}else{
			echo 'No audit id';
		}

		return;
	}

	public function audit_client_return_task_linkbutton( $audit_id = 0 ){

		$task_link = "";
		$button_data = array();
		if( $audit_id != 0){
			$tasks = Task_Class::g()->get( array( 'post_parent' => $audit_id ) );
		}else{
			$tasks = array();
		}

		foreach( $tasks as $key => $task ){
			if( $task->data[ 'parent_id' ] == $audit_id ){

				$button_data[ $task->data[ 'id' ] ] = array(
					'title' => $task->data[ 'title' ]
				);

				if( $task_link != "" ){
					$task_link .= "," . $task->data[ 'id' ];
				}else{ // Premier element
					$task_link = $task->data[ 'id' ];
				}

			}
		}

		ob_start();
		foreach( $button_data as $key => $value ){
			\eoxia\View_Util::exec(
				'task-manager',
				'audit',
				'audit-tasklink-button',
				array(
					'id' => $key,
					'title' => $value[ 'title' ],
					'audit_id' => $audit_id
				)
			);
		}

		echo ob_get_clean();
	}

	public function sortByDateStartDateEndDate( $audits, $date_start_str, $date_end_str, $selector_modification ){

		if( $date_start_str == 0 ){ // on vérifie que la date de début soit valide (en timestamp)
			$date_start_str = strtotime( "midnight", strtotime( 'now' ) );
		}

		if( $date_end_str == 0 ){ // on vérifie que la date de début soit valide (en timestamp)
			$date_end_str = strtotime( "midnight", strtotime( 'now' ) );
		}

		$date_end_str += 60*60*24 - 1; // Pour la date de fin, on fini le jour à 23h59n, pour avoir toute la journée comptabilisée

		if( $date_start_str >= $date_end_str ){ // on vérifie que les dates soient valide
			$date_start_str = $date_end_str;
			$date_start_str -= 60*60*24 - 1;
		}

		foreach( $audits as $key => $audit ){ // Pour chaque audit

			if( ! $audits[ $key ]->data[ 'valid' ] && $selector_modification ){
				continue;
			}else{
				$audits[ $key ]->data[ 'valid' ] = false;
			}

			if( $date_start_str < strtotime( $audit->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) && $date_end_str > strtotime( $audit->data[ 'date' ][ 'rendered' ][ 'mysql' ] ) ){
				$audits[ $key ]->data[ 'valid' ] = true;
			}

		}

		$return = array(
		'audits'     => $audits,
		'date_start' => $date_start_str,
		'date_end'   => $date_end_str
	);

		return $return;
	}

	public function sortByStatus( $audits, $status ){

		$list_valid_audit = array();


		foreach( $audits as $key_audit => $audit ){

			$audits[ $key_audit ]->data[ 'total_count_completed_points' ] = 0;
			$audits[ $key_audit ]->data[ 'total_count_uncompleted_points' ] = 0;
			$audits[ $key_audit ]->data[ 'completed_task' ] = false;
			$audits[ $key_audit ]->data[ 'valid' ] = false;
			/*if( ! empty( $audits[ $key_audit ]->data[ 'valid' ] ) && ! $audits[ $key_audit ]->data[ 'valid' ] ){
				continue;
			}else{
				$audits[ $key_audit ]->data[ 'valid' ] = false;
			}*/

			$tasks = Task_Class::g()->get( array( 'post_parent' => $audit->data[ 'id' ] ) );

			foreach( $tasks as $key => $task ){

				if( ! empty( $task->data[ 'parent_id' ] ) ){
					if( $task->data[ 'parent_id' ] == $audit->data[ 'id' ] ){
						$audits[ $key_audit ]->data[ 'total_count_completed_points' ] += $task->data[ 'count_completed_points' ];
						$audits[ $key_audit ]->data[ 'total_count_uncompleted_points' ] += $task->data[ 'count_uncompleted_points' ];

					}
				}
			}

			if( $audits[ $key_audit ]->data[ 'total_count_completed_points' ] != 0 || $audits[ $key_audit ]->data[ 'total_count_uncompleted_points' ] != 0 ){
				if( $audits[ $key_audit ]->data[ 'total_count_uncompleted_points' ] > 0 ){
					$audits[ $key_audit ]->data[ 'completed_task' ] = false;
				}else{
					$audits[ $key_audit ]->data[ 'completed_task' ] = true;
				}
			}


			if( $status == "completed" && $audits[ $key_audit ]->data[ 'completed_task' ] ){
				$audits[ $key_audit ]->data[ 'valid' ] = true;

			}else if( $status == "progress" && ! $audits[ $key_audit ]->data[ 'completed_task' ] ){
				$audits[ $key_audit ]->data[ 'valid' ] = true;

			}
		}

		return $audits;
	}

	public function callable_audit_page(){
		\eoxia\View_Util::exec(
			'task-manager',
			'audit',
			'audit-page/main',
			array()
		);
	}

	public function callback_audit_list_metabox( $args = array(), $array = array(), $showedit = false ){

		$audits = $this->audit_task_link( $args );
		if( ! empty( $audits ) ){
			foreach( $audits as $key => $audit ){
				if( $audit->data[ 'parent_id' ] ){
					$query = new \WP_Query(
						array(
							'p' => $audit->data[ 'parent_id' ],
							'post_type'   => 'wpshop_customers',
						)
					);
					if( ! empty( $query->posts ) ){
						$audit->data[ 'parent_title' ] = $query->post->post_title;
					}else{
						$audit->data[ 'parent_title' ] = '';
					}
				}
			}
		}

		\eoxia\View_Util::exec(
			'task-manager',
			'audit',
			'audit-page/metabox-main',
			array(
				'audits' => $audits,
				'showedit' => $showedit
			)
		);
		$this->audit_create_indicator_javascript( $audits );
	}
}

Audit_Class::g();
