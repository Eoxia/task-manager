<?php
/**
 * Mise à jour des données pour les version à partir de 1.3.4.0
 * Modification du type pour les commentaires liés à une tâche pour les différencier autrement
 *
 * @package Task Manager
 * @subpackage Module/Update_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$query = "UPDATE $wpdb->comments SET comment_type = %s WHERE comment_post_ID IN ( SELECT ID FROM {$wpdb->posts} WHERE post_type = %s )";
$query_args = array( 'wpeo-point', 'wpeo-task' );
$wpdb->query( $wpdb->prepare( $query, $query_args ) );
