<?php
/**
 * Vue pour afficher la liste des catégories dans une tâche.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<ul class="wpeo-tag-wrap edit">
	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			View_Util::exec( 'tag', 'backend/tag-edit', array(
				'tag' => $tag,
				'task' => $task,
			) );
		endforeach;
	else :
		?>
		<li>
			<?php esc_html_e( 'Créer des categories ', 'task-manager' ); ?>
			<a href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=wpeo_tag' ) ); ?>" target="_blank"><?php esc_html_e( 'ici', 'task-manager' ); ?></a>
			<?php esc_html_e( ' et rafraichissez cette fenêtre.', 'task-manager' ); ?>
		</li>
		<?php
	endif;
	?>

	<li class="wpeo-tag edit action-attribute"
		data-action="close_tag_edit_mode"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_tag_edit_mode' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->id ); ?>">
		<i class="fa fa-floppy-o" ></i>
	</li>
</ul>
