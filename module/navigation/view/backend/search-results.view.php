<?php
/**
 * Affichage des critères de recherche.
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

<ul class="search-results">
	<?php if ( ! empty( $task_id ) ) : ?>
		<li><?php echo esc_attr( $task_id ); ?></li>
	<?php endif; ?>
	
	<?php if ( ! empty( $point_id ) ) : ?>
		<li><?php echo esc_attr( $point_id ); ?></li>
	<?php endif; ?>
	
	<?php if ( ! empty( $follower_searched ) ) : ?>
		<li><?php echo esc_attr( $follower_searched ); ?></li>
	<?php endif; ?>
	
	<?php if ( ! empty( $post_parent_searched ) ) : ?>
		<li><?php echo esc_attr( $post_parent_searched ); ?></li>
	<?php endif; ?>
</ul>
