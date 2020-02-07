<?php
/**
 * Le contenu la page "mon-compte" de WPShop.
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.0.0
 * @version 1.2.0
 * @copyright 2015-2017 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="wpeo-project-wrap tm-wrap">

	<h2><?php esc_html_e( 'Customer support', 'task-manager' ); ?></h2>

	<a href="<?php echo esc_attr( home_url( '/mon-compte/?account_dashboard_part=support' ) ); ?>">Retour à la liste BG</a>

	<p>#<?php echo $project->data['id'] . ' ' . $project->data['title']; ?></p>
	<p>#<?php echo $task->data['id'] . ' ' . $task->data['content']; ?></p>
	<p><?php echo $project->data['time_info']['elapsed'] . '/' . $project->data['time_info']['estimated_time']; ?></p>
	<p><?php echo $project->readable_tag; ?></p>
	<hr />

	<div class="wpeo-form">
		<div class="form-element">
			<span class="form-label"><?php esc_html_e( 'A description', 'task-manager' ); ?></span>
			<label class="form-field-container">
				<textarea id="description" name="description" rows="6" class="form-field"></textarea>
			</label>
		</div>

		<div class="wpeo-button button-main alignright">
			<span>Envoyer</span>
		</div>
	</div>

	<hr />

	<p>Ca c'est les commentaires</p>
	<?php
	if ( ! empty( $comments ) ) :
		foreach ( $comments as $comment ) :
			?>
			<p><?php echo esc_attr( $comment->data['content'] ); ?></p>
		<?php
		endforeach;
	else:
	endif;
	?>
</div>
