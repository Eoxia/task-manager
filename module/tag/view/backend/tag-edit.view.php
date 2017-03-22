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

<li class="action-attribute"
	data-id="<?php echo esc_attr( $tag->id ); ?>"
	data-parent-id="<?php echo esc_attr( $task_id ); ?>"
	data-action="tag_affectation"
	data-nonce="<?php echo esc_attr( wp_create_nonce( 'tag_affectation' ) ); ?>">
	<?php echo esc_html( $tag->name ); ?>
</li>
