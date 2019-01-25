<?php
/**
 * Catégorie en mode édition.
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

<li class="wpeo-tag action-attribute wpeo-button button-grey button-radius-3 <?php echo in_array( $tag->data['id'], $task->data['taxonomy'][ Tag_Class::g()->get_type() ], true ) ? 'active' : ''; ?>"
	data-id="<?php echo esc_attr( $tag->data['id'] ); ?>"
	data-parent-id="<?php echo esc_attr( $task->data['id'] ); ?>"
	data-action="<?php echo in_array( $tag->data['id'], $task->data['taxonomy'][ Tag_Class::g()->get_type() ], true ) ? 'tag_unaffectation' : 'tag_affectation'; ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( in_array( $tag->data['id'], $task->data['taxonomy'][ Tag_Class::g()->get_type() ], true ) ? 'tag_unaffectation' : 'tag_affectation' ) ); ?>"
	data-namespace="taskManager"
	data-module="tag"
	data-before-method="<?php echo in_array( $tag->data['id'], $task->data['taxonomy'][ Tag_Class::g()->get_type() ], true ) ? 'beforeUnaffectTag' : 'beforeAffectTag'; ?>">

	<?php echo esc_html( $tag->data['name'] ); ?>
</li>
