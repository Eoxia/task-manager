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

<div class="search-results">
	<?php if ( ! empty( $have_search ) ) : ?>
		<ul>
			<li><?php echo esc_attr( $task_id ); ?></li>
		</ul>
	<?php endif; ?>
</div>
