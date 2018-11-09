<?php
/**
 * Visualisation d'un commentaire dans la metabox "Customer request".
 *
 * @author Jimmy Latour <jimmy@eoxia.com>
 * @since 1.3.0
 * @version 1.3.0
 * @copyright 2017-2018 Eoxia
 * @package Task_Manager_WPShop
 */

namespace task_manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>


<div class="activity">
	<div class="information">
		<?php echo do_shortcode( '[task_avatar ids="' . $comment->data['author_id'] . '" size="30"]' ); ?>
		<span class="time-posted"><?php echo esc_html( $time ); ?></span>
	</div>

	<div class="content">
		<div class="event-header">
			<!-- Client -->
			<span class="event-client">
				<i class="fa fa-user"></i>
				<?php if ( ! empty( $comment->data['post_parent']->ID ) ) : ?>
				<a href="<?php echo esc_url( admin_url( 'post.php?action=edit&post=' . $comment->data['post_parent']->ID ) ); ?>" target="wptm_view_activity_element" >
					<?php echo esc_html( '#' . $comment->data['post_parent']->ID . ' ' . $comment->data['post_parent']->post_title ); ?>
				</a>
			<?php else : ?>
				<?php echo esc_html( '-' ); ?>
			<?php endif; ?>
			</span>
			<!-- Tâche -->
			<a href="<?php echo esc_attr( 'admin.php?page=wpeomtm-dashboard&term=' . $comment->data['task']->data['id'] ); ?>" class="event-task">
				<i class="dashicons dashicons-layout"></i> <?php echo esc_html( '#' . $comment->data['task']->data['id'] . ' ' . $comment->data['task']->data['title'] ); ?>
			</a>
			<!-- Point -->
			<a  href="<?php echo esc_attr( 'admin.php?page=wpeomtm-dashboard&term=' . $comment->data['task']->data['id'] . '&point_id=' . $comment->data['point']->data['id'] ); ?>" class="event-point">
				<i class="fa fa-list-ul"></i> <?php echo esc_html( '#' . $comment->data['point']->data['id'] . ' ' . $comment->data['point']->data['content'] ); ?>
			</a>

			<a class="wpeo-button button-secondary action-attribute"
			data-action="mark_as_read"
			data-loader="activities"
			data-id="<?php echo esc_attr( $comment->data['id'] ); ?>"
			data-nonce="<?php echo esc_attr( wp_create_nonce( 'mark_as_read' ) ); ?>"><?php esc_html_e( 'Mark as READ', 'task-manager-wpshop' ); ?></a>
		</div>

		<span class="event-content">
			<?php
			$link = 'admin.php?page=wpeomtm-dashboard&term=' . $comment->data['task']->data['id'] . '&point_id=' . $comment->data['point']->data['id'] . '&comment_id=' . $comment->data['id'];
			if ( ! empty( $comment->data['post_parent']->id ) ) :
				$link = 'post.php?post=' . $comment->data['post_parent']->id . '&term=' . $comment->data['task']->data['id'] . '&action=edit&point_id=' . $comment->data['point']->data['id'] . '&comment_id=' . $comment->data['id'];
			endif;
			?>
			<a target="wptm_view_activity_element" href="<?php echo esc_url( admin_url( $link ) ); ?>" >
				<?php
				echo wp_kses( $comment->data['content'], array(
					'br' => array(),
					'p'  => array(),
				) );
				?>
			</a>
		</span>

	</div>
</div>