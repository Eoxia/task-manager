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
		<h3>
			<?php esc_html_e( 'Search criteria: ', 'task-manager' ); ?>

			<?php
			if ( 'any' === $status ) :
				esc_html_e( ' all tasks ', 'task-manager' );
			elseif ( 'archive' === $status ) :
				esc_html_e( ' archived tasks', 'task-manager' );
			endif;
			?>

			<?php
			if ( ! empty( $term ) ) :
				esc_html_e( ' with the term ', 'task-manager' );
				?>"<?php echo esc_html( $term ); ?>"<?php
			endif;

			if ( ! empty( $categories_searched ) ) :
				esc_html_e( ' in the categories: ', 'task-manager' );
				?>"<?php echo esc_html( $categories_searched ); ?>"<?php
			endif;

			if ( ! empty( $follower_searched ) ) :
				esc_html_e( ' for the followers: ', 'task-manager' );
				?>"<?php echo esc_html( $follower_searched ); ?>"<?php
			endif;
			?>

			<a href="<?php echo esc_attr( admin_url( 'admin.php' ) ); ?>?page=wpeomtm-dashboard"><span class="dashicons dashicons-no-alt"></span></a>
		</h3>
	<?php endif; ?>
</div>
