<?php
/**
 * Catégorie en mode édition.
 *
 * @package Task Manager
 * @subpackage Module/Tag
 *
 * @since 1.0.0.0
 * @version 1.3.6.0
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {	exit; } ?>

<li class="wpeo-tag action-attribute <?php echo in_array( $tag->id, $task->taxonomy['wpeo_tag'], true ) ? 'active' : ''; ?>"
	data-id="<?php echo esc_attr( $tag->id ); ?>"
	data-parent-id="<?php echo esc_attr( $task->id ); ?>"
	data-action="<?php echo in_array( $tag->id, $task->taxonomy['wpeo_tag'], true ) ? 'tag_unaffectation' : 'tag_affectation'; ?>"
	data-nonce="<?php echo esc_attr( wp_create_nonce( in_array( $tag->id, $task->taxonomy['wpeo_tag'], true ) ? 'tag_unaffectation' : 'tag_affectation' ) ); ?>"
	data-namespace="taskManager"
	data-module="tag"
	data-before-method="<?php echo in_array( $tag->id, $task->taxonomy['wpeo_tag'], true ) ? 'beforeUnaffectTag' : 'beforeAffectTag'; ?>">
	<?php echo esc_html( $tag->name ); ?>
</li>
