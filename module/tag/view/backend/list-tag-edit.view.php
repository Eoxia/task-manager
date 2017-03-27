<?php
/**
 * La liste des catégories en mode édition.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; }

if ( ! empty( $tags ) ) :
	foreach ( $tags as $tag ) :
		View_Util::exec( 'tag', 'backend/tag-edit', array(
			'tag' => $tag,
			'task_id' => $task->id,
		) );
	endforeach;
endif; ?>

<span class="action-attribute dashicons dashicons-edit"
			data-action="close_tag_edit_mode"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'close_tag_edit_mode' ) ); ?>"
			data-task-id="<?php echo esc_attr( $task->id ); ?>"></span>
