<?php
/**
 * Vue pour afficher la liste des catégories dans une tâche.
 *
 * @package Task Manager
 *
 * @since 1.0.0
 * @version 1.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<ul class="wpeo-tag-wrap">
	<?php
	if ( ! empty( $tags ) ) :
		foreach ( $tags as $tag ) :
			\eoxia\View_Util::exec(
				'task-manager',
				'tag',
				'backend/tag',
				array(
					'tag' => $tag,
				)
			);
		endforeach;
	endif;
	?>
	<?php //echo '<pre>'; print_r( $task ); echo '</pre>';exit;?>
	<li class="wpeo-tag add action-attribute wpeo-button button-grey button-radius-3"
			data-action="load_tags"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'load_tags' ) ); ?>"
			data-id="<?= $task->data[ 'id' ] ?>">

			<i class="fas fa-tag"></i> <i class="fas fa-plus"></i>
	</li>
</ul>
