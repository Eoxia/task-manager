<?php
/**
 * Vue pour afficher la liste des catégories dans une tâche.
 *
 * @since 1.0.0
 * @version 1.6.0
 *
 * @package Task_Manager
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="wpeo-tag-wrap edit">
	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'tag',
				'backend/tag-edit',
				array(
					'tag'  => $tag,
					'task' => $task,
				)
			);
		endforeach;
	else :
		?>
		<li>
			<?php esc_html_e( 'Create categories ', 'task-manager' ); ?>
			<a href="<?php echo esc_attr( admin_url( 'edit-tags.php?taxonomy=wpeo_tag' ) ); ?>" target="_blank"><?php esc_html_e( 'here', 'task-manager' ); ?></a>
			<?php esc_html_e( 'and refresh this section.', 'task-manager' ); ?>
		</li>
		<?php
	endif;
	?>

	<li class="wpeo-tag wpeo-button edit action-attribute button-grey button-radius-3"
		data-action="close_tag_edit_mode"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_tag_edit_mode' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->data['id'] ); ?>">

			<i class="fas fa-save"></i>
	</li>
</ul>
