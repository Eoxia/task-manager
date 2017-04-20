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

<ul class="wpeo-tag-wrap">
	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			View_Util::exec( 'tag', 'backend/tag-edit', array(
				'tag' => $tag,
				'task' => $task,
			) );
		endforeach;
	endif;
	?>

	<span class="wpeo-tag edit action-attribute dashicons dashicons-edit"
		data-action="close_tag_edit_mode"
		data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_tag_edit_mode' ) ); ?>"
		data-task-id="<?php echo esc_attr( $task->id ); ?>"></span>

</ul>
