<?php
/**
 * Template d'affichage des tâches dans les élements de type post
 *
 * @package Task Manager
 * @subpackage Module/Liaison-Post-Type
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap wpeo-project-wrap">
	<form action="<?php echo admin_url( 'admin-ajax.php' ); ?>" method="POST"></form>
	<input type="hidden" id="wpeo_user_id" value="<?php echo esc_attr( get_current_user_id() ); ?>" />
	<input type="hidden" class="wpeo-task-post-parent" value="<?php echo esc_attr( $post->ID ); ?>" />

	<!-- Le titre de la page, et également un filtre pour ajouter autant d'actions souhaitées. -->
	<div class="wpeo-project-dashboard">
		<?php echo apply_filters( 'task_manager_dashboard_title', '' ); ?>
	</div>

	<!-- Le contenu du dashboard -->
	<?php echo apply_filters( 'task_manager_dashboard_content', '', $post->ID ); ?>
</div>
