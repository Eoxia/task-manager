<?php
/**
 * Template d'affichage pour les tâches dans la page d'édition des posts
 *
 * @package task-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** On charge les tâches. L'appel se fait ici pour pouvoir utiliser les différents filtres présents dans l'affichage des tâches et afficher un résumé en haut de la metabox */
$task_output = apply_filters( 'task_manager_dashboard_content', '', $post->ID );

$format = '%hh %imin';
if ( 1440 <= $this->total_element_elapsed_time ) {
	$format = '%aj ' . $format;
}
$human_readable_time = taskmanager\util\wpeo_util::minutes_to_time( $this->total_element_elapsed_time, $format );

?><div class="wrap wpeo-project-wrap">
	<form action="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" method="POST"></form>
	<input type="hidden" id="wpeo_user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
	<input type="hidden" class="wpeo-task-post-parent" value="<?php echo esc_attr( $post->ID ); ?>" />
	<!-- Le titre de la page, et également un filtre pour ajouter autant d'actions souhaitées. -->
	<div class="wpeo-project-dashboard task-manager-in-post-type" >
		<div class="alignleft" ><?php	echo apply_filters( 'task_manager_dashboard_title', '' ); // WPCS: XSS ok.	?></div>

		<table class="alignright" >
			<tr>
				<td><?php echo esc_html( sprintf( __( 'Temps total passé: %2$s ( %1$smin )', 'task-manager' ), $this->total_element_elapsed_time, $human_readable_time ) ); ?></td>
				<td class="hidden" >
					<?php esc_html_e( 'Trier les tâches par', 'task-manager' ); ?>
					<ul>
						<li><?php esc_html_e( 'Date de création', 'task_manager' ); ?></li>
						<li><?php esc_html_e( 'Date de modification', 'task_manager' ); ?></li>
					</ul>
				</td>
			</tr>
		</table>
	</div>

	<!-- Le contenu du dashboard -->
	<?php echo $task_output; // WPCS: XSS ok. ?>
</div>
