<?php
/**
 * Les actions relatives a l'export des tâches.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.1
 * @version 1.5.1
 * @copyright 2015-2018 Eoxia
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Les actions relatives a l'export des tâches.
 */
class Export_Class extends \eoxia\Singleton_Util {

	/**
	 * Initialise les actions liées a l'export des tâches.
	 *
	 * @since 1.5.1
	 * @version 1.5.1
	 */
	protected function construct() {}

	/**
	 * Write a file with exported datas
	 *
	 * @param  Task_Model $task    La tâches dont il faut exporter les données.
	 * @param  string     $content Les données à inscrire dans le fichier.
	 *
	 * @return array                   Les informations concernant le fichier généré.
	 */
	public function export_to_file( $task, $content ) {
		$upload = wp_upload_dir();

		$file_name = $task->data['slug'] . current_time( 'timestamp' ) . '.txt';
		$file_info = array(
			'name' => $task->data['slug'] . current_time( 'timestamp' ) . '.txt',
			'path' => $upload['path'] . '/' . $file_name,
			'url'  => $upload['url'] . '/' . $file_name,
		);

		$fp = fopen( $file_info['path'], 'w' );
		fputs( $fp, $content );
		fclose( $fp );

		return $file_info;
	}

	/**
	 * Met en forme l'export des données pour une tâche.
	 *
	 * @param  Task_Model   $task   La tâche pour laquelle il faut générer l'export.
	 * @param  Point_Models $datas  Les points correspondant à la demande d'export.
	 * @param  array        $args   Une liste d'arguments pour la récupération des des données à afficher.
	 *
	 * @return string               Les données formatées pour l'export.
	 */
	public function build_data( $task, $datas, $args ) {
		$points_to_export = array(
			'uncompleted' => array(
				'label' => __( 'Uncompleted', 'task-manager' ),
				'items' => array(),
			),
			'completed'   => array(
				'label' => __( 'Completed', 'task-manager' ),
				'items' => array(),
			),
		);

		$points_to_export['completed']['items'] = array_filter(
			$datas,
			function( $point ) {
				return true == $point->data['completed'];
			}
		);

		$points_to_export['uncompleted']['items'] = array_filter(
			$datas,
			function( $point ) {
				return false == $point->data['completed'];
			}
		);

		$export_data = $task->data['id'] . ' - ' . $task->data['title'] . "\r\n\r\n";

		if ( ! empty( $points_to_export ) ) {
			foreach ( $points_to_export as $point_type => $point_type_def ) {
				$export_data .= $point_type_def['label'] . "\r\n";
				foreach ( $point_type_def['items'] as $point ) {
					$export_data .= ( $args['with_id'] ? $point->data['id'] . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n", $point->data['content'] ) ) . "\r\n";
					if ( $args['with_comments'] ) {
						$point_comments = Task_Comment_Class::g()->get_comments( $point->data['id'], $args );
						if ( ! empty( $point_comments ) ) {
							foreach ( $point_comments as $comment ) {
								// Récupération de la longeur du titre du point + ce qui précéde : identifiant ou simple fleche pour aligner l'affichage.
								$spacer       = str_repeat( ' ', ( strlen( ( $args['with_id'] ? $comment->data['id'] . ' - ' : ' > ' ) ) + 3 ) );
								$export_data .= '	' . ( $args['with_id'] ? $comment->data['id'] . ' - ' : ' > ' ) . trim( str_replace( '<br>', "\r\n	" . $spacer, $comment->data['content'] ) ) . "\r\n\r\n";
							}
						}
						$export_data .= "\r\n";
					}
				}
				$export_data .= "\r\n";
			}
		}

		return $export_data;
	}

}
