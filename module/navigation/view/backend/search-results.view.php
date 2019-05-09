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

<?php if ( $have_search ) : ?>
	<div class="search-results">
		<span class="result-title"><?php esc_html_e( 'The search criteria : ', 'task-manager' ); ?></span>

		<ul class="result-list-tags">
			<?php if ( ! empty( $term ) ) : ?>
				<li class="result-tag"><i class="fas fa-search fa-fw"></i> <?php echo esc_attr( $term ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $task_id ) ) : ?>
				<li class="result-tag"><i class="fas fa-th-large fa-fw"></i> <?php echo esc_attr( $task_id ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $point_id ) ) : ?>
				<li class="result-tag"><i class="fas fa-list-ul fa-fw"></i> <?php echo esc_attr( $point_id ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $follower_searched ) ) : ?>
				<li class="result-tag"><i class="fas fa-user-circle fa-fw"></i> <?php echo esc_attr( $follower_searched ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $categories_searched ) ) : ?>
				<li class="result-tag"><i class="fas fa-tag fa-fw"></i> <?php echo esc_attr( $categories_searched ); ?></li>
			<?php endif; ?>

			<?php if ( ! empty( $post_parent_searched ) ) : ?>
				<li class="result-tag">
					<?php if ( 'wpshop_shop_order' === $data['post_parent'] ) : ?>
						<i class="fas fa-shopping-cart fa-fw"></i>
					<?php else : ?>
						<i class="fas fa-user fa-fw"></i>
					<?php endif; ?>
					<?php echo esc_attr( $post_parent_searched ); ?>
				</li>
			<?php endif; ?>
		</ul>

		<?php if ( $display_button ) : ?>
			<a class="wpeo-button button-main button-radius-2 button-size-small wpeo-modal-event load_more_result"
				data-action="load_modal_create_shortcut"
				data-title="<?php esc_html_e( 'Create shortcut', 'task-manager' ); ?>"
				data-term="<?php echo ! empty( $term ) ? esc_attr( $term ) : ''; ?>"
				data-task-id="<?php echo ! empty( $task_id ) ? esc_attr( $task_id ) : ''; ?>"
				data-point-id="<?php echo ! empty( $point_id ) ? esc_attr( $point_id ) : ''; ?>"
				data-user-id="<?php echo ! empty( $data['user_id'] ) ? esc_attr( $data['user_id'] ) : ''; ?>"
				data-categories-id="<?php echo ! empty( $data['categories_id'] ) ? esc_attr( $data['categories_id'] ) : ''; ?>"
				data-post-parent="<?php echo ! empty( $data['post_parent_id'] ) ? esc_attr( $data['post_parent_id'] ) : ''; ?>"
				data-target="wpeo-modal"><i class="button-icon fas fa-plus"></i> <span><?php esc_html_e( 'Shortcut', 'task-manager' ); ?></span></a>
			<?php endif; ?>
	</div>
<?php endif; ?>
