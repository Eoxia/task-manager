<?php
/**
 * Le titre de la POPUP
 *
 * @author Eoxia <dev@eoxia.com>
 * @since 1.5.0
 * @version 1.5.0
 * @copyright 2015-2017 Eoxia
 * @package DigiRisk
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $have_search ) ) : ?>
	<?php esc_html_e( 'Results', 'task-manager' ); ?>

	<?php
	if ( ! empty( $term ) ) :
		esc_html_e( ' with the term ', 'task-manager' );
		?>
		"<?php echo esc_html( $term ); ?>"
		<?php
	endif;

	if ( ! empty( $categories_searched ) ) :
		esc_html_e( ' in the categories: ', 'task-manager' );
		?>
		"<?php echo esc_html( $categories_searched ); ?>"
		<?php
	endif;

	if ( ! empty( $follower_searched ) ) :
		esc_html_e( ' for the followers: ', 'task-manager' );
		?>
		"<?php echo esc_html( $follower_searched ); ?>"
		<?php
	endif;
	?>
<?php endif; ?>
